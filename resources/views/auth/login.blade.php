<x-guest-layout>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Connexion</h2>
            <p class="text-gray-500 mt-2">Heureux de vous revoir !</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Adresse email')" class="text-gray-700" />
                <x-text-input id="email"
                    class="block mt-1 w-full rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="vous@exemple.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700" />
                    @if (Route::has('password.request'))
                        <a class="text-sm text-emerald-600 hover:text-emerald-700 transition" href="{{ route('password.request') }}">
                            Mot de passe oublie ?
                        </a>
                    @endif
                </div>

                <x-text-input id="password"
                    class="block mt-1 w-full rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Se connecter
                </button>
            </div>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Pas encore de compte ?</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('register') }}" class="w-full flex justify-center py-3 px-4 border-2 border-emerald-600 rounded-lg text-sm font-medium text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Creer un compte
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
