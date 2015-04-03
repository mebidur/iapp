<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model {

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'receipts';
	protected $fillable = ['receiptNumber','organization_id','serviceProvider','serviceDate','serviceReceiver','companyAddress','clientAddress','termsCondition','bankDetails','keyNote'];


	public function description(){
		return $this->hasMany('App\Description');
	}
}
