<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'invoices';
	protected $fillable = ['invoiceNumber', 'organization_id','customer_id','serviceDate','currency','type','status'];

	public function organization()
	{
		return $this->belongsTo('App\Organization');
	}

	public function customer()
	{
		return $this->belongsTo('App\Customer');
	}

	public function description()
	{
		return $this->hasMany('App\Description');
	}
}
