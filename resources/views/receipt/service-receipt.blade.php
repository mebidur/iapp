@extends('app')
@section('content')
<p style="margin-top:80px;"></p>
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Receipts / Service Receipt</div>
	</div>
</div>
<div class="container container-bordered">
	<h3 class="title-line">Create New Service Receipt</h3>
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
		<div class="col-md-6" ng-controller="DateController">
			<label><b>Receipt Date</b></label>
			<!--  -->
			<input type="text" class="form-control iapp-date" 
			name="serviceDate" 
			placeholder="YYYY/MM/DD" 
			ng-model="serviceDate"
			ng-maxlength="10"
			maxlength="10"
			ng-datepicker
			required>
			<div class="" ng-show="serviceReceiptForm.serviceDate.$dirty && serviceReceiptForm.serviceDate.$invalid">
		        <small class="text-danger" ng-show="serviceReceiptForm.serviceDate.$error.required">
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
				<option value="Rs">Nepalese Rupee</option>
				<option value="&euro;">Euro</option>				
				<option value="&pound;" selected>Pound Sterling</option>			
				<option value="&dollar;">US Dollar</option>
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
			<div class="col-md-5">
				<label><b>Amount</b></label>
				<input type="text" class="form-control" name="amount[]" placeholder="Amount">
			</div>
			<div class="col-md-1">
				<br>
				<button type="button"class="btn btn-primary add-more-service btn-more btn-block" ng-click="add()" style="float: right; margin-top:5px;"><b>+</b> More</button>
			</div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Note</b></label>
			<textarea class="form-control" 
			rows="5" name="keyNote" 
			placeholder="Note from service provider"
			ng-model="keyNote"
			ng-minlength="20"
			required ng-init="keyNote='{{old('keyNote')}}'">
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
		<div class="col-md-6"><br>
			<div class="row">
				<div class="col-md-6 col-md-6 col-xs-6 col-sm-6">
					<div class="choices-holder">
						<!-- <input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label><br> -->
						<input type="hidden" name="requestType" value="default">

						<!-- <input type="radio" id="printInvoice" name="requestType" value="serviceReceipt" checked> <label for="printInvoice"><b>Print Invoice</b></label> -->
					</div>
				</div>
				<div class="col-md-6 col-md-6 col-xs-6 col-sm-6"><p></p>
					<button type="submit" ng-disabled="serviceReceiptForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn choices-holder" data-loading-text="Please wait ...">Continue ...</button>
				</div>
			</div>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop
