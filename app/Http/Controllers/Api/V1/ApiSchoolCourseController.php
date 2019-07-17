<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Datautil\Util;
use App\Http\Requests\UserRequest;
use App\Mail\Restore;
use App\Models\Package;
use App\Models\School;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Mail;
use Reminder;
use Sentinel;
use stdClass;
use URL;
use Validator;
use View;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use File;
use Illuminate\Support\Facades\Input;


class ApiSchoolCourseController extends Controller
{
    public function buyPackage($manager_id, Request $request)
    {
        $pay_status = $request->get('pay_status');
        $pay_amount = $request->get('pay_amount');
        $package = \DB::table('school_package')
            ->insert([
                'package_id'   => $request->get('package_id'),
                'pay_amount'   => $pay_amount,
                'pay_status'   => $pay_status,
                'user_id'      => $manager_id
            ]);
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }

    public function confirmPackage($manager_id, Request $request)
    {

        $package = \DB::table('school_package')
            ->where('user_id', $manager_id)
            ->first();
        if ($package) $status = 1;
        else $status = 0;
        $ret = array();
        $ret['status']    = $status;
        $ret['code']     = 200;
        return Response::json($ret);
    }

    public function createCourse(Request $request)
    {

        $ret = array();
        if ($file = $request->file('course_pic')) {
            $extension = $file->extension() ?: 'png';
            $destinationPath = public_path() . '/uploads/courses/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/courses/' . $safeName;
        }
        DB::beginTransaction();
        $course_id = \DB::table('school_course')->insertGetId([
            'school_id'     => $request->input('school_id'),
            'pic'           => $request->input('pic'),
            'course_id'     => $request->input('course_id'),
            'level_id'      => $request->input('level_id'),
            'course_name'   => $request->input('course_name'),
            'course_desc'   => $request->input('course_desc'),
            'course_dates'  => $request->input('course_dates'),
            'course_days'   => $request->input('course_days'),
            'course_seats'  => $request->input('course_seats'),
            'price'         => $request->input('price'),
            'status'        => 0,
            'trainer_id'    => $request->input('trainer_id'),
            'author_id'     => $request->input('user_id'),
        ]);
        if ($course_id > 0) {
            $dates      =   $request->input('course_dates');
            $dates_array = json_decode($dates, true);

            $list = array();
            foreach ($dates_array as $date) {
                $one = array();
                $one['school_course_id'] = $course_id;
                $one['course_id']  = $request->input('course_id');
                $one['level_id']   = $request->input('level_id');
                $one['lesson_name']  = $date['lesson_name'];
                $one['lesson_desc']  = $date['lesson_desc'];
                $one['lesson_date']  = $date['lesson_date'];
                $one['start_time']   = $date['start_time'];
                $one['end_time']     = $date['end_time'];
                $one['trainer_id']   = $request->input('trainer_id');
                $one['author_id']    = $request->input('user_id');
                $list[] = $one;
            }

            $date_list = \DB::table('school_course_date')->insert($list);
            $ret['code']    = '200';
            $ret['msg']     = 'Completed successfully!';
        } else {
            DB::rollBack();
            $ret['code']    = '400';
            $ret['msg']     = 'Caan not register. Plese register again.';
        }
        DB::commit();
        return Response::json($ret);
    }

    public function updateCourse($course_id, Request $request)
    {
        $ret = array();
        if ($file = $request->file('course_pic')) {
            $extension = $file->extension() ?: 'png';
            $destinationPath = public_path() . '/uploads/courses/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/courses/' . $safeName;
        }
        $course = \DB::table('school_course')
            ->where('id', $course_id)
            ->first();

        if (File::exists(public_path() . $course->pic)) {
            File::delete(public_path() . $course->pic);
        }

        $activated =  $course->activated;
        if ($activated == 0) {
            DB::beginTransaction();
            $req = $req = $request->except('course_pic', 'user_id', 'token', 'role');
            $req['author_id'] = $request->get('user_id');

            $course_update = \DB::table('school_course')
                ->where('id', $course_id)
                ->update($req);

            if ($course_update) {
                //delete date
                $dates_delete = \DB::table('school_course_date')->where('school_course_id', $course_id)->delete();

                $dates          = $request->input('course_dates');
                $dates_array    = json_decode($dates, true);

                $list = array();
                foreach ($dates_array as $date) {

                    $one = array();
                    $one['school_course_id'] = $course_id;
                    $one['course_id']    = $course->course_id;
                    $one['level_id']     = $request->input('level_id', 0);
                    $one['lesson_name']  = $date['lesson_name'];
                    $one['lesson_desc']  = $date['lesson_desc'];
                    $one['lesson_date']  = $date['lesson_date'];
                    $one['start_time']   = $date['start_time'];
                    $one['end_time']     = $date['end_time'];
                    $one['trainer_id']   = $request->input('trainer_id', 0);
                    $one['author_id']    = $request->input('user_id');
                    $list[] = $one;
                }

                $date_list = \DB::table('school_course_date')->insert($list);

                $ret['code']    = '200';
                $ret['msg']     = 'Completed successfully!';
            } else {
                $ret['code']    = '400';
                $ret['msg']     = 'Can not update.';
                DB::rollBack();
            }
            DB::commit();
        } else {
            $ret['code']    = '400';
            $ret['msg']     = 'Can not update. activated status is true.';
        }
        return Response::json($ret);
    }

    public function activeUpdate($course_id, Request $request)
    {
        $ret = array();
        $activated_status   = $request->get("active", 0);
        $trainer_id         = $request->get("trainer_id", 0);

        if ($trainer_id != 0) {
            $course = \DB::table('school_course')
                ->where('id', $course_id)
                ->update(['status' => $activated_status, 'activated' => $activated_status, 'trainer_id' => $trainer_id]);

            $course_dates = \DB::table('school_course_date')
                ->where('school_course_id', $course_id)
                ->update(['status' => $activated_status, 'trainer_id' => $trainer_id]);
        }
        else
        {
            $course = \DB::table('school_course')
                ->where('id', $course_id)
                ->update(['status' => $activated_status, 'activated' => $activated_status]);

            $course_dates = \DB::table('school_course_date')
                ->where('school_course_id', $course_id)
                ->update(['status' => $activated_status]);
        }

        $course_reservation = false;
        if ($activated_status == 2) // if school admin want to close this course.... it must be applied to reservation table.
        {
            $course_reservation = \DB::table('school_course_reservation')
                ->where('school_course_id', $course_id)
                ->update(['status' => $activated_status]);
        }

        if ($course_dates && $course && $course_reservation) {
            $ret['code']    = '200';
            $ret['msg']     = 'Completed successfully!';
        } else {
            $ret['code']    = '404';
            $ret['msg']     = 'Invalid parameter!';
        }

        return Response::json($ret);
    }

    public function getCourseList(Request $request)
    {
        $_id = $request->get('id', 0);
        $course_id = $request->get('course_id', 0);
        $level_id  = $request->get('level_id', 0);
        $school_id = $request->get('school_id', 0);
        $status    = $request->get('status', 5);
        $trainer_id = $request->get('trainer_id', 0);

        $courses    = \DB::table('school_course as sc')
            ->leftJoin('course_main as cm', 'cm.id', '=', 'sc.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'sc.level_id')
            ->leftJoin('users as u', 'u.id', '=', 'sc.trainer_id')
            ->leftJoin('users as us', 'us.id', '=', 'sc.author_id');

        if ($_id != 0)
            $courses = $courses->where('sc.id', $_id);
        if ($course_id != 0)
            $courses = $courses->where('sc.course_id', $course_id);
        if ($level_id != 0)
            $courses = $courses->where('sc.level_id', $level_id);
        if ($school_id != 0)
            $courses = $courses->where('sc.school_id', $school_id);
        if ($status != 5)
            $courses = $courses->where('sc.status', $status);
        if ($trainer_id != 0)
            $courses = $courses->where('sc.trainer_id', $trainer_id);

        $courses = $courses->select([
            'sc.*', 'cm.course_name as quarter_name', 'cl.level_name',
            'u.first_name as trainer_first_name', 'u.last_name as trainer_last_name',
            'us.first_name as author_first_name', 'us.last_name as author_last_name'
        ])->get();
        return Response::json($courses);
    }
}
