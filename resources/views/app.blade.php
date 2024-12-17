<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>EPCST Toolkit</title>

  @routes
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
  @endif

  @inertiaHead
</head>
<body class="h-full">
  @inertia
</body>
</html>
