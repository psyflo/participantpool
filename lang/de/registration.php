<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Registration Prozess Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which are used during
    | the whole registration process.
    |
    */

    'title' => 'Registrierung',
    'form' => [
        'firstname' => 'Vorname',
        'name' => 'Name',
        'email' => 'E-Mail',
        'gender' => 'Was ist Ihr Geschlecht?',
        'birthdate' => 'Geburtsdatum',
        'location' => 'Was ist Ihr Wohnort?',
        'education' => 'Was ist Ihre höchste Ausbildung?',
        'language' => 'Was ist Ihre Muttersprache?',
        'survey_languages' => 'In welchen Sprachen können Sie einen Fragebogen ausfüllen?',
        'study_interest' => 'An welcher Art von Studien sind Sie interessiert?',
        'info' => [
            'birthdate' => 'Sie müssen mindestens 18 Jahre sein.',
            'education' => 'Bitte wählen Sie Ihre höchste Ausbildung und falls diese an einer Universität oder FH war, bitte auch das Thema angeben.'
        ],
        'action' => [
            'register' => 'Registrieren'
        ],
    ],
    'options' => [
        'gender' => [
            'F' => 'Weiblich',
            'M' => 'Männlich',
            'N' => 'Nichtbinär',
            'D' => 'Keine Angabe',
            'S' => 'Anderes'
        ],
        'education' => [
            'UNI' => 'Universität',
            'FH' => 'Fachhochschule',
            'HIGH' => 'Matura / Abitur',
            'APP' => 'Lehre',
            'BASIC' => 'Obligatorische Schule',
            'NONE' => 'Keine'
        ],
        'topic' => [
            'MED' => 'Medizin',
            'SCIENCE' => 'Naturwissenschaften',
            'PSYCH' => 'Psychologie',
            'LAW' => 'Recht',
            'LANG' => 'Sprachen',
            'ECO' => 'Wirtschaft',
            'OTHER' => 'Andere'
        ],
        'language' => [
            'DE' => 'Deutsch',
            'EN' => 'Englisch',
            'FR' => 'Französisch',
            'IT' => 'Italienisch',
            'OO' => 'Andere',
        ],
        'interest' => [
            'O' => 'Online-Studien',
            'L' => 'Laborstudien vor Ort'
        ],
    ],
    'verify' => [
        'subject' => 'Ihre Registrierung',
    ],
    'update' => [
        'title' => 'Ihre Registrierung',
        'save' => 'Aktualisieren',
    ],

];