@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3 class="title-line">Service Receipt</h3>
	<p></p>
	@include('include.error-msg')
	<p></p>
	{!!Form::open(['url' => 'receipt/service','name' => 'serviceReceiptForm','novalidate'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Receipt No</b></label>
			<input class="form-control" 
			type="text"
			ng-class="{
						error: serviceReceiptForm.receiptNumber.$dirty &&
						serviceReceiptForm.receiptNumber.$invalid}"
			placeholder="Receipt Number" 
			name="receiptNumber" 
			ng-model="receiptNumber"
            ng-minlength="5" 
            ng-maxlength="20"
            maxlength="20" 
            ng-focus
            is-unique="receiptNumber"
			required />

		<div ng-show="serviceReceiptForm.receiptNumber.$dirty && serviceReceiptForm.receiptNumber.$invalid && !serviceReceiptForm.receiptNumber.$focused">
        <small class="text-danger" ng-show="serviceReceiptForm.receiptNumber.$error.required">
        The receipt no is required field.
        </small>
        <small class="text-danger" 
                ng-show="serviceReceiptForm.receiptNumber.$error.minlength">
                The receipt no is required to be at least 5 characters
        </small>
        <small class="text-danger" 
                ng-show="serviceReceiptForm.receiptNumber.$error.maxlength">
                Your receipt no cannot be longer than 20 characters
        </small>
        <small class="text-danger"
				ng-show="serviceReceiptForm.receiptNumber.$error.isunique" ng-hide="serviceReceiptForm.receiptNumber.$error">
				That receipt number provided is  already taken, please try another.
		</small>
      </div>

		</div>
		<div class="col-md-6" ng-controller="DatesController">
			<label><b>Receipt Date</b></label>
			<!--  -->
			<input type="text" class="form-control iapp-date" 
			name="receiptDate" 
			placeholder="YYYY/MM/DD" 
			ng-model="receiptDate"
			ng-maxlength="10"
			maxlength="10"
			ng-datepicker
			required>
			<div class="" ng-show="serviceReceiptForm.receiptDate.$dirty && serviceReceiptForm.receiptDate.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.receiptDate.$error.required">
		           Invoice Date is required.
		        </small>
		        <small class="text-danger" ng-show="serviceReceiptForm.serviceDate.$error.maxlength">
		        	Date format provided is invalid.
		        </small>
		      </div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Service Provider</b></label>
			<input type="text" class="form-control" 
			name="serviceProvider" 
			ng-model="serviceProvider"
			placeholder="Company Name" 
			ng-minlength="3"
			required>
			<div ng-show="serviceReceiptForm.serviceProvider.$dirty && serviceReceiptForm.serviceProvider.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.serviceProvider.$error.required">
		           Service provide name is required.
		        </small>
		        <small class="text-danger" ng-show="serviceReceiptForm.serviceProvider.$error.minlength">
		        	Service provider name is required to be at least 3 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<label><b>Customer Name</b></label>
			{!!Form::text('serviceReceiver',old('serviceReceiver'),['placeholder' => 'Customer Name', 'class'=> 'form-control','ng-model' =>'serviceReceiver','ng-minlength' => '3','required'])!!}
			<div ng-show="serviceReceiptForm.serviceReceiver.$dirty && serviceReceiptForm.serviceReceiver.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.serviceReceiver.$error.required">
		           Customer name is required.
		        </small>
		        <small class="text-danger" ng-show="serviceReceiptForm.serviceReceiver.$error.minlength">
		        	Customer name is required to be at least 3 characters
		        </small>
		      </div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Service Provider Address</b></label>
			<textarea class="form-control" 
			rows="5" 
			name="companyAddress" 
			placeholder="Company Location"
			ng-model="companyAddress"
			ng-minlength="5"
			required></textarea>
			<div ng-show="serviceReceiptForm.companyAddress.$dirty && serviceReceiptForm.companyAddress.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.companyAddress.$error.required">
		           Service Provider Address is required.
		        </small>
		        <small class="text-danger" ng-show="serviceReceiptForm.companyAddress.$error.minlength">
		        	Service Provider Address provided is required to be at least 5 characters
		        </small>
		    </div>
		</div>
		<div class="col-md-6">
			<label><b>Client Address</b></label>
			<textarea class="form-control" 
			rows="5" name="clientAddress" 
			placeholder="Customer Address"
			ng-minlength="5"
			ng-model="clientAddress"
			required></textarea>
			<div ng-show="serviceReceiptForm.clientAddress.$dirty && serviceReceiptForm.clientAddress.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.clientAddress.$error.required">
		           Customer Address is required.
		        </small>
		        <small class="text-danger" ng-show="serviceReceiptForm.clientAddress.$error.minlength">
		        	Customer Address provided is required to be at least 5 characters
		        </small>
		    </div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Currency</b></label>
			<select class="form-control" name="currency" ng-model="currency" required>
				<option selected disabled value="">Select Currency</option>
				<option value="NPR">Nepalse Rupee</option>
				<option value="IC">Indian Rupee</option>
				<option value="EUR">Euro</option>				
				<option value="GBP">Pound Sterling</option>			
				<option value="USD">U.S. Dollar</option>
			</select>
			<div ng-show="serviceReceiptForm.currency.$dirty && serviceReceiptForm.currency.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.currency.$error.required">
		           The Currency field is required.
		        </small>
		      </div>
		</div>
		<div class="col-md-6"></div>
	</div>
	<p></p>
	<div id="workDescriptonHolder" ng-controller="CrtElementService">
		<div class="row">
			<div class="col-md-6">
				<label><b>Description of Work</b></label>
				<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>
			</div>
			<div class="col-md-4">
				<label><b>Amount</b></label>
				<input type="text" class="form-control" name="amount[]" placeholder="Amount">
			</div>
			<div class="col-md-2">
				<br>
				<button type="button"class="btn btn-primary add-more-service" ng-click="add()" style="float: right;"><b>+</b> More</button>
			</div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Note</b></label>
			<textarea class="form-control" 
			rows="5" name="keyNote" 
			placeholder="Special note from service provider"
			ng-model="keyNote"
			ng-minlength="20"
			required>{{old('keyNote')}}
			</textarea>
			<div class="" ng-show="serviceReceiptForm.keyNote.$dirty && serviceReceiptForm.keyNote.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.keyNote.$error.required">
		           This field is required.
		        </small>
		        <small class="text-danger" ng-show="serviceReceiptForm.keyNote.$error.minlength">
		        	This field is required to be at least 20 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<br>
				<div class="choices-holder">
					<input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label>&nbsp;	&nbsp;
					<input type="radio" id="printInvoice" name="requestType" value="serviceReceipt" checked> <label for="printInvoice"><b>Print Invoice</b></label>
				</div>				
			<br>
			<button type="submit" ng-disabled="serviceReceiptForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn" data-loading-text="Please wait ...">Continue ...</button>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop
