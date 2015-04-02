@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3 class="title-line">Service Invoice</h3>
	<p></p>
	@include('include.error-msg')
	<p></p>
	{!!Form::open(['url' => 'invoice/service','name' => 'serviceInvoiceForm','novalidate'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Invoice No</b></label>
			<input class="form-control" 
			type="text"
			placeholder="Invoice Number" 
			name="invoiceNumber" 
			ng-model="serviceInvoice.invoiceNumber"
			value="{{old('invoiceNumber')}}" 
            ng-minlength="5" 
            ng-maxlength="20"
			required>

		<div ng-show="serviceInvoiceForm.invoiceNumber.$dirty && serviceInvoiceForm.invoiceNumber.$invalid">
        <small class="text-danger" ng-show="serviceInvoiceForm.invoiceNumber.$error.required">
           Invoice no# is required.
        </small>
        <small class="text-danger" 
                ng-show="serviceInvoiceForm.invoiceNumber.$error.minlength">
                Your Invoice no# is required to be at least 5 characters
        </small>
        <small class="text-danger" 
                ng-show="serviceInvoiceForm.invoiceNumber.$error.maxlength">
                Your name cannot be longer than 20 characters
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
			ng-model="serviceInvoice.serviceDate"
			required/>
			<div class="" ng-show="serviceInvoiceForm.serviceDate.$dirty && serviceInvoiceForm.serviceDate.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceDate.$error.required">
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
			ng-model="serviceInvoice.serviceProvider"
			placeholder="Company Name" 
			ng-minlength="3" 
			value="{{old('serviceProvider')}}"
			required />
			<div ng-show="serviceInvoiceForm.serviceProvider.$dirty && serviceInvoiceForm.serviceProvider.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceProvider.$error.required">
		           Service Provide name is required.
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceProvider.$error.minlength">
		        	Service Provider name is required to be at least 3 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<label><b>Customer Name</b></label>
			{!!Form::text('serviceReceiver',old('serviceReceiver'),['placeholder' => 'Customer Name', 'class'=> 'form-control','ng-model' =>'serviceInvoice.serviceReceiver','ng-minlength' => '3','required'])!!}
			<div ng-show="serviceInvoiceForm.serviceReceiver.$dirty && serviceInvoiceForm.serviceReceiver.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceReceiver.$error.required">
		           Client's name is required.
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceReceiver.$error.minlength">
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
			ng-model="serviceInvoice.companyAddress"
			ng-minlength="5"
			required></textarea>
			<div ng-show="serviceInvoiceForm.companyAddress.$dirty && serviceInvoiceForm.companyAddress.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.companyAddress.$error.required">
		           Service Provider Address is required.
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.companyAddress.$error.minlength">
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
			ng-model="serviceInvoice.clientAddress"
			required></textarea>
			<div ng-show="serviceInvoiceForm.clientAddress.$dirty && serviceInvoiceForm.clientAddress.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.clientAddress.$error.required">
		           Customer Address is required.
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.clientAddress.$error.minlength">
		        	Customer Address provided is required to be at least 5 characters
		        </small>
		    </div>
		</div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-6">
			<label><b>Currency</b></label>
			<select class="form-control" name="currency" ng-model="serviceInvoice.currency" required>
				<option selected disabled value="">Select Currency</option>
				<option value="NPR">Nepalse Rupee</option>
				<option value="IC">Indian Rupee</option>
				<option value="EUR">Euro</option>				
				<option value="GBP">Pound Sterling</option>			
				<option value="USD">U.S. Dollar</option>
			</select>
			<div ng-show="serviceInvoiceForm.currency.$dirty && serviceInvoiceForm.currency.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.currency.$error.required">
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
			ng-model="serviceInvoice.keyNote"
			ng-minlength="20"
			required>{{old('keyNote')}}
			</textarea>
			<div class="" ng-show="serviceInvoiceForm.keyNote.$dirty && serviceInvoiceForm.keyNote.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.keyNote.$error.required">
		           This field is required.
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.keyNote.$error.minlength">
		        	This field is required to be at least 20 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<br>
				<div class="choices-holder">
					<input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label>&nbsp;	&nbsp;
					<input type="radio" id="printInvoice" name="requestType" value="serviceInvoice" checked> <label for="printInvoice"><b>Print Invoice</b></label>
				</div>				
			<br>
			<button type="submit" ng-disabled="serviceInvoiceForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn" data-loading-text="Please wait ...">Continue ...</button>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop