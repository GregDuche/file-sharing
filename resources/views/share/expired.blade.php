@extends('app')
@section('style')
<link rel="stylesheet" type="text/css" href="/vendors/bootstrap-slider.css" />
@endsection

@section('page-title')
    <h2>File expired</h2>
@endsection

@section('content')
<section class="row">
    <div class="col-md-12">
    	<p>This file expired and is not available to download anymore.</p>
    	<a href="/my-files">Back to my files</a>
	</div>
</section>
@endsection