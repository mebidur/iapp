@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3>Invoice History</h3>
		@if(Session::has('message'))
		<div class="alert alert-success">
			{{Session::get('message')}}
			<a href="javascript:void(0)" class="pull-right alert-close">&times;</a>
		</div>
		@endif
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>Invoice No.</th>
				<th>Client</th>
				<th>Created Date</th>
				<th>Amount</th>
				<th>Status</th>
			</tr>
			@if(!empty($invoices))
				@foreach($invoices as $invoice)
					<tr>
						<?php $total = 0;?>
						<td>{{$invoice->invoiceNumber}}</td>
						<td>{{$invoice->serviceReceiver}}</td>
						<td>{{$invoice->created_at}}</td>
						@foreach($invoice['description'] as $each)
							<?php $total += ($each->rate * $each->hour) ?>
						@endforeach
						<td>{{$total}}</td>
					</tr>
				@endforeach
			@endif

		</table>
	</div>
</div>
@stop
