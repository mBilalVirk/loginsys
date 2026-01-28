<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hi. I'm a Web developer and UIUX designer👨‍💻👨‍💻">
    <meta name="robots" content="max-image-preview:large">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('logo512.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=edit" />
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('head')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
