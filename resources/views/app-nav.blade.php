<div class="row">
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'home') ? 'active-tab' : ''}}">
		<a href="{{url('/')}}">
		    <h4 class="text-center">Home</h4>
		    <h5 class="text-center text-muted visible-lg visible-md hidden-xs">Invoices / History</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Invoices / History</h6>
		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'work-invoice') ? 'active-tab' : ''}}">
		<a href="{{url('/invoice/work')}}">
		    <h4 class="text-center">Hourly Invoice</h4>
		    <h5 class="text-center text-muted visible-lg visible-md hidden-xs">New Invoice</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">New Invoice</h6>
		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'service-invoice') ? 'active-tab' : ''}}">
		<a href="{{url('/invoice/service')}}">
			<h4 class="text-center">Service Invoice</h4>
			<h5 class="text-center text-muted visible-lg visible-md hidden-xs">New Invoice</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">New Invoice</h6>

		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'receipt') ? 'active-tab' : ''}}">
		<a href="{{url('/receipt')}}">
		    <h4 class="text-center">Receipts</h4>
		    <h5 class="text-center text-muted visible-lg visible-md hidden-xs">Receipts / History</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">Receipts / History</h6>
		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'work-receipt') ? 'active-tab' : ''}}">
		<a href="{{url('/receipt/work')}}">
			<h4 class="text-center">Hourly Receipt</h4>
			<h5 class="text-center text-muted visible-lg visible-md hidden-xs">New Receipt</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">New Receipt</h6>

		</a>
	</div>
	<div class="col-md-2 col-xs-12 col-lg-2 col-sm-12 nav-icon {{($current == 'service-receipt') ? 'active-tab' : ''}}">
		<a href="{{url('/receipt/service')}}">
			<h4 class="text-center">Service Receipt</h4>
			<h5 class="text-center text-muted visible-lg visible-md hidden-xs">New Receipt</h5>
			<h6 class="text-center text-muted hidden-lg hidden-md visible-sm visible-xs">New Receipt</h6>
		</a>
	</div>
</div>