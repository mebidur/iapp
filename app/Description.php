<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Description extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'descriptions';
	protected $fillable = ['invoice_id','receipt_id','workDescription','rate','hour','type','amount','unit'];

	public function invoice()
	{
		return $this->belongsTo('App\Invoice');
	}
}
