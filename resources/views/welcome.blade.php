<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iCTqzufHpAmYCslQySHUOm1Npn9uMRNsBx78hCNv9wgsEaQgzLkL8T" crossorigin="anonymous">


        @vite(['resources/css/app.css'])

    </head>
    <body>
        <div id="app">
            {{-- <cron-job-list></cron-job-list> --}}
        </div>
        @vite(['resources/js/app.js'])
    </body>
</html>
