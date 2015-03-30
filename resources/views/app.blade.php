<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice App</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{url('css/default.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('css/datepicker.css')}}">
	<script type="text/javascript" src="{{url('js/angular.min.js')}}"></script>
</head>
<body>
	<div class="wrapper">
	<nav class="navbar navbar-default iapp-navbar">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="javascript:void(0)">Invoice App</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/') }}">Login</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{URL::to('/auth/logout')}}"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	<div>
		@yield('content')
	</div> 
	<div class="iapp-footer">
       <center>
          <p >&copy; 2015 | All rights reserved. <a href="javascript:void(0)" class="iapp-terms">Privacy Policy</a> - <a href="javascript:void(0)" class="iapp-policy">Terms &amp; Conditions.</a></p>
       </center>
             
  </div>
  </div>
	{!!HTML::script('js/jquery.min.js')!!}
	{!!HTML::script('js/bootstrap.min.js')!!}
	{!!HTML::script('js/bootstrap-datepicker.js')!!}
	{!!HTML::script('js/custom.js')!!}
	@yield('script')
</body>
</html>
