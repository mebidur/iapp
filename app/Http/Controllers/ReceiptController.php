<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\WorkReceipt;
use App\Http\Requests\ServiceReceipt;
use App\Receipt;
use App\Organization;
use App\Description;
use Vsmoraes\Pdf\Pdf;
use View;

class ReceiptController extends Controller {
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function getIndex(){
		$receipts = Receipt::with(['description'])
							->where('organization_id', \Auth::user()->organization_id)
							->paginate(10);
							
		return \View::make('receipt.view-receipt')->with(['current' => 'receipt',
														  'receipts' => $receipts,]);
	}
	public function getWork()
	{
		return View::make('receipt.work-receipt')
					->with(['current' => 'work-receipt']);
	}

	public function postWork(WorkReceipt $request, Receipt $receipt, PDF $pdf)
	{
		$data = Receipt::with('description')
						->where('receiptNumber',$request->receiptNumber)
						->first();
		
		if(!empty($data)){
			$receipt = $data;
			$desc = $receipt['description'];

		}else{
			$descArray = [];
			$receipt = $receipt->create(array_merge($request->all(),['organization_id' => \Auth::user()->organization_id]));	
			
			for($i = 0; $i < count($request->rate); $i++){
				array_push($descArray, new Description(['receipt_id' => $receipt->id,
														'workDescription' => $request->workDescription[$i],
														'rate' => $request->rate[$i],
														'hour' => $request->hour[$i],
														'type'	=> '1',]));
			}
			$desc = $receipt->description()->saveMany($descArray);
		}
			 
		if($request->requestType == 'workReceipt'){
		    return \View::make('receipt.workReceiptPdf')->with(['receipt' => $receipt,
		    													'description' => $desc,
		    													'currency' => $request->currency,
		    													'requestType' => $request->requestType,])->render();

		}else{
			$html = \View::make('receipt.workReceiptPdf')->with(['receipt' => $receipt,
																 'description' => $desc,
																 'currency' => $request->currency, 
																 'requestType' => $request->requestType,])->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}
	}

	public function getService()
	{
		return View::make('receipt.service-receipt')
					->with(['current' => 'service-receipt',]);
	}

	public function postService(WorkReceipt $request, Receipt $receipt, PDF $pdf)
	{
		$data = Receipt::with('description')->where('receiptNumber',$request->receiptNumber)->first();
		
		if(!empty($data)){
			$receipt = $data;
			$desc = $receipt['description'];

		}else{
			$descArray = [];
			$receipt = $receipt->create(array_merge($request->all(),['organization_id' => \Auth::user()->organization_id]));	
			
			for($i = 0; $i < count($request->workDescription); $i++){
				array_push($descArray, new Description(['receipt_id' => $receipt->id,
														'workDescription' => $request->workDescription[$i],
														'amount' => $request->amount[$i],
														'type'	=> '2',]));
			}
			$desc = $receipt->description()->saveMany($descArray);
		}
			
		if($request->requestType == 'serviceReceipt'){
		    return \View::make('receipt.serviceReceiptPdf')->with(['receipt' => $receipt, 
		    														'description' => $desc, 
		    														'requestType' => $request->requestType])->render();

		}else{
			$html = \View::make('receipt.serviceReceiptPdf')->with(['receipt' => $receipt, 
																	'description' => $desc, 
																	'requestType' => $request->requestType])->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}
	}
	
	public function postCheck()
	{
		$data = Receipt::where('receiptNumber',\Input::get('receiptId'))->first();
		if(!empty($data)){
			return true;
		}
		return false;
	}

}
