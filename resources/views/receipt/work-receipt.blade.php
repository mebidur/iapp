@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3 class="title-line">Working Hour Receipt</h3>
	<p></p>
	@include('include.error-msg')
	<p></p>
	{!!Form::open(['url' => 'receipt/work','name' => 'workReceiptForm','novalidate'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Receipt No</b></label>
			<input class="form-control" 
			type="text"
			ng-class="{
						error: workReceiptForm.receiptNumber.$dirty &&
						workReceiptForm.receiptNumber.$invalid}"
			placeholder="Receipt Number" 
			name="receiptNumber" 
			ng-model="receiptNumber"
            ng-minlength="5"
            ng-focus 
            ng-maxlength="20"
            maxlength="20" 
            is-unique=""
			required />

		<div ng-show="workReceiptForm.receiptNumber.$dirty && workReceiptForm.receiptNumber.$invalid && !workReceiptForm.receiptNumber.$focused">
        <small class="text-danger" ng-show="workReceiptForm.receiptNumber.$error.required">
           The receipt no is requird field
        </small>
        <small class="text-danger" 
                ng-show="workReceiptForm.receiptNumber.$error.minlength">
                The receipt no is required to be at least 5 characters
        </small>
        <small class="text-danger" 
                ng-show="workReceiptForm.receiptNumber.$error.maxlength">
                The receipt no cannot be longer than 20 characters
        </small>
        <small class="text-danger"
				ng-show="workReceiptForm.receiptNumber.$error.isunique" ng-hide="workReceiptForm.receiptNumber.$error">
				That receipt number provided is  already taken, please try another.
		</small>
      </div>

		</div>
		<div class="col-md-6" ng-controller="DatesController">
			<label><b>Receipt Date</b></label>
			<input type="text" class="form-control iapp-date" 
			name="receiptDate" 
			ng-maxlength="10"
			maxlength="10"
			placeholder="YYYY/MM/DD" 
			ng-model="receiptDate"
			ng-datepicker
			required>
			<div ng-show="workReceiptForm.receiptDate.$dirty && workReceiptForm.receiptDate.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.receiptDate.$error.required">
		           Receipt date is required field
		        </small>
		        <small class="text-danger" ng-show="workReceiptForm.serviceDate.$error.maxlength">
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
			<div ng-show="workReceiptForm.serviceProvider.$dirty && workReceiptForm.serviceProvider.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.serviceProvider.$error.required">
		           Service provide name is required field
		        </small>
		        <small class="text-danger" ng-show="workReceiptForm.serviceProvider.$error.minlength">
		        	Service provider name is required to be at least 3 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<label><b>Customer Name</b></label>
			{!!Form::text('serviceReceiver',old('serviceReceiver'),['placeholder' => 'Customer Name', 'class'=> 'form-control','ng-model' =>'serviceReceiver','ng-minlength' => '3','required'])!!}
			<div ng-show="workReceiptForm.serviceReceiver.$dirty && workReceiptForm.serviceReceiver.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.serviceReceiver.$error.required">
		           Customer name is required field
		        </small>
		        <small class="text-danger" ng-show="workReceiptForm.serviceReceiver.$error.minlength">
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
			<div ng-show="workReceiptForm.companyAddress.$dirty && workReceiptForm.companyAddress.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.companyAddress.$error.required">
		           Service provider address is required.
		        </small>
		        <small class="text-danger" ng-show="workReceiptForm.companyAddress.$error.minlength">
		        	Service provider address is required to be at least 5 characters
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
			<div ng-show="workReceiptForm.clientAddress.$dirty && workReceiptForm.clientAddress.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.clientAddress.$error.required">
		           Customer address is required field
		        </small>
		        <small class="text-danger" ng-show="workReceiptForm.clientAddress.$error.minlength">
		        	Customer address is required to be at least 5 characters
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
			<div ng-show="workReceiptForm.currency.$dirty && workReceiptForm.currency.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.currency.$error.required">
		           The currency field is required.
		        </small>
		      </div>
		</div>
		<div class="col-md-6"></div>
	</div>
	<p></p>
	<div id="workDescriptonHolder" ng-controller="CrtElement">
		<div class="row">
			<div class="col-md-6">
				<label><b>Description of Work</b></label>
				<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>
			</div>
			<div class="col-md-2">
				<label><b>Rate</b></label>
				<input type="text" class="form-control" name="rate[]" placeholder="Rate">
			</div>
			<div class="col-md-3">
				<label><b>Hour</b></label>
				<input type="text" class="form-control" name="hour[]" placeholder="Worked Hour">
			</div>
			<div class="col-md-1">
				<br>
				<button type="button"class="btn btn-primary add-more-field" ng-click="add()" style="float: right;  position: relative; top: 6px;"><b>+</b> More</button>
			</div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Note</b></label>
			<textarea class="form-control" 
			rows="5" name="keyNote" 
			placeholder="Note from business ..."
			ng-model="keyNote"
			ng-minlength="20"
			required>
			</textarea>
			<div class="" ng-show="workReceiptForm.keyNote.$dirty && workReceiptForm.keyNote.$invalid">
		        <small class="text-danger" ng-show="workReceiptForm.keyNote.$error.required">
		           This field is required field
		        </small>
		        <small class="text-danger" ng-show="workReceiptForm.keyNote.$error.minlength">
		        	This field is required to be at least 20 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<br>
				<div class="choices-holder">
					<input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label>&nbsp;	&nbsp;
					<input type="radio" id="printInvoice" name="requestType" value="workReceipt" checked> <label for="printInvoice"><b>Print Invoice</b></label>
				</div>				
			<br>
			<button type="submit" ng-disabled="workReceiptForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn" data-loading-text="Please wait ...">Continue ...</button>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop