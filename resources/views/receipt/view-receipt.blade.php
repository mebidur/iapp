@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3>Receipt History</h3>
		@if(Session::has('message'))
		<div class="alert alert-success">
			{{Session::get('message')}}
			<a href="javascript:void(0)" class="pull-right alert-close">&times;</a>
		</div>
		@endif
	<span class="pagination-view">Showing {{$receipts->firstItem()}} to {{$receipts->lastItem()}} of {{$receipts->total()}} Receipts</span>
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>SN</th>
				<th>Receipt No.</th>
				<th>Client</th>
				<th>Created Date</th>
				<th>Receipt Date</th>
				<th>Amount</th>
			</tr>
			<?php $sn = $receipts->firstItem();?>
			@if(!empty($receipts))
				@foreach($receipts as $receipt)
					<tr>
						<td>{{$sn}}</td>
						<td><a href="{{url('receipt/view?secret='.$receipt->id.'&secure='.$receipt->type)}}">{{$receipt->receiptNumber}}</a></td>
						<td>{{$receipt->serviceReceiver}}</td>
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
						<td><span class="currency-view">{{$receipt->currency}}</span>{{($receipt->type == 1) ? $workTotal : $serviceTotal}}</td>
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
