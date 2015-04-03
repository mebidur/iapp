<div class="row">
	<div class="col-md-1 col-xs-12 col-lg-1 col-sm-12 nav-icon {{($current == 'home') ? 'active-tab' : ''}}">
		<a href="{{url('/')}}">
		    <h4 class="text-center">Home</h4>
		    <h5 class="text-center text-muted visible-lg visible-md hidden-xs">Invoices</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Invoices</h6>
		</a>
	</div>
	<div class="col-md-1 col-xs-12 col-lg-1 col-sm-12 nav-icon {{($current == 'receipt') ? 'active-tab' : ''}}">
		<a href="{{url('/receipt')}}">
		    <h4 class="text-center">Receipts</h4>
		    <h5 class="text-center text-muted visible-lg visible-md hidden-xs">Receipts</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Receipts</h6>
		</a>
	</div>
	<div class="col-md-3 col-xs-12 col-lg-3 col-sm-12 nav-icon {{($current == 'work-invoice') ? 'active-tab' : ''}}">
		<a href="{{url('/invoice/work')}}">
		    <h4 class="text-center">Working Hour Invoice</h4>
		    <h5 class="text-center text-muted visible-lg visible-md hidden-xs">Invoice Billing</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Invoice Billing</h6>
		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'service-invoice') ? 'active-tab' : ''}}">
		<a href="{{url('/invoice/service')}}">
			<h4 class="text-center">Service Invoice</h4>
			<h5 class="text-center text-muted visible-lg visible-md hidden-xs">Invoice Billing</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Invoice Billing</h6>

		</a>
	</div>
	<div class="col-md-3 col-xs-12 col-lg-3 col-sm-12 nav-icon {{($current == 'work-receipt') ? 'active-tab' : ''}}">
		<a href="{{url('/receipt/work')}}">
			<h4 class="text-center">Working Hour Receipt</h4>
			<h5 class="text-center text-muted visible-lg visible-md hidden-xs">Receipt Billing</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Receipt Billing</h6>

		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'service-receipt') ? 'active-tab' : ''}}">
		<a href="{{url('/receipt/service')}}">
			<h4 class="text-center">Service Receipt</h4>
			<h5 class="text-center text-muted visible-lg visible-md hidden-xs">Receipt Billing</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Receipt Billing</h6>
		</a>
	</div>
</div>