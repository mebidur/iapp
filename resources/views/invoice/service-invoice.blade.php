@extends('app')
@section('content')
<p style="margin-top:80px;"></p>
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Invoices / Service Invoice</div>
	</div>
</div>
<div class="container container-bordered">
	<h3 class="title-line">Create New Service Invoice</h3>
	<p></p>
	@include('include.error-msg')
	<p></p>
	{!!Form::open(['url' => 'invoice/service','name' => 'serviceInvoiceForm','novalidate'])!!}
	<div class="row">
		<div class="col-md-6">
			<label><b>Invoice No</b></label>
			<input class="form-control"
			ng-class="{
						error: serviceInvoiceForm.invoiceNumber.$dirty &&
						serviceInvoiceForm.invoiceNumber.$invalid}" 
			type="text"
			placeholder="Invoice Number" 
			name="invoiceNumber" 
			ng-model="invoiceNumber"
            ng-minlength="5" 
            ng-maxlength="20"
            maxlength="20" 
            ensure-unique="invoiceNumber"
			required
			ng-focus />

		<div ng-show="serviceInvoiceForm.invoiceNumber.$dirty && serviceInvoiceForm.invoiceNumber.$invalid && !serviceInvoiceForm.invoiceNumber.$focused">
        <small class="text-danger" ng-show="serviceInvoiceForm.invoiceNumber.$error.required">
           Invoice no is required field.
        </small>
        <small class="text-danger" 
                ng-show="serviceInvoiceForm.invoiceNumber.$error.minlength">
                The invoice no is required to be at least 5 characters
        </small>
        <small class="text-danger" 
                ng-show="serviceInvoiceForm.invoiceNumber.$error.maxlength">
                Your name cannot be longer than 20 characters
        </small>
        <small class="text-danger"
				ng-show="serviceInvoiceForm.invoiceNumber.$error.unique" ng-hide="serviceInvoiceForm.invoiceNumber.$error">
				The invoice number provided is  already taken, please try another.
		</small>
      </div>

		</div>
		<div class="col-md-6" ng-controller="DateController">
			<label><b>Invoice Date</b></label>			
			<input type="text" class="form-control iapp-date" 
			name="serviceDate" 
			ng-maxlength="10"
			maxlength="10"
			placeholder="YYYY/MM/DD"
			ng-model="serviceDate"
			ng-datepicker
			required/>
			<div ng-show="serviceInvoiceForm.serviceDate.$dirty && serviceInvoiceForm.serviceDate.$invalid" ng-hide="serviceInvoiceForm.serviceDate.$pristine">
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceDate.$error.required">
		           Invoice date is required field.
		        </small>
				<small class="text-danger" ng-show="serviceInvoiceForm.serviceDate.$error.maxlength">
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
			value="{{old('serviceProvider')}}"
			required />
			<div ng-show="serviceInvoiceForm.serviceProvider.$dirty && serviceInvoiceForm.serviceProvider.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceProvider.$error.required">
		           Service provide name is required field
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceProvider.$error.minlength">
		        	Service provider name is required to be at least 3 characters
		        </small>
		      </div>
		</div>
		<div class="col-md-6">
			<label><b>Customer Name</b></label>
			{!!Form::text('serviceReceiver',old('serviceReceiver'),['placeholder' => 'Customer Name', 'class'=> 'form-control','ng-model' =>'serviceReceiver','ng-minlength' => '3','required'])!!}
			<div ng-show="serviceInvoiceForm.serviceReceiver.$dirty && serviceInvoiceForm.serviceReceiver.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceReceiver.$error.required">
		           Customer name is required field
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.serviceReceiver.$error.minlength">
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
			<div ng-show="serviceInvoiceForm.companyAddress.$dirty && serviceInvoiceForm.companyAddress.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.companyAddress.$error.required">
		           Service provider address is required.
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.companyAddress.$error.minlength">
		        	Service provider address provided is required to be at least 5 characters
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
			<div ng-show="serviceInvoiceForm.clientAddress.$dirty && serviceInvoiceForm.clientAddress.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.clientAddress.$error.required">
		           Customer address is required field
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.clientAddress.$error.minlength">
		        	Customer address provided is required to be at least 5 characters
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
			<div ng-show="serviceInvoiceForm.currency.$dirty && serviceInvoiceForm.currency.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.currency.$error.required">
		           The currency field is required field
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
				<button type="button"class="btn btn-primary add-more-service btn-more" ng-click="add()" style="float: right;"><b>+</b> More</button>
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
			maxlength="250"
			ng-model="termsCondition"
			placeholder="Terms of Services" required></textarea>
			<div ng-show="serviceInvoiceForm.termsCondition.$dirty && serviceInvoiceForm.termsCondition.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.termsCondition.$error.required">
		           The terms &amp; conditions field is required field
		        </small>			        
		        <small class="text-danger" ng-show="serviceInvoiceForm.termsCondition.$error.minlength">
		        	Terms &amp; condition provided is required to be at least 20 characters
		        </small>
		        <small class="text-danger" ng-show="serviceInvoiceForm.termsCondition.$error.maxlength">
		        	Terms &amp; condition provided is required to be at maximum 250 characters
		        </small>
			</div>
			</div>
		<div class="col-md-6">
			<label><b>Bank Account Details</b></label>
			<textarea class="form-control" 
			rows="5" name="bankDetails" 
			placeholder="Bank Account Detail Information ..."
			ng-model="bankDetails"
			required></textarea>
			<div ng-show="serviceInvoiceForm.bankDetails.$dirty && serviceInvoiceForm.bankDetails.$invalid">
		        <small class="text-danger" ng-show="serviceInvoiceForm.bankDetails.$error.required">
		           The bank details field is required field
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
			placeholder="Note from service provider"
			ng-model="keyNote"
			ng-minlength="20"
			required>
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
		<div class="col-md-6"><br>
			<div class="row">
				<div class="col-md-6 col-md-6 col-xs-6 col-sm-6">
					<div class="choices-holder">
						<!-- <input type="radio" id="downloadPDF" name="requestType" value="downloadPDF"> <label for="downloadPDF"><b>Download PDF</b></label><br> -->
						<!-- <input type="radio" id="printInvoice" name="requestType" value="serviceInvoice" checked> <label for="printInvoice"><b>Print Invoice</b></label> -->
						<input type="hidden" name="requestType" value="default">
					</div>
				</div>
				<div class="col-md-6 col-md-6 col-xs-6 col-sm-6"><p></p>
					<button type="submit" ng-disabled="serviceInvoiceForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn choices-holder" data-loading-text="Please wait ...">Continue ...</button>
				</div>
			</div>
		</div>
	</div>
	<p></p>
	{!!Form::close()!!}
</div>
@stop