@extends('emails.layout')

@section('title', 'Demande de réservation annulée')

@section('content')
    <h2>Demande annulée</h2>

    @if($cancelledBy === 'client')
        <p>Bonjour {{ $photographer->user->name }},</p>

        <p>Nous vous informons que <strong>{{ $client->name }}</strong> a annulé sa demande de réservation pour le projet <strong>"{{ $bookingRequest->project->title }}"</strong>.</p>

        <div class="alert-warning">
            <p style="font-weight: 500; margin-bottom: 8px;">Détails du projet annulé :</p>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Projet : {{ $bookingRequest->project->title }}</li>
                <li>Type : {{ ucfirst($bookingRequest->project->project_type) }}</li>
                @if($bookingRequest->project->event_date)
                    <li>Date prévue : {{ $bookingRequest->project->event_date->format('d/m/Y') }}</li>
                @endif
            </ul>
        </div>

        <p>Cette demande n'est plus disponible. De nouvelles opportunités arriveront bientôt !</p>

        <div class="cta-container">
            <a href="{{ route('photographer.requests.index') }}" class="cta-button">
                Voir mes autres demandes
            </a>
        </div>
    @else
        <p>Bonjour {{ $client->name }},</p>

        <p>Nous vous informons que <strong>{{ $photographer->user->name }}</strong> a annulé la demande pour le projet <strong>"{{ $bookingRequest->project->title }}"</strong>.</p>

        <p>Ne vous découragez pas ! Notre plateforme compte de nombreux photographes talentueux qui seraient ravis de travailler avec vous.</p>

        <div class="cta-container">
            <a href="{{ url('/search-photographers') }}" class="cta-button">
                Trouver un autre photographe
            </a>
        </div>

        <div style="background-color: #f0fdf4; border-radius: 12px; padding: 20px; margin-top: 24px; text-align: center;">
            <p style="color: #166534; margin: 0; font-size: 15px;">
                <strong>Astuce :</strong> Essayez d'élargir votre recherche pour trouver plus de photographes disponibles.
            </p>
        </div>
    @endif

    <p style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 24px;">
        Nous restons à vos côtés pour vos projets photo !
    </p>
@endsection
