<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SI-IPEH | Sistem Indikasi Informasi Permukiman Kumuh</title>

    <link href="{{ asset('/assets/img/logo/logo.png') }}" rel="icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" integrity="sha512-rqQltXRuHxtPWhktpAZxLHUVJ3Eombn3hvk9PHjV/N5DMUYnzKPC1i3ub0mEXgFzsaZNeJcoE0YHq0j/GFsdGg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/assets/css/ruang-admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        *{
            font-family: 'Poppins';
        }
        .fs-14{
            font-size: 14px !important;
        }
        .bg-green {
            background-color: #03A64A !important;
        }
        .text-green {
            color: #03A64A !important;
        }
        .myForm {
            /* min-width: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); */
        }

        .section {
            padding: 2rem 20%;
        }

        .top{
            top: 25vh !important;
        }

        .section:nth-child(2) {
            width: 100% !important;
            background: #03A64A;
            color: white;
            clip-path: polygon(0 40%, 100% 0, 100% 60%, 0 100%);
            padding: 10rem 10%;
        }

        .img{
            height: 50vh !important;
            width: 100% !important;
        }

        @media (min-width: 768px) {
            .top{
                top: 18vh !important;
            }
        }

        @media (min-width: 992px) {
            .top{
                top: 25vh !important;
            }
        }

        @media (min-width: 1200px) {
            .top{
                top: 28vh !important;
            }
        }



    </style>

</head>

<body class="bg-gradient-login">

    {{ $slot }}

    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ruang-admin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    @stack('scripts')

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
