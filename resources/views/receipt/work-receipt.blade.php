@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Receipts / Work Receipt</div>
	</div>
</div>
<div class="container container-bordered" ng-controller="WorkReceiptController">
	<h3 class="title-line hidden-xs">Create New Hourly Receipt</h3>
	<h4 class="title-line visible-xs hidden-lg hidden-md hidden-sm">New Receipt</h4>
	<p></p>
	@include('include.error-msg')
	<p></p>
	<form name="workReceiptForm" ng-submit="workReceiptForm.$valid && workReceiptProcess()"  novalidate>
	<div class="org-content section-content">
		<h4 class="content-title">Organization Information</h4>
		<div class="row">
			<div class="col-md-5 col-xs-9 col-sm-9">
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
			<div class="col-md-1 col-sm-3 col-xs-3">
				<label><b style="color:#fff;" class="hidden-print">Option</b></label>
				<button  class="btn btn-primary add-more-field" ng-click="doFocus()">
					<span class="glyphicon glyphicon-pencil"></span>
				</button>
				<input type="hidden" ng-model="organization.isManualCode" name="isManualCode">
			</div>
			<div class="col-md-6">
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
		</div>
		<p></p>
		<div class="row">
			<div class="col-md-6">
				<label><b>Service Provider</b></label>
				<textarea class="form-control" 
				name="serviceProvider" 
				ng-model="organization.name"
				placeholder="Company Name" 
				ng-minlength="3" 
				rows="3"
				required>
				</textarea>
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
				<label><b>Service Provider Address</b></label>
				<textarea class="form-control" 
				rows="3" 
				name="companyAddress" 
				placeholder="Company Location"
				ng-model="organization.address"
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
		</div>
		<p></p>
		<div class="row">
			<div class="col-md-6">
				<label><b>Phone No</b></label>
				<input type="text" class="form-control" placeholder="Phone No" ng-pattern="/^0|[1-9][0-9]*$/"  name="companyPhone" ng-model="organization.phoneNo" required>
			</div>
			<div class="col-md-6">
				<label><b>Email</b></label>
				<input type="email" class="form-control" placeholder="Email" name="companyEmail" ng-model="organization.email" required>
			</div>
		</div>
		<p></p>
		<div class="row">
			<div class="col-md-6">
				<label><b>City</b></label>
				<input type="text" class="form-control" name="companyCity" ng-model="organization.city" placeholder="City" required>
			</div>
			<div class="col-md-6">
				<label><b>State</b></label>
				<input type="text" class="form-control" name="companyState" ng-model="organization.state" placeholder="State" required>
			</div>
		</div>
		<p></p>
		<div class="row">
			<div class="col-md-6">
				<label><b>Country</b></label>
				<select class="form-control" name="companyCountry" ng-model="organization.country" required>
					@include('include.country-list')
				</select>
			</div>
			<div class="col-md-6">
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
		<div class="row">
			<div class="col-md-6">
				<label><b>Note</b></label>
				<textarea class="form-control" 
				rows="5" name="keyNote" 
				placeholder="Note from service provider"
				ng-model="organization.note"
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
				placeholder="Customer Address"
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
		<div class="row">
			<div class="col-md-6">
				<label><b>Phone No</b></label>
				<input type="text" class="form-control" placeholder="Phone No" ng-pattern="/^0|[1-9][0-9]*$/" name="customerPhone" ng-model="customer.phone" required>
			</div>
			<div class="col-md-6">
				<label><b>Email</b></label>
				<input type="email" class="form-control" placeholder="Email" name="customereEmail" ng-model="customer.email" required>
			</div>
		</div>
		<p></p>
		<div class="row">
			<div class="col-md-6">
				<label><b>City</b></label>
				<input type="text" class="form-control" name="customerCity" ng-model="customer.city" placeholder="City" required>
			</div>
			<div class="col-md-3">
				<label><b>State</b></label>
				<input type="text" class="form-control" name="customerState" ng-model="customer.state" placeholder="State" required>
			</div>
			<div class="col-md-3">
				<label><b>Country</b></label>
				<select class="form-control" name="customerCountry" ng-model="customer.country" required>
					@include('include.country-list')
				</select>
			</div>
		</div>
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
		     	<div class="col-md-3 col-xs-8 col-sm-8">
		     		<label><b>Hour</b></label>
		     		<input type="text" class="form-control" ng-model="choice.hour" name="" ng-pattern="/^0|[1-9][0-9]*$/" placeholder="Enter rate" required>
		     	</div>
		     	<div class="col-md-1 col-xs-3 col-sm-3"><p><br></p>
		     		<button type="button" ng-click="removeInput($index)" ng-hide="($index == 0)" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></button>
		     		<button type="button" ng-show="$first" ng-click="addNewChoice()" class="btn btn-primary add-more-field"><span class="glyphicon glyphicon-plus-sign"></span></button>
		     	</div>
		     </div>	 
		     	  
	    </div>
	</div>
	<p></p>
	<div class="row">
		<div class="col-md-12">
			<button type="submit" ng-disabled="workReceiptForm.$invalid || !workReceiptButtonStatus" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn choices-holder">[[workReceiptButton]]</button>
		</div>
	</div>
	<p></p>
</form>
</div>
@stop