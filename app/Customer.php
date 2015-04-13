<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'customers';
	protected $fillable = ['organization_id','name','address','phone','email','city','state','country'];


	public function organization()
	{
		return $this->belongsTo('App\Organization');
	}

	public function invoice()
	{
		return $this->hasMany('App\Invoice');
	}

	public function receipt()
	{
		return $this->hasMany('App\Receipt');
	}

}
