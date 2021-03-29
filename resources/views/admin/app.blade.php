@extends('admin.layouts.master')

@section('styles')
    <style>
        :root {
            --z-accent-color: {{$settings['accentColor']}};
        }
    </style>
@endsection

@section('body-content')
    <div id="react-root"></div>
@endsection

@section('scripts')
    <script>
        const settings = @json($settings);
    </script>
    <script src="{{ asset('js/client/admin/roots/app.js') }}"></script>
@endsection