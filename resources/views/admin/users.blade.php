@extends('app-admin')

@section('content')
<h2>User management</h2>
<section class="row" ng-app="app">
    <div ng-view></div>
</section>
@endsection


@section('script')
    <script>
        var API_KEY = '{{\App\Services\UserToken::getToken()}}';
    </script>

    <script type="text/Javascript" src="/vendors/ng-table/ng-table.min.js"></script>
    <script type="text/Javascript" src="/angular/user-management/app.js"></script>
    <script type="text/Javascript" src="/angular/factories/HttpInterceptor.js"></script>
    <script type="text/Javascript" src="/angular/resources/Users.js"></script>
    <script type="text/Javascript" src="/angular/user-management/Users.js"></script>
    
@endsection
