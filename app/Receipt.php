<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'receipts';
	protected $fillable = ['receiptNumber','organization_id','customer_id','serviceDate','currency','type','state','ismanual','referenceInvoiceNumber'];



	public function description(){
		return $this->hasMany('App\Description');
	}
	
	public function organization()
	{
		return $this->belongsTo('App\Organization');
	}

	public function customer()
	{
		return $this->belongsTo('App\Customer');
	}

}
