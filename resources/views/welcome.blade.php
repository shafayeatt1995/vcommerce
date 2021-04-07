<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

    </head>
    <body>
        <div id="vue">
            <app></app>
        </div>

        @auth
        <script>
            window.validation = {
                "id": {{ auth()->user()->id }},
                "type": {{ auth()->user()->role_id }},
                "content": "{{ Str::random(25) }}"
            };
        </script>
        @endauth
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
