<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        
        <link rel="stylesheet" href="/css/styles.css">
        <script src="/js/scripts.js"></script>
    </head>
    <body>
        <h1>Título</h1>
        <img src="/img/banner.jpeg" alt="Banner">
        @if(10 > 15) 
            <p>A condição é true</p>
        @endif
    </body>
</html>
