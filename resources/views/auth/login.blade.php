@extends('app')
@section('content')
<div class="container-fluid">
<div class="container">
	<div class="login-container">
	<div id="output"></div>
	<div class="avatar"></div>
	<div class="form-box">
	    {!!Form::open(['url' => '/auth/login'])!!}
	        <input name="email" type="text" placeholder="email" value="{{old('email')}}">
	        <input type="password" name="password" placeholder="password" value="{{old('password')}}">
	       	@if(count($errors) > 0)
	       		<div class="alert alert-danger login-alert">
	       		{!!$errors->first('email','<span class="text-danger">:message</span><br>')!!}
	       		{!!($errors->has('password') && $errors->has('email')) ? '<hr>': ''!!}
	        	{!!$errors->first('password','<span class="text-danger">:message</span>')!!}
	       	</div>
	       	@endif
	        
	        <button class="btn btn-login btn-block login" data-loading-text="Signing In..." type="submit">Sign In</button>
	    {!!Form::close()!!}
	</div>
	</div>        
</div>
</div>
@stop
</body>
</html>
