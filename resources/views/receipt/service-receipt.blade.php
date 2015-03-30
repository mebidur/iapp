@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3 class="title-line">Working Hour Invoice</h3>
	<p></p>
	{!!Form::open(['url' => 'receipt/work'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Invoice No#</b></label>
			<label class="form-control">{{$receiptNumber}}</label>
			<input type="hidden" name="receiptNumber" value="{{$receiptNumber}}">
		</div>
		<div class="col-md-6">
			<label><b>Service Date</b></label>
			<input type="text" class="form-control iapp-date datepicker" name="serviceDate" placeholder="DD/MM/YYYY">
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Service Provider</b></label>
			<input type="text" class="form-control" name="serviceProvider" placeholder="Eg: ABC Inc,">
		</div>
		<div class="col-md-6">
			<label><b>Client's Name</b></label>
			<input type="text" class="form-control" name="serviceReceiver" placeholder="Client's Name">
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
				<option value="NPR">NPR</option>
				<option value="IC">IC</option>
				<option value="&euro;">&euro; Euro</option>
				<option value="&pound;">&pound; Pound</option>
				<option value="&dollar;">$ USD</option>
			</select>
		</div>
		<div class="col-md-6"></div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Description of Work</b></label>
			<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>
		</div>
		<div class="col-md-4">
			<label><b>Amount</b></label>
			<input type="text" class="form-control" name="amount[]" placeholder="Eg: 10">
		</div>
		<div class="col-md-2">
			<br>
			<button type="button"class="btn btn-primary" style="float: right;"><b>+</b> Add More</button>
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
			<input type="radio" id="printReceipt" name="requestType" value="printReceipt" checked> <label for="printReceipt"><b>Print Receipt</b></label>
			<br>
			<button type="submit" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn" data-loading-text="Submiting ...">Continue ...</button>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop