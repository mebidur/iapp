<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkInvoice extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'invoices';
	protected $fillable = ['invoiceNumber', 'organization_id', 'serviceProvider','serviceDate','serviceReceiver','companyAddress','clientAddress','description_id','termsCondition','bankDetails','keyNote'];

	public function organization()
	{
		return $this->belongsTo('App\Organization');
	}

	public function description()
	{
		return $this->hasMany('App\Description','id','description_id');
	}
}
