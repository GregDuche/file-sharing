<!DOCTYPE html>
<html>
<head>
    <title>File Sharing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/styles/main.css">
    
    @yield('style')
    
</head>
<body>
    <header>
       <nav class="navbar navbar-default">
        @if (\Auth::check())
            <div class="col-md-12 user">
                Connected as {{\Auth::user()->name}} ({{\Auth::user()->email}}) | <a href="/auth/logout"> Log out</a>
            </div>
        @endif
            <div class="container-fluid">
                <div class="navbar-header">
                     <a class="navbar-brand" href="#"><img alt="Brand" height="50" src="/images/logo.png" /></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="{{\Request::path() == '/' ? 'active' : ''}}"><a href="/">Share</a></li>
                        @if (\Auth::check())
                        <li class="{{\Request::path() == 'my-files' ? 'active' : ''}}"><a href="/my-files">My files</a></li>
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="{{\Request::path() == 'users' ? 'active settings' : 'settings'}}"><a href="/users" ><span class="glyphicon glyphicon-cog"></span></a></li>                        
                    </ul>
                </div>
            </div>

        </nav>
    </header>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 app">
            @yield('page-title')
            @yield('content')
        </div>
    </div>
    
    
    <script type="text/javascript" src="/vendors/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/vendors/angular-1.3.14/angular.js"></script>
    <script type="text/javascript" src="/vendors/angular-1.3.14/angular-resource.min.js"></script>
    <script type="text/javascript" src="/vendors/angular-1.3.14/angular-route.min.js"></script>
    <script type="text/javascript" src="/vendors/ng-bootstrap/ng-bootstrap-0.12.1.min.js"></script>
    <script type="text/javascript" src="/vendors/ng-flow/ng-flow-standalone.min.js"></script>
    <script type="text/javascript" src="/vendors/fusty-flow-js/fusty-flow-factory.js"></script>
    @yield('script')
    </body>
</html>
