@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Receipts / Edit Work Receipt</div>
	</div>
</div>
<div class="container container-bordered" ng-controller="EditWorkReceiptController" ng-init="general.currentId='{{$currentId}}';">
	<p></p>
	<div class="errors" ng-if="hasErrors">
		<div ng-class="{'alert alert-danger' : hasErrors}">
			<div ng-repeat="error in errors">
				<span ng-bind="error" ng-if="hasErrors"></span><br>
			</div>
		</div>
	</div>
	<p></p>
	<form name="workReceiptForm" ng-submit="workReceiptForm.$valid && workReceiptProcess()"  novalidate>
	<div class="org-content section-content">
		<h4 class="content-title">General Information</h4>
		<input type="hidden" ng-model="general.currentId" name="currentId">
		<div class="row">
			<div class="col-md-5 col-xs-9 col-sm-10">
				<label><b>Receipt No</b></label>
				<input class="form-control unique-number is-unique-receipt" 
				type="text"
				placeholder="Receipt Number" 
				name="receiptNumber" 
				ng-model="organization.receiptNumber"
	            ng-minlength="5"
	            ng-change="checkState()"		
	            ng-disabled="!manualCode"
	            ng-focus 
	            ng-maxlength="20"
	            maxlength="20" 
	            is-unique="receiptNumber"
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
			<div class="col-md-1 col-sm-1 col-xs-1">
				<label><b style="color:#fff;" class="hidden-print">Option</b></label>
				<button type="button" class="btn btn-primary add-more-field" ng-click="doFocus()">
					<span class="glyphicon glyphicon-pencil"></span>
				</button>
				<input type="hidden" ng-model="organization.isManualCode" name="isManualCode">
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12">
				<label><b>Receipt Date</b></label>
				<input type="text" class="form-control iapp-date" 
				name="serviceDate" 
				ng-maxlength="10"
				maxlength="10"
				placeholder="YYYY/MM/DD" 
				ng-model="organization.serviceDate"
				ng-datepicker
				required>
				<div ng-show="workReceiptForm.serviceDate.$dirty && workReceiptForm.serviceDate.$invalid">
			        <small class="text-danger" ng-show="workReceiptForm.serviceDate.$error.required">
			           Receipt date is required field
			        </small>
			        <small class="text-danger" ng-show="workReceiptForm.serviceDate.$error.maxlength">
			        	Date format provided is invalid.
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
	</div>
	<p></p>
	<div class="customer-content section-content">
		<h4 class="content-title">Customer Information</h4>
		<div class="row">
			<div class="col-md-6">
				<label><b>Customer Name</b></label>
				<textarea
				ng-minlength ="3"
				required
				rows ="3"
				name="customerName"
				ng-model="customer.name"
				class="form-control"
				placeholder="Customer Name">					
				</textarea>			
				<div ng-show="workReceiptForm.serviceReceiver.$dirty && workReceiptForm.serviceReceiver.$invalid">
			        <small class="text-danger" ng-show="workReceiptForm.serviceReceiver.$error.required">
			           Customer name is required field
			        </small>
			        <small class="text-danger" ng-show="workReceiptForm.serviceReceiver.$error.minlength">
			        	Customer name is required to be at least 3 characters
			        </small>
			      </div>
			</div>
			<div class="col-md-6">
				<label><b>Customer Address</b></label>
				<textarea class="form-control" 
				rows="3" name="clientAddress" 
				placeholder="Street, City, State, Country"
				ng-minlength="5"
				ng-model="customer.address"
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
	</div>
	<p></p>
	<div class="desc-holder section-content">
	<h4 class="content-title">Other Information</h4>
		<div data-ng-repeat="choice in choices">
		     <div class="row">
		     	<div class="col-md-6">
		     		<label><b>Work Description</b></label>
		     		<textarea rows="3" class="form-control" ng-model="choice.workDescription" placeholder="Work Description ..." required></textarea>
		     	</div>
		     	<div class="col-md-2 col-xs-12 col-sm-12">
		     	<label><b>Rate</b></label>
		     		<input type="text" class="form-control" ng-model="choice.rate" name="" ng-pattern="/^0|[1-9][0-9]*$/" placeholder="Enter hour" required>
		     	</div>
		     	<div class="col-md-3 col-xs-9 col-sm-10">
		     		<label><b>Hour</b></label>
		     		<input type="text" class="form-control" ng-model="choice.hour" name="" ng-pattern="/^0|[1-9][0-9]*$/" placeholder="Enter rate" required>
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
		<div class="col-md-12">
			<button type="submit" ng-disabled="workReceiptForm.$invalid || !workReceiptButtonStatus" class="btn btn-primary input-lg work-invoice-btn iapp-lg-btn choices-holder">[[workReceiptButton]]</button>
		</div>
	</div>
	<p></p>
</form>
</div>
@stop