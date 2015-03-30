<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Http\Requests\WorkInvoiceRequest;
use App\WorkInvoice;
use App\Organization;
use App\Description;

class InvoiceController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getWork()
	{
		return \View::make('invoice.work-invoice')
					->with(['current' => 'work-invoice',]);
	}

	public function postWork(WorkInvoiceRequest $request, WorkInvoice $invoice)
	{
		$invoice = new WorkInvoice;
		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->organization_id = 1;//Session::get('organization_id');
		$invoice->serviceProvider =	$request->serviceProvider;
		$invoice->serviceDate = $request->serviceDate;
		$invoice->serviceReceiver = $request->serviceReceiver;
		$invoice->companyAddress = $request->companyAddress;
		$invoice->clientAddress = $request->clientAddress;
		$invoice->termsCondition = $request->termsCondition;
		$invoice->bankDetails = $request->bankDetails;
		$invoice->keyNote = $request->keyNote;

		$invoice->save();
		for($i = 0; $i < count($request->workDescription); $i++){
			$desc = new Description;
			$desc->invoice_id = $invoice->id;
			$desc->workDescription = $request->workDescription[$i];
			if($request->requestType == 'printWorkInvoice' && $request->requestType == 'downloadPDF'){
				$desc->rate = $request->rate[$i];	
				$desc->hour = $request->hour[$i];
			}
			$desc->save();
		}
		
		if($request->requestType == 'printWorkInvoice'){
			return \Redirect::to('/home')->with(['message'=>'New Work Invoice Created.']);
		}else{
			return 'under construction Download PDF ...';
		}
		


	}

	public function getService()
	{
		return \View::make('invoice.service-invoice')
					->with(['current' => 'service-invoice',]);
	}
	public function postService()
	{
		return \Input::all();
	}

}
