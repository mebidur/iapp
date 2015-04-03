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
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>Receipt No.</th>
				<th>Client</th>
				<th>Created Date</th>
				<th>Amount</th>
			</tr>
			@if(!empty($receipts))
				@foreach($receipts as $receipt)
					<tr>
						<td>{{$receipt->receiptNumber}}</td>
						<td>{{$receipt->serviceReceiver}}</td>
						<td>{{$receipt->created_at}}</td>
						<td></td>
					</tr>
				@endforeach
			@endif
		</table>
	</div>
</div>
@stop
