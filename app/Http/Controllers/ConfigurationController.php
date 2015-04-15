<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Organization;
use App\Receipt;
use App\Invoice;

class ConfigurationController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function getProfile()
	{
		return View::make('company.org-profile')->with(['current' => 'profile']);
	}

	public function postStore(Config $request)
	{
		$company = Organization::find(\Auth::user()->organization_id);
		$company->update($request->all());
		return \Redirect::to('home')->with(['message' => 'Organization Information Updated Successfully!']);
	}
	private function autoReceipt()
	{
		$year = substr(date('Y'),1,4).'-';
		$number = Receipt::select('receiptNumber')
							->whereOrganizationId(\Auth::user()->organization_id)
							->whereState(0)
							->orderBy('created_at', 'desc')->first();
		$isUnique = (empty($number)) ? 1000 : (intval(explode('-', $number->receiptNumber)[1]) + 1);
		return $year.$isUnique;
	}
	
	private function autoInvoice()
	{
		$year = substr(date('Y'),1,4).'-';
		$number = Invoice::select('invoiceNumber')
							->whereOrganizationId(\Auth::user()->organization_id)
							->whereState(0)
							->orderBy('created_at', 'desc')->first();
		$isUnique = (empty($number)) ? 1000 : (intval(explode('-', $number->invoiceNumber)[1]) + 1);
		return $year.$isUnique;
	}


	public function organization()
	{
		$orgData =  Organization::select('name','address','phoneNo','city','state','country','email','rules','note','bankDetails')->whereId(\Auth::user()->organization_id)->first();
		return (!empty($orgData)) ? $orgData->toArray(): [] ;
	}

	public function getInitialize()
	{
		if(\Request::ajax()){
			return array_merge(['invoiceNumber' => $this->autoInvoice(),'isManualCode' => $this->autoInvoice() ,'currency' => '£', 'serviceDate' => date('Y-m-d')],$this->organization()); 		
		}
	}

	public function getInitializer()
	{
		if(\Request::ajax())
		{
			return array_merge(['receiptNumber' => $this->autoReceipt(),'isManualCode' => $this->autoReceipt() ,'currency' => '£', 'serviceDate' => date('Y-m-d')],$this->organization());
		}
	}

	public function getInitializeo()
	{
		if(\Request::ajax())
		{
			return $this->organization();
		}
	}

}
