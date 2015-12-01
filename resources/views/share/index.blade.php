@extends('app')
@section('style')
<link rel="stylesheet" type="text/css" href="/vendors/bootstrap-slider.css" />
@endsection

@section('page-title')
    <h2>File Sharing</h2>
    <p>Drag and drop the files you want to send, fill your recipient's details, and click on send.</p>
@endsection

@section('content')
<section class="row" ng-app="app">
    <div ng-view></div>
</section>
@endsection

@section('script')
<script>
    var maxSize = false;

    function initslider(){
      $('#ex1').slider({
        min:1,
        max: 30,
        value: 1,
        formater: function(value) {
          return   value  + ' Days ';
        }
      });
      $('#ex1').slider('setValue', 30);
    }
</script>
    <script>
        var API_KEY = '{{\App\Services\UserToken::getToken()}}';
        var user_id = '{{$user->id}}';
    </script>
    
    <script src="/vendors/bootstrap-slider.js"></script>
    <script type="text/Javascript" src="/vendors/ng-bootstrap/ng-bootstrap-0.12.1.min.js"></script>
    <script type="text/Javascript" src="/angular/file-sharing/app.js"></script>
    <script type="text/Javascript" src="/angular/resources/SharedFiles.js"></script>
    <script type="text/Javascript" src="/angular/resources/Users.js"></script>
    <script type="text/Javascript" src="/angular/factories/HttpInterceptor.js"></script>
    <script type="text/Javascript" src="/angular/file-sharing/FileSharing.js"></script>
    
@endsection
