<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/output.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/photos/gassor2.png') }}">

    @yield('vendor-style')
</head>

<body>
    <div id="Content-Container"
        class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>

    @yield('scripts')
</body>

</html>
