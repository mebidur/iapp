<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Http\Requests\WorkInvoice;
use App\Http\Requests\ServiceInvoice;
use App\Invoice;
use App\Receipt;
use App\Organization;
use App\Customer;
use App\Description;
use Vsmoraes\Pdf\Pdf;

class InvoiceController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		$invoices = Invoice::with(['description','customer'])
							->orderBy('created_at', 'desc')
							->where('organization_id', \Auth::user()->organization_id)
							->paginate(10);

		return \View::make('home')->with(['current' => 'home',
										  'invoices' => $invoices,]);
	}

	public function getWork()
	{	

		return \View::make('invoice.work-invoice')
					->with(['current' => 'work-invoice']);
	}

	public function postWork(WorkInvoice $request, Invoice $invoice, PDF $pdf, Customer $customer)
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

				$invoiceOld = Invoice::with('description','customer')->whereInvoicenumber(\Input::get('organization')['invoiceNumber'])->first();
				
				if(!empty($invoiceOld)){
					$invoice = $invoiceOld;
					$desc = $invoice['description'];
					$cust = $invoice['customer'];

				}else{

					$invoiceData = array_merge(['state'=> $request->get('organization')['isManual'],'organization_id' => \Auth::user()->organization_id, 'type' => 1,'customer_id' => $customer->id], array_only(\Input::get('organization'), ['invoiceNumber','serviceDate','currency']));
					$invoice = $invoice->create($invoiceData);

					$fillDesc = [];

					foreach (\Input::get('descs') as $each){
						array_push($fillDesc, new Description(array_merge($each, ['invoice_id' => $invoice->id, 'unit' => ucwords($each['unit'])])));
					}

					$desc = $invoice->description()->saveMany($fillDesc);
					$cust = $invoice->customer;
				}
				return ['status' => 'OK', 'statusCode' => 200, 'invoiceId' => $invoice->id,'invoiceTpye' => $invoice->type,'response' => true];
			} catch (Exception $e) {
				return ['status' => 'Database Error', 'statusCode' => 503, 'response' => false];
			}
		}
		return 'Bad request!';
	}

	public function getService()
	{
		return \View::make('invoice.service-invoice')
					->with(['current' => 'service-invoice']);
	}

	public function getView()
	{
		if(\Input::has('response') && \Input::has('secure'))
		{
			$invoiceData = Invoice::with('customer','organization','description')
									->whereId(\Input::get('response'))
									->whereOrganizationId(\Auth::user()->organization_id)
									->first();
			if(!empty($invoiceData) && $invoiceData->type == '1')
			{
				return \View::make('invoice.workPdf')->with(['invoice' => $invoiceData,
		    												'requestType' => 'viewAgain',])
			 											->render();		
			}
			if(!empty($invoiceData) && $invoiceData->type == '2')
			{
				return \View::make('invoice.servicePdf')->with(['invoice' => $invoiceData,
		    												'requestType' => 'viewAgain',])
			 											->render();
			}
		}
		return 'Bad request!';
	}

	public function postDownload(PDF $pdf)
	{
		if(\Input::has('invoiceId'))
		{
			$invoiceData = Invoice::with('customer','organization','description')
							  ->whereId(\Input::get('invoiceId'))
							  ->whereOrganizationId(\Auth::user()->organization_id)
							  ->first();
		}
		
		if(\Input::get('requestType') == 'downloadServicePDF' && !empty($invoiceData))
		{
			$html = \View::make('invoice.servicePdf')
						->with(['invoice' => $invoiceData,
						 		'requestType' => 'downloadServicePDF'])
						->render();

			return $pdf->load($html, 'A4', 'portrait')
					   ->filename($filename = $invoiceData->invoiceNumber.'.pdf')
					   ->download();
		}
		if(\Input::get('requestType') == 'downloadWorkPDF' && !empty($invoiceData))
		{
			$html = \View::make('invoice.workPdf')->with(['invoice' => $invoiceData,
														  'requestType' => 'downloadWorkPDF',])
														->render();
			return $pdf->load($html, 'A4', 'portrait')
					   ->filename($filename = $invoiceData->invoiceNumber.'.pdf')
					   ->download();
		}
		return 'Bad Request!';
	}

	public function postService(ServiceInvoice $request, Invoice $invoice, PDF $pdf, Customer $customer)
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

				$invoiceOld = Invoice::with('description','customer')->whereInvoicenumber(\Input::get('organization')['invoiceNumber'])->first();
				
				if(!empty($invoiceOld)){
					$invoice = $invoiceOld;
					$desc = $invoice['description'];
					$cust = $invoice['customer'];

				}else{

					$invoiceData = array_merge(['state'=> $request->get('organization')['isManual'],'organization_id' => \Auth::user()->organization_id, 'type' => 2,'customer_id' => $customer->id], array_only(\Input::get('organization'), ['invoiceNumber','serviceDate','currency']));
					$invoice = $invoice->create($invoiceData);

					$fillDesc = [];

					foreach (\Input::get('descs') as $each){
						array_push($fillDesc, new Description(array_merge(['invoice_id' => $invoice->id],$each)));
					}

					$desc = $invoice->description()->saveMany($fillDesc);
					$cust = $invoice->customer;
				}
				return ['status' => 'OK', 'statusCode' => 200, 'invoiceId' => $invoice->id,'invoiceTpye' => $invoice->type,'response' => true];
			} catch (Exception $e) {
				return ['status' => 'Database Error', 'statusCode' => 503, 'response' => false];
			}
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

	public function postStatus(ConfigController $config, Receipt $receipt)
	{
		if(\Input::has('id'))
		{
			$invoice = Invoice::find(\Input::get('id'));
			if(!empty($invoice)){
				$newReceiptNo = $config->autoReceipt();
				$desc = [];
				$receipt = Receipt::where('referenceInvoiceNumber', $invoice->invoiceNumber)->first();
				if(empty($receipt))
				{
					$receipt = new Receipt;
					$receipt->receiptNumber = $newReceiptNo;
					$receipt->referenceInvoiceNumber = $invoice->invoiceNumber;
					$receipt->organization_id = $invoice->organization_id;
					$receipt->customer_id = $invoice->customer_id;
					$receipt->serviceDate = date('Y/m/d');
					$receipt->currency = $invoice->currency;
					$receipt->type = $invoice->type;
					$receipt->state = 0;
					$receipt->ismanual = 0;
					$receipt->save();

					foreach ($invoice->description as $each){
						if($invoice->type == 1)
						{
							$desc = new Description;
							$desc->receipt_id = $receipt->id;
							$desc->workDescription = $each->workDescription;
							$desc->rate = $each->rate;
							$desc->hour = $each->hour;
							$desc->unit = $each->unit;
							
							$desc->unit = $each->unit;

						}elseif($invoice->type == 2)
						{
							$desc = new Description;
							$desc->receipt_id = $receipt->id;
							$desc->workDescription = $each->workDescription;
							$desc->amount = $each->amount;
							
							$desc->unit = $each->unit;
						}
						$desc->save();					
					}				
					$invoice->status = 1;
					$invoice->update();
					return ['statusCode' => 200, 
							'status' => 'OK', 
							'message' => 'Receipt Generated Successfully'];
				}
			}
		}
	}

	public function postEdit()
	{
		if(\Input::has('id'))
		{
			$invoice = Invoice::with('description')->whereId(\Input::get('id'))->first();
			if(!empty($invoice))
			{
				return ['results' => $invoice, 'statusCode' => 200, 'message' => 'success'];
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
				Invoice::find(\Input::get('id'))->delete();
				\Session::flash('message', 'Invoice Deleted Successfully.');
				
				return ['message' => 'success', 'statusCode' => 200];
			} catch (Exception $e) {
				return ['message' => $e->getMessage(), 'statusCode' => 408,];
			}

		}
	}

	public function getEdit($Id)
	{
		if($Id && is_numeric($Id))
		{
			$invoice = Invoice::find($Id);
			if(empty($invoice)){
				return '404 Error, Sorry the page you requested not found!';
			}
			if($invoice->type == 1){
				return \View::make('invoice.edit-work-invoice')->with(['current' => 'invoice', 'currentId' => $Id]);
			}
			elseif($invoice->type == 2)
			{
				return \View::make('invoice.edit-service-invoice')->with(['current' => 'invoice', 'currentId' => $Id]);	
			}
		}
		return 'Bad request!';	
	}

	public function postUpdatework(WorkInvoice $request)
	{
		if($request->has('currentId'))
		{

			try {
				$invoice = Invoice::find($request->get('currentId'));				

				$invoice->update(array_merge($request->get('organization'),['state' => $request->get('organization')['isManual']]));
				$invoice->customer->update($request->get('customer'));				
				$invoiceIds = [];
				foreach ($request->get('descs') as $each) 
				{				
					if(!in_array('id', array_keys($each)))
					{
						$desc = new Description;
						$desc = $desc->create(array_merge($each,['invoice_id' => $request->get('currentId'), 'unit' => ucwords($each['unit'])]));
						array_push($invoiceIds, $desc->id);
					}
					elseif(in_array('id', array_keys($each)) && $each['id'])
					{
						$desc = Description::find($each['id']);
						$desc->update(array_merge($each, ['unit' => ucwords($each['unit'])]));
						array_push($invoiceIds, $each['id']);
					}
				}

				$existingIds = array_column(Description::select('id')
											->whereInvoiceId($request->get('currentId'))
											->get()->toArray(), 'id');

				$exists = array_diff($existingIds, $invoiceIds);
				Description::whereIn('id', $exists)->delete();

				return ['status' => 'OK', 
						'statusCode' => 200, 
						'invoiceId' => $invoice->id,
						'invoiceTpye' => $invoice->type,
						'response' => true];
			} 
			catch (Exception $e) 
			{
				return ['status' => $e->getMessage(), 'statusCode' => 503, 'response' => false];
			}
		}
		return 'Bad Request!';
	}

	public function postUpdateservice(ServiceInvoice $request) 
	{
		if($request->has('currentId'))
		{
			try {
				$invoice = Invoice::find($request->get('currentId'));				

				$invoice->update(array_merge($request->get('organization'),['state' => $request->get('organization')['isManual']]));
				$invoice->customer->update($request->get('customer'));				
				$invoiceIds = [];
				foreach ($request->get('descs') as $each) 
				{				
					if(!in_array('id', array_keys($each)))
					{
						$desc = new Description;
						$desc->create(array_merge($each,['invoice_id' => $request->get('currentId')]));
						array_push($invoiceIds, $desc->id);
					}
					elseif(in_array('id', array_keys($each)) && $each['id'])
					{
						$desc = Description::find($each['id']);
						$desc->update($each);
						array_push($invoiceIds, $each['id']);
					}
				}
				$existingIds = array_column(Description::select('id')
								->whereInvoiceId($request->get('currentId'))
								->get()->toArray(), 'id');

				$exists = array_diff($existingIds, $invoiceIds);
				Description::whereIn('id',$exists)->delete();

				return ['status' => 'OK', 
						'statusCode' => 200, 
						'invoiceId' => $invoice->id,
						'invoiceTpye' => $invoice->type,
						'response' => true];
			} 
			catch (Exception $e) 
			{
				return ['status' => $e->getMessage(), 
						'statusCode' => 503, 
						'response' => false];
			}
		}
		return 'Bad Request!';
	}

	public function getCurrent($Id)
	{
		if(\Request::ajax() && $Id)
		{
			$invoice = Invoice::whereId($Id)->first();

			if($invoice->type == 2)
			{
				$i = 0;
				foreach ($invoice->description as $each) {
					$description[$i] = ['id' => $each->id, 
										'workDescription' => $each->workDescription, 
										'amount' => $each->amount];
					$i++;
				}
			}
			elseif($invoice->type == 1)
			{
				$i = 0;
				foreach ($invoice->description as $each) {
					$description[$i] = ['id' => $each->id, 
										'workDescription' => $each->workDescription, 
										'rate' => $each->rate, 
										'hour' => $each->hour,
										'unit' => strtolower($each->unit)];
					$i++;
				}				
			}

			$customer = ['name' => $invoice->customer->name, 
						'address' => $invoice->customer->address,
						'id' => $invoice->customer->id];

			$organization = ['invoiceNumber' => $invoice->invoiceNumber, 
							'serviceDate' => $invoice->serviceDate, 
							'currency' => $invoice->currency,
							'id'=> $invoice->id, 
							'isManualCode' => $invoice->invoiceNumber,
							'isManual' => 0];

			return ['organization' => $organization, 
					'customer' => $customer, 
					'description' => $description];
		}
	}
}
