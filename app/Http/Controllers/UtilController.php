<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Route;
use DateTime;
use App;
use Session;
use App\Http\Datautil\Util;

class UtilController extends Controller
{
    
    //get State
    public function State_list()
    {   
        $states = Util::State_list();
        return $states;
    }

    public function Stateslist()
    {   
        $states = Util::State_list();
        return Response::json($states);
    }

    //get regiion list
    public function Region_list(Request $request) {
       $state_id = $request->input('state_name');        
       $regions = Util::Region_list($state_id);

       return Response::json($regions);	
    }

    //get province list
    public function Province_list(Request $request) {      
        $region_id = $request->input('region_name');          
        $provinces = Util::Province_list($region_id);

        return Response::json($provinces);	       
    }

    //get city lsit
    public static function City_list(Request $request) {
        $province_id = $request->input('province_name');         
        $citys = Util::City_list($province_id) ;
        return Response::json($citys);	  
    }

    
    public static function Front() {
        return view('frontend');
    }
    public static function BackendDemo() {
        return view('backend');
    }
   
}
