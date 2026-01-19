<x-guest-layout>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Creer un compte</h2>
            <p class="text-gray-500 mt-2">Rejoignez la communaute PhotoMatch</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Role Selection -->
            <div class="mb-6">
                <x-input-label :value="__('Je suis')" class="text-gray-700 mb-3" />
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="client" class="peer sr-only" {{ old('role', 'client') === 'client' ? 'checked' : '' }}>
                        <div class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-gray-300 transition">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 peer-checked:bg-emerald-100 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-500 peer-checked:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Client</span>
                                <span class="text-xs text-gray-500 mt-1">Je cherche un photographe</span>
                            </div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="photographer" class="peer sr-only" {{ old('role') === 'photographer' ? 'checked' : '' }}>
                        <div class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-gray-300 transition">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 peer-checked:bg-emerald-100 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-500 peer-checked:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Photographe</span>
                                <span class="text-xs text-gray-500 mt-1">Je propose mes services</span>
                            </div>
                        </div>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nom complet')" class="text-gray-700" />
                <x-text-input id="name"
                    class="block mt-1 w-full rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Jean Dupont" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Adresse email')" class="text-gray-700" />
                <x-text-input id="email"
                    class="block mt-1 w-full rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                    placeholder="vous@exemple.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700" />
                <x-text-input id="password"
                    class="block mt-1 w-full rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimum 8 caracteres" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-700" />
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Retapez votre mot de passe" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Creer mon compte
                </button>
            </div>

            <p class="mt-4 text-xs text-center text-gray-500">
                En creant un compte, vous acceptez nos conditions d'utilisation et notre politique de confidentialite.
            </p>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Deja inscrit ?</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border-2 border-emerald-600 rounded-lg text-sm font-medium text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Se connecter
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
