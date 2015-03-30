<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'organizations';
	protected $fillable = ['name','address','phoneNo','city','state','country','email'];

	public function invoice()
	{
		return $this->hasMany('App\WorkInvoice');
	}

}
