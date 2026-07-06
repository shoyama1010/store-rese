<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StoreRese</title>

    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('css/review_create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/review_edit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
    
        @yield('css')
        <script src="{{ asset('js/app.js') }}" defer>
    </script>
</head>

<body>
    <div id="app">
        @component('components.header')
        @endcomponent
        @yield('main')
    </div>
</body>

</html>