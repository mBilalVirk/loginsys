<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
   <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
   <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>VITE</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.jsx')
</head>
<body>

    <div id="app"></div>  
</body>
</html>