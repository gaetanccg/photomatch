<x-app-layout>
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Conditions Generales d'Utilisation</h1>

            <div class="prose prose-emerald max-w-none">
                <p class="text-gray-600 mb-8">Derniere mise a jour : {{ now()->format('d/m/Y') }}</p>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Objet</h2>
                    <p>Les presentes Conditions Generales d'Utilisation (ci-apres "CGU") ont pour objet de definir les modalites et conditions d'utilisation de la plateforme <strong>Trouve Ton Photographe</strong> (ci-apres "la Plateforme"), accessible a l'adresse {{ config('seo.site_url') }}.</p>
                    <p class="mt-4">La Plateforme est un service de mise en relation entre des clients recherchant des services de photographie (ci-apres "les Clients") et des photographes professionnels proposant leurs services (ci-apres "les Photographes").</p>
                    <p class="mt-4">L'utilisation de la Plateforme implique l'acceptation pleine et entiere des presentes CGU.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Inscription et compte utilisateur</h2>
                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">2.1 Conditions d'inscription</h3>
                    <p>L'inscription sur la Plateforme est gratuite et ouverte a toute personne physique majeure ou personne morale. L'utilisateur s'engage a fournir des informations exactes, completes et a jour lors de son inscription.</p>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">2.2 Types de comptes</h3>
                    <p>La Plateforme propose deux types de comptes :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><strong>Compte Client :</strong> permet de creer des projets photo, rechercher des photographes et envoyer des demandes de reservation.</li>
                        <li><strong>Compte Photographe :</strong> permet de creer un profil professionnel, gerer un portfolio, definir ses disponibilites et recevoir des demandes de reservation.</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">2.3 Securite du compte</h3>
                    <p>L'utilisateur est responsable de la confidentialite de ses identifiants de connexion et de toutes les activites effectuees depuis son compte. En cas d'utilisation frauduleuse, l'utilisateur doit en informer immediatement {{ config('seo.legal.company_name') }}.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Services proposes</h2>
                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">3.1 Pour les Clients</h3>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Creation et gestion de projets photo</li>
                        <li>Recherche de photographes par specialite, localisation, tarif et disponibilite</li>
                        <li>Consultation des profils, portfolios et avis des photographes</li>
                        <li>Envoi de demandes de reservation</li>
                        <li>Depot d'avis apres une mission</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">3.2 Pour les Photographes</h3>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Creation et gestion d'un profil professionnel</li>
                        <li>Publication d'un portfolio</li>
                        <li>Gestion des disponibilites</li>
                        <li>Reception et traitement des demandes de reservation</li>
                        <li>Reponse aux avis clients</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Engagements des utilisateurs</h2>
                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">4.1 Engagements communs</h3>
                    <p>Les utilisateurs s'engagent a :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Utiliser la Plateforme de maniere loyale et conforme a sa destination</li>
                        <li>Ne pas usurper l'identite d'un tiers</li>
                        <li>Ne pas diffuser de contenu illicite, diffamatoire, obscene ou portant atteinte aux droits de tiers</li>
                        <li>Respecter les droits de propriete intellectuelle</li>
                        <li>Ne pas tenter de compromettre la securite de la Plateforme</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">4.2 Engagements specifiques des Photographes</h3>
                    <p>Les Photographes s'engagent en outre a :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Etre en regle avec leurs obligations legales et fiscales</li>
                        <li>Disposer des assurances professionnelles necessaires</li>
                        <li>Fournir des informations exactes sur leurs competences et tarifs</li>
                        <li>Publier uniquement des photos dont ils sont les auteurs ou pour lesquelles ils disposent des droits</li>
                        <li>Respecter les engagements pris envers les Clients</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Mise en relation et reservations</h2>
                    <p>{{ config('seo.legal.company_name') }} agit uniquement en qualite d'intermediaire technique entre les Clients et les Photographes. A ce titre :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>{{ config('seo.legal.company_name') }} n'est pas partie aux contrats conclus entre les Clients et les Photographes</li>
                        <li>{{ config('seo.legal.company_name') }} ne garantit pas la disponibilite, la qualite ou la conformite des services des Photographes</li>
                        <li>Les transactions financieres sont effectuees directement entre les Clients et les Photographes</li>
                        <li>Les litiges eventuels doivent etre regles directement entre les parties concernees</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Avis et notations</h2>
                    <p>Les Clients peuvent deposer des avis et noter les Photographes apres une mission completee. Ces avis doivent :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Refleter une experience reelle et verifiable</li>
                        <li>Etre rediges de maniere courtoise et respectueuse</li>
                        <li>Ne pas contenir de propos diffamatoires, injurieux ou discriminatoires</li>
                        <li>Ne pas inclure de donnees personnelles de tiers</li>
                    </ul>
                    <p class="mt-4">{{ config('seo.legal.company_name') }} se reserve le droit de moderer ou supprimer tout avis ne respectant pas ces regles.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Propriete intellectuelle</h2>
                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">7.1 Contenu de la Plateforme</h3>
                    <p>L'ensemble des elements composant la Plateforme (structure, design, textes, logos, etc.) est protege par le droit de la propriete intellectuelle et appartient a {{ config('seo.legal.company_name') }}.</p>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">7.2 Contenu des utilisateurs</h3>
                    <p>Les utilisateurs conservent la propriete intellectuelle de leurs contenus (photos, textes, etc.). En publiant du contenu sur la Plateforme, ils accordent a {{ config('seo.legal.company_name') }} une licence non exclusive, gratuite et mondiale pour utiliser, reproduire et afficher ce contenu dans le cadre du fonctionnement de la Plateforme.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">8. Responsabilite</h2>
                    <p>{{ config('seo.legal.company_name') }} met tout en oeuvre pour assurer la disponibilite et le bon fonctionnement de la Plateforme, mais ne peut garantir une disponibilite permanente.</p>
                    <p class="mt-4">{{ config('seo.legal.company_name') }} ne saurait etre tenue responsable :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Des contenus publies par les utilisateurs</li>
                        <li>Des relations et transactions entre Clients et Photographes</li>
                        <li>Des dommages directs ou indirects resultant de l'utilisation de la Plateforme</li>
                        <li>Des interruptions de service pour maintenance ou raisons techniques</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">9. Suspension et resiliation</h2>
                    <p>{{ config('seo.legal.company_name') }} se reserve le droit de suspendre ou supprimer tout compte en cas de :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Non-respect des presentes CGU</li>
                        <li>Fourniture d'informations fausses ou trompeuses</li>
                        <li>Comportement frauduleux ou nuisible</li>
                        <li>Demande des autorites competentes</li>
                    </ul>
                    <p class="mt-4">L'utilisateur peut a tout moment supprimer son compte depuis les parametres de son espace personnel.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">10. Modification des CGU</h2>
                    <p>{{ config('seo.legal.company_name') }} se reserve le droit de modifier les presentes CGU a tout moment. Les utilisateurs seront informes des modifications par tout moyen approprie. La poursuite de l'utilisation de la Plateforme vaut acceptation des nouvelles CGU.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">11. Droit applicable et litiges</h2>
                    <p>Les presentes CGU sont soumises au droit francais. En cas de litige, les parties s'engagent a rechercher une solution amiable avant toute action judiciaire. A defaut, les tribunaux francais seront seuls competents.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">12. Contact</h2>
                    <p>Pour toute question concernant les presentes CGU, vous pouvez nous contacter :</p>
                    <ul class="list-none mt-4 space-y-2">
                        <li><strong>Email :</strong> <a href="mailto:{{ config('seo.legal.email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.email') }}</a></li>
                        <li><strong>Adresse :</strong> {{ config('seo.legal.address') }}</li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
