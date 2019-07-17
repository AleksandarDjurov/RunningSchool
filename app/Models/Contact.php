<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Contact extends Model {


    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'contact';

    
	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */


	protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'comment',        
        'status',
		'role',
		'pic',
		'address',
		'birthday',
        'accept_key',
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
