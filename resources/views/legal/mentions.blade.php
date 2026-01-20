<x-app-layout>
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Mentions Legales</h1>

            <div class="prose prose-emerald max-w-none">
                <p class="text-gray-600 mb-8">Derniere mise a jour : {{ now()->format('d/m/Y') }}</p>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Editeur du site</h2>
                    <p>Le site <strong>{{ config('seo.site_url') }}</strong> est edite par :</p>
                    <ul class="list-none mt-4 space-y-2 text-gray-700">
                        <li><strong>Raison sociale :</strong> {{ config('seo.legal.company_name') }}</li>
                        <li><strong>Forme juridique :</strong> {{ config('seo.legal.company_type') }}</li>
                        <li><strong>Capital social :</strong> {{ config('seo.legal.company_capital') }} euros</li>
                        <li><strong>Siege social :</strong> {{ config('seo.legal.address') }}</li>
                        <li><strong>SIRET :</strong> {{ config('seo.legal.siret') }}</li>
                        <li><strong>RCS :</strong> {{ config('seo.legal.rcs') }}</li>
                        <li><strong>Numero de TVA intracommunautaire :</strong> {{ config('seo.legal.vat_number') }}</li>
                        <li><strong>Directeur de la publication :</strong> {{ config('seo.legal.director') }}</li>
                        <li><strong>Email :</strong> <a href="mailto:{{ config('seo.legal.email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.email') }}</a></li>
                        <li><strong>Telephone :</strong> {{ config('seo.legal.phone') }}</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Hebergement</h2>
                    <p>Le site est heberge par :</p>
                    <ul class="list-none mt-4 space-y-2 text-gray-700">
                        <li><strong>Hebergeur :</strong> {{ config('seo.legal.host_name') }}</li>
                        <li><strong>Adresse :</strong> {{ config('seo.legal.host_address') }}</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Propriete intellectuelle</h2>
                    <p>L'ensemble du contenu de ce site (textes, images, graphismes, logo, icones, sons, logiciels, etc.) est la propriete exclusive de {{ config('seo.legal.company_name') }} ou de ses partenaires et est protege par les lois francaises et internationales relatives a la propriete intellectuelle.</p>
                    <p class="mt-4">Toute reproduction, representation, modification, publication, transmission, ou plus generalement toute exploitation non autorisee du site ou de son contenu, par quelque procede que ce soit, est interdite sans l'autorisation ecrite prealable de {{ config('seo.legal.company_name') }}.</p>
                    <p class="mt-4">Toute exploitation non autorisee du site ou de son contenu serait constitutive d'une contrefacon sanctionnee par les articles L.335-2 et suivants du Code de la Propriete Intellectuelle.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Donnees personnelles</h2>
                    <p>Les informations relatives au traitement de vos donnees personnelles sont detaillees dans notre <a href="{{ route('legal.privacy') }}" class="text-emerald-600 hover:underline">Politique de Confidentialite</a>.</p>
                    <p class="mt-4">Conformement au Reglement General sur la Protection des Donnees (RGPD), vous disposez de droits sur vos donnees personnelles. Pour exercer ces droits, vous pouvez nous contacter a l'adresse : <a href="mailto:{{ config('seo.legal.dpo_email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.dpo_email') }}</a></p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Cookies</h2>
                    <p>Le site utilise des cookies pour ameliorer l'experience utilisateur. Pour en savoir plus sur notre utilisation des cookies, veuillez consulter notre <a href="{{ route('legal.cookies') }}" class="text-emerald-600 hover:underline">Politique des Cookies</a>.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Liens hypertextes</h2>
                    <p>Le site peut contenir des liens vers d'autres sites internet. {{ config('seo.legal.company_name') }} n'exerce aucun controle sur ces sites et n'assume aucune responsabilite quant a leur contenu.</p>
                    <p class="mt-4">La mise en place d'un lien hypertexte vers notre site necessite une autorisation prealable et ecrite. Pour toute demande, veuillez nous contacter a l'adresse : <a href="mailto:{{ config('seo.legal.email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.email') }}</a></p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Limitation de responsabilite</h2>
                    <p>{{ config('seo.legal.company_name') }} s'efforce d'assurer au mieux l'exactitude et la mise a jour des informations diffusees sur le site. Toutefois, {{ config('seo.legal.company_name') }} ne peut garantir l'exactitude, la precision ou l'exhaustivite des informations mises a disposition sur le site.</p>
                    <p class="mt-4">En consequence, {{ config('seo.legal.company_name') }} decline toute responsabilite pour toute imprecision, inexactitude ou omission portant sur des informations disponibles sur le site, ainsi que pour tous dommages resultant d'une intrusion frauduleuse d'un tiers.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">8. Droit applicable</h2>
                    <p>Les presentes mentions legales sont soumises au droit francais. En cas de litige, les tribunaux francais seront seuls competents.</p>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
