<nav x-data="{ open: false }" class="nav-container" id="navi-generale">
    <!-- Primary Navigation Menu -->
    <div class="nav-inner">
        <div class="nav-content">
            <div class="nav-left-section">
                <!-- Logo -->
                <div class="logo-container">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="logo" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="nav-links">
                    @if (Auth::check())
                        @php $userRole = Auth::user()->role; @endphp 
                        @if ($userRole === 'étudiant')
                            <x-nav-link :href="route('dashboard.etudiant')" :active="request()->routeIs('etudiant.dashboard')">
                                {{ __('ACCUEIL') }}
                            </x-nav-link>
                        @elseif ($userRole === 'présentateur')
                            <x-nav-link :href="route('presentateur.dashboard')" :active="request()->routeIs('presentateur.dashboard')">
                                {{ __('ACCUEIL') }}
                            </x-nav-link>
                        @elseif ($userRole === 'secretaire')
                            <x-nav-link :href="route('secretary.dashboard')" :active="request()->routeIs('secretaire.dashboard')">
                                {{ __('ACCUEIL') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('ACCUEIL') }}
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('login')">
                            {{ __('ACCUEIL') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="nav-right-section">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="team-dropdown">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="dropdown-trigger">
                                    <button type="button" class="team-button">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="dropdown-content">
                                    <!-- Team Management -->
                                    <div class="dropdown-header">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="dropdown-divider"></div>

                                        <div class="dropdown-header">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="user-dropdown">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                        <span class="inline-flex rounded-md"> {{-- Enveloppeur pour aligner --}}
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150 your-custom-trigger-class"> {{-- Ajoutez vos classes CSS personnalisées ici si besoin --}}
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <img class="profile-image h-8 w-8 rounded-full object-cover me-2" src="{{ Auth::user()->profile_photo_url }}" alt="{{ (Auth::user()->role === 'présentateur' ? 'Prof. ' : '') . Auth::user()->name }}" />
                                @endif

                                <span class="user-name-display"> {{-- Classe pour styler le nom --}}
                                    @if(Auth::user()->role === 'présentateur')
                                        Prof.
                                    @elseif(Auth::user()->role === 'secretaire')
                                       Sr.
                                    @elseif(Auth::user()->role === 'étudiant')
                                       Etudiant.   
                                    @endif
                                    {{ Auth::user()->prenom }} {{ Auth::user()->name }}
                                </span>

                                <svg class="dropdown-icon ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </span>
                    </x-slot>
                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="dropdown-header">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="dropdown-divider"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="mobile-menu-button">
                <button @click="open = ! open" class="hamburger">
                    <svg class="hamburger-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="mobile-menu">
        <div class="mobile-links">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="mobile-user-section">
            <div class="user-info">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="mobile-profile-photo">
                        <img class="mobile-profile-image" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mobile-user-links">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="dropdown-divider"></div>

                    <div class="dropdown-header">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="dropdown-divider"></div>

                        <div class="dropdown-header">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>