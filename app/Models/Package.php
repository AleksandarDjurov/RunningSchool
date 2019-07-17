<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Package extends Model {


    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'packages';

    
	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */


	protected $fillable = [
        'name',
        'pic',
        'stock',        
        'status'
    ];
	protected $guarded = ['id'];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = ['password', 'remember_token'];
    protected $hidden = [];
	

    protected $dates = ['deleted_at'];

}
