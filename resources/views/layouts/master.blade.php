<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name') }} [beta] {{ config('app.version') }}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="shortcut icon" href="/favicon.png"> 
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}" type="text/css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        @stack('page-css')

    </head>

    <body class="modal-open">
        <div id="app">

        <!-- Main Content Area-->

            <div class='header'>
                <div class="container-fluid">
                    <img src="/images/mob-boss.png" />
                    <h4 style="display: inline;vertical-align: bottom;color: #333333;">V 2.0
                                    </h4>
                </div>

            </div>

            <div class="container-fluid">
                <main>
                    @yield('content')
                </main>
            </div>

            <div class='footer'>
                <div class="container-fluid">
                    <div class='row'>
                        <div class="col-xs-12">
                            <div class='footer-body'>
                                <img src="/images/mob-boss-footer.png" />
                                <mob-monitor></mob-monitor>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
        <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>

        @stack('page-js')

    </body>

</html>
