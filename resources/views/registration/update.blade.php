@extends('layouts.app')

@section('content')
<div id="admin" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h5>{{ __('registration.update.title') }}</h5>

            <form class="" action="{{ Request::fullUrl() }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="firstname" class="d-block">{{ __('registration.form.firstname') }}:&nbsp;<span class="text-danger">&ast;</span></label>
                    <input name="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" required="required" value="{{ old('firstname') ?? $participant->firstname }}">
                    @error('firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="name" class="d-block">{{ __('registration.form.name') }}:&nbsp;<span class="text-danger">&ast;</span></label>
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" required="required" value="{{ old('name') ?? $participant->name }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="email" class="d-block">{{ __('registration.form.email') }}:&nbsp;<span class="text-danger">&ast;</span></label>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" required="required" value="{{ old('email') ?? $participant->email }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="gender" class="d-block">{{ __('registration.form.gender') }}</label>
                    @foreach ($genders as $key => $gender)
                        <div class="form-check form-check-inline">
                            <input id="gender_{{ strtolower($key) }}" class="form-check-input" type="radio" name="gender" value="{{ $key }}" {{ old('gender') === $key ? 'checked' : ( $participant->gender === $key ? 'checked' : '' ) }}>
                            <label for="gender_{{ strtolower($key) }}" class="form-check-label text-nowrap">{{ $gender }}</label>@if($key === 'S'):&nbsp;<input type="text" class="form-control">@endif
                        </div>
                    @endforeach
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="birthdate" class="d-block">{{ __('registration.form.birthdate') }}:</label>
                    <input name="birthdate" type="text" class="form-control @error('birthdate') is-invalid @enderror" placeholder="DD.MM.YYYY" pattern="([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})" value="{{ old('birthdate') ?? $participant->birthdate->format('d.m.Y') }}">
                    <small class="form-text text-muted">{{ __('registration.form.info.birthdate') }}</small>
                    @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="location" class="d-block">{{ __('registration.form.location') }}</label>
                    <input name="location" type="text" placeholder="" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') ?? $participant->location }}">
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="education" class="d-block">{{ __('registration.form.education') }}</label>
                    <div class="row">
                        <div class="col-sm">
                            <select class="custom-select" name="education">
                                @foreach ($educations as $key => $education)
                                    <option value='{{ $key }}' {{ old('education') === $key ? 'selected' : ( $participant->education === $key ? 'selected' : '' ) }}>{{ $education }}</option>        
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm">
                            <select class="custom-select" name="education_topic">
                                @foreach ($topics as $key => $topic)
                                    <option value='{{ $key }}' {{ old('education_topic') === $key ? 'selected' : ( $participant->education_topic === $key ? 'selected' : '' ) }}>{{ $topic }}</option>        
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <small class="form-text text-muted">{{ __('registration.form.info.education') }}</small>
                </div>

                <div class="form-group">
                    <label for="language" class="d-block">{{ __('registration.form.language') }}&nbsp;<span class="text-danger">&ast;</span></label>
                    @foreach ($languages as $key => $language)
                        <div class="form-check form-check-inline">
                            <input id="language_{{ strtolower($key) }}" class="form-check-input @error('language') is-invalid @enderror" type="radio" name="language" x-required="required" value="{{ $key }}" {{ old('language') === $key ? 'checked' : ( $participant->language === $key ? 'checked' : '' ) }}>
                            <label class="form-check-label" for="language_{{ strtolower($key) }}">{{ $language }}</label>
                        </div>
                    @endforeach
                    @error('language')<div class="invalid-feedback" style="display: block;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="survey_languages" class="d-block">{{ __('registration.form.survey_languages') }}&nbsp;<span class="text-danger">&ast;</span></label>
                    @foreach ($languages as $key => $language)
                        <div class="form-check form-check-inline">
                            <input id="survey_language_{{ strtolower($key) }}" class="form-check-input @error('survey_languages') is-invalid @enderror" type="checkbox" name="survey_languages[]" value="{{ $key }}" {{ old('survey_languages') !== null ? ( in_array($key, old('survey_languages')) ? 'checked' : '' ) : ( in_array($key, explode(',', $participant->survey_languages)) ? 'checked' : '' ) }}>
                            <label class="form-check-label" for="survey_language_{{ strtolower($key) }}">{{ $language }}</label>
                        </div>
                    @endforeach
                    @error('survey_languages')<div class="invalid-feedback" style="display: block;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="study_interest" class="d-block">{{ __('registration.form.study_interest') }}</label>
                    @foreach ($interests as $key => $interest)
                        <div class="form-check form-check-inline">
                            <input id="study_interest_{{ strtolower($key) }}" class="form-check-input" type="checkbox" name="study_interest[]" value="{{ $key }}" {{ old('study_interest') !== null ? ( in_array($key, old('study_interest')) ? 'checked' : '' ) : ( in_array($key, explode(',', $participant->study_interest)) ? 'checked' : '' ) }}>
                            <label class="form-check-label" for="study_interest_{{ strtolower($key) }}">{{ $interest }}</label>
                        </div>
                    @endforeach
                </div>

                <hr><button type="submit" class="btn btn-primary">{{ __('registration.update.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection