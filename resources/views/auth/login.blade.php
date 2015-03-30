@extends('app')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="text-center">Invoice App Login</h4></div>
				<div class="panel-body login-container">

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-6 control-label"><!-- Email Address --></label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
								<span class="text-default">{{$errors->first('email')}}</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-6 control-label"><!-- Password --></label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password" placeholder="Password">
								<span class="text-default">{{$errors->first('password')}}</span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-6">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-6">
								<button type="submit" data-loading-text="Signing In..." class="btn btn-primary sign-in-btn">Sign In</button>

								<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
