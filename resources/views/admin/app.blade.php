@extends('admin.layouts.master')

@section('styles')
    <style>
        :root {
            /* accent color */
            --z-accent-color: {{$settings['accentColor']}};
            --z-accent-color-rgba: rgba({{Utils::getRgbValue($settings['accentColor'])}}, 1);
            --z-accent-color-light-dark: rgb({{Utils::getRgbValue($settings['accentColor'])}}, 0.8);
            --z-accent-color-light: rgb({{Utils::getRgbValue($settings['accentColor'])}}, 0.5);
            --z-accent-color-lighter: rgb({{Utils::getRgbValue($settings['accentColor'])}}, 0.3);
            --z-accent-color-lightest: rgb({{Utils::getRgbValue($settings['accentColor'])}}, 0.1);

            --z-sidebar-bg: {{ $settings['sidebarBG'] }};
            --z-sidebar-menu-color: {{ $settings['sidebarColor'] }};

            --z-navbar-bg: {{ $settings['navbarBG'] }};
            --z-navbar-color: {{ $settings['navbarColor'] }};

            --z-avatar: {{ $settings['avatar'] }};
            --filepond--drop-label: url('{{ asset($settings['avatar']) }}');
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