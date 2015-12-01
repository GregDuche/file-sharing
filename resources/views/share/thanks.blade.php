@extends('app')
@section('style')
<link rel="stylesheet" type="text/css" href="/vendors/bootstrap-slider.css" />
@endsection

@section('page-title')
    <h2>Files shared</h2>
@endsection

@section('content')
<section class="row">
    <div class="col-md-12">
    	<p>Your files have been successfully shared</p>
    	<a href="/">Send other files</a><br />
    	<a href="/my-files">See my files</a>
	</div>
</section>
@endsection