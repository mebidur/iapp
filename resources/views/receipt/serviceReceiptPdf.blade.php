<!DOCTYPE html>
<html>
<head>
  <title>Service Receipt PDF File</title>
<style type="text/css">
  html {
    font-family: sans-serif;
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%
  }
  body {
      font-family: 'Open Sans', sans-serif;
      font-size: 14px;
      line-height: 1.42857;
      background-color: #fff
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
  .iapp-details{
    max-width: 900px !important;
    padding: 4px!important;
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
    padding: 6px 15px;
    border-radius: 13px;
    background: #0099C9;
    border: 0px;
    font-size: 13px;
    border-color: #087A9D;
    color: #fff; 
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
    max-width:900px !important;
    overflow: hidden !important;
  }
  .center-content{
    margin: 0 auto !important;
  }
  .table-header tr td{
    vertical-align: middle !important;
  }
  .iapp-details h4{
    font-size: 16px !important;
  }
  .invoice-date{
    text-align: right !important;
    vertical-align: middle !important;
  }
  .invoice-no{
    width: 50% !important;
    vertical-align: middle !important;
  }
  .btn-download-pdf{
    padding: 6px 12px;
    border-radius: 13px;
    background: #0099C9;
    border: 0px;
    font-size: 13px;
    border-color: #087A9D;
    color: #fff;
  }
  .button-content{
    position:absolute;
    margin-top: 10px !important;
  }
.currency-view{
  padding-right: 2px !important;
}
.go-back, .go-back:hover{
  padding: 6px 12px;
  text-decoration: none;
  border-radius: 13px;
  background: #0099C9;
  border: 0px;
  font-size: 13px;
  border-color: #087A9D;
  color: #fff;
}
</style>
</head>
<body>
  <div  id="container" class="container {{($requestType != 'downloadServicePDF') ? 'center-content' : ''}}" ng-app="iApp" ng-controller="WorkPdfController">
  @if($requestType != 'downloadServicePDF')
  <div class="hidden-print">
    {!!Form::open(['url' => '/receipt/download'])!!}
      <input type="hidden" name="requestType" value="downloadServicePDF">
      <input type="hidden" name="receiptId" value="{{$receipt->id}}">
      <div class="pdf-buttons button-content">
        <button  type="button" class="go-back" ng-click="goBack()">Go Back</button>
        <button type="submit" class="btn-download-pdf">Download PDF</button>
        <button type="button" id="pdf-print-btn" class="pdf-print-btn">Print</button>
      </div>  
    {!!Form::close()!!}
    
  </div>
  @endif
  <h2 style="text-align:center">Receipt</h2><br>
  <table class="table table-header">
    <tr>
      <td class="invoice-no"><b>Invoice No:</b> {{$receipt->receiptNumber}}</td>
      <td class="invoice-date"><b>Invoice Date:</b> {{date('d/m/Y',strtotime($receipt->serviceDate))}}</td>
    </tr>
  </table>
  <table class="table table-bordered panels">
    <tr>
      <th>Service Provider</th>
      <th>Customer Information</th>
    </tr>
    <tr>
      <td>
       <div class="iapp-details">
         <pre>
            <b>{{$receipt['organization']->name}}</b>
         </pre>
       </div>
          <div class="iapp-details">
            <pre>
              {{$receipt['organization']->address}},<?php echo "\n" ?>
              {{$receipt['organization']->city}}<?php echo "\n" ?>
              {{$receipt['organization']->country}}
            </pre>
          </div>
      </td>
      <td>
        <div class="iapp-details">
          <pre>
            <b>{{$receipt['customer']->name}}</b>
          </pre>
        </div>
          <div class="iapp-details">
            <pre>
              {{$receipt['customer']->address}}
            </pre>
          </div>
      </td>
    </tr>
  </table>
  <p></p>
  <table class="table mid-panels">
    <thead>
      <tr>
        <th>Service</th>
        <th>Amount</th>
        <th>Sub Total</th>
      </tr>
    </thead>
    <tbody>
    <?php $subTotal = 0;?>
      @foreach($receipt['description'] as $each)
      <tr>
        <td>{{$each['workDescription']}}</td>
        <td>{{$each['amount']}}</td>
        <td><span class="currency-view">{{$receipt->currency}}</span><span>{{$each['amount']}}</span></td>
        <?php $subTotal += $each['amount'];?>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="text-align:right"><b>Total:</b></td>
        <td><b><span class="currency-view">{{$receipt->currency}}</span>{{round($subTotal,2)}}</b></td>
      </tr>
    </tbody>
  </table>
    <p></p>
    <table class="panels table table-bordered">
    <tr>
      <th>Note</th>
    </tr>
    <tr>
      <td>
          <div class="iapp-details">
            <pre>
              {{$receipt['organization']->note}}
            </pre>
        </div>
      </td>
    </tr>
  </table>
</div>
<script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/printPdf.js')}}"></script>
<script type="text/javascript" src="{{url('js/angular.min.js')}}"></script>
<script type="text/javascript">
  var app = angular.module('iApp', []);
  app.controller('WorkPdfController',function($scope, $timeout, $window){
    $scope.goBack = function(){
      $window.history.back();
    }
  });

</script>
</body>
</html>