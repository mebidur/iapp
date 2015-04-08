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
		if(\Input::get('requestType') == 'default'){
			$data = Invoice::with('description')->whereInvoicenumber($request->invoiceNumber)->first();
			
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
					array_push($allDesc, 
						new Description(['invoice_id' => $invoice->id,
										'workDescription' => $descOnly['workDescription'][$i],
										'rate' => $descOnly['rate'][$i],
										'hour' => $descOnly['hour'][$i],
										]));
				}
				$desc = $invoice->description()->saveMany($allDesc);
			}
			return \View::make('invoice.workPdf')->with(['invoice' => $invoice,
			    			 								 'description' => $desc,
			    			 								 'requestType' => $request->requestType])
			    									 ->render();
		}
		return 'Bad request!';
	}

	public function getService()
	{
		return \View::make('invoice.service-invoice')
					->with(['current' => 'service-invoice',]);
	}

	public function getView()
	{
		if(\Input::has('secret') && \Input::has('secure'))
		{
			$invoiceData = Invoice::whereId(\Input::get('secret'))->with('description')->first();
			if(!empty($invoiceData) && $invoiceData->type == '1')
			{
				return \View::make('invoice.workPdf')->with(['invoice' => $invoiceData,
			 												'description' => $invoiceData['description'],
		    												'requestType' => 'viewAgain',])
			 											->render();		
			}
			if(!empty($invoiceData) && $invoiceData->type == '2')
			{
				return \View::make('invoice.servicePdf')->with(['invoice' => $invoiceData,
			 												'description' => $invoiceData['description'],
		    												'requestType' => 'viewAgain',])
			 											->render();
			}
		}
		return 'Bad request!';
	}

	public function postDownload(PDF $pdf)
	{
		$invoiceData = Invoice::with('description')
							  ->whereId(\Input::get('invoiceId'))
							  ->whereOrganizationId(\Auth::user()->organization_id)
							  ->first();
		if(\Input::get('requestType') == 'downloadServicePDF' && !empty($invoiceData))
		{
			$html = \View::make('invoice.servicePdf')
						->with(['invoice' => $invoiceData,
						 		'description' => $invoiceData['description'],
						 		'requestType' => 'downloadServicePDF'])
						->render();

			return $pdf->load($html, 'A4', 'portrait')->download();
		}
		if(\Input::get('requestType') == 'downloadWorkPDF' && !empty($invoiceData))
		{
			$html = \View::make('invoice.workPdf')->with(['invoice' => $invoiceData,
																 'description' => $invoiceData['description'],
																 'requestType' => 'downloadWorkPDF',])
														->render();
			return $pdf->load($html, 'A4', 'portrait')->download();
		}
		return 'Bad Request!';
	}

	public function postService(ServiceInvoice $request, Invoice $invoice, PDF $pdf)
	{
		if($request->requestType == 'default')
		{
			$data = Invoice::with('description')
							->where('invoiceNumber',$request->invoiceNumber)
							->first();
			
			if(!empty($data)){
				$invoice = $data;
				$desc = $invoice['description'];

			}else{
				$allDesc = [];
				$invoiceData = array_merge($request->all(),['organization_id' => \Auth::user()->organization_id,'type' => 2]);
				$fillInvoice = array_except($invoiceData, ['workDescription','amount']);
				$descOnly = array_only($invoiceData, ['workDescription','amount']);

				$invoice = $invoice->create($fillInvoice);	
				
				for($i = 0; $i < count($descOnly['workDescription']); $i++){
					array_push($allDesc, 
						new Description(['invoice_id' => $invoice->id,
										'workDescription' => $descOnly['workDescription'][$i],
										'amount' => $descOnly['amount'][$i],
										]));
				}
				$desc = $invoice->description()->saveMany($allDesc);
			}
			 return \View::make('invoice.servicePdf')->with(['invoice' => $invoice,
			 												'description' => $desc,
		    												'requestType' => $request->requestType,])
			 										->render();
		}
		return 'Bad request!';		
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
