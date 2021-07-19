<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', env('APP_NAME'))</title>

    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/iziToast/css/iziToast.min.css">

    <link rel="stylesheet" href="/assets/admin/stisla/css/style.css">
    <link rel="stylesheet" href="/assets/admin/stisla/css/components.css">
    <link rel="stylesheet" href="/assets/admin/stisla/css/common.css">

    <style>
        .response {
            font-size: 35px;
            font-weight: bold;
            text-align: center;
        }

        .response.success {
            color: #42ba96;
        }

        .response.error {
            color: #ff3333;
        }

        .response p:last-child {
            margin-bottom: 0;
        }

        .icon-box {
            display: flex;
            justify-content: center;
        }

        .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 75px;
            height: 75px;
            color: #ffffff;
            border-radius: 100%;
        }

        .icon i {
            font-size: 30px;
        }

        .icon.success {
            background-color: #42ba96;
        }

        .icon.error {
            background-color: #ff3333;
        }

    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card mt-4">
                    <div class="card-body py-5">
                        @if ($response == 1)
                            <div class="response success">
                                <div class="icon-box">
                                    <div class="icon success">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p>Ödeme Başarılı</p>
                            </div>
                        @else
                            <div class="response error">
                                <div class="icon-box">
                                    <div class="icon error">
                                        <i class="fas fa-times" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p>Ödeme Başarısız</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/admin/vendor/jquery/jquery-3.6.0.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/admin/vendor/jquery/jquery.nicescroll.min.js"></script>
    <script src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script src="/assets/admin/vendor/iziToast/js/iziToast.min.js"></script>

    <script src="/assets/admin/stisla/js/stisla.js"></script>
    <script src="/assets/admin/stisla/js/scripts.js"></script>
    <script src="/assets/admin/js/plugins.js"></script>
    <script src="/assets/admin/js/masks.js"></script>
    <script src="/assets/admin/js/modals.js"></script>
    <script src="/assets/admin/js/common.js"></script>
</body>

</html>
