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
		if($request->requestType == 'default')
		{
			$data = Receipt::with('description')
							->where('receiptNumber',$request->receiptNumber)
							->first();
			
			if(!empty($data)){
				$receipt = $data;
				$desc = $receipt['description'];

			}else{
				$descAll = [];
				$allInput = array_merge($request->all(),['organization_id' => \Auth::user()->organization_id,'type' => '1']);
				$receiptData = array_except($allInput, ['workDescription','rate','hour']);
				$descOnly = array_only($allInput, ['workDescription','rate','hour']);

				$receipt = $receipt->create($receiptData);	
				
				for($i = 0; $i < count($descOnly['rate']); $i++){
					array_push($descAll, new Description(['receipt_id' => $receipt->id,
														  'workDescription' => $descOnly['workDescription'][$i],
														  'rate' => $descOnly['rate'][$i],
														  'hour' => $descOnly['hour'][$i]
															]));
				}
				$desc = $receipt->description()->saveMany($descAll);
			}
		
	    	return \View::make('receipt.workReceiptPdf')->with(['receipt' => $receipt,
															'description' => $desc,
															'requestType' => $request->requestType,])
	    											->render();
	    }
	    return 'Bad request!';
	}

	public function getService()
	{
		return View::make('receipt.service-receipt')
					->with(['current' => 'service-receipt',]);
	}

	public function getView()
	{
		if(\Input::has('secret') && \Input::has('secure'))
		{
			$receiptData = Receipt::whereId(\Input::get('secret'))->with('description')->first();
			if(\Input::get('secure') == '1')
			{
				return \View::make('receipt.workReceiptPdf')->with(['receipt' => $receiptData,
			 												'description' => $receiptData['description'],
		    												'requestType' => 'viewAgain',])
			 											->render();		
			}
			if(\Input::get('secure') == '2')
			{
				return \View::make('receipt.serviceReceiptPdf')->with(['receipt' => $receiptData,
			 												'description' => $receiptData['description'],
		    												'requestType' => 'viewAgain',])
			 											->render();
			}
		}
		return 'Bad request!';
	}
	
	public function postDownload(PDF $pdf){
		$receiptData = Receipt::with('description')
							  ->whereId(\Input::get('receiptId'))
							  ->whereOrganizationId(\Auth::user()->organization_id)
							  ->first();
		if(\Input::get('requestType') == 'downloadServicePDF')
		{
			$html = \View::make('receipt.serviceReceiptPdf')
						->with(['receipt' => $receiptData,
						 		'description' => $receiptData['description'],
						 		'requestType' => 'downloadServicePDF'])
						->render();

			return $pdf->load($html, 'A4', 'portrait')->download();
		}
		if(\Input::get('requestType') == 'downloadWorkPDF')
		{
			$html = \View::make('receipt.workReceiptPdf')->with(['receipt' => $receiptData,
																 'description' => $receiptData['description'],
																 'requestType' => 'downloadWorkPDF',])
														->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}
		return 'Bad Request!';
	}

	public function postService(ServiceReceipt $request, Receipt $receipt, PDF $pdf)
	{	

		if($request->requestType == 'default'){
			$data = Receipt::with('description')->whereReceiptnumber($request->receiptNumber)->first();
		
			if(!empty($data)){
				$receipt = $data;
				$desc = $receipt['description'];

			}else{
				$descAll = [];
				$allData = array_merge($request->all(),['organization_id' => \Auth::user()->organization_id,'type' => '2']);
				$receiptData = array_except($allData, ['workDescription','amount']);
				$descOnly = array_only($allData, ['workDescription','amount']);
				
				$receipt = $receipt->create($receiptData);	
				
				for($i = 0; $i < count($descOnly['workDescription']); $i++){
					array_push($descAll, new Description(['receipt_id' => $receipt->id,
															'workDescription' => $descOnly['workDescription'][$i],
															'amount' => $descOnly['amount'][$i],
															]));
				}
				$desc = $receipt->description()->saveMany($descAll);
			}
			
		    return \View::make('receipt.serviceReceiptPdf')
		    			->with(['receipt' => $receipt, 
								'description' => $desc, 
								'requestType' => $request->requestType])
		    			->render();
		}
		return 'Bad Request!';

	}
	
	public function postCheck()
	{
		$data = Receipt::whereReceiptnumber(\Input::get('field'))->first();
		if(!empty($data)){
			return ['isUnique' => false ];
		}else{
			return ['isUnique' => true ];
		}
	}

}
