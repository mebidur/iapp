<!DOCTYPE html>
<html lang="en" ng-app="iApp">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Invoice App</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{url('css/default.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('css/datepicker.css')}}">
</head>
<body>
	<div class="wrapper">
		<div id="app-menu" class="navbar navbar-default navbar-fixed-top" role="navigation">
		    <div class="container">
		        <div class="navbar-header"><a class="navbar-brand" href="{{url('/')}}">Invoice App</a>
		            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="glyphicon glyphicon-menu-hamburger"></span>
		            </button>
		        </div>
		        <div class="collapse navbar-collapse navbar-menubuilder">
		            @if(Auth::check())
		            <ul class="nav navbar-nav navbar-left iapp-nav">
		                <li class="{{($current == 'home') ? 'current' : ''}}"><a href="{{url('/')}}"><b>Home</b></a></li>
		                <li class="dropdown {{($current == 'work-invoice' || $current == 'service-invoice') ? 'current' : ''}}">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><b>Invoices</b></a>
							<ul class="dropdown-menu iapp-drop-down-menu" role="menu">
								<div class="triangle-up"></div>
								<li><a href="{{url('/invoice/work')}}" class="{{($current == 'work-invoice') ? 'current-list' : ''}}"><span class="glyphicon glyphicon-file"></span> New Hourly Invoice</a></li>
								<li class="divider"></li>
								<li><a href="{{url('/invoice/service')}}" class="{{($current == 'service-invoice') ? 'current-list' : ''}}"><span class="glyphicon glyphicon-file"></span> New Service Invoice</a></li>
							</ul>
						</li>
	                	<li class="dropdown {{($current == 'work-receipt' || $current == 'service-receipt') ? 'current' : ''}}">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><b>Receipts</b></a>
							<ul class="dropdown-menu iapp-drop-down-menu" role="menu">
								<div class="triangle-up"></div>
								<li><a href="{{url('/receipt/work')}}" class="{{($current == 'work-receipt') ? 'current-list': ''}}"><span class="glyphicon glyphicon-file"></span> New Hourly Receipt</a></li>
								<li class="divider"></li>
								<li><a href="{{url('/receipt/service')}}" class="{{($current == 'service-receipt') ? 'current-list': ''}}"><span class="glyphicon glyphicon-file"></span> New Service Receipt</a></li>
								<li class="divider"></li>
								<li><a href="{{url('/receipt')}}" class="{{($current == 'receipt') ? 'current-list': ''}}"><span class="glyphicon glyphicon-option-vertical"></span> Receipts History</a></li>
							</ul>
						</li>						
		            </ul>
		            @endif
		            <ul class="nav navbar-nav navbar-right iapp-nav">
		            	@if (Auth::check())
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->email }} <span class="caret"></span></a>
								<ul class="dropdown-menu iapp-drop-down-menu" role="menu">
									<div class="triangle-up"></div>
									<li><a href="{{url('config/profile')}}"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
									<li class="divider"></li>
									<li><a href="{{URL::to('/auth/logout')}}"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
								</ul>
							</li>
						@endif
		            </ul>
		        </div>
		    </div>
		</div>
		<div class="content-holder">
			@yield('content')
		</div> 
		<div class="iapp-footer">
	       <center>
	          <p>&copy; 2015 | All rights reserved. <a href="javascript:void(0)" class="iapp-terms hidden-xs">Privacy</a>  <a href="javascript:void(0)" class="iapp-policy hidden-xs">Terms</a> <a href="" class="iapp-policy hidden-xs">Security</a></p>
	          <input type="hidden" id="siteUrl" value="{{url('/')}}">
	          <input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
	          <input type="hidden" value="{{date('Y-m-m')}}" id="_date">
	       </center>
	             
	  </div>
  </div>
	{!!HTML::script('js/jquery.min.js')!!}
	{!!HTML::script('js/bootstrap.min.js')!!}
	{!!HTML::script('js/bootstrap-datepicker.js')!!}
	{!!HTML::script('js/angular.min.js')!!}
	{!!HTML::script('js/angular-messages.js')!!}
	{!!HTML::script('js/app.js')!!}
	@yield('script')
</body>
</html>
