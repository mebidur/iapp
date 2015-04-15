@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Home / Settings</div>
	</div>
</div>
<div class="container container-bordered" ng-controller="ConfigController">
	<h4 class="title-line hidden-xs">Organization Information</h4>
	<h4 class="title-line visible-xs hidden-lg hidden-md hidden-sm">Organization Settings</h4>
	<p></p>
	@include('include.error-msg')
	<p></p>	
	<div class="org-content section-content">
		<form name="configForm" method="post" action="{{url('config/store')}}" novalidate>
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<div class="row">
				<div class="col-md-6">
				<label><b>Name</b></label>
				<input type="text" class="form-control" name="name" ng-model="company.name" placeholder="Organization Name" required>
				</div>
				<div class="col-md-6">
				<label><b>Address</b></label>
				<input type="text" class="form-control" name="address" ng-model="company.address" placeholder="Organization Address" required>
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-md-6">
					<label><b>Email</b></label>
					<input type="email" class="form-control" name="email" ng-model="company.email" placeholder="Email" required>
				</div>
				<div class="col-md-6">
					<label><b>Contact No</b></label>
					<input type="text" ng-model="company.phoneNo" name="phoneNo" class="form-control" placeholder="Contact No" ng-pattern="/^0|[1-9][0-9]*$/" required>
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-md-6">
					<label><b>City</b></label>
					<input type="text" ng-model="company.city" name="city" class="form-control" placeholder="City" required>
				</div>
				<div class="col-md-6">
					<label><b>State</b></label>
					<input type="text" class="form-control" name="state" ng-model="company.state" placeholder="State" required>
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-md-6">
					<label><b>Country</b></label>
					<select ng-model="company.country" class="form-control" name="country" required>
						@include('include.country-list')
					</select>
				</div>
				<div class="col-md-6">
					
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-md-6">
					<label><b>Terms &amp; Conditions of Services</b></label>
					<textarea class="form-control" rows="10" name="rules" placeholder="Terms and Conditions for Services" ng-model="company.rules" required></textarea>
				</div>
				<div class="col-md-6">
					<label><b>Bank Account Details</b></label>
					<textarea class="form-control" rows="10" name="bankDetails" placeholder="Bank Account Details" ng-model="company.bankDetails" required></textarea>
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-md-12">
					<label><b>Note</b></label>
					<textarea class="form-control" rows="8" name="note" ng-model="company.note" required></textarea>
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-md-12">
					<button type="submit" ng-disabled="configForm.$invalid" ng-click="doLoad()" class="btn btn-primary btn-block input-lg iapp-lg-btn">[[configButton]]</button>
				</div>
			</div>
		</form>
	</div>
</div>
@stop