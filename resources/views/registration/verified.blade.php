@extends('layouts.app')

@section('content')
<div id="admin" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h5>{{ __('registration.title') }}</h5>
            @if (App::currentLocale() === 'de')
                <p>Danke, Ihre E-Mail-Adresse wurde erfolgreich verifiziert!</p>
                <p>Wir werden Sie per E-Mail zu passenden Studien einladen. Wenn Sie irgendwelche Fragen haben, bitte kontaktieren Sie uns unter der folgenden Adresse:</p>
                <p>Florian Brühlmann<br>Fakultät für Psychologie<br>Abt. für Allgemeine Psychologie und Methodologie<br>Universität Basel<br>Missionsstrasse 62a<br>4055 Basel</p>
                <p><a href="mailto:{{ setting('participantpool.contact_email') }}">{{ setting('participantpool.contact_email') }}</a></p>
            @else
                <p>Thank you, your email address has been verified successfully!</p>
                <p>We will invite you to suitable studies by e-mail. If you have any questions, please contact us at the following address:</p>
                <p>Florian Brühlmann<br>Department of Psychology<br>Center for Cognitive Psychology and Methodology<br>University of Basel<br>Missionsstrasse 62a<br>4055 Basel</p>
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
