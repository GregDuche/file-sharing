@extends('app')
@section('style')
<link rel="stylesheet" type="text/css" href="/vendors/bootstrap-slider.css" />
@endsection

@section('page-title')
    <h2>My files</h2>
@endsection

@section('content')
<section class="row" ng-app="app">
    <div ng-view></div>
</section>
@endsection

@section('script')
<script>
    var maxSize = false;
    var API_KEY = '{{\App\Services\UserToken::getToken()}}';
    var user_id = '{{$user->id}}';
</script>
    
    <script src="/vendors/bootstrap-slider.js"></script>
    <script type="text/Javascript" src="/vendors/ng-table/ng-table.min.js"></script>
    <script type="text/Javascript" src="/vendors/ng-bootstrap/ng-bootstrap-0.12.1.min.js"></script>
    <script type="text/Javascript" src="/angular/file-sharing/my-files/app.js"></script>
    <script type="text/Javascript" src="/angular/resources/SharedFiles.js"></script>
    <script type="text/Javascript" src="/angular/resources/SharedFilesRecipient.js"></script>
    <script type="text/Javascript" src="/angular/resources/Users.js"></script>
    <script type="text/Javascript" src="/angular/factories/HttpInterceptor.js"></script>
    <script type="text/Javascript" src="/angular/file-sharing/my-files/MyFiles.js"></script>
    
@endsection
