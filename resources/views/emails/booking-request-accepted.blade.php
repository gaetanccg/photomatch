@extends('emails.layout')

@section('title', 'Votre demande a été acceptée')

@section('content')
    <h2 style="color: #10b981;">Excellente nouvelle !</h2>

    <p>Bonjour {{ $client->name }},</p>

    <p>Votre demande pour le projet <strong>"{{ $bookingRequest->project->title }}"</strong> a été acceptée par le photographe !</p>

    <div class="info-box">
        <table>
            <tr>
                <td class="info-label">Photographe</td>
                <td class="info-value">{{ $photographer->user->name }}</td>
            </tr>
            @if($photographer->user->phone)
            <tr>
                <td class="info-label">Téléphone</td>
                <td class="info-value">
                    <a href="tel:{{ $photographer->user->phone }}" style="color: #10b981; text-decoration: none;">
                        {{ $photographer->user->phone }}
                    </a>
                </td>
            </tr>
            @endif
            @if($photographer->user->email)
            <tr>
                <td class="info-label">Email</td>
                <td class="info-value">
                    <a href="mailto:{{ $photographer->user->email }}" style="color: #10b981; text-decoration: none;">
                        {{ $photographer->user->email }}
                    </a>
                </td>
            </tr>
            @endif
            @if($bookingRequest->proposed_price)
            <tr>
                <td class="info-label">Tarif</td>
                <td class="info-value" style="color: #10b981; font-weight: 600;">{{ number_format($bookingRequest->proposed_price, 0, ',', ' ') }} €</td>
            </tr>
            @endif
        </table>
    </div>

    @if($bookingRequest->photographer_response)
    <div class="message-box">
        <p style="font-style: normal; font-weight: 500; margin-bottom: 8px; color: #047857;">Message du photographe :</p>
        <p>"{{ $bookingRequest->photographer_response }}"</p>
    </div>
    @endif

    <p>Vous pouvez maintenant prendre contact avec le photographe pour finaliser les détails de votre projet.</p>

    <div class="cta-container">
        <a href="{{ url('/client/requests/' . $bookingRequest->id) }}" class="cta-button">
            Voir les détails
        </a>
    </div>

    <p style="text-align: center; color: #6b7280; font-size: 14px;">
        Nous vous souhaitons une excellente collaboration !
    </p>
@endsection
