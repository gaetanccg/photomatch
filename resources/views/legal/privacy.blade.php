<x-app-layout>
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Politique de Confidentialite</h1>

            <div class="prose prose-emerald max-w-none">
                <p class="text-gray-600 mb-8">Derniere mise a jour : {{ now()->format('d/m/Y') }}</p>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Introduction</h2>
                    <p>{{ config('seo.legal.company_name') }} (ci-apres "nous", "notre", "nos") s'engage a proteger la vie privee des utilisateurs de la plateforme Trouve Ton Photographe (ci-apres "la Plateforme").</p>
                    <p class="mt-4">Cette Politique de Confidentialite explique comment nous collectons, utilisons, stockons et protegeons vos donnees personnelles conformement au Reglement General sur la Protection des Donnees (RGPD) et a la loi Informatique et Libertes.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Responsable du traitement</h2>
                    <p>Le responsable du traitement des donnees personnelles est :</p>
                    <ul class="list-none mt-4 space-y-2 text-gray-700">
                        <li><strong>{{ config('seo.legal.company_name') }}</strong></li>
                        <li>{{ config('seo.legal.address') }}</li>
                        <li>Email : <a href="mailto:{{ config('seo.legal.dpo_email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.dpo_email') }}</a></li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Donnees collectees</h2>
                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">3.1 Donnees fournies par l'utilisateur</h3>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><strong>Donnees d'identification :</strong> nom, prenom, adresse email, numero de telephone</li>
                        <li><strong>Donnees de profil :</strong> photo de profil, biographie, competences, experience</li>
                        <li><strong>Donnees professionnelles (Photographes) :</strong> portfolio, tarifs, specialites, disponibilites, localisation</li>
                        <li><strong>Donnees de projet (Clients) :</strong> description du projet, lieu, date, budget</li>
                        <li><strong>Communications :</strong> messages echanges via la Plateforme, avis et commentaires</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-800 mt-4 mb-2">3.2 Donnees collectees automatiquement</h3>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><strong>Donnees de connexion :</strong> adresse IP, type de navigateur, systeme d'exploitation</li>
                        <li><strong>Donnees de navigation :</strong> pages visitees, date et heure de visite, duree de session</li>
                        <li><strong>Donnees de cookies :</strong> voir notre <a href="{{ route('legal.cookies') }}" class="text-emerald-600 hover:underline">Politique des Cookies</a></li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Finalites du traitement</h2>
                    <p>Vos donnees personnelles sont traitees pour les finalites suivantes :</p>
                    <table class="w-full mt-4 border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Finalite</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Base legale</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Creation et gestion de votre compte</td>
                                <td class="border border-gray-300 px-4 py-2">Execution du contrat</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Mise en relation entre Clients et Photographes</td>
                                <td class="border border-gray-300 px-4 py-2">Execution du contrat</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Envoi de notifications liees au service</td>
                                <td class="border border-gray-300 px-4 py-2">Interet legitime</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Amelioration de nos services</td>
                                <td class="border border-gray-300 px-4 py-2">Interet legitime</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Envoi de communications marketing</td>
                                <td class="border border-gray-300 px-4 py-2">Consentement</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Respect des obligations legales</td>
                                <td class="border border-gray-300 px-4 py-2">Obligation legale</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Partage des donnees</h2>
                    <p>Vos donnees peuvent etre partagees avec :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><strong>Les autres utilisateurs :</strong> les informations de profil des Photographes sont visibles par les Clients, et inversement dans le cadre d'une demande de reservation</li>
                        <li><strong>Nos prestataires techniques :</strong> hebergement, analyse, envoi d'emails (dans le cadre de contrats conformes au RGPD)</li>
                        <li><strong>Les autorites competentes :</strong> en cas d'obligation legale</li>
                    </ul>
                    <p class="mt-4">Nous ne vendons jamais vos donnees personnelles a des tiers.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Transferts internationaux</h2>
                    <p>Vos donnees sont principalement traitees au sein de l'Union Europeenne. En cas de transfert vers des pays tiers, nous nous assurons que des garanties appropriees sont en place (clauses contractuelles types, certification adequation, etc.).</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Duree de conservation</h2>
                    <table class="w-full mt-4 border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Type de donnees</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Duree de conservation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Donnees de compte</td>
                                <td class="border border-gray-300 px-4 py-2">Duree du compte + 3 ans apres suppression</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Donnees de transaction</td>
                                <td class="border border-gray-300 px-4 py-2">10 ans (obligation legale)</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Donnees de connexion</td>
                                <td class="border border-gray-300 px-4 py-2">1 an</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Cookies</td>
                                <td class="border border-gray-300 px-4 py-2">13 mois maximum</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">8. Vos droits</h2>
                    <p>Conformement au RGPD, vous disposez des droits suivants :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li><strong>Droit d'acces :</strong> obtenir une copie de vos donnees personnelles</li>
                        <li><strong>Droit de rectification :</strong> corriger des donnees inexactes ou incompletes</li>
                        <li><strong>Droit a l'effacement :</strong> demander la suppression de vos donnees</li>
                        <li><strong>Droit a la limitation :</strong> restreindre le traitement de vos donnees</li>
                        <li><strong>Droit a la portabilite :</strong> recevoir vos donnees dans un format structure</li>
                        <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos donnees</li>
                        <li><strong>Droit de retirer votre consentement :</strong> a tout moment pour les traitements bases sur le consentement</li>
                    </ul>
                    <p class="mt-4">Pour exercer ces droits, contactez-nous a : <a href="mailto:{{ config('seo.legal.dpo_email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.dpo_email') }}</a></p>
                    <p class="mt-4">Vous disposez egalement du droit d'introduire une reclamation aupres de la CNIL (Commission Nationale de l'Informatique et des Libertes).</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">9. Securite des donnees</h2>
                    <p>Nous mettons en oeuvre des mesures techniques et organisationnelles appropriees pour proteger vos donnees personnelles :</p>
                    <ul class="list-disc ml-6 mt-2 space-y-2">
                        <li>Chiffrement des donnees en transit (HTTPS/TLS)</li>
                        <li>Chiffrement des mots de passe (hachage)</li>
                        <li>Acces restreint aux donnees personnelles</li>
                        <li>Sauvegardes regulieres</li>
                        <li>Surveillance et detection des incidents</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">10. Modifications</h2>
                    <p>Nous pouvons modifier cette Politique de Confidentialite a tout moment. En cas de modification substantielle, nous vous en informerons par email ou via la Plateforme. La date de derniere mise a jour est indiquee en haut de ce document.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">11. Contact</h2>
                    <p>Pour toute question concernant cette Politique de Confidentialite ou le traitement de vos donnees personnelles :</p>
                    <ul class="list-none mt-4 space-y-2">
                        <li><strong>Delegue a la Protection des Donnees :</strong> <a href="mailto:{{ config('seo.legal.dpo_email') }}" class="text-emerald-600 hover:underline">{{ config('seo.legal.dpo_email') }}</a></li>
                        <li><strong>Adresse :</strong> {{ config('seo.legal.address') }}</li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
