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
	<div class="table-responsive iapp-option {{(count($invoices) < 10) ? 'has-more-invoice' : '' }}">
		<table class="table">
			<tr>
				<th>SN</th>
				<th>Invoice No.</th>
				<th>Client</th>
				<th>Created Date</th>
				<th>Inovice Date</th>
				<th>Amount</th>
				<th>Status</th>
				<th>Action</th>
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
						<td class="dropdown">
							<button type="button" class="edit-btn dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-option-vertical"></span></button>
								<ul class="dropdown-menu dropdown-menu-hover" role="menu">
									<div class="triangle-up triangle-iapp"></div>
									@if($invoice->status == 0)
									<li><a href="javascript:void(0)" data-id="{{$invoice->id}}" ng-pay-invoice><span class="glyphicon glyphicon-credit-card"></span> Paid</a></li>
									<li class="divider"></li>
									@endif
									<li><a href="javascript:void(0)" data-id="{{$invoice->id}}" ng-edit-invoice><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
									<li class="divider"></li>
									<li><a href="javascript:void(0)" data-id="{{$invoice->id}}" ng-remove-invoice><span class="glyphicon glyphicon-remove-sign"></span> Delete</a></li>
								</ul>
						</td>
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