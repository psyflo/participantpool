@extends('layouts.app')

@section('content')
<div id="admin" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h5>{{ __('registration.update.title') }}</h5>
            @if (App::currentLocale() === 'de')
                <p>Danke für die Aktualisierung Ihrer Registrierung!</p>
                <p>Florian Brühlmann<br>Fakultät für Psychologie<br>Abt. für Allgemeine Psychologie und Methodologie<br>Universität Basel<br>Missionsstrasse 62a<br>4055 Basel</p>
                <p><a href="mailto:{{ setting('participantpool.contact_email') }}">{{ setting('participantpool.contact_email') }}</a></p>
            @else
                <p>Thank you for updating your registration!</p>
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