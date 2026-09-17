<!DOCTYPE html>
<html lang="en" dir="ltr" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Save theme over -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

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
    <script>
        (function() {
            const html = document.documentElement;

            // Fungsi apply tema
            function applyTheme(theme) {
                html.setAttribute('data-bs-theme', theme);
                if (theme === 'dark') {
                    document.body.classList.add('dark');
                } else {
                    document.body.classList.remove('dark');
                }
            }

            // Fungsi simpan & apply
            function setTheme(theme) {
                localStorage.setItem('theme', theme);
                applyTheme(theme);
            }

            // 1. Apply dari localStorage pas load
            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);

            // 2. Re-apply setelah semua script selesai (Modernize sering override)
            window.addEventListener('load', function() {
                setTimeout(function() {
                    applyTheme(localStorage.getItem('theme') || 'light');
                }, 100);
            });

            // 3. MutationObserver — kalau ada yang coba ngubah, balikin
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'data-bs-theme') {
                        const current = html.getAttribute('data-bs-theme');
                        const saved = localStorage.getItem('theme') || 'light';
                        if (current !== saved) {
                            html.setAttribute('data-bs-theme', saved);
                        }
                    }
                });
            });
            observer.observe(html, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });

            // 4. Handler toggle dark
            document.querySelectorAll('.dark-layout').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    setTheme('dark');
                });
            });

            // 5. Handler toggle light
            document.querySelectorAll('.light-layout').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    setTheme('light');
                });
            });

            // 6. Handle sidebar click — re-apply setelah navigasi
            document.querySelectorAll('.sidebar-link, .nav-link').forEach(function(el) {
                el.addEventListener('click', function() {
                    setTimeout(function() {
                        applyTheme(localStorage.getItem('theme') || 'light');
                    }, 50);
                });
            });
        })();
    </script>
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
