@extends('layouts.app')

@section('content')
<div id="admin" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <router-view></router-view>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
@endsection

@section('scripts')
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>

    <script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/chartjs/dist/chart.min.js') }}"></script>
    <script src="{{ asset('js/app-admin.js') }}"></script>
@endsection
