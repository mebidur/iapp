<link rel="stylesheet" type="text/css" href="{{url('css/app.css')}}">
<style type="text/css">
	html {
	  font-family: sans-serif;
	}
	body {
	  margin: 0;
	}
  .panel{
      -webkit-box-shadow: none !important;
      box-shadow: none !important;
      border-color: #dfdfdf;
  }
  .total-label{
    padding-right: 0px !important; 
    padding-left: 0px !important; 
    text-align: right;
  }
  .panel-info {
	  border-color: #CADCE5 !important;
	}
  h5{
    font-style: bold !important;
  }
  .underline-text{
    padding-bottom: 2px !important;
    border-bottom: 1px dashed #dfdfdf;
  }
  .iapp-details pre{
  	  background: #fff !important;
	  border: none !important;
	  padding: 0px !important;
	  font-family: sans-serif !important;
  }
  tbody tr{
  	font-size: 14px !important;
  }
</style>
</head>  
<body>
<div class="container" style="max-width:900px !important;">
  <div class="row" style="border-bottom:1px dashed #dfdfdf;">
    <div class="col-xs-12">
      <h2 class="text-center">Receipt</h2>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-xs-5"><b>Receipt No:</b> {{$receipt->receiptNumber}}</div>
    <div class="col-xs-5 col-xs-offset-2 text-left"><b>Receipt Date:</b> {{date('d/m/Y',strtotime($receipt->receiptDate))}}</div>
  </div>
  <p></p>
  <div class="row">
    <div class="col-xs-5">
      <div class="panel panel-info">
      <div class="panel-heading">
          <h5>Service Provider</h5>
        </div>
      <div class="panel-body">
          <p>
           <b>{{$receipt->serviceProvider}}</b><br>
            <span>{{$receipt->companyAddress}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-xs-5 col-xs-offset-2 text-left">
      <div class="panel panel-info">
       <div class="panel-heading">
          <h5>Customer Information</h5>
        </div>
        <div class="panel-body">
          <p>
            <b>{{$receipt->serviceReceiver}}</b><br>
            <span>{{$receipt->clientAddress}}</span>
          </p>
        </div>
      </div>
    </div>
  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th><h5><b>Service</b></h5></th>
        <th><h5><b>Amount</b></h5></th>
        <th><h5><b>Sub Total</b></h5></th>
      </tr>
    </thead>
    <tbody>
    <?php $subTotal = 0;?>
      @foreach($description as $each)
      <tr>
        <td>{{$each['workDescription']}}</td>
        <td>{{$each['amount']}}</td>
        <td>{{$each['amount']}}</td>
        <?php $subTotal += $each['amount'];?>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" class="text-right"><b>Total:</b></td>
        <td><b>{{$currency . ' '.round($subTotal,2)}}</b></td>
      </tr>
    </tbody>
  </table>
  <p></p>
  <div class="row col-xs-12 iapp-details">
  	<h5 class="underline-text"><b >Note</b></h5>
  	<pre>{{$receipt->keyNote}}</pre>
  </div>
  <p></p>
</div>