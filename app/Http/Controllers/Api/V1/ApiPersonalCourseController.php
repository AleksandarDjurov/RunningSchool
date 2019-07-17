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
use Psy\Util\Json;
use Illuminate\Http\Resources\Json\Resource;

class ApiPersonalCourseController extends Controller
{
    public function updateLesson($lesson_id, Request $request)
    {
        $ret = array();
        $request['status'] = 1;
        $course = \DB::table('school_course as sc')
            ->leftJoin('school_course_date as scd', 'scd.school_course_id', '=', 'sc.id')
            ->where('scd.id', $lesson_id)
            ->select(['sc.activated'])
            ->first();
        $activated =  $course->activated;
        if ($activated == 0) {
            $req = $req = $request->except('user_id', 'token', 'role');
            $lesson = \DB::table('school_course_date')
                ->where('id', $lesson_id)
                ->update($req);
            $ret['code']    = '200';
            $ret['msg']     = 'Completed successfully!';
        } else {
            $ret['code']    = '400';
            $ret['msg']     = 'Can not update. activated status is true for course.';
        }
        return Response::json($ret);
    }


    public function getLessonList(Request $request)
    {
        $user_id = $request->get('user_id', 0);

        $courses    = \DB::table('school_course as sc')
            ->leftJoin('course_main as cm', 'cm.id', '=', 'sc.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'sc.level_id')
            ->leftJoin('users as u', 'u.id', '=', 'sc.trainer_id')
            ->where('sc.trainer_id', $user_id)
            ->where('sc.status', 1)
            ->select([
                'sc.*', 'cm.course_name as quarter_name', 'cl.level_name',
                'u.first_name as trainer_first_name', 'u.last_name as trainer_last_name'
            ])->get();
        return Response::json($courses);
    }

    public function getLessonDetailList($school_course_id, Request $request)
    {
        $user_id = $request->get('user_id', 0);

        $courses    = \DB::table('school_course_date as scd')
            ->leftJoin('course_main as cm', 'cm.id', '=', 'scd.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'scd.level_id')
            ->leftJoin('users as u', 'u.id', '=', 'scd.trainer_id')
            ->where('scd.school_course_id', $school_course_id)
            ->where('scd.trainer_id', $user_id)
            // ->where('scd.status', 1)
            ->select([
                'scd.*', 'cm.course_name as quarter_name', 'cl.level_name',
                'u.first_name as trainer_first_name', 'u.last_name as trainer_last_name'
            ])->get();
        return Response::json($courses);
    }

    public function getAthleteList($school_course_id, Request $request)
    {
        $status = $request->get('status');
        $athletes = \DB::table('school_course_reservation as scr')
            ->leftJoin('course_main as cm', 'cm.id', '=', 'scr.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'scr.level_id')
            ->leftJoin('school_course as sc', 'sc.id', '=', 'scr.school_course_id')
            ->leftJoin('users as u', 'scr.user_id', '=', 'u.id')
            ->where('scr.school_course_id', $school_course_id);
        if ($status != 5)
            $athletes = $athletes->where('scr.status', $status);
        $athletes = $athletes
            ->select([
                'scr.*', 'cm.course_name', 'cl.level_name', 'sc.course_name as school_course_name', 'sc.course_desc as school_course_desc',
                'u.first_name as athlete_first_name', 'u.last_name as athlete_last_name', 'u.pic as avatar', 'u.email as mail', 'u.mobile_phone as phone',
                'u.gender as sex'
            ])
            ->get();
        return Response::json($athletes);
    }

    public function getSchoolAthleteList($school_id, Request $request)
    {
        $status = $request->get('status');
        $athletes = \DB::table('school_course_reservation as scr')
            ->leftJoin('course_main as cm', 'cm.id', '=', 'scr.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'scr.level_id')
            ->leftJoin('school_course as sc', 'sc.id', '=', 'scr.school_course_id')
            ->leftJoin('users as u', 'scr.user_id', '=', 'u.id')
            ->where('sc.school_id', $school_id);

        if ($status != 5)
            $athletes = $athletes->where('scr.status', $status);

        $athletes = $athletes
            ->select([
                'scr.*', 'cm.course_name', 'cl.level_name', 'sc.course_name as school_course_name', 'sc.course_desc as school_course_desc',
                'u.first_name as athlete_first_name', 'u.last_name as athlete_last_name', 'u.pic as avatar', 'u.email as mail', 'u.mobile_phone as phone',
                'u.gender as sex'
            ])
            ->get();
        return Response::json($athletes);
    }

    public function getPersonalAthleteList($trainer_id, Request $request)
    {
        $status = $request->get('status');
        $athletes = \DB::table('school_course_reservation as scr')
            ->leftJoin('course_main as cm', 'cm.id', '=', 'scr.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'scr.level_id')
            ->leftJoin('school_course as sc', 'sc.id', '=', 'scr.school_course_id')
            ->leftJoin('users as u', 'scr.user_id', '=', 'u.id')
            ->where('sc.trainer_id', $trainer_id);

        if ($status != 5)
            $athletes = $athletes->where('scr.status', $status);
            
        $athletes = $athletes
            ->select([
                'scr.*', 'cm.course_name', 'cl.level_name', 'sc.course_name as school_course_name', 'sc.course_desc as school_course_desc',
                'u.first_name as athlete_first_name', 'u.last_name as athlete_last_name', 'u.pic as avatar', 'u.email as mail', 'u.mobile_phone as phone',
                'u.gender as sex'
            ])
            ->get();
        return Response::json($athletes);
    }

    public function updateReservation($reservation_id, Request $request)
    {
        $status = $request->get('status');
        $athletes = \DB::table('school_course_reservation')
            ->where('id', $reservation_id)
            ->update(["status" => $status]);

        $ret = array();
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }

    public function viewSchoolCoursrDate(Request $request)
    {
        $school_course_date_id = $request->get('school_course_date_id');

        $course_detail    = \DB::table('school_course_date as scd')
            ->where('scd.id', $school_course_date_id)
            ->leftJoin('course_main as cm', 'cm.id', '=', 'scd.course_id')
            ->leftJoin('course_level as cl', 'cl.id', '=', 'scd.level_id')
            ->leftJoin('users as u', 'u.id', '=', 'scd.trainer_id')
            ->select([
                'scd.*', 'cm.course_name as quarter_name', 'cl.level_name',
                'u.first_name as trainer_first_name', 'u.last_name as trainer_last_name'
            ])->first();
        return Response::json($course_detail);
    }

    public function addAttendCourseDate(Request $request)
    {
        $school_course_date_id = $request->get('school_course_date_id');
        $attendance_status = $request->get('attendance_status');
        
        $athlete_id     = $request->get('athlete_id');
        $course_date = \DB::table('school_course_date')
            ->where('id', $school_course_date_id)
            ->first();
        $course_id  = $course_date->course_id;
        $level_id   = $course_date->level_id;
        $school_course_id = $course_date->school_course_id;
        $trainer_id = $course_date->trainer_id;

        //get reservation id
        $reservation = \DB::table('school_course_reservation')
            ->where('school_course_id', $school_course_id)
            ->where('course_id', $course_id)
            ->where('level_id', $level_id)
            ->where('user_id', $athlete_id)
            ->first();

        if ($reservation)
            $reservation_id     = $reservation->id;
        else $reservation_id    = 0;
        $arr = array();
        $arr['course_id']   = $course_id;
        $arr['level_id']    = $level_id;
        $arr['school_course_id']    = $school_course_id;
        // $arr['school_course_date_id']    = $school_course_date_id;
        $arr['reservation_id']      = $reservation_id;
        $arr['athlete_id']          = $athlete_id;
        $arr['trainer_id']          = $trainer_id;
        //get original attend for past date course;
        $date_attend = array();
        $past_attend = \DB::table('school_course_date_attend')
            ->where('school_course_id', $school_course_id)
            ->where('course_id', $course_id)
            ->where('level_id', $level_id)
            ->where('reservation_id', $reservation_id)
            ->where('athlete_id', $athlete_id)
            ->where('trainer_id', $trainer_id)
            ->first();
        $date_str = array();
        $date_str[$school_course_date_id] = $attendance_status;
        if ($past_attend) {
            $date_attend = json_decode($past_attend->attend);
            //update if there is a record like athlete_id
            $exist_course_date = false;
            foreach ($date_attend as $key => $value) {
                $sub_key = key((array) $value);
                if ($sub_key == $school_course_date_id) {
                    $one = array();
                    $one[$sub_key] = $attendance_status;
                    $date_attend[$key] = $one;
                    $exist_course_date = true;
                    break;
                }
            }
            if ($exist_course_date == false) array_push($date_attend, $date_str);
            $arr['attend'] = json_encode($date_attend);
            $insert_attend = \DB::table('school_course_date_attend')
                ->where('school_course_id', $school_course_id)
                ->where('course_id', $course_id)
                ->where('level_id', $level_id)
                ->where('reservation_id', $reservation_id)
                ->where('athlete_id', $athlete_id)
                ->where('trainer_id', $trainer_id)
                ->update($arr);
        } else {
            array_push($date_attend, $date_str);
            $arr['attend'] = json_encode($date_attend);
            $insert_attend = \DB::table('school_course_date_attend')->insert([$arr]);
        }
        $ret = array();
        if ($insert_attend) {
            $ret['code']    = "200";
            $ret['msg']     = "Completed Successfully.";
        }
        return Response::json($ret);
    }

    public function giveFeedbackToAthlete($attend_id, Request $request)
    {
        $review = $request->get('review', '');
        $score = $request->get('score', '0');

        $course_dates = \DB::table('school_course_date_attend')
                        ->where('id', $attend_id)
                        ->update(['review'=>$review, 'score' => $score]);
        if ($course_dates) {
            $ret['code']    = "200";
            $ret['msg']     = "Completed Successfully.";
        }
        else{
            $ret['code']    = "401";
            $ret['msg']     = "Invalid parameters.";
        }

        return Response::json($ret);
    }

    public function getAttendList(Request $request)
    {
        $course_id = $request->get('course_id', 0);
        $athlete_id = $request->get('athlete_id', 0);

        $course_date = \DB::table('school_course_date as scr')
            ->where('scr.school_course_id', $course_id)
            ->select(['scr.*'])
            ->get();

        if( $athlete_id != 0)
        {
            $attendance = \DB::table('school_course_date_attend as scr')
            ->leftJoin('users as ur', 'ur.id', '=', 'scr.athlete_id')
            ->where('scr.school_course_id', $course_id)
            ->where('scr.athlete_id', $athlete_id)
            ->select(['scr.*', 'ur.first_name as fname', 'ur.last_name as lname', 'ur.pic as pic'])
            ->get();
        }
        else{
            $attendance = \DB::table('school_course_date_attend as scr')
            ->leftJoin('users as ur', 'ur.id', '=', 'scr.athlete_id')
            ->where('scr.school_course_id', $course_id)
            ->select(['scr.*', 'ur.first_name as fname', 'ur.last_name as lname', 'ur.pic as pic'])
            ->get();   
        }

        $ret = array();
        foreach ($attendance as $rec_attend) {
            $arr = array();
            $arr['attend_id'] = $rec_attend->id;
            $arr['athlete_id'] = $rec_attend->athlete_id;
            $date_attend = json_decode($rec_attend->attend);
            $arr_dates = array();
            $arr_ids = array();
            $arr_attend = array();
            
            foreach ($course_date as $rec_course) {
                $date_id = $rec_course->id;
                $found = false;
                $szAttend = '';
                foreach($date_attend as $row) {
                    foreach($row as $key => $val) {
                        if( $date_id == $key )
                        {
                            $szAttend = $val;
                            $found = true;
                            break;
                        }
                    }
                    if( $found )
                        break;
                }

                if( !$found ) $szAttend = '0';

                array_push($arr_attend, $szAttend);
                array_push($arr_dates, $rec_course->lesson_date);
                array_push($arr_ids, $rec_course->id);
            }

            $arr['first_name'] = $rec_attend->fname;
            $arr['last_name'] = $rec_attend->lname;
            $arr['pic'] = $rec_attend->pic;
            $arr['dates'] = $arr_dates;
            $arr['attend'] = $arr_attend;
            $arr['ids'] = $arr_ids;
            array_push($ret, $arr);
        }

        return Response::json($ret);
    }
    
    public function getSummaryAttendList(Request $request)
    {
        $course_id = $request->get('course_id', 0);
        $athlete_id = $request->get('athlete_id', 0);

        $course_date = \DB::table('school_course_date as scr')
            ->where('scr.school_course_id', $course_id)
            ->select(['scr.*'])
            ->get();

        if( $athlete_id != 0)
        {
            $attendance = \DB::table('school_course_date_attend as scr')
            ->leftJoin('users as ur', 'ur.id', '=', 'scr.athlete_id')
            ->where('scr.school_course_id', $course_id)
            ->where('scr.athlete_id', $athlete_id)
            ->select(['scr.*', 'ur.first_name as fname', 'ur.last_name as lname', 'ur.pic as pic'])
            ->get();
        }
        else{
            $attendance = \DB::table('school_course_date_attend as scr')
            ->leftJoin('users as ur', 'ur.id', '=', 'scr.athlete_id')
            ->where('scr.school_course_id', $course_id)
            ->select(['scr.*', 'ur.first_name as fname', 'ur.last_name as lname', 'ur.pic as pic'])
            ->get();   
        }

        $ret = array();
        foreach ($attendance as $rec_attend) {
            $arr = array();
            $arr['attend_id'] = $rec_attend->id;
            $arr['athlete_id'] = $rec_attend->athlete_id;
            $date_attend = json_decode($rec_attend->attend);
            
            $total_day = 0;
            $present_day = 0;
            $absent_day = 0;
            $late_day = 0;

            foreach ($course_date as $rec_course) {
                $date_id = $rec_course->id;
                $found = false;
                $szAttend = '';
                foreach($date_attend as $row) {
                    foreach($row as $key => $val) {
                        if( $date_id == $key )
                        {
                            $szAttend = $val;
                            $found = true;
                            break;
                        }
                    }
                    if( $found )
                        break;
                }

                if( !$found ) $szAttend = '0';

                if( $szAttend == '0')
                    $absent_day++;
                else if( $szAttend == '1')
                    $present_day++;
                else if( $szAttend == '2')
                    $late_day++;

                $total_day++;
            }

            $arr['first_name'] = $rec_attend->fname;
            $arr['last_name'] = $rec_attend->lname;
            $arr['pic'] = $rec_attend->pic;
            $arr['total'] = $total_day;
            $arr['present'] = $present_day;
            $arr['absent'] = $absent_day;
            $arr['late'] = $late_day;
            $arr['review'] = $rec_attend->review;
            $arr['score'] = $rec_attend->score;

            array_push($ret, $arr);
        }

        return Response::json($ret);
    }
}
