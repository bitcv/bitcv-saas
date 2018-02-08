<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <title>@yield('title', 'SaaS')</title>

    <link href="/static/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/admin/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/static/admin/css/animate.css" rel="stylesheet">
    <link href="/static/admin/css/style.css" rel="stylesheet">
    @yield('css')

</head>

<body>
    <div id="wrapper">
        @yield('menu')
        <div id="wrapper">
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                        </div>
                        <ul class="nav navbar-top-links navbar-right">
                            <li>
                                <span class="m-r-sm text-muted welcome-message">Welcome to Bitcv.</span>
                            </li>

                            <li>
                                <a href="{{route('saas.logout')}}">
                                    <i class="fa fa-sign-out"></i>退出
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @yield('content')
            </div>
        </div>
    </div>

    <script src="/js/libs/jquery.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script src="/static/admin/js/bootstrap.min.js"></script>
    @yield('js')
    <script>
    </script>
</body>
</html>