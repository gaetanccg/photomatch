@extends('emails.layout')

@section('title', 'Nouvelle demande de réservation')

@section('content')
    <h2>Nouvelle demande de réservation</h2>

    <p>Bonjour {{ $photographer->user->name }},</p>

    <p>Bonne nouvelle ! Vous avez reçu une nouvelle demande de réservation pour votre service de photographie.</p>

    <div class="info-box">
        <table>
            <tr>
                <td class="info-label">Projet</td>
                <td class="info-value">{{ $bookingRequest->project->title }}</td>
            </tr>
            <tr>
                <td class="info-label">Client</td>
                <td class="info-value">{{ $bookingRequest->project->client->name }}</td>
            </tr>
            <tr>
                <td class="info-label">Type</td>
                <td class="info-value">{{ $projectTypeLabel }}</td>
            </tr>
            <tr>
                <td class="info-label">Lieu</td>
                <td class="info-value">{{ $bookingRequest->project->location }}</td>
            </tr>
            @if($bookingRequest->project->date)
            <tr>
                <td class="info-label">Date</td>
                <td class="info-value">{{ \Carbon\Carbon::parse($bookingRequest->project->date)->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($bookingRequest->proposed_price)
            <tr>
                <td class="info-label">Budget</td>
                <td class="info-value" style="color: #10b981; font-weight: 600;">{{ number_format($bookingRequest->proposed_price, 0, ',', ' ') }} €</td>
            </tr>
            @endif
        </table>
    </div>

    @if($bookingRequest->client_message)
    <div class="message-box">
        <p>"{{ $bookingRequest->client_message }}"</p>
    </div>
    @endif

    <div class="cta-container">
        <a href="{{ url('/photographer/requests/' . $bookingRequest->id) }}" class="cta-button">
            Voir la demande
        </a>
    </div>

    <p style="text-align: center; color: #6b7280; font-size: 14px;">
        Connectez-vous à votre espace pour accepter ou décliner cette demande.
    </p>
@endsection
