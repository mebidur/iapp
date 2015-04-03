@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3 class="title-line">Working Hour Invoice</h3>
	<p></p>
	@include('include.error-msg')
	<p></p>
	{!!Form::open(['url' => 'invoice/work','name' => 'workInvoiceForm','novalidate'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Invoice No</b></label>
			<input class="form-control" 
			type="text"
			placeholder="Invoice Number" 
			name="invoiceNumber" 
			ng-model="workInvoice.invoiceNumber"
            ng-minlength="5" 
            ng-maxlength="20"
            ng-init ="{{old('invoiceNumber')}}"
            ensure-unique="invoiceNumber"
			required />

		<div ng-show="workInvoiceForm.invoiceNumber.$dirty && workInvoiceForm.invoiceNumber.$invalid">
        <small class="text-danger" ng-show="workInvoiceForm.invoiceNumber.$error.required">
           Invoice no# is required.
        </small>
        <small class="text-danger" 
                ng-show="workInvoiceForm.invoiceNumber.$error.minlength">
                Your Invoice no# is required to be at least 5 characters
        </small>
        <small class="text-danger" 
                ng-show="workInvoiceForm.invoiceNumber.$error.maxlength">
                Your name cannot be longer than 20 characters
        </small>
        <small class="text-danger"
				ng-show="workInvoiceForm.invoiceNumber.$error.unique">
				That invoice number provided is  already taken, please try another.
		</small>
      </div>

		</div>
		<div class="col-md-6">
			<label><b>Invoice Date</b></label>
			<!-- datepicker -->
			<input type="date" class="form-control iapp-date" 
			name="serviceDate" 
			placeholder="DD/MM/YYYY" 
			value="{{old('serviceDate')}}"
			ng-model="workInvoice.serviceDate"
			required/>
			<div class="" ng-show="workInvoiceForm.serviceDate.$dirty && workInvoiceForm.serviceDate.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.serviceDate.$error.required">
		           Invoice Date is required.
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
			ng-model="workInvoice.serviceProvider"
			placeholder="Company Name" 
			ng-minlength="3" 
			value="{{old('serviceProvider')}}"
			required />
			<div ng-show="workInvoiceForm.serviceProvider.$dirty && workInvoiceForm.serviceProvider.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.serviceProvider.$error.required">
		           Service Provide name is required.
		        </small>
		        <small class="text-danger" ng-show="workInvoiceForm.serviceProvider.$error.minlength">
		        	Service Provider name is required to be at least 3 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<label><b>Customer Name</b></label>
			{!!Form::text('serviceReceiver',old('serviceReceiver'),['placeholder' => 'Customer Name', 'class'=> 'form-control','ng-model' =>'workInvoice.serviceReceiver','ng-minlength' => '3','required'])!!}
			<div ng-show="workInvoiceForm.serviceReceiver.$dirty && workInvoiceForm.serviceReceiver.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.serviceReceiver.$error.required">
		           Client's name is required.
		        </small>
		        <small class="text-danger" ng-show="workInvoiceForm.serviceReceiver.$error.minlength">
		        	Client's name is required to be at least 3 characters
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
			ng-model="workInvoice.companyAddress"
			ng-minlength="5"
			required></textarea>
			<div ng-show="workInvoiceForm.companyAddress.$dirty && workInvoiceForm.companyAddress.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.companyAddress.$error.required">
		           Service Provider Address is required.
		        </small>
		        <small class="text-danger" ng-show="workInvoiceForm.companyAddress.$error.minlength">
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
			ng-model="workInvoice.clientAddress"
			required></textarea>
			<div ng-show="workInvoiceForm.clientAddress.$dirty && workInvoiceForm.clientAddress.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.clientAddress.$error.required">
		           Customer Address is required.
		        </small>
		        <small class="text-danger" ng-show="workInvoiceForm.clientAddress.$error.minlength">
		        	Customer Address provided is required to be at least 5 characters
		        </small>
		    </div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Currency</b></label>
			<select class="form-control" name="currency" ng-model="workInvoice.currency" required>
				<option selected disabled value="">Select Currency</option>
				<option value="NPR">Nepalse Rupee</option>
				<option value="IC">Indian Rupee</option>
				<option value="EUR">Euro</option>				
				<option value="GBP">Pound Sterling</option>			
				<option value="USD">U.S. Dollar</option>
			</select>
			<div ng-show="workInvoiceForm.currency.$dirty && workInvoiceForm.currency.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.currency.$error.required">
		           The Currency field is required.
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
			<label><b>Terms &amp; Conditions</b></label>
			<textarea class="form-control" 
			rows="10" name="termsCondition" 
			ng-minlength="20"
			ng-maxlength="250"
			ng-model="workInvoice.termsCondition"
			placeholder="Terms of Services" required></textarea>
			<div ng-show="workInvoiceForm.termsCondition.$dirty && workInvoiceForm.termsCondition.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.termsCondition.$error.required">
		           The Terms &amp; Conditions field is required.
		        </small>			        
		        <small class="text-danger" ng-show="workInvoiceForm.termsCondition.$error.minlength">
		        	Terms &amp; Condition provided is required to be at least 20 characters
		        </small>
		        <small class="text-danger" ng-show="workInvoiceForm.termsCondition.$error.maxlength">
		        	Terms &amp; Condition provided is required to be at maximum 250 characters
		        </small>
			</div>
			</div>
		<div class="col-md-6">
			<label><b>Bank Account Details</b></label>
			<textarea class="form-control" 
			rows="5" name="bankDetails" 
			placeholder="Bank Account Detail Information ..."
			ng-model="workInvoice.bankDetails"
			required></textarea>
			<div ng-show="workInvoiceForm.bankDetails.$dirty && workInvoiceForm.bankDetails.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.bankDetails.$error.required">
		           The Bank Details field is required.
		        </small>
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
			ng-model="workInvoice.keyNote"
			ng-minlength="20"
			required />{{old('keyNote')}}
			</textarea>
			<div class="" ng-show="workInvoiceForm.keyNote.$dirty && workInvoiceForm.keyNote.$invalid">
		        <small class="text-danger" ng-show="workInvoiceForm.keyNote.$error.required">
		           This field is required.
		        </small>
		        <small class="text-danger" ng-show="workInvoiceForm.keyNote.$error.minlength">
		        	This field is required to be at least 20 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<br>
				<div class="choices-holder">
					<input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label>&nbsp;	&nbsp;
					<input type="radio" id="printInvoice" name="requestType" value="workInvoice" checked> <label for="printInvoice"><b>Print Invoice</b></label>
				</div>				
			<br>
			<button type="submit" ng-disabled="workInvoiceForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn" data-loading-text="Please wait ...">Continue ...</button>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop