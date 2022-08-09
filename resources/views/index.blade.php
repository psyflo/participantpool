@extends('layouts.app')

@section('content')
<div id="admin" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (App::currentLocale() === 'de')
                <p><small><a href="{{ url('/?lang=en') }}">English</a></small></p>
                <p>
                    Sehr geehrte Damen und Herren, <br/>
                    Das Institut für Psychologie der Universität Basel führt zu Forschungszwecken regelmässig Untersuchungen durch. Dazu werden Testpersonen benötigt.*<br/>
                    Haben Sie Interesse daran, an solchen Untersuchungen teilzunehmen?<br/>
                    Dann können Sie hier Ihre demographischen Daten hinterlegen (Dauer: max. 2 Minuten).<br/><br/>
                    Entlöhnung:<br/>
                    Für die Teilnahme an Onlinestudien werden jeweils Geschenkgutscheine für Online-Shops verlost.<br/>
                    Die persönliche Teilnahme an Laborstudien wird in der Regel mit Bargeld entlöhnt.<br/><br/>
                    Bitte füllen Sie den Fragebogen vollständig und wahrheitsgetreu aus. Nur so können wir unsere Teilnehmer gezielt zu Studien einladen. Sie werden dann für mögliche Teilnahmen kontaktiert. Ihre Daten werden vertraulich behandelt und nicht weitergegeben. Ausserdem lassen die jeweiligen Untersuchungen keine Rückschlüsse auf Ihre Identität zu.<br/><br/>
                    Besten Dank für Ihr Interesse.<br/>
                </p>
                <p>
                    <b>Dr. Florian Brühlmann</b><br>Fakultät für Psychologie<br>Abt. für Allgemeine Psychologie und Methodologie<br>Universität Basel<br>Missionsstrasse 62a<br>4055 Basel<br/>
                    <a href="mailto:{{ setting('participantpool.contact_email') }}">{{ setting('participantpool.contact_email') }}</a><br/>
                    <a href="https://www.hci-basel.ch" target="_blank">www.mmi-basel.ch</a>
                </p>
                <p><a class="btn btn-primary btn-lg btn-block" href="{{ route('index.register') }}">Jetzt registrieren</a></p>
                <p><small>* Die durchgeführten Studien können aus unterschiedlichen Quellen finanziert sein: Von der Universität, von Organisationen und Stiftungen zur Forschungsförderung, sowie von privatwirtschaftlichen Unternehmen oder öffentlichen Institutionen.</small></p>
            @else
                <p><small><a href="{{ url('/?lang=de') }}">Deutsch</a></small></p>
                <p>
                    Dear Sir or Madam, <br/>
                    The Institute for Psychology at the University of Basel is always looking for people to participate in scientific studies.*<br/>
                    Are you interested in participating in such studies?<br/>
                    If yes, you can sign up with your demographic details on this website. It takes less than 2 minutes.<br/><br/>
                    Compensation:<br/>
                    For the participation in online studies we usually raffle gift for online shops.<br/>
                    Participation in laboratory studies will usually be compensated with cash.<br/><br/>
                    Please fill in the questionnaire thoroughly and truthfully. Only then are we able to contact eligable participants directly. We will then approach you for matching studies. All your information will be treated confidentially and not forwarded to third parties. Unless stated otherwise, our online studies are anonymous.<br/><br/>
                    Many thanks for your interest.<br/>
                </p>
                <p>
                    <b>Dr. Florian Brühlmann</b><br>Department of Psychology<br>Center for Cognitive Psychology and Methodology<br>University of Basel<br>Missionsstrasse 62a<br>4055 Basel<br/>
                    <a href="mailto:{{ setting('participantpool.contact_email') }}">{{ setting('participantpool.contact_email') }}</a><br/>
                    <a href="https://www.hci-basel.ch" target="_blank">www.mmi-basel.ch</a>
                </p>
                <p><a class="btn btn-primary btn-lg btn-block" href="{{ route('index.register') }}">Register now</a></p>
                <p><small>* Studies conducted at our research group can be funded by different sources: By the University, by organisations and foundations supporting scientific research, as well as private companies or public institutions.</small></p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection
