@extends('layouts.app')

@section('content')
<div id="admin" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h5>{{ __('registration.title') }}</h5>
            @if (App::currentLocale() === 'de')
                <p>Danke für Ihre Registrierung! Sie sollten bereits eine Bestätigung erhalten haben, bitte verifizieren Sie Ihre E-Mail-Adresse mit dem enthaltenen Link.</p>
                <p>Florian Brühlmann<br>Department of Psychology<br>Center for Cognitive Psychology and Methodology<br>University of Basel<br>Missionsstrasse 62a<br>4055 Basel</p>
                <p><a href="mailto:{{ setting('participantpool.contact_email') }}">{{ setting('participantpool.contact_email') }}</a></p>
            @else
                <p>Thank you for your registration! You should already have received a confirmation, please use the contained link to verify your email address.</p>
                <p>Florian Brühlmann<br>Fakultät für Psychologie<br>Abt. für Allgemeine Psychologie und Methodologie<br>Universität Basel<br>Missionsstrasse 62a<br>4055 Basel</p>
                <p><a href="mailto:{{ setting('participantpool.contact_email') }}">{{ setting('participantpool.contact_email') }}</a></p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection