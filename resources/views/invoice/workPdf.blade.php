<style type="text/css">
  html {
    font-family: sans-serif;
  }
  body {
    margin: 0;
  }
    table {
    background-color: transparent;
  }
  th {
    text-align: left;
  }
  .table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
  }
  .table > thead > tr > th,
  .table > tbody > tr > th,
  .table > tfoot > tr > th,
  .table > thead > tr > td,
  .table > tbody > tr > td,
  .table > tfoot > tr > td {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
  }
  .table > thead > tr > th {
    vertical-align: bottom;
    border-bottom: 2px solid #ddd;
  }
  .table > caption + thead > tr:first-child > th,
  .table > colgroup + thead > tr:first-child > th,
  .table > thead:first-child > tr:first-child > th,
  .table > caption + thead > tr:first-child > td,
  .table > colgroup + thead > tr:first-child > td,
  .table > thead:first-child > tr:first-child > td {
    border-top: 0;
  }
  .table > tbody + tbody {
    border-top: 2px solid #ddd;
  }
  .table .table {
    background-color: #fff;
  } 
  .table-striped > tbody > tr:nth-child(odd) > td,
  .table-striped > tbody > tr:nth-child(odd) > th {
    background-color: #f9f9f9;
  }
  table col[class*="col-"] {
    position: static;
    display: table-column;
    float: none;
  }
  table td[class*="col-"],
  table th[class*="col-"] {
    position: static;
    display: table-cell;
    float: none;
  }
  .table > thead > tr > td.active,
  .table > tbody > tr > td.active,
  .table > tfoot > tr > td.active,
  .table > thead > tr > th.active,
  .table > tbody > tr > th.active,
  .table > tfoot > tr > th.active,
  .table > thead > tr.active > td,
  .table > tbody > tr.active > td,
  .table > tfoot > tr.active > td,
  .table > thead > tr.active > th,
  .table > tbody > tr.active > th,
  .table > tfoot > tr.active > th {
    background-color: #f5f5f5;
  }
  .table-hover > tbody > tr > td.active:hover,
  .table-hover > tbody > tr > th.active:hover,
  .table-hover > tbody > tr.active:hover > td,
  .table-hover > tbody > tr:hover > .active,
  .table-hover > tbody > tr.active:hover > th {
    background-color: #e8e8e8;
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
    margin: 0px !important;
      white-space: pre-line !important;
  }

  .pdf-print-btn{
  width: 70px;
  padding: 6px 0px;
  border-radius: 13px;
  background: #0099C9;
  margin: 24px 40px;
  border: 0px;
  font-size: 13px;
  position: absolute;
  border-color: #087A9D;
  color: #fff;
  right: 16%;
  }

.invoice-info td{
  width: 50%;
}
.mid-panels th{
  background: #D9EDF7;
}
.panels th{
  width: 50%;
  background: #D9EDF7;
}
@media print {
  .hidden-print {
    display: none !important;
  }
}
.container{
  width:900px !important;
}
.center-content{
  margin: 0 auto !important;
}

</style>
</head>  
<body>
<div class="container {{($requestType != 'downloadPDF') ? 'center-content' : ''}}">
  @if($requestType != 'downloadPDF')
  <div class="hidden-print">
    <button class="pdf-print-btn"><span class="glyphicon glyphicon-print"></span> Print</button>
  </div>
  @endif
  <h2 style="text-align:center">Invoice</h2><br>
  <table class="table">
    <tr>
      <td><b>Invoice No:</b> {{$invoice->invoiceNumber}}</td>
      <td><b>Invoice Date:</b> {{date('d/m/Y',strtotime($invoice->serviceDate))}}</td>
    </tr>
  </table>
  <table class="table table-bordered panels">
    <tr>
      <th>Service Provider</th>
      <th>Customer Information</th>
    </tr>
    <tr>
      <td>
        <b>{{$invoice->serviceProvider}}</b><br>
          <span>{{$invoice->companyAddress}}</span>
      </td>
      <td>
        <b>{{$invoice->serviceReceiver}}</b><br>
          <span>{{$invoice->clientAddress}}</span>
      </td>
    </tr>
  </table>
  <p></p>
  <table class="table table-bordered mid-panels">
    <thead>
      <tr>
        <th>Service</th>
        <th>Hours / Qty</th>
        <th>Rate / Price</th>
        <th>Sub Total</th>
      </tr>
    </thead>
    <tbody>
    <?php $subTotal = 0;?>
      @foreach($description as $each)
      <tr>
        <td>{{$each['workDescription']}}</td>
        <td>{{$each['hour']}}</td>
        <?php $subTotal += $each['rate'] * $each['hour'];?>
        <td>{{$currency . ' '.$each['rate']}}</td>
        <td>{{$currency . ' '.$each['rate'] * $each['hour']}}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="3" style="text-align:right"><b>Total:</b></td>
        <td><b>{{$currency . ' '.round($subTotal,2)}}</b></td>
      </tr>
    </tbody>
  </table>
  <p></p>
  <table class="panels table table-bordered">
    <tr>
      <th>Bank details</th>
      <th>Terms &amp; Conditions</th>
    </tr>
    <tr>
      <td>
        <div class="iapp-details">
          <pre>{{$invoice->bankDetails}}</pre>
        </div>
      </td>
      <td>
        <div class="iapp-details">
          <pre>
            {{$invoice->termsCondition}}
          </pre>
        </div>
      </td>
    </tr>
  </table>
  <p></p>
  <div class="row col-xs-12 iapp-details">
    <h5 class="underline-text"><b >Note</b></h5>
    <pre>{{$invoice->keyNote}}</pre>
  </div>
  <p></p>
</div>



