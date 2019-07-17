<?php

namespace App\Http\Datautil;


use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use DB;
use DateTime;
use App;
use Session;
class Util
{
    //get route
    public static function getRoute()
    {
        $route = Route::getFacadeRoot()->current()->uri();
        return $route;
    }
    //get sub route
    public static function getSubRoute()
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $route_path = explode('/',$route);
        if(!empty($route_path[1])) $subroute = $route_path[1];
        else $subroute = "";
        return $subroute;
    }
    
    //get State
    public static function State_list()
    {        
        $state = \DB::table('state')->where('id', 1)->get();
        return $state;
    }

    public static function Region_list($state_id) {
        
        $regions = DB::table('region');
//         if($state_id != 0)
//            $regions = $regions->where('state_id',$state_id);

        $regions = $regions->orderby('region_name','asc')->get();    
        return $regions;
    }

    //get province list
    public static function Province_list($region_id) {
      
        $provinces = DB::table('province') ;
            if($region_id !=0) 
                $provinces = $provinces->where('region_id', $region_id) ;
        $provinces = $provinces->orderby('province_name')->get();        
            
        return $provinces;
    }

     //get city lsit
     public static function City_list($province_id) {
    
        $province = DB::table('province')
                ->where('id',$province_id)->first();
        $citys = explode(',',$province->city_list);        

    return $citys;
    }
    //get state_name
    public static function getStateName($state_id) {
        $state = \DB::table('state')->where('id', $state_id)->first();       
        return $state->state_name;
    }

    public static function getStateId($state_name) {
        $st = \DB::table('state')->where('state_name', $state_name)->first();   
        if($st)    
            return $st->id;
        else return 0;    
    }


    //get region_name
    public static function getRegionName($region_id) {
        $region = \DB::table('region')->where('id', $region_id)->first();       
        return $region->region_name;
    }

    public static function getRegionId($region_name) {      
        $region = \DB::table('region')->where('region_name', $region_name)->first();                      
        if($region)
            return $region->id;
        else 
            return 0;             
    }

    //get province_name
    public static function getProvinceName($province_id) {
        $province = \DB::table('province')->where('id', $province_id)->first();       
        return $province->province_name;
    }

    public static function getProvinceID($province_name) {
        $pro = \DB::table('province')->where('province_name', $province_name)->first();       
        if($pro)
            return $pro->id;
        else
            return 0;    
    }
    
}
