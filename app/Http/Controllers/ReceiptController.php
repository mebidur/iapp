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
use App\Customer;
use Vsmoraes\Pdf\Pdf;
use View;

class ReceiptController extends Controller {
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function getIndex()
	{
		$receipts = Receipt::with(['description','customer'])
							->orderBy('created_at', 'desc')
							->whereOrganizationId(\Auth::user()->organization_id)
							->paginate(10);
							
		return \View::make('receipt.view-receipt')->with(['current' => 'receipt',
														  'receipts' => $receipts,]);
	}

	public function getWork()
	{
		return View::make('receipt.work-receipt')
					->with(['current' => 'work-receipt']);
	}

	public function postWork(WorkReceipt $request, Receipt $receipt, PDF $pdf, Customer $customer)
	{		
		if(\Input::get('requestType') == 'default'){
			
			try {
				$customerOld = Customer::whereName(\Input::get('customer')['name'])->first();
					
				if(!empty($customerOld)){
					$customer = $customerOld;
				}else{
					$customerData = array_merge(['organization_id' => \Auth::user()->organization_id],\Input::get('customer'));
					$customer = $customer->create($customerData);
				}

				$receiptOld = Receipt::with('description','customer')->whereReceiptnumber(\Input::get('organization')['receiptNumber'])->first();
				
				if(!empty($receiptOld)){
					$receipt = $receiptOld;
					$desc = $receipt['description'];
					$cust = $receipt['customer'];

				}else{

					$receiptData = array_merge(['state'=> $request->get('organization')['isManual'],'organization_id' => \Auth::user()->organization_id, 'type' => 1,'customer_id' => $customer->id], array_only(\Input::get('organization'), ['receiptNumber','serviceDate','currency']));
					$receipt = $receipt->create($receiptData);

					$fillDesc = [];

					foreach (\Input::get('allDesc') as $each){
						array_push($fillDesc, new Description(array_merge(['receipt_id' => $receipt->id],$each)));
					}

					$desc = $receipt->description()->saveMany($fillDesc);
					$cust = $receipt->customer;
				}
				return ['status' => 'OK', 'statusCode' => 200, 'receiptId' => $receipt->id,'receiptTpye' => $receipt->type,'response' => true];
			} catch (Exception $e) {
				return ['status' => 'Database Error', 'statusCode' => 503, 'response' => false];
			}
		}
		return 'Bad request!';
	}

	public function getService()
	{
		return View::make('receipt.service-receipt')
					->with(['current' => 'service-receipt']);
	}

	public function getView()
	{
		if(\Input::has('response') && \Input::has('secure'))
		{
			$receiptData = Receipt::with('customer','organization','description')
									->whereId(\Input::get('response'))
									->whereOrganizationId(\Auth::user()->organization_id)
									->first();

			if(!empty($receiptData) && $receiptData->type == '1')
			{
				return \View::make('receipt.workReceiptPdf')->with(['receipt' => $receiptData,
		    												'requestType' => 'viewAgain',])
			 											->render();		
			}
			if(!empty($receiptData) && $receiptData->type == '2')
			{
				return \View::make('receipt.serviceReceiptPdf')->with(['receipt' => $receiptData,
		    												'requestType' => 'viewAgain',])
			 											->render();
			}
		}
		return 'Bad request!';
	}
	
	public function postDownload(PDF $pdf){
		if(\Input::has('receiptId'))
		{
			$receiptData = Receipt::with('customer','organization','description')
								  ->whereId(\Input::get('receiptId'))
								  ->whereOrganizationId(\Auth::user()->organization_id)
								  ->first();
		}
		if(\Input::get('requestType') == 'downloadServicePDF' && !empty($receiptData))
		{
			$html = \View::make('receipt.serviceReceiptPdf')
						->with(['receipt' => $receiptData,
						 		'requestType' => 'downloadServicePDF'])
						->render();

			return $pdf->load($html, 'A4', 'portrait')->download();
		}
		if(\Input::get('requestType') == 'downloadWorkPDF' && !empty($receiptData))
		{
			$html = \View::make('receipt.workReceiptPdf')->with(['receipt' => $receiptData,
																 'requestType' => 'downloadWorkPDF',])
														->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}
		return 'Bad Request!';
	}

	public function postService(ServiceReceipt $request, Receipt $receipt, PDF $pdf, Customer $customer)
	{	
		if(\Input::get('requestType') == 'default'){
			
			try {
				$customerOld = Customer::whereName(\Input::get('customer')['name'])->first();
					
				if(!empty($customerOld)){
					$customer = $customerOld;
				}else{
					$customerData = array_merge(['organization_id' => \Auth::user()->organization_id],\Input::get('customer'));
					$customer = $customer->create($customerData);
				}

				$receiptOld = Receipt::with('description','customer')->whereReceiptnumber(\Input::get('organization')['receiptNumber'])->first();
				
				if(!empty($receiptOld)){
					$receipt = $receiptOld;
					$desc = $receipt['description'];
					$cust = $receipt['customer'];

				}else{

					$receiptData = array_merge(['state'=> $request->get('organization')['isManual'],'organization_id' => \Auth::user()->organization_id, 'type' => 2,'customer_id' => $customer->id], array_only(\Input::get('organization'), ['receiptNumber','serviceDate','currency']));
					$receipt = $receipt->create($receiptData);

					$fillDesc = [];

					foreach (\Input::get('allDesc') as $each){
						array_push($fillDesc, new Description(array_merge(['receipt_id' => $receipt->id],$each)));
					}

					$desc = $receipt->description()->saveMany($fillDesc);
					$cust = $receipt->customer;
				}
				return ['status' => 'OK', 'statusCode' => 200, 'receiptId' => $receipt->id,'receiptTpye' => $receipt->type,'response' => true];
			} catch (Exception $e) {
				return ['status' => 'Database Error', 'statusCode' => 503, 'response' => false];
			}
		}
		return 'Bad request!';

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

	public function postEdit()
	{
		if(\Input::has('id'))
		{
			$receipt = Receipt::with('description')->whereId(\Input::get('id'))->first();
			if(!empty($receipt))
			{
				return ['results' => $receipt, 'statusCode' => 200, 'message' => 'success'];
			}else
			{
				return ['results' => [], 'statusCode' => 408,'message' => 'No data received invalid data provided!'];
			}
		}
	}

	public function postRemove()
	{
		if(\Input::has('id'))
		{
			try {
				Receipt::find(\Input::get('id'))->delete();
				
				\Session::flash('message', 'Receipt Deleted Successfully.');
				return ['message' => 'success', 'statusCode' => 200];
			} catch (Exception $e) {
				return ['message' => $e->getMessage(), 'statusCode' => 408,];
			}

		}
	}
}
