@extends('app')
@section('content')
<div class="container">
	@include('app-nav')
</div>
<br>
<div class="container container-bordered">
	<h3>receipt History</h3>
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
			<?php $wtotal = 0; $stotal = 0; $sn = $receipts->firstItem();?>
			@if(!empty($receipts))
				@foreach($receipts as $receipt)
					<tr>
						<td>{{$sn}}</td>
						<td>{{$receipt->receiptNumber}}</td>
						<td>{{$receipt->serviceReceiver}}</td>
						<td>{{date('d/m/Y H:i:s',strtotime($receipt->created_at))}}</td>
						<td>{{date('d/m/Y',strtotime($receipt->serviceDate))}}</td>
						@foreach($receipt['description'] as $each)
							@if($receipt->type == 1)
							<?php $wtotal += ($each->rate * $each->hour) ?>
							@else
								<?php $stotal += $each->amount;?>
							@endif
						@endforeach
						<td><span>{{$receipt->currency}} </span>{{($receipt->type == 1) ? $wtotal : $stotal}}</td>
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
