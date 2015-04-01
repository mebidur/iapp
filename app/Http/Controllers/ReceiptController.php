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
	
	public function getWork()
	{
		return View::make('receipt.work-receipt')
					->with(['current' => 'work-receipt',
						'receiptNumber'=> strtoupper(Str::random(14)),]);
	}

	public function postWork(WorkReceipt $request, Receipt $receipt, PDF $pdf)
	{
		$descArray = [];
		$receipt = $receipt->create(array_merge($request->all(),['organization_id' => \Auth::user()->organization_id]));	
		
		for($i = 0; $i < count($request->rate); $i++){
			array_push($descArray, new Description(['receipt_id' => $receipt->id,
													'workDescription' => $request->workDescription[$i],
													'rate' => $request->rate[$i],
													'hour' => $request->hour[$i] ]));
		}
		$desc = $receipt->description()->saveMany($descArray);
		
		if($request->requestType == 'workReceipt'){
		    return \View::make('receipt.workReceiptPdf')->with(['receipt' => $receipt, 'description' => $desc,'currency' => $request->currency])->render();

		}else{
			return 'under construction Download PDF ...';
			$html = \View::make('receipt.workPdf')->with(['receipt' => $receipt, 'description' => $desc,'currency' => $request->currency])->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
			// return response()->download($file, "Ahmed Badawy - CV.pdf");
		}
	}

	public function getService()
	{
		return View::make('receipt.service-receipt')
					->with(['current' => 'service-receipt',
							'receiptNumber'=> strtoupper(Str::random(14)),]);
	}

	public function postService(WorkReceipt $request, Receipt $receipt, PDF $pdf)
	{
		$descArray = [];
		$receipt = $receipt->create(array_merge($request->all(),['organization_id' => \Auth::user()->organization_id]));	
		
		for($i = 0; $i < count($request->workDescription); $i++){
			array_push($descArray, new Description(['receipt_id' => $receipt->id, 'workDescription' => $request->workDescription[$i],'amount' => $request->amount[$i],]));
		}
		$desc = $receipt->description()->saveMany($descArray);
		
		if($request->requestType == 'serviceReceipt'){
		    return \View::make('receipt.serviceReceiptPdf')->with(['receipt' => $receipt, 'description' => $desc,'currency' => $request->currency])->render();

		}else{
			return 'under construction Download PDF ...';
			$html = \View::make('receipt.serviceReceipt')->with(['receipt' => $receipt, 'description' => $desc,'currency' => $request->currency])->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
			// return response()->download($file, "Ahmed Badawy - CV.pdf");
		}
	}

}
