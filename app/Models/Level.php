<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Level extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'course_level';


    /**
     * The attributes to be fillable from the model.
     *
     * A dirty hack to allow fields to be fillable by calling empty fillable array
     *
     * @var array
     */


    protected $fillable = [
        'level_name',
        'level_content',
        'level_pic',        
        'status',
        'course_id',
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
