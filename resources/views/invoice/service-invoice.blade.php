@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Invoices / Service Invoice</div>
	</div>
</div>
<div class="container container-bordered" ng-controller="ServiceInvoiceController">
	<h3 class="title-line hidden-xs">Create New Service Invoice</h3>
	<h4 class="title-line visible-xs hidden-lg hidden-md hidden-sm">New Invoice</h4>
	<p></p>
	<div class="errors" ng-if="hasErrors">
		<div ng-class="{'alert alert-danger' : hasErrors}">
			<div ng-repeat="error in errors">
				<span ng-bind="error" ng-if="hasErrors"></span><br>
			</div>
		</div>
	</div>
	<p></p>	
	<form name="serviceInvoiceForm" ng-submit="serviceInvoiceForm.$valid && serviceInvoiceProcess()"  novalidate>
		<div class="org-content section-content">
			<h4 class="content-title">General Information</h4>
			<div class="row">
				<div class="col-md-5 col-lg-5 col-xs-9 col-sm-10">
					<label><b>Invoice No</b></label>
					<input class="form-control unique-number is-unique-invoice"
					ng-class="{
								error: serviceInvoiceForm.invoiceNumber.$dirty &&
								serviceInvoiceForm.invoiceNumber.$invalid}" 
					type="text"
					placeholder="Invoice Number" 
					name="invoiceNumber" 
					ng-model="organization.invoiceNumber"
					ng-change="checkState()"		
	            	ng-disabled="!manualCode"
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
					name="serviceDate" 
					ng-maxlength="10"
					maxlength="10"
					placeholder="YYYY/MM/DD"
					ng-model="organization.serviceDate"
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
<!-- 			<p></p>
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
					<label><b>Service Provider Address</b></label>
					<textarea class="form-control" 
					rows="3" 
					name="companyAddress" 
					placeholder="Company Location"
					ng-model="organization.address"
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
			</div>
			<p></p> -->
<!-- 			<div class="row">
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
				</div> -->
				<!-- <div class="col-md-6">
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
			<p></p> -->
		<!-- 	<div class="row">
				<div class="col-md-6">
					<label><b>Terms &amp; Conditions</b></label>
					<textarea class="form-control" 
					rows="10" name="termsCondition" 
					ng-minlength="20"
					ng-maxlength="250"
					maxlength="250"
					ng-model="organization.rules"
					placeholder="Terms of Services" required>
					</textarea>
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
					name="bankDetails" 
					rows="10"
					placeholder="Bank Account Detail Information ..."
					ng-model="organization.bankDetails"
					required>
					</textarea>
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
					ng-model="organization.note"
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
			</div>
			<p></p> -->
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
						<div ng-show="serviceInvoiceForm.serviceReceiver.$dirty && serviceInvoiceForm.serviceReceiver.$invalid">
					        <small class="text-danger" ng-show="serviceInvoiceForm.serviceReceiver.$error.required">
					           Customer name is required field
					        </small>
					        <small class="text-danger" ng-show="serviceInvoiceForm.serviceReceiver.$error.minlength">
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
						required>
						</textarea>
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
				<!-- <p></p>
				<div class="row">
					<div class="col-md-6">
						<label><b>Phone No</b></label>
						<input type="text" class="form-control" placeholder="Phone No" ng-pattern="/^0|[1-9][0-9]*$/" name="customerPhone" ng-model="customer.phone" required>
					</div>
					<div class="col-md-6">
						<label><b>Email</b></label>
						<input type="email" class="form-control" placeholder="Email" name="customereEmail" ng-model="customer.email" required>
					</div>
				</div> -->
				<!-- <p></p>
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
				</div> -->
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
				     	<div class="col-md-5 col-xs-8 col-sm-8">
				     	<label><b>Amount</b></label>
				     		<input type="text" class="form-control" ng-model="choice.amount" name="" ng-pattern="/^0|[1-9][0-9]*$/" placeholder="Enter Amount" required>
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
					<button type="submit" ng-disabled="serviceInvoiceForm.$invalid" class="btn btn-primary btn-block input-lg work-invoice-btn iapp-lg-btn choices-holder">[[serviceInvoiceButton]]</button>
				</div>
			</div>
			<p></p>
		</div>
	</form>
</div> 	
@stop