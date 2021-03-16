<!DOCTYPE html>
<html lang="en">
    <head>
    @include('admin.layouts.partials._header')
    </head>
    <body id="body">
        @include('common.preloader2')
        @yield('body-content')
        @yield('scripts')
    </body>
</html>