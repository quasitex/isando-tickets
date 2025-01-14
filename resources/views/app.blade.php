<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <title>{{env('APP_NAME')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="{{asset('css/app.css')}}">
    <link rel="icon" type="image/png" href="{{asset('favicon.jpg')}}"/>
</head>
<body>
<div id="app">
    <app></app>
</div>
<script src="{{asset('js/app.js')}}"></script>
</body>
<style>
    html {
        overflow-y: hidden;
        overflow-x: hidden;
    }
</style>
</html>
