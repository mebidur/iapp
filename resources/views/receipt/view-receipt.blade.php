@extends('app')
@section('content')
<div class="container iapp-status">
	<div class="row">
		<div class="col-md-12">Receipts / Receipts History</div>
	</div>
</div>
<div class="container container-bordered">
	<h3 class="title-text">Receipt History</h3>
		@if(Session::has('message'))
		<div class="alert alert-success">
			{{Session::get('message')}}
			<a href="javascript:void(0)" class="pull-right alert-close" ng-fade-out>&times;</a>
		</div>
		@endif
	<span class="pagination-view hidden-xs">Showing {{$receipts->firstItem()}} to {{$receipts->lastItem()}} of {{$receipts->total()}} Receipts</span>
	<div class="table-responsive iapp-option {{(count($receipts) < 10) ? 'has-more-invoice' : '' }}">
		<table class="table">
			<tr>
				<th>SN</th>
				<th>Receipt No.</th>
				<th>Client</th>
				<th>Created Date</th>
				<th>Receipt Date</th>
				<th>Amount</th>
				<th>Option</th>
			</tr>
			<?php $sn = $receipts->firstItem();?>
			@if(!empty($receipts))
				@foreach($receipts as $receipt)
					<tr>
						<td>{{$sn}}</td>
						<td><a href="{{url('receipt/view?response='.$receipt->id.'&secure='.$receipt->type)}}">{{$receipt->receiptNumber}}</a></td>
						<td>{{$receipt['customer']->name}}</td>
						<td>{{date('d/m/Y H:i:s',strtotime($receipt->created_at))}}</td>
						<td>{{date('d/m/Y',strtotime($receipt->serviceDate))}}</td>
						<?php $workTotal = 0; $serviceTotal = 0; ?>
						@foreach($receipt['description'] as $each)
							@if($receipt->type == 1)
							<?php $workTotal += ($each->rate * $each->hour)?>
							@else
								<?php $serviceTotal += $each->amount;?>
							@endif
						@endforeach						
						<td>
							<span class="currency-view">{{$receipt->currency}}</span>{{($receipt->type == 1) ? $workTotal : $serviceTotal}}
						</td>
						<td class="dropdown">
							<button type="button" class="edit-btn dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-option-vertical"></span></button>
								<ul class="dropdown-menu dropdown-menu-hover" role="menu">
									<div class="triangle-up triangle-iapp"></div>
									<!-- <li><a href="javascript:void(0)" data-id="{{$receipt->id}}" ng-edit-receipt><span class="glyphicon glyphicon-pencil"></span> Edit</a></li> -->
									<!-- <li class="divider"></li> -->
									<li><a href="javascript:void(0)" data-id="{{$receipt->id}}" ng-remove-receipt><span class="glyphicon glyphicon-remove-sign"></span> Delete</a></li>
								</ul>
						</td>
					</tr>
					<?php $sn++;?>
				@endforeach
			@endif
		</table>
		<div class="iapp-pagination">
			{!! $receipts->render() !!}
		</div>
	</div>
</div>
@stop
