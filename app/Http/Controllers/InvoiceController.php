<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Http\Requests\WorkInvoice;
use App\Http\Requests\ServiceInvoice;
use App\Invoice;
use App\Organization;
use App\Description;
use Vsmoraes\Pdf\Pdf;

class InvoiceController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		$invoices = Invoice::with(['description',])
							->where('organization_id', \Auth::user()->organization_id)
							->paginate(10);

		return \View::make('home')->with(['current' => 'home',
										  'invoices' => $invoices,]);
	}

	public function getWork()
	{
		return \View::make('invoice.work-invoice')
					->with(['current' => 'work-invoice',]);
	}

	public function postWork(WorkInvoice $request, Invoice $invoice, PDF $pdf)
	{
		$data = Invoice::with('description')->where('invoiceNumber',$request->invoiceNumber)->first();
		
		if(!empty($data)){
			$invoice = $data;
			$desc = $invoice['description'];

		}else{

			$invoiceData = array_merge($request->all(),['organization_id' => \Auth::user()->organization_id, 'type' => 1]);
			$fillInvoice = array_except($invoiceData, ['workDescription','hour','rate']);
			$descOnly = array_only($invoiceData, ['workDescription','hour','rate']);

			$invoice = $invoice->create($fillInvoice);
			$allDesc = [];
			
			for($i = 0; $i < count($descOnly['rate']); $i++){
				array_push($allDesc, new Description(['invoice_id' => $invoice->id,
														'workDescription' => $descOnly['workDescription'][$i],
														'rate' => $descOnly['rate'][$i],
														'hour' => $descOnly['hour'][$i],
														]));
			}
			$desc = $invoice->description()->saveMany($allDesc);
		}
		
		if($request->requestType == 'workInvoice'){
		    return \View::make('invoice.workPdf')->with(['invoice' => $invoice,
		    			 								 'description' => $desc,
		    			 								 'requestType' => $request->requestType])
		    									 ->render();

		}else{
			$html = \View::make('invoice.workPdf')->with(['invoice' => $invoice, 
														  'description' => $desc,
														  'requestType' => $request->requestType])
												  ->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}
	}

	public function getService()
	{
		return \View::make('invoice.service-invoice')
					->with(['current' => 'service-invoice',]);
	}
	public function postService(ServiceInvoice $request, Invoice $invoice, PDF $pdf)
	{
		$data = Invoice::with('description')
						->where('invoiceNumber',$request->invoiceNumber)
						->first();
		
		if(!empty($data)){
			$invoice = $data;
			$desc = $invoice['description'];

		}else{
			$descArray = [];
			$invoice = $invoice->create(array_merge($request->all(),['organization_id' => \Auth::user()->organization_id,'type' => '2']));	
			
			for($i = 0; $i < count($request->workDescription); $i++){
				array_push($descArray, new Description(['invoice_id' => $invoice->id,
														'workDescription' => $request->workDescription[$i],
														'amount' => $request->amount[$i],
														]));
			}
			$desc = $invoice->description()->saveMany($descArray);
		}
			
		if($request->requestType == 'serviceInvoice'){
		    return \View::make('invoice.servicePdf')->with(['invoice' => $invoice, 'description' => $desc,
		    												'requestType' => $request->requestType])->render();

		}else{
			$html = \View::make('invoice.servicePdf')->with(['invoice' => $invoice, 'description' => $desc,
															'requestType' => $request->requestType])->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}		
	}

	public function postCheck()
	{		
		$data = Invoice::whereInvoicenumber(\Input::get('field'))->first();
		if(!empty($data)){
			return ['isUnique' => false ];
		}else{
			return ['isUnique' => true ];
		}
		
	}

}
