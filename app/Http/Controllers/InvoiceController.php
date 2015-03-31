<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Http\Requests\WorkInvoiceRequest;
use App\Invoice;
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

	public function postWork(WorkInvoiceRequest $request, Invoice $invoice)
	{
		$descArray = [];
		$invoice = $invoice->create(array_merge($request->all(),['organization_id' => \Auth::user()->organization_id]));	
		
		for($i = 0; $i < count($request->rate); $i++){
			array_push($descArray, new Description(['invoice_id' => $invoice->id, 'workDescription' => $request->workDescription[$i],'rate' => $request->rate[$i], 'hour' => $request->hour[$i] ]));
		}
		$desc = $invoice->description()->saveMany($descArray);
		
		if($request->requestType == 'printWorkInvoice'){

		    return $html = view('invoice.workPdf')->with(['invoice' => $invoice, 'description' => $desc])->render();
			return PDF::load($html)->show();

			// return \Redirect::to('/home')->with(['message'=>'New Work Invoice Created.']);
		}else{
			return 'under construction Download PDF ...';
			// return response()->download($file, "Ahmed Badawy - CV.pdf");
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
