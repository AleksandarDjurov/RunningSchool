<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Course extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'course_main';


    /**
     * The attributes to be fillable from the model.
     *
     * A dirty hack to allow fields to be fillable by calling empty fillable array
     *
     * @var array
     */


    protected $fillable = [
        'course_name',
        'course_content',
        'course_pic',
        'from_date',
        'to_date',
        'status',
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
