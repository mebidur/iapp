@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Invoices / Work Invoice</div>
	</div>
</div>
<div class="container container-bordered" ng-controller="WorkInvoiceController">
	<h4 class="title-line hidden-xs content-title">Create New Hourly Invoice</h4>
	<h4 class="title-line visible-xs hidden-lg hidden-md hidden-sm">New Invoice</h4>
	<p></p>
<!-- 	<div ng-class="{'alert alert-danger database-error-msg': databaseError}" ng-if="databaseError">
		<span ng-if="databaseError"><strong>Oops! Error Occured</strong> Some thing went wrong! Please be sure with your database configuration ...</span>
	</div>

	<div class="{'alert alert-danger submitted-error-msg' : dataSubmitted}" ng-if="dataSubmitted">
		<strong>Oops! Error Occured</strong> Some thing went wrong! Fill required input fields ...
	</div> -->
	<div class="errors" ng-if="hasErrors">
		<div ng-class="{'alert alert-danger' : hasErrors}">
			<div ng-repeat="error in errors">
				<span ng-bind="error" ng-if="hasErrors"></span><br>
			</div>
		</div>
	</div>
	<p></p>
	<form name="workInvoiceForm" ng-submit="workInvoiceForm.$valid && workInvoiceProcess()"  novalidate>	
	<div class="org-content section-content">
		<h4 class="content-title">General Information</h4>
		<div class="row">
			<div class="col-md-5 col-lg-5 col-xs-9 col-sm-10">
				<label><b>Invoice No</b></label>
				<input class="form-control unique-number" 
				type="text"
				placeholder="Invoice Number" 
				name="invoiceNumber" 
				ng-model="organization.invoiceNumber"
	            ng-minlength="5" 
	            ng-maxlength="20"
	            maxlength="20" 
	            ng-change="checkState()"
	            ng-disabled="!manualCode"
	            ng-focus
	            ensure-unique="invoiceNumber"
				required />

				<div ng-show="workInvoiceForm.invoiceNumber.$dirty && workInvoiceForm.invoiceNumber.$invalid && !workInvoiceForm.invoiceNumber.$focused">
		        <small class="text-danger"
						ng-show="workInvoiceForm.invoiceNumber.$error.unique" ng-hide="workInvoiceForm.invoiceNumber.$error">
						That invoice number provided is  already taken, please try another.
				</small>
		      </div>
	      </div>
			<div class="col-md-1 col-sm-1 col-xs-1">
				<label><b style="color:#fff;" class="hidden-print">Option</b></label>
				<button type="button" class="btn btn-primary add-more-field" ng-click="doFocus()">
					<span class="glyphicon glyphicon-pencil"></span>
				</button>
				<input type="hidden" ng-model="organization.isManualCode" name="isManualCode">
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12">
				<label><b>Invoice Date</b></label>
				<input type="text" class="form-control iapp-date" 
				ng-maxlength="10"
				maxlength="10"
				name="organization.serviceDate" 
				placeholder="YYYY/MM/DD"
				ng-model="organization.serviceDate"
				ng-datepicker
				required/>
				
				<div ng-show="workInvoiceForm.serviceDate.$dirty && workInvoiceForm.serviceDate.$invalid">
			        <small class="text-danger" ng-show="workInvoiceForm.serviceDate.$error.maxlength">
			        	Date format provided not valid date.
			        </small>
			      </div>
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12">
				<label><b>Currency</b></label>
				<select class="form-control" name="currency" ng-model="organization.currency" required>
					<option selected disabled value="">Select Currency</option>
					<option value="Rs">Nepalese Rupee</option>
					<option value="&euro;">Euro</option>				
					<option value="&pound;" selected>Pound Sterling</option>			
					<option value="&dollar;">US Dollar</option>
				</select>
			</div>
		</div>
		<p></p>
	</div>
	<p></p>
	<div class="customer-content section-content">
	<h4 class="content-title">Customer Information</h4>
		<div class="row">
			<div class="col-md-6">
				<div class="customer-name-wrap">
					<label><b>Customer Name</b></label>
					<textarea
					ng-minlength ="1"
					required
					rows ="3"
					name="customerName"
					ng-change="searchCustomer()"
					ng-model="customer.name"
					class="form-control"
					placeholder="Customer Name">					
					</textarea>
					<div ng-show="workInvoiceForm.serviceReceiver.$dirty && workInvoiceForm.serviceReceiver.$invalid">
				        <small class="text-danger" ng-show="workInvoiceForm.serviceReceiver.$error.minlength">
				        	Customer name is required to be at least 3 characters long.
				        </small>
				      </div>
				    <div class="searched-result" ng-show="suggestList">
						<ul id="results" data-ng-repeat="customer in customers">
							<li class="result">
								<a class="each-result" ng-click="selectAddress($index)">
									<h6>
										<span class="customer-name">[[customer.name]]</span> 
										<span class="glyphicon glyphicon-map-marker"></span> 
										<span class="searched-address">[[customer.short_address]]</span> 
									</h6>
								</a>
							</li>
						</ul>
				    </div>
				</div>
			</div>
			<div class="col-md-6">
				<label><b>Customer Address</b></label>
				<textarea class="form-control" 
				rows="3" name="clientAddress" 
				placeholder="Street Address, City, State, Country"
				ng-minlength="5"
				ng-model="customer.address"
				required></textarea>
				<div ng-show="workInvoiceForm.clientAddress.$dirty && workInvoiceForm.clientAddress.$invalid">
			        <small class="text-danger" ng-show="workInvoiceForm.clientAddress.$error.minlength">
			        	Customer address is required to be at least 5 characters long.
			        </small>
			    </div>
			</div>
		</div>	
		<p></p>
	</div>
	<p></p>
	<div class="desc-holder section-content">
	<h4 class="content-title">Other Information</h4>
		<div data-ng-repeat="choice in choices">
		     <div class="row">
		     	<div class="col-md-6">
		     		<label><b>Work Description</b></label>
		     		<textarea rows="2" class="form-control" ng-model="choice.workDescription" placeholder="Work Description ..." required></textarea>
		     	</div>
		     	<div class="col-md-2 col-xs-12 col-sm-12">
		     	<label><b>Rate</b></label>
		     		<input type="text" class="form-control" ng-model="choice.rate" name="" ng-pattern="/^0|[1-9][0-9]*$/" placeholder="Enter rate" required>
		     	</div>
		     	<div class="col-md-3 col-xs-9 col-sm-10">
		     					
					<input type="radio" name="descType[[$index]]" ng-attr-id="ishour[[$index]]" ng-model="choice.descType" value="hour" ng-checked="true"> <label for="ishour[[$index]]"><b> Hour</b></label>&nbsp;
					<input type="radio" name="descType[[$index]]" ng-attr-id="isitem[[$index]]" ng-model="choice.descType" value="item" ng-checked="false"> <label for="isitem[[$index]]"><b> Item</b></label>

		     		<input type="text" class="form-control" ng-model="choice.hour" name="" ng-pattern="/^0|[1-9][0-9]*$/" placeholder="Enter hour / item" required>
		     	</div>
		     	<div class="col-md-1 col-xs-2 col-sm-1"><p><br></p>
		     		<button type="button" ng-click="removeInput($index)" ng-hide="($index == 0)" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></button>
		     		<button type="button" ng-show="$first" ng-click="addNewChoice()" class="btn btn-primary add-more-field"><span class="glyphicon glyphicon-plus-sign"></span></button>
		     	</div>
		     </div>	 
		     	  
	    </div>
	</div>
	<p></p>
	<div class="row">
		<p></p>
		<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
			<button type="submit" ng-disabled="workInvoiceForm.$invalid || !workInvoiceButtonStatus" class="btn btn-primary input-lg work-invoice-btn iapp-lg-btn choices-holder">[[workInvoiceButton]]</button>
		</div>
		<p></p>
	</div>
	<p></p>
	</form>
</div>
@stop