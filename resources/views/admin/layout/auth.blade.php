<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', env('APP_NAME'))</title>

    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="/assets/admin/stisla/css/style.css">
    <link rel="stylesheet" href="/assets/admin/stisla/css/components.css">
    <link rel="stylesheet" href="/assets/admin/stisla/css/common.css">

    @yield('style')
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="/assets/images/ruzgarnet.png" alt="{{ env('APP_NAME') }}" width="200">
                        </div>

                        @yield('content')

                        <div class="simple-footer">
                            Copyright &copy; RüzgarNET 2021
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="/assets/admin/vendor/jquery/jquery-3.6.0.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/admin/vendor/jquery/jquery.nicescroll.min.js"></script>
    <script src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script src="/assets/admin/stisla/js/stisla.js"></script>
    <script src="/assets/admin/stisla/js/scripts.js"></script>
    <script src="/assets/admin/stisla/js/common.js"></script>

    @yield('script')
</body>

</html>
