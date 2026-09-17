<!DOCTYPE html>
<html lang="en" dir="ltr" data-color-theme="Blue_Theme" data-layout="vertical" data-bs-theme="light">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/image/logo.png') }}" />

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    <title>@yield('title', 'Sistem Inventaris TKJ')</title>
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons/tabler-icons.css') }}">
    @stack('style')
</head>

<body>
    <div id="main-wrapper">
        @include('admin.layouts.sidebar')
        <div class="page-wrapper">
            @include('admin.layouts.header')
            <div class="body-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <!-- Import Js Files -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>

    <!-- solar icons -->
    <script src="{{ asset('assets/js/iconify-icon.min.js') }}"></script>
    @stack('script')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: @json(session('warning')),
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        </script>
    @endif
</body>

</html>
