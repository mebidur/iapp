@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Home / Invoices History</div>
	</div>
</div>
<div class="container container-bordered">
	<h3 class="title-text">Invoice History</h3>
		@if(Session::has('message'))
		<div class="alert alert-success">
			{{Session::get('message')}}
			<a href="javascript:void(0)" class="pull-right alert-close" ng-fade-out><span class="success-close">&times;</span></a>
		</div>
		@endif
	<span class="pagination-view hidden-xs" style="top: 4% !important;">Showing {{$invoices->firstItem()}} to {{$invoices->lastItem()}} of {{$invoices->total()}} Invoices</span>
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
			<?php $sn = $invoices->firstItem();?>
			@if(!empty($invoices))
				@foreach($invoices as $invoice)
					<tr>
						<td>{{$sn}}</td>
						<td><a href="{{url('invoice/view?response='.$invoice->id.'&secure='.$invoice->type)}}">{{$invoice->invoiceNumber}}</a></td>
						<td>{{$invoice['customer']->name}}</td>
						<td>{{date('d/m/Y H:i:s',strtotime($invoice->created_at))}}</td>
						<td>{{date('d/m/Y',strtotime($invoice->serviceDate))}}</td>
						<?php $workTotal = 0; $serviceTotal = 0; ?>
						@foreach($invoice['description'] as $each)
							@if($invoice->type == 1)
							<?php $workTotal += ($each->rate * $each->hour)?>
							@else
								<?php $serviceTotal += $each->amount;?>
							@endif
						@endforeach
						<td><span class="currency-view">{{$invoice->currency}}</span>{{($invoice->type == 1) ? $workTotal : $serviceTotal}}</td>
						
						<td>{!! ($invoice->status == 0) ? '<span class="iapp-badge">Pending</span>': '<span class="iapp-badge">Paid</span>' !!}</td>
						<td>{!! ($invoice->status == 0) ? '<button data-id="'.$invoice->id.'" class="status-print-btn" ng-pay-invoice>Paid</button>': '<span class="glyphicon glyphicon-ok iapp-ok"></span>' !!}</td>
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
