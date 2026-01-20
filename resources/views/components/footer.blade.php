<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div class="col-span-1 md:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center">
                    <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold">Trouve Ton Photographe</span>
                </a>
                <p class="mt-4 text-gray-400 text-sm max-w-md">
                    La plateforme de reference pour trouver le photographe professionnel ideal pour tous vos projets : mariage, portrait, evenementiel, corporate et bien plus.
                </p>
                <div class="mt-4 flex space-x-4">
                    <a href="https://facebook.com/trouvetonphotographe" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://instagram.com/trouvetonphotographe" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition" aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                    </a>
                    <a href="https://twitter.com/TrouveTonPhoto" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition" aria-label="Twitter">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Navigation</h3>
                <ul class="mt-4 space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition">Accueil</a>
                    </li>
                    <li>
                        <a href="{{ route('search.index') }}" class="text-gray-400 hover:text-white text-sm transition">Trouver un photographe</a>
                    </li>
                    <li>
                        <a href="{{ route('photographers.index') }}" class="text-gray-400 hover:text-white text-sm transition">Nos photographes</a>
                    </li>
                    @guest
                    <li>
                        <a href="{{ route('register') }}" class="text-gray-400 hover:text-white text-sm transition">Devenir photographe</a>
                    </li>
                    @endguest
                </ul>
            </div>

            {{-- Legal --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Informations</h3>
                <ul class="mt-4 space-y-2">
                    <li>
                        <a href="{{ route('legal.mentions') }}" class="text-gray-400 hover:text-white text-sm transition">Mentions legales</a>
                    </li>
                    <li>
                        <a href="{{ route('legal.cgu') }}" class="text-gray-400 hover:text-white text-sm transition">Conditions generales</a>
                    </li>
                    <li>
                        <a href="{{ route('legal.privacy') }}" class="text-gray-400 hover:text-white text-sm transition">Politique de confidentialite</a>
                    </li>
                    <li>
                        <a href="{{ route('legal.cookies') }}" class="text-gray-400 hover:text-white text-sm transition">Gestion des cookies</a>
                    </li>
                    <li>
                        <a href="mailto:{{ config('seo.legal.email') }}" class="text-gray-400 hover:text-white text-sm transition">Contact</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-gray-800">
            <p class="text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} Trouve Ton Photographe. Tous droits reserves.
            </p>
        </div>
    </div>
</footer>
