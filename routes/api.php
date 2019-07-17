<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::pattern('slug', '[a-z0-9- _]+');  
//web rule
#get country, state, region, province, city
Route::any('states', 'UtilController@Stateslist')->name('state');
Route::any('region', 'UtilController@Region_list')->name('region');
Route::any('province', 'UtilController@Province_list')->name('province');
Route::any('city', 'UtilController@City_list')->name('city');

Route::group(['namespace'=>'Api\V1'], function () {        
    # All basic routes defined here    
   
    Route::post('signin', 'ApiAuthController@postSignin')->name('postSignin');
    Route::post('signup', 'ApiAuthController@postSignup')->name('signup');
    Route::post('forgot-password', 'ApiAuthController@postForgotPassword')->name('signup');


    # Forgot Password Confirmation
    Route::get('forgot-password/{userId}/{passwordResetCode}', 'ApiAuthController@getForgotPasswordConfirm')->name('forgot-password-confirm');
    Route::post('forgot-password/{userId}/{passwordResetCode}', 'ApiAuthController@getForgotPasswordConfirm');

    # Logout
    Route::post('logout', 'ApiAuthController@getLogout')->name('logout');
    
    #contact us
    Route::any('contact/add', 'ApiContactController@addContact');
    Route::post('contact/confirm', 'ApiContactController@contactConfirm');
    Route::get('contact/test', 'ApiContactController@test');



    #school create
    Route::post('school/create', 'ApiSchoolController@Create');
    Route::post('school/technical/create', 'ApiSchoolController@createTechnical');
    Route::post('school/personal/create', 'ApiPersonalTrainerController@createPersonalTrainer');
    Route::post('school/athlete/create', 'ApiAthleteController@createAthlete');
    Route::post('school/athlete/verify', 'ApiAthleteController@confirmVerify');


    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'ApiAuthController@getActivate')->name('activate');
});

//admin permission
Route::group(['namespace' => 'Api\V1', 'middleware' => 'angularapi'], function () {
    //contact management
    Route::post('contact/list', 'ApiContactController@getContactList');
    Route::post('contact/user/{user}', 'ApiContactController@getUser');
    Route::any('contact/clear/{contact}', 'ApiContactController@clearContactList');
    Route::post('contact/approve', 'ApiContactController@sendApproved');

    //school management
    Route::post('school', 'ApiSchoolController@index');
    Route::post('school/show/{school_id}', 'ApiSchoolController@getSchool');
    Route::post('school/update/{school_id}', 'ApiSchoolController@updateSchool');
    Route::post('school/technical/update/{technical_id}', 'ApiSchoolController@updateTechnical');
    Route::any('school/personal', 'ApiPersonalTrainerController@index');
    Route::post('school/personal/update/{technical_id}', 'ApiPersonalTrainerController@updatePersonalTrainer');
    Route::post('school/personal/show/{technical_id}', 'ApiPersonalTrainerController@showPersonalTrainer');
    Route::post('school/getname', 'ApiSchoolController@getNames');
    Route::post('school/getschoolname', 'ApiSchoolController@getSchoolName');

    Route::any('school/athlete', 'ApiAthleteController@index');
    Route::post('school/athlete/update/{athlete_id}', 'ApiAthleteController@updateAthlete');
    Route::post('school/athlete/show/{athlete_id}', 'ApiAthleteController@showAthlete');
    Route::post('athlete/school/course/reservation', 'ApiAthleteController@reservationSchoolCourse');
    


    //course management
    Route::post('course/courses', 'ApiCourseController@getCourses');
    Route::post('course/course', 'ApiCourseController@course');
    Route::post('course/addcourse', 'ApiCourseController@addCourse');
    Route::post('course/updatecoursestatus', 'ApiCourseController@updateCourseStatus');
    Route::post('course/updatecourse', 'ApiCourseController@updateCourse');
    Route::post('course/deletecourse', 'ApiCourseController@deleteCourse');
    
    Route::post('course/quarters', 'ApiCourseController@quarters');
    Route::post('course/addlevel', 'ApiCourseController@addLevel');
    Route::post('course/updatelevelstatus', 'ApiCourseController@updateLevelStatus');
    Route::post('course/updatelevel', 'ApiCourseController@updateLevel');
    Route::post('course/deletelevel', 'ApiCourseController@deleteLevel');
    Route::post('course/levels', 'ApiCourseController@getLevels');    
    Route::post('course/level', 'ApiCourseController@level');
    
    Route::post('course/lessons', 'ApiCourseController@getLessons');
    Route::post('course/category', 'ApiCourseController@getCategory');
    Route::post('course/lesson', 'ApiCourseController@level');
    Route::post('course/addlesson', 'ApiCourseController@addLesson');

    //school course management
    Route::post('school/course/buy_package/{manager_id}', 'ApiSchoolCourseController@buyPackage');
    Route::post('school/course/confirm_package/{manager_id}', 'ApiSchoolCourseController@confirmPackage');
    Route::post('school/course/create', 'ApiSchoolCourseController@createCourse');
    Route::post('school/course/update/{course_id}', 'ApiSchoolCourseController@updateCourse');
    Route::post('school/course/activeupdate/{course_id}', 'ApiSchoolCourseController@activeUpdate');
    Route::post('school/course/list', 'ApiSchoolCourseController@getCourseList');
    Route::post('school/course/list/athlete/{school_id}', 'ApiPersonalCourseController@getSchoolAthleteList');
        

    //personal course management        
    Route::post('personal/course/update/{lesson_id}', 'ApiPersonalCourseController@updateLesson');
    Route::post('personal/course/list', 'ApiPersonalCourseController@getLessonList');
    Route::post('personal/course/list/{school_course_id}', 'ApiPersonalCourseController@getLessonDetailList');
    Route::post('personal/course/list/athlete/{school_course_id}', 'ApiPersonalCourseController@getAthleteList');
    Route::post('personal/course/athlete/reservation/{reservation_id}', 'ApiPersonalCourseController@updateReservation');
    Route::post('personal/course/date', 'ApiPersonalCourseController@viewSchoolCoursrDate');
    Route::post('/personal/course/athlete/add_attendance', 'ApiPersonalCourseController@addAttendCourseDate');
    Route::post('/personal/course/athlete/attend/list', 'ApiPersonalCourseController@getAttendList');
    Route::post('/personal/course/athlete/attend/summarylist', 'ApiPersonalCourseController@getSummaryAttendList');
    Route::post('/personal/course/athlete/feedback/{attend_id}', 'ApiPersonalCourseController@giveFeedbackToAthlete');
    Route::post('/personal/course/athlete/list/{trainer_id}', 'ApiPersonalCourseController@getPersonalAthleteList');
    
    //membership type
    Route::any('membership', 'ApiMemberShipController@index');
    Route::post('membership/create', 'ApiMemberShipController@create');
    Route::post('membership/update', 'ApiMemberShipController@update');

    //package
    Route::any('package', 'ApiPackageController@index');
    Route::post('package/create', 'ApiPackageController@create');
    Route::post('package/update', 'ApiPackageController@update');

    Route::post('group', 'ApiGroupController@group');
});

//school permission
//athlete permission
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
