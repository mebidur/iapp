@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered" ng-app="iApp" ng-controller="CrtElement">
	<h3 class="title-line">Working Hour Invoice</h3>
	<p>
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
	</p>
	{!!Form::open(['url' => 'invoice/work'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Invoice No#</b></label>
			<input class="form-control" type="text" name="invoiceNumber" value="{{\Input::old('invoiceNumber')}}">
		</div>
		<div class="col-md-6">
			<label><b>Service Date</b></label>
			<input type="text" class="form-control iapp-date datepicker" name="serviceDate" placeholder="DD/MM/YYYY" value="{{\Input::old('serviceDate')}}">
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Service Provider</b></label>
			<input type="text" class="form-control" name="serviceProvider" placeholder="Eg: ABC Inc," value="{{\Input::old('serviceProvider')}}">
		</div>
		<div class="col-md-6">
			<label><b>Client's Name</b></label>
			<input type="text" class="form-control" name="serviceReceiver" placeholder="Client's Name" value="{{\Input::old('serviceReceiver')}}">
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Service Provider Address</b></label>
			<textarea class="form-control" rows="5" name="companyAddress" placeholder="Eg: President George Bush Turnpike, Irving, TX 75038, USA"></textarea>
		</div>
		<div class="col-md-6">
			<label><b>Client Address</b></label>
			<textarea class="form-control" rows="5" name="clientAddress" placeholder="Eg: 1315 Commerce Street, Dallas, TX 75202, USA"></textarea>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Currency</b></label>
			<select class="form-control" name="currency">
				<option selected disabled value="">Select Currency</option>
				<option value="NPR">Nepalse Rupee</option>
				<option value="IC">Indian Rupee</option>
				<option value="AUD">Australian Dollar</option>
				<option value="BRL">Brazilian Real </option>
				<option value="CAD">Canadian Dollar</option>
				<option value="CZK">Czech Koruna</option>
				<option value="DKK">Danish Krone</option>
				<option value="EUR">Euro</option>
				<option value="HKD">Hong Kong Dollar</option>
				<option value="HUF">Hungarian Forint </option>
				<option value="ILS">Israeli New Sheqel</option>
				<option value="JPY">Japanese Yen</option>
				<option value="MYR">Malaysian Ringgit</option>
				<option value="MXN">Mexican Peso</option>
				<option value="NOK">Norwegian Krone</option>
				<option value="NZD">New Zealand Dollar</option>
				<option value="PHP">Philippine Peso</option>
				<option value="PLN">Polish Zloty</option>
				<option value="GBP">Pound Sterling</option>
				<option value="SGD">Singapore Dollar</option>
				<option value="SEK">Swedish Krona</option>
				<option value="CHF">Swiss Franc</option>
				<option value="TWD">Taiwan New Dollar</option>
				<option value="THB">Thai Baht</option>
				<option value="TRY">Turkish Lira</option>
				<option value="USD">U.S. Dollar</option>
			</select>
		</div>
		<div class="col-md-6"></div>
	</div>
	<p></p>
	<div id="workDescriptonHolder">
		<div class="row">
			<div class="col-md-6">
				<label><b>Description of Work</b></label>
				<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>
			</div>
			<div class="col-md-2">
				<label><b>Rate</b></label>
				<input type="text" class="form-control" name="rate[]" placeholder="Eg: 10">
			</div>
			<div class="col-md-3">
				<label><b>Hour</b></label>
				<input type="text" class="form-control" name="hour[]" placeholder="Eg: 10">
			</div>
			<div class="col-md-1">
				<br>
				<button type="button"class="btn btn-primary" ng-click="add()" style="float: right;  position: relative; top: 6px;"><b>+</b> More</button>
			</div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Terms &amp; Conditions</b></label>
			<textarea class="form-control" rows="18" name="termsCondition" placeholder="Eg: Payment within 14 days of Invoice date."></textarea>
		</div>
		<div class="col-md-6">
			<label><b>Bank Account Details</b></label>
			<textarea class="form-control" rows="5" name="bankDetails" placeholder="Bank Name:Bank of America
Routing (ABA):061000052
Account Number:00003508397694056
Account Type:CHECKING"></textarea>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Note</b></label>
			<textarea class="form-control" rows="5" name="keyNote" placeholder="Eg: Thank you for your business."></textarea>
		</div>
		<div class="col-md-6">
			<br>
			<input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label>&nbsp;	&nbsp;
			<input type="radio" id="printInvoice" name="requestType" value="printWorkInvoice" checked> <label for="printInvoice"><b>Print Invoice</b></label>
			<br>
			<button type="submit" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn" data-loading-text="Submiting ...">Continue ...</button>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop
@section('script')
<script type="text/javascript" src="{{url('js/CreateElement.js')}}"></script>
@stop