@extends('emails.layout')

@section('title', 'Votre demande a été déclinée')

@section('content')
    <h2>Demande déclinée</h2>

    <p>Bonjour {{ $client->name }},</p>

    <p>Nous sommes désolés de vous informer que votre demande pour le projet <strong>"{{ $bookingRequest->project->title }}"</strong> a été déclinée par {{ $photographer->user->name }}.</p>

    @if($bookingRequest->photographer_response)
    <div class="alert-warning">
        <p style="font-weight: 500; margin-bottom: 8px;">Raison indiquée :</p>
        <p>"{{ $bookingRequest->photographer_response }}"</p>
    </div>
    @endif

    <p>Ne vous découragez pas ! Notre plateforme compte de nombreux photographes talentueux qui seraient ravis de travailler avec vous.</p>

    <div class="cta-container">
        <a href="{{ url('/search-photographers?project_id=' . $bookingRequest->project->id) }}" class="cta-button">
            Trouver un autre photographe
        </a>
    </div>

    <div style="background-color: #f0fdf4; border-radius: 12px; padding: 20px; margin-top: 24px; text-align: center;">
        <p style="color: #166534; margin: 0; font-size: 15px;">
            <strong>Astuce :</strong> Essayez d'élargir votre recherche ou d'ajuster vos critères pour trouver plus de photographes disponibles.
        </p>
    </div>

    <p style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 24px;">
        Nous restons à vos côtés pour trouver le photographe idéal !
    </p>
@endsection
