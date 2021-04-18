<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
    </head>
    <body class="antialiased">
        <div 
            id="react-root"
            data-code="@yield('code')"
            data-message="@yield('message')"
        />

        <script src="{{ asset('js/client/frontend/roots/error.js') }}"></script>
    </body>
</html>
