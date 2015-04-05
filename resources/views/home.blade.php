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
	<span class="pagination-view" style="top: 4% !important;">Showing {{$invoices->firstItem()}} to {{$invoices->lastItem()}} of {{$invoices->total()}} Invoices</span>
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>SN</th>
				<th>Invoice No.</th>
				<th>Client</th>
				<th>Created Date</th>
				<th>Inovice Date</th>
				<th>Amount</th>
				<th>Status</th>
				<th>Option</th>
			</tr>
			<?php $wtotal = 0; $stotal = 0; $sn = $invoices->firstItem();?>
			@if(!empty($invoices))
				@foreach($invoices as $invoice)
					<tr>
						<td>{{$sn}}</td>
						<td>{{$invoice->invoiceNumber}}</td>
						<td>{{$invoice->serviceReceiver}}</td>
						<td>{{date('d/m/Y H:i:s',strtotime($invoice->created_at))}}</td>
						<td>{{date('d/m/Y',strtotime($invoice->serviceDate))}}</td>
						@foreach($invoice['description'] as $each)
							@if($invoice->type == 1)
							<?php $wtotal += ($each->rate * $each->hour) ?>
							@else
								<?php $stotal += $each->amount;?>
							@endif
						@endforeach
						<td><span>{{$invoice->currency}} </span>{{($invoice->type == 1) ? $wtotal : $stotal}}</td>
						
						<td>{!! ($invoice->status == 0) ? '<span class="iapp-badge">Pending</span>': '<span class="iapp-badge">Paid</span>' !!}</td>
						<td>{!! ($invoice->status == 0) ? '<button class="status-print-btn">Paid</button>': '<span class="glyphicon glyphicon-ok iapp-ok"></span>' !!}</td>
					</tr>
					<?php $sn++;?>
				@endforeach
			@endif
		</table>
		<div class="iapp-pagination">
			{!! $invoices->render() !!}
		</div>
	</div>
</div>
@stop
