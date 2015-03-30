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
				<th>Invoice No#</th>
				<th>Issued To</th>
				<th>Created Date</th>
				<th>Amount</th>
				<th>Status</th>
			</tr>

		</table>
	</div>
</div>
@stop
