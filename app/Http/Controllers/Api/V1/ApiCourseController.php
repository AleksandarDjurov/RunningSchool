<?php namespace App\Http\Controllers\Api\V1;


use App\Http\Datautil\Util;
use App\Http\Requests\UserRequest;
use App\Mail\Restore;
use App\Models\Course;
use App\Models\Level;
use App\Models\School;
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


class ApiCourseController extends Controller
{
    public function getCourses(Request $request)
    {
        $courses = \DB::table('course_main as cm')
                            ->leftJoin('course_level as cl', 'cl.course_id', '=', 'cm.id')
                            ->where('cm.status', '!=' , 2)
                            ->select(['cm.*','cl.level_name as ln'])
                            ->orderBy('cl.course_id', 'asc')
                            ->get();
        
        $ret = array(); $rec = array();
        $course_id = 0; $levels = '';
        foreach ($courses as $rec_course) {
            if( $course_id != $rec_course->id && $course_id != 0 )
            {
                $rec->ln = $levels;
                array_push($ret, $rec);
                $levels = '';
            }
            
            if( $levels == '')
                $levels = $rec_course->ln;
            else
                $levels = ($levels . ',' . $rec_course->ln);

            $course_id = $rec_course->id;
            $rec = $rec_course;
        }
        
        if(count($courses) > 0 )
        {
            $rec->ln = $levels;
            array_push($ret, $rec);
        }
        return Response::json($ret);        
    }

    public function course(Request $request) {
        $course_id = $request->get('course_id');
        $course = Course::find($course_id);
        return Response::json($course);
    }

    public function addCourse(Request $request) {

        if ($file = $request->file('course_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/courses/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/courses/'.$safeName;
        }

       $course = Course::create([
            'course_name'   => $request->get('course_name'),
            'course_content'   => $request->get('course_content'),
            'course_pic'   => $request->get('pic'),
            'from_date'     => $request->get('from_date'),
            'to_date'       => $request->get('to_date'),
            'status' => $request->get('status')
        ]);
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        $ret['course']  = $course;
        return Response::json($ret);
    }

    public function updateCourseStatus(Request $request) {
        $course_id  = $request->get('course_id');
        $status     = $request->get('status');
        $course     = Course::find($course_id);
        $course->status = $status;
        $course->save();
        $ret = array();
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        $ret['course']  = $course;
        return Response::json($ret);
    }

    public function updateCourse(Request $request) {
        $cu_date = date('Y-m-d H:i:s');
        $course_id  = $request->get('course_id');
        $course     = Course::find($course_id);
        if ($file = $request->file('course_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/courses/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['course_pic'] = '/uploads/courses/'.$safeName;
            if (File::exists(public_path() . $course->course_pic)) {
                File::delete(public_path() . $course->course_pic);
            }
        }
        $request['updated_at'] = $cu_date;
        $req = $request->except('course_id','token','user_id', 'course_file', 'role');
        $course->update($req);

        $ret = array();
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        $ret['course']  = $course;
        return Response::json($ret);
    }

    public function deleteCourse(Request $request) {
        $cu_date = date('Y-m-d H:i:s');
        $course_id  = $request->get('course_id');
        $course     = Course::find($course_id);
        $course->status = 2;
        $course->deleted_at = $cu_date;
        $course->save();

        $ret = array();
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }

    public function quarters(Request $request) {
        $courses = Course::where('status' , 1)->select(['id', 'course_name as value', 'course_name as label'])->get();
        return Response::json($courses);
    }

    /*
    ************* level function**************
    */
    public function addLevel(Request $request) {

        if ($file = $request->file('level_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/courses/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/courses/'.$safeName;
        }
        
        $level = Level::create([
                'level_name'   => $request->get('level_name'),
                'level_content' => $request->get('level_content'),
                'level_pic'     => $request->get('pic'),            
                'status'        => $request->get('status'),
                'course_id'     => $request->get('course_id'),
            ]);            
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        $ret['level']   = $level;
        return Response::json($ret);
    }


    public function getLevels(Request $request)
    {
        $course_id = $request->get('course_id');
        $levels = \DB::table('course_level as cl')
                            ->leftJoin('course_main as cm', 'cm.id', '=', 'cl.course_id');
        if($course_id)
            $levels = $levels->where('cl.course_id', $course_id) ;
        
            $levels = $levels->select(['cl.*','cm.course_name'])->get();
        
        
        return Response::json($levels);
    }

    public function updateLevelStatus(Request $request) {
        $level_id  = $request->get('level_id');
        $status     = $request->get('status');
        $level     = Level::find($level_id);
        $level->status = $status;
        $level->save();
        $ret = array();
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        $ret['level']  = $level;
        return Response::json($ret);
    }


    public function updateLevel(Request $request) {
        $cu_date = date('Y-m-d H:i:s');
        $level_id  = $request->get('level_id');
        $level     = Level::find($level_id);
        if(!$level) {
            $ret = array();
            $ret['code']    = '401';
            $ret['msg']     = 'Can not find this level.';
            return Response::json($ret);
        }else {
            if ($file = $request->file('level_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/courses/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                $request['course_pic'] = '/uploads/courses/'.$safeName;
                if (File::exists(public_path() . $level->course_pic)) {
                    File::delete(public_path() . $level->course_pic);
                }
            }
            $request['updated_at'] = $cu_date;
            $req = $request->except('level_id','token','user_id', 'level_file', 'role');
            $level->update($req);

            $ret = array();
            $ret['code']    = '200';
            $ret['msg']     = 'Completed successfully!';
            $ret['level']  = $level;
            return Response::json($ret);
        }
    }

    public function deleteLevel(Request $request) {
        $cu_date = date('Y-m-d H:i:s');
        $level_id  = $request->get('level_id');
        $level     = Level::find($level_id);
        if($level) {
            $level->status = 2;
            $level->deleted_at = $cu_date;
            $level->save();

            $ret = array();
            $ret['code']    = '200';
            $ret['msg']     = 'Completed successfully!';
            return Response::json($ret);
        }else {
            $ret = array();
            $ret['code']    = '401';
            $ret['msg']     = 'Can not find a level';
            return Response::json($ret);
        }
    }

    public function level(Request $request) {
        $level_id = $request->get('level_id');        
        $level = \DB::table('course_level as cl')
                    ->leftJoin('course_main as cm', 'cm.id', '=', 'cl.course_id')        
                    ->where('cl.id', $level_id)
                    ->select(['cl.*', 'cm.course_name'])
                    ->first();        
        return Response::json($level);
    }

    /**
     * ===================test ==========
     */
    public function getCategory(Request $request) {
        $category = array();
        $one = new stdClass();
        $one->id = 0;
        $one->value = 'web';
        $one->label = 'web';
        $category[] = $one;

        $one->id = 1;
        $one->value = 'firebase';
        $one->label = 'Firebase';
        $category[] = $one;

        $one->id = 2;
        $one->value = 'cloud';
        $one->label = 'cloud';
        $category[] = $one;

        $one->id = 3;
        $one->value = 'android';
        $one->label = 'Android';
        $category[] = $one;

        return Response::json($category);
    }

    public function demoSteps() {
        $demosteps = array();
        for($i=0; $i < 10 ;$i++) {
           $one = new stdClass();
           $one->title = 'Introduction';
           $one->content = '<h1>Step 1 - Introduction</h1>'.
               '<br>' .
               'This is an example step of the course. You can put anything in here from example codes to videos.' .
               '<br><br>' .
               'To install the CLI you need to have installed <b>npm</b> which typically comes with <b>NodeJS</b>.' .
               'To install or upgrade the CLI run the following <b>npm</b> command:' .
               '<br><br>' .
               '<code>npm -g install @angular/cli</code>' .
               '<br><br>' .
               'To verify that the CLI has been installed correctly, open a console and run:' .
               '<br><br>' .
               '<code>ng version</code>' .
               '<br><br>' .
               '<h2>Install dependencies</h2>' .
               '<br>' .
               'To moderate the images we\'ll need a few Node.js packages:' .
               '<br><br>' .
               '<ul>' .
               '<li>' .
               'The Google Cloud Vision Client Library for Node.js: @google-cloud/vision to run the image through the Cloud Vision API to detect inappropriate images.' .
               '</li>' .
               '<br>' .
               '<li>' .
               'The Google Cloud Storage Client Library for Node.js: @google-cloud/storage to download and upload the images from Cloud Storage.' .
               '</li>' .
               '<br>' .
               '<li>' .
               'A Node.js library allowing us to run processes: child-process-promise to run ImageMagick since the ImageMagick command-line tool comes pre-installed on all Functions instances.' .
               '</li>' .
               '</ul>' .
               '<br>' .
               'To install these three packages into your Cloud Functions app, run the following npm install --save command. Make sure that you do this from the functions directory.' .
               '<br><br>' .
               '<code>npm install --save @google-cloud/vision @google-cloud/storage child-process-promise</code>' .
               '<br><br>' .
               'This will install the three packages locally and add them as declared dependencies in your package.js file.' ;
            $demosteps[] = $one;
        }

        return $demosteps;
    }


    public function getLessons(Request $request)
    {

        $courses = array();

        for($i =0; $i < 10; $i++) {
            $one = new stdClass();
            $one->id    = '154588a0864d2881124';
            $one->title = 'Basics of Angular';
            $one->slug = 'basics-of-angular';
            $one->category = 'web';
            $one->length = '30';
            $one->updated = 'Jun 28, 2017';
            $courses[] = $one;
        }
        return Response::json($courses);
    }


}
