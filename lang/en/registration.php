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

    'title' => 'Registration',
    'form' => [
        'firstname' => 'First name',
        'name' => 'Last name',
        'email' => 'Email',
        'gender' => 'What is your gender?',
        'birthdate' => 'Date of birth',
        'location' => 'What is your city of residence?',
        'education' => 'What is your highest educational qualification?',
        'language' => 'What is your first language?',
        'survey_languages' => 'In which languages would you feel comfortable taking a survey?',
        'study_interest' => 'What type of studies are you interested in?',
        'info' => [
            'birthdate' => 'You have to be at least 18 years of age.',
            'education' => 'Choose your highest educational qualification and if you have a university or college degree your topic too.',
        ],
        'action' => [
            'register' => 'Register'
        ],
    ],
    'options' => [
        'gender' => [
            'F' => 'Woman',
            'M' => 'Man',
            'N' => 'Non-binary',
            'D' => 'Prefer not to disclose',
            'S' => 'Prefer to self-describe'
        ],
        'education' => [
            'UNI' => 'University Degree',
            'FH' => 'College Degree',
            'HIGH' => 'Highschool',
            'APP' => 'Apprenticeship',
            'BASIC' => 'Basic Education',
            'NONE' => 'None'
        ],
        'topic' => [
            'MED' => 'Medicine',
            'SCIENCE' => 'Natural Sciences',
            'PSYCH' => 'Psychology',
            'LAW' => 'Law',
            'LANG' => 'Languages',
            'ECO' => 'Economics',
            'OTHER' => 'Other'
        ],
        'language' => [
            'DE' => 'German',
            'EN' => 'English',
            'FR' => 'French',
            'IT' => 'Italian',
            'OO' => 'Other',
        ],
        'interest' => [
            'O' => 'Online studies',
            'L' => 'Studies in our lab'
        ],
    ],
    'verify' => [
        'subject' => 'Your Registration',
    ],
    'update' => [
        'title' => 'Your Registration',
        'save' => 'Save',
    ],
];