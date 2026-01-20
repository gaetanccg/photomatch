<x-app-layout>
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Politique des Cookies</h1>

            <div class="prose prose-emerald max-w-none">
                <p class="text-gray-600 mb-8">Derniere mise a jour : {{ now()->format('d/m/Y') }}</p>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Qu'est-ce qu'un cookie ?</h2>
                    <p>Un cookie est un petit fichier texte depose sur votre terminal (ordinateur, tablette, smartphone) lors de votre visite sur un site web. Il permet au site de memoriser des informations sur votre visite, comme vos preferences de langue ou vos informations de connexion, afin de faciliter votre prochaine visite et rendre le site plus utile pour vous.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Cookies utilises sur notre site</h2>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">2.1 Cookies strictement necessaires</h3>
                    <p>Ces cookies sont indispensables au fonctionnement du site. Ils vous permettent d'utiliser les fonctionnalites essentielles comme la navigation securisee et l'acces a votre espace personnel.</p>
                    <table class="w-full mt-4 border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 text-left">Nom</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Finalite</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Duree</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">XSRF-TOKEN</td>
                                <td class="border border-gray-300 px-3 py-2">Protection contre les attaques CSRF</td>
                                <td class="border border-gray-300 px-3 py-2">Session</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">laravel_session</td>
                                <td class="border border-gray-300 px-3 py-2">Gestion de la session utilisateur</td>
                                <td class="border border-gray-300 px-3 py-2">2 heures</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">remember_web_*</td>
                                <td class="border border-gray-300 px-3 py-2">Connexion persistante ("Se souvenir de moi")</td>
                                <td class="border border-gray-300 px-3 py-2">5 ans</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">2.2 Cookies de performance et d'analyse</h3>
                    <p>Ces cookies nous permettent de comprendre comment les visiteurs utilisent notre site, afin d'ameliorer son fonctionnement et votre experience.</p>
                    <table class="w-full mt-4 border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 text-left">Nom</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Finalite</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Duree</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">_ga</td>
                                <td class="border border-gray-300 px-3 py-2">Google Analytics - Identification des visiteurs</td>
                                <td class="border border-gray-300 px-3 py-2">13 mois</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">_gid</td>
                                <td class="border border-gray-300 px-3 py-2">Google Analytics - Identification des sessions</td>
                                <td class="border border-gray-300 px-3 py-2">24 heures</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">2.3 Cookies de fonctionnalite</h3>
                    <p>Ces cookies permettent de memoriser vos preferences (comme la langue ou la region) pour vous offrir une experience personnalisee.</p>
                    <table class="w-full mt-4 border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 text-left">Nom</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Finalite</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Duree</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">locale</td>
                                <td class="border border-gray-300 px-3 py-2">Memorisation de la preference de langue</td>
                                <td class="border border-gray-300 px-3 py-2">1 an</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">cookie_consent</td>
                                <td class="border border-gray-300 px-3 py-2">Memorisation de vos choix de cookies</td>
                                <td class="border border-gray-300 px-3 py-2">1 an</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Gestion des cookies</h2>
                    <p>Vous pouvez a tout moment choisir d'accepter ou de refuser les cookies non essentiels.</p>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">3.1 Via notre bandeau de consentement</h3>
                    <p>Lors de votre premiere visite, un bandeau vous permet de choisir les categories de cookies que vous acceptez. Vous pouvez modifier vos preferences a tout moment en cliquant sur le lien "Gerer les cookies" en bas de page.</p>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">3.2 Via les parametres de votre navigateur</h3>
                    <p>Vous pouvez egalement configurer votre navigateur pour accepter ou refuser les cookies :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/fr/kb/activer-desactiver-cookies" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/fr-fr/guide/safari/sfri11471/mac" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Safari</a></li>
                        <li><a href="https://support.microsoft.com/fr-fr/microsoft-edge/supprimer-les-cookies-dans-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Microsoft Edge</a></li>
                    </ul>
                    <p class="mt-4"><strong>Attention :</strong> le blocage de certains cookies peut affecter le fonctionnement du site.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Cookies tiers</h2>
                    <p>Certains services tiers peuvent deposer des cookies sur votre terminal :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><strong>Google Analytics :</strong> pour l'analyse du trafic. <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Politique de confidentialite de Google</a></li>
                        <li><strong>OpenStreetMap/Leaflet :</strong> pour l'affichage des cartes (pas de cookies de suivi)</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Duree de conservation</h2>
                    <p>Conformement a la reglementation, les cookies ont une duree de vie maximale de 13 mois a compter de leur depot sur votre terminal. A l'expiration de ce delai, votre consentement sera a nouveau demande.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Modifications</h2>
                    <p>Nous pouvons etre amenes a modifier cette Politique des Cookies. Toute modification sera publiee sur cette page avec une date de mise a jour.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Contact</h2>
                    <p>Pour toute question concernant notre utilisation des cookies :</p>
                    <ul class="list-none mt-4 space-y-2">
                        <li><strong>Email :</strong> <a href="mailto:{{ config('seo.legal.dpo_email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.dpo_email') }}</a></li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
