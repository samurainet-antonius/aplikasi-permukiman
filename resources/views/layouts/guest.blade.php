<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name') }}</title>

    <link href="{{ asset('/assets/img/logo/logo.png') }}" rel="icon">
    <link href="{{ asset('/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/assets/css/ruang-admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

</head>

<body class="bg-gradient-login">

    {{ $slot }}
    
    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ruang-admin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    @if (session()->has('success'))
    <script>
        const notyf = new Notyf({dismissible: true})
            notyf.success('{{ session('success') }}')
    </script>
    @endif

    @if($errors->any())
    @foreach ($errors->all() as $error)
    <script>
        const notyf = new Notyf({dismissible: true})
        notyf.error('{{ $error }}')
    </script>
    @endforeach
    @endif
</body>

</html>