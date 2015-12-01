@extends('app')

@section('content')
	<h1>Login</h1>
	<form method="POST" action="/auth/login">
		{!! csrf_field() !!}
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  		<div class="form-group">
    		<label for="email">Email address</label>
    		<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
  		</div>
  		<div class="form-group">
    		<label for="password">Password</label>
    		<input type="password" class="form-control" name="password" id="password" placeholder="Password">
  		</div>
  		<div class="text-right login-button">
        <button class="btn btn-primary" type="submit">Login</button>
      </div>
	</form>
@endsection