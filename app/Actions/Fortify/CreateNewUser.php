<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\RegistrationCode; // Assurez-vous que ce modèle existe et est correct
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Importez la façade Rule
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
// ValidationException n'est pas explicitement nécessaire si vous utilisez ->validate()

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => ['required', Rule::in(['étudiant', 'présentateur', 'secretaire'])], // Plus propre avec Rule::in
            'registration_code' => [
                // Règle 1: Requis si le rôle est 'présentateur' ou 'secretaire'
                Rule::requiredIf(function () use ($input) {
                    return isset($input['role']) && in_array($input['role'], ['présentateur', 'secretaire']);
                }),
                // Règle 2: Doit être nullable si non requis par la règle ci-dessus (ex: pour étudiant)
                'nullable',
                'string',
                'max:255',
                // Règle 3: Vérification personnalisée de l'existence et de la correspondance du rôle du code
                // Cette règle ne s'appliquera que si un code est fourni.
                function ($attribute, $value, $fail) use ($input) {
                    // On ne vérifie le code que si le rôle nécessite un code et qu'un code est fourni.
                    if (isset($input['role']) && in_array($input['role'], ['présentateur', 'secretaire']) && !empty($value)) {
                        $registrationCodeEntry = RegistrationCode::where('code', $value)->first();

                        if (!$registrationCodeEntry) {
                            $fail('Le code d\'inscription fourni n\'est pas valide.');
                            return;
                        }

                        // Vérifier si le rôle associé au code correspond au rôle sélectionné par l'utilisateur
                        if ($registrationCodeEntry->role !== $input['role']) {
                            // Vous pouvez personnaliser ce message si vous le souhaitez
                            $fail('Ce code d\'inscription ne correspond pas au rôle sélectionné.');
                        }
                        // Optionnel: ici, vous pourriez ajouter une logique pour marquer le code comme "utilisé"
                        // si les codes sont à usage unique. Pour l'instant, vos codes sont statiques.
                    }
                },
            ],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Création de l'utilisateur
        return User::create([
            'name' => $input['name'],
            'prenom' => $input['prenom'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
            // Si vous souhaitez stocker le code d'inscription avec l'utilisateur,
            // vous devez ajouter une colonne à votre table 'users' et l'ajouter ici:
            // 'registration_code_used' => $input['registration_code'] ?? null,
        ]);
    }
}