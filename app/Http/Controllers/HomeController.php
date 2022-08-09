<?php

namespace App\Http\Controllers;

use App\Mail\ParticipantVerifyEmail;
use App\Models\Participant;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'register', 'register_save', 'register_verify', 'register_update', 'register_update_save']);
    }

    /**
     * Show application start page.
     *
     * @return void
     */
    public function index(Request $request)
    {
        /*
         * Available languages
         */
        $languages = ['en', 'de'];

        if ($request->session()->has('locale'))
        {
            /*
             * Get query locale or if missing use locale from current session
             */
            $locale = $request->query('lang', $request->session()->get('locale'));
        }
        else
        {
            /*
             * Get query locale or if missing preferred language based on client browser configuration
             */
            $locale = $request->query('lang', $request->getPreferredLanguage($languages));
        }

        /*
         * Validate locale or change it to fallback locale
         */
        $locale = in_array($locale, $languages) ? $locale : config('app.fallback_locale');

        /*
         * Store locale in session for whole registration process
         */
        $request->session()->put('locale', $locale);

        /*
         * Set locale
         */
        App::setLocale($locale);

        return view('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function admin()
    {
        return view('admin');
    }

    /**
     * Show registration page.
     *
     * @return void
     */
    public function register(Request $request)
    {
        /*
         * Set locale from session or use fallback
         */
        App::setLocale($request->session()->get('locale', config('app.fallback_locale')));

        /*
         * Build options
         */
        $genders = ['F' => __('registration.options.gender.F'), 'M' => __('registration.options.gender.M'), 'N' => __('registration.options.gender.N'), 'D' => __('registration.options.gender.D'), 'S' => __('registration.options.gender.S')];
        $educations = ['' => '', 'UNI' => __('registration.options.education.UNI'), 'FH' => __('registration.options.education.FH'), 'HIGH' => __('registration.options.education.HIGH'), 'APP' => __('registration.options.education.APP'), 'BASIC' => __('registration.options.education.BASIC'), 'NONE' => __('registration.options.education.NONE')];
        $topics = ['' => '', 'MED' => __('registration.options.topic.MED'), 'SCIENCE' => __('registration.options.topic.SCIENCE'), 'PSYCH' => __('registration.options.topic.PSYCH'), 'LAW' => __('registration.options.topic.LAW'), 'LANG' => __('registration.options.topic.LANG'), 'ECO' => __('registration.options.topic.ECO'), 'OTHER' => __('registration.options.topic.OTHER')];
        $languages = ['DE' => __('registration.options.language.DE'), 'EN' => __('registration.options.language.EN'), 'FR' => __('registration.options.language.FR'), 'IT' => __('registration.options.language.IT'), 'OO' => __('registration.options.language.OO')];
        $interests = ['O' => __('registration.options.interest.O'), 'L' => __('registration.options.interest.L')];
        
        return view('registration.register', ['genders' => $genders, 'educations' => $educations, 'topics' => $topics, 'languages' => $languages, 'interests' => $interests]);
    }

    /**
     * Register participant.
     *
     * @param Request $request
     * 
     * @return void
     */
    public function register_save(Request $request)
    {
        try
        {
            /*
             * Get locale from session or use fallback
             */
            $locale = $request->session()->get('locale', config('app.fallback_locale'));

            /*
             * Set locale
             */
            App::setLocale($locale);

            /*
             * Validate form input
             */
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'firstname' => 'required|string|nullable',
                'email' => 'required|string|email|unique:participants,email',
                'gender' => ['string', Rule::in(['F', 'M', 'N', 'D', 'S'])],
                'birthdate' => 'date|nullable|before:-18 years',
                'location' => 'string|nullable',
                'education' => 'string|nullable',
                'education_topic' => 'string|nullable',
                'language' => 'required|string',
                'survey_languages' => 'required|array|min:1',
                'study_interest' => 'array|nullable',
            ]);
     
            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }
            
            /*
             * Read input and create participant
             */
            $participant = new Participant();
            
            $participant->firstname = $request->input('firstname', null);
            $participant->name = $request->input('name', null);
            $participant->email = $request->input('email', null);
            $participant->gender = $request->input('gender', null);
            $participant->location = $request->input('location', null);
            $participant->education = $request->input('education', null);
            $participant->language = $request->input('language', null);

            /*
             * Parse date and set to birthdate property
             */
            $birthdate = Carbon::parse($request->input('birthdate', null));
            $participant->birthdate = $birthdate->toDateString();

            /*
             * Only save topic if selected education is university or college degree
             */
            if ($participant->education === 'UNI' || $participant->education === 'FH')
            {
                $participant->education_topic = $request->input('education_topic', null);
            }
            
            /*
             * Check if any survey languages are selected, otherwise input does not exists and no need to save it
             */
            if ($request->input('survey_languages', null) !== null)
            {
                $participant->survey_languages = implode(',', $request->input('survey_languages', []));
            }

            /*
             * Check if any study interests are selected, otherwise input does not exists and no need to save it
             */
            if ($request->input('study_interest', null) !== null)
            {
                $participant->study_interest = implode(',', $request->input('study_interest', []));
            }
            
            /*
             * Save participant
             */
            $participant->save();

            /*
             * Get link expiration days
             */
            $expiration = intval(setting('participantpool.updatelink_days'));

            /*
             * Build email verification link
             */
            $verificationLink = URL::temporarySignedRoute('index.register.verify', now()->addMinutes($expiration * 1440), ['id' => $participant->id, 'locale' => $locale]);
            
            /*
             * Send verification email
             */
            Mail::to($participant->email)->send(new ParticipantVerifyEmail($participant, __('registration.verify.subject'), $verificationLink));

            return response()->view('registration.registered');
        }
        catch(Exception $ex)
        {
            return response()->view('index', ['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Verfiy participant's email address.
     *
     * @return void
     */
    public function register_verify($id, $locale, Request $request)
    {
        /*
         * Set locale
         */
        App::setLocale($locale);

        if ($request->hasValidSignature())
        {
            $participant = Participant::find($id);

            if ($participant !== null)
            {
                $participant->email_verified = Carbon::now();
                $participant->save();
            }

            return response()->view('registration.verified');
        }

        abort(401);
    }

    /**
     * Update participant registration.
     *
     * @return void
     */
    public function register_update($id, $locale, Request $request)
    {
        /*
         * Store locale in session for whole update process
         */
        $request->session()->put('locale', $locale);

        /*
         * Set locale
         */
        App::setLocale($locale);

        /*
         * Build options
         */
        $genders = ['F' => __('registration.options.gender.F'), 'M' => __('registration.options.gender.M'), 'N' => __('registration.options.gender.N'), 'D' => __('registration.options.gender.D'), 'S' => __('registration.options.gender.S')];
        $educations = ['' => '', 'UNI' => __('registration.options.education.UNI'), 'FH' => __('registration.options.education.FH'), 'HIGH' => __('registration.options.education.HIGH'), 'APP' => __('registration.options.education.APP'), 'BASIC' => __('registration.options.education.BASIC'), 'NONE' => __('registration.options.education.NONE')];
        $topics = ['' => '', 'MED' => __('registration.options.topic.MED'), 'SCIENCE' => __('registration.options.topic.SCIENCE'), 'PSYCH' => __('registration.options.topic.PSYCH'), 'LAW' => __('registration.options.topic.LAW'), 'LANG' => __('registration.options.topic.LANG'), 'ECO' => __('registration.options.topic.ECO'), 'OTHER' => __('registration.options.topic.OTHER')];
        $languages = ['DE' => __('registration.options.language.DE'), 'EN' => __('registration.options.language.EN'), 'FR' => __('registration.options.language.FR'), 'IT' => __('registration.options.language.IT'), 'OO' => __('registration.options.language.OO')];
        $interests = ['O' => __('registration.options.interest.O'), 'L' => __('registration.options.interest.L')];

        if ($request->hasValidSignature())
        {
            $participant = Participant::find($id);

            return response()->view('registration.update', ['participant' => $participant, 'genders' => $genders, 'educations' => $educations, 'topics' => $topics, 'languages' => $languages, 'interests' => $interests]);
        }

        abort(401);
    }

    /**
     * Save updated participant registration.
     *
     * @return void
     */
    public function register_update_save($id, Request $request)
    {
        try
        {
            /*
             * Get locale from session or use fallback
             */
            $locale = $request->session()->get('locale', config('app.fallback_locale'));

            /*
             * Set locale from session or use fallback
             */
            App::setLocale($locale);

            /*
             * Validate signature
             */
            if ($request->hasValidSignature() === false)
            {
                abort(401);
            }

            /*
             * Validate form input
             */
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'firstname' => 'required|string|nullable',
                'email' => 'required|string|email',
                'gender' => ['string', Rule::in(['F', 'M', 'N', 'D', 'S'])],
                'birthdate' => 'date|nullable|before:-18 years',
                'location' => 'string|nullable',
                'education' => 'string|nullable',
                'education_topic' => 'string|nullable',
                'language' => 'required|string',
                'survey_languages' => 'required|array|min:1',
                'study_interest' => 'array|nullable',
            ]);
     
            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }
            
            /*
             * Read input and create participant
             */
            $participant = Participant::find($id);

            /*
             * Check if email address has changed and revalidate
             */
            $emailChanged = ($participant->email !== $request->input('email', null));

            if ($emailChanged)
            {
                $validator = Validator::make(['email' => $request->input('email', null)], ['email' => 'required|string|email|unique:participants,email',]);

                if ($validator->fails())
                {
                    return back()->withErrors($validator)->withInput();
                }

                /*
                 * Reset verified due to email has changed
                 */
                $participant->email_verified = null;
            }
            
            /*
             * Update data
             */
            $participant->firstname = $request->input('firstname', null);
            $participant->name = $request->input('name', null);
            $participant->email = $request->input('email', null);
            $participant->gender = $request->input('gender', null);
            $participant->location = $request->input('location', null);
            $participant->education = $request->input('education', null);
            $participant->language = $request->input('language', null);

            /*
             * Parse date and set to birthdate property
             */
            $birthdate = Carbon::parse($request->input('birthdate', null));
            $participant->birthdate = $birthdate->toDateString();

            /*
             * Only save topic if selected education is university or college degree
             */
            if ($participant->education === 'UNI' || $participant->education === 'FH')
            {
                $participant->education_topic = $request->input('education_topic', null);
            }
            
            /*
             * Check if any survey languages are selected, otherwise input does not exists and no need to save it
             */
            if ($request->input('survey_languages', null) !== null)
            {
                $participant->survey_languages = implode(',', $request->input('survey_languages', []));
            }

            /*
             * Check if any study interests are selected, otherwise input does not exists and no need to save it
             */
            if ($request->input('study_interest', null) !== null)
            {
                $participant->study_interest = implode(',', $request->input('study_interest', []));
            }
            
            /*
             * Save participant
             */
            $participant->save();

            /*
             * Resend email validation when address has changed
             */
            if ($emailChanged)
            {
                /*
                 * Get link expiration days
                 */
                $expiration = intval(setting('participantpool.updatelink_days'));

                /*
                 * Build email verification link
                 */
                $verificationLink = URL::temporarySignedRoute('index.register.verify', now()->addMinutes($expiration * 1440), ['id' => $participant->id, 'locale' => $locale]);

                /*
                 * Send verification email
                 */
                Mail::to($participant->email)->send(new ParticipantVerifyEmail($participant, __('registration.verify.subject'), $verificationLink));
            }

            return response()->view('registration.updated');
        }
        catch(Exception $ex)
        {
            return response()->view('index', ['error' => $ex->getMessage()], 500);
        }
    }
}
