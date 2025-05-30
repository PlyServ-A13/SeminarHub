<?php

namespace Database\Factories;

// Supprimez "use App\Models\Team;" et "use Laravel\Jetstream\Features;" si vous n'utilisez plus withPersonalTeam
// use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use Laravel\Jetstream\Features; // Supprimez ou commentez si withPersonalTeam n'est pas utilisé

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $roles = ['étudiant', 'présentateur', 'secretaire']; // Vos rôles possibles

        return [
            'name' => fake()->lastName(), // Ou fake()->name() si vous voulez nom complet
            'prenom' => fake()->firstName(), // Ajout pour la colonne prenom
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement($roles), // Ajout pour la colonne role
            'remember_token' => Str::random(10),
            // Les lignes suivantes sont supprimées car les colonnes n'existent pas dans votre table users
            // 'two_factor_secret' => null,
            // 'two_factor_recovery_codes' => null,
            // 'profile_photo_path' => null,
            // 'current_team_id' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // Si vous n'utilisez pas les fonctionnalités d'équipe de Jetstream,
    // vous pouvez commenter ou supprimer toute la méthode withPersonalTeam.
    /*
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) { // Cette ligne causera une erreur si Features n'est pas importé
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
    */
}