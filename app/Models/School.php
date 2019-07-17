<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class School extends Model {


    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'schools';

    
	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */


	protected $fillable = [
        'name',
        'logo_path',
        'banner_path',
        'reference_asd',
        'company_code',
        'state',
        'region',
        'province',
        'city',
        'address',
        'postal_code',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
