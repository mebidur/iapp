<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Str;

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

	public function postWork()
	{
		return \Input::all();
	}

	public function getService()
	{
		return View::make('receipt.service-receipt')
					->with(['current' => 'service-receipt',
							'receiptNumber'=> strtoupper(Str::random(14)),]);
	}

	public function postService()
	{
		return \Input::all();
	}

}
