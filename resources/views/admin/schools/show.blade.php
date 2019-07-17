@extends('layouts/default')

{{-- Page title --}}
@section('title')
    View School Details
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/pages/new/school.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/pages/user_profile.css') }}" rel="stylesheet"/>
    <style>
        .form-group > div {
            padding-top:5px;
            font-size:16px;
        }
        .technical {
            cursor:pointer;            
            margin-left:30px;
        }
        .technical:hover {
            cursor:pointer;
            background-color: #d3d4d8;
        }
    </style>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>School</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#">Schools</a>
            </li>
            <li class="active">Sschool view</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <div class="row">
            <div class="col-md-12">    
                <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"> <i class="livicon" data-name="users" data-size="16" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                View School : <p class="user_name_max">{!! $school->name!!}</p>
                            </h3>
                            <span class="pull-right clickable">
                                <i class="glyphicon glyphicon-chevron-up"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <!--main userpanel content-->
                            <div class="row" id="userpanel"  >
                                <div class="col-md-12">

                                    {!! Form::model($user, ['url' => URL::to('schools/'. $school->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'viewForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                                                                    
                                            <div id="rootwizard" class="row" >
                                                <!--section sschool part-->
                                                <div class="row" >
                                                    <div class="col-sm-12" style="margin-bottom:10px; margin-top:10px;"  >
                                                        <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">School information
                                                            <a class="technical" ><i class="fas fa-address-book"></i> View Techincal Manager </a>
                                                        </b>                                                                                                        
                                                    </div>
                                                    <div>
                                                            <!--line 1-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('logo_file', 'has-error') }}">
                                                                <label for="logo" class="col-sm-3 control-label">School Logo</label>
                                                                <div class="col-sm-9">
                                                                    @if($school->logo_path)
                                                                        <img src="{!! url('/').'/uploads/schools/'.$school->logo_path !!}"
                                                                                alt="img"
                                                                                class="img-responsive"  />
                                                                    @else            
                                                                        <img src="http://placehold.it/350x65" alt="..." class="img-responsive" >
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('school_name', 'has-error') }}">
                                                                <label for="school_name" class="col-sm-3 control-label">School Name *</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->name}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('reference_asd', 'has-error') }}">
                                                                <label for="reference_asd" class="col-sm-3 control-label">Reference ASD</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->reference_asd}}
                                                                </div>
                                                            </div>

                                                            <!--line-->

                                                            <div class="col-sm-6 form-group {{ $errors->first('company_code', 'has-error') }}">
                                                                <label for="company_code" class="col-sm-3 control-label">Company Code</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->company_code}}
                                                                </div>
                                                            </div>                                                   
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('school_state', 'has-error') }}">
                                                                <label for="school_state" class="col-sm-3 control-label">School State</label>
                                                                <div class="col-sm-9">                                                                                                                        
                                                                    {{$school->state}}
                                                                </div>                                                            
                                                            </div>
                                                            
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('school_region', 'has-error') }}">
                                                                <label for="school_region" class="col-sm-3 control-label">School Region</label>
                                                                <div class="col-sm-9">                                                               
                                                                    {{$school->region}}  
                                                                </div>                                                            
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('school_province', 'has-error') }}">
                                                                <label for="school_province" class="col-sm-3 control-label">School Province</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->province}}
                                                                </div>                                                            
                                                            </div>                        
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('school_city', 'has-error') }}">
                                                                <label for="school_city" class="col-sm-3 control-label">School City</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->city}}
                                                                </div>                                                            
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('school_address', 'has-error') }}">
                                                                <label for="school_address" class="col-sm-3 control-label">School Address</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->address}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('postal_code', 'has-error') }}">
                                                                <label for="postal_code" class="col-sm-3 control-label">School Postal Code</label>
                                                                <div class="col-sm-9">
                                                                    {{$school->postal_code}}
                                                                </div>
                                                            </div>
                                                            <!--line-->    
                                                            <div class="col-sm-12" style="border-bottom:1px solid #f0eeee;margin-bottom:10px;"   >                                                        
                                                            </div>     
                                                    </div>   
                                                </div>                                        
                                                <!--section school end-->

                                                <!--section user part-->
                                                <div class="row">
                                                    <div class="col-sm-12" style="margin-bottom:10px;"  >
                                                        <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">Manager information</b>                                                
                                                    </div>
                                                    <div>
                                                            <!--line 1-->
                                                            <div class="col-sm-6 form-group ">
                                                                <label for="pic" class="col-sm-3 control-label">User picture</label>
                                                                <div class="col-sm-9">
                                                                            @if($user->pic)
                                                                                <img src="{!! url('/').'/uploads/users/'.$user->pic !!}"
                                                                                        alt="img"
                                                                                        class="img-responsive"/>
                                                                            @elseif($user->gender === "male")
                                                                                <img src="{{ asset('assets/images/authors/avatar3.png') }}"
                                                                                        alt="..."
                                                                                        class="img-responsive"/>
                                                                            @elseif($user->gender === "female")
                                                                                <img src="{{ asset('assets/images/authors/avatar5.png') }}"
                                                                                        alt="..."
                                                                                        class="img-responsive"/>
                                                                            @else
                                                                                <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}"
                                                                                        alt="..."
                                                                                        class="img-responsive"/>
                                                                            @endif
                                                                </div>
                                                            </div>

                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('first_name', 'has-error') }}">
                                                                <label for="first_name" class="col-sm-3 control-label">First Name *</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->first_name}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('last_name', 'has-error') }}">
                                                                <label for="last_name" class="col-sm-3 control-label">Last Name *</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->last_name}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('email', 'has-error') }}">
                                                                <label for="email" class="col-sm-3 control-label">Email</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->email}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group ">
                                                                <h2 class="hidden">&nbsp;</h2> 
                                                                <div class=" {{ $errors->first('dob', 'has-error') }}">
                                                                    <label for="dob" class="col-sm-3 control-label">Date of Birth</label>
                                                                    <div class="col-sm-9">
                                                                        {{$user->dob}}
                                                                    </div>                                                                
                                                                </div>    
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('mobile_phone', 'has-error') }}">
                                                                <label for="mobile_phone" class="col-sm-3 control-label">Mobile phone</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->mobile_phone}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('gender', 'has-error') }}">
                                                                    <label for="gender" class="col-sm-3 control-label">Gender *</label>
                                                                    <div class="col-sm-9">
                                                                        {{$user->gender}}
                                                                    </div>                                                                
                                                            </div>
                                                            <!--country italy line-->                                                   
                                                            <input id="country" name="country" type="hidden" value="IT"/>                                                   
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('state', 'has-error') }}">
                                                                <label for="state" class="col-sm-3 control-label">State</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->state}}                                                         
                                                                </div>                                                           
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('region', 'has-error') }}">
                                                                <label for="region" class="col-sm-3 control-label">Region</label>
                                                                <div class="col-sm-9">                                                               
                                                                    {{$user->region}} 
                                                                </div>                                                            
                                                            </div>
                                                            <!--line-->                                                        
                                                            <div class="col-sm-6 form-group {{ $errors->first('province', 'has-error') }}">
                                                                <label for="province" class="col-sm-3 control-label">Province</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->province}} 
                                                                </div>                                                            
                                                            </div>                          
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('city', 'has-error') }}">
                                                                <label for="city" class="col-sm-3 control-label">City</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->city}}
                                                                </div>                                                            
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('address', 'has-error') }}">
                                                                <label for="user_address" class="col-sm-3 control-label">Address</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->address}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('postal', 'has-error') }}">
                                                                <label for="postal" class="col-sm-3 control-label">Postal Code</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->postal}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group" >
                                                                <label for="group" class="col-sm-3 control-label">Group *</label>
                                                                <div class="col-sm-9">                                                                
                                                                    @foreach($roles as $role)
                                                                        @php 
                                                                            if (array_key_exists($role->id, $userRoles)) {
                                                                                echo $role->name ;             
                                                                            } else { echo '' ; } 
                                                                        @endphp
                                                                    @endforeach                                                                
                                                                </div>                                                            
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('vat_number', 'has-error') }}">
                                                                <label for="vat_number" class="col-sm-3 control-label">Vat number</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->vat_number}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group {{ $errors->first('fiscal_code', 'has-error') }}">
                                                                <label for="fiscal_code" class="col-sm-3 control-label">Fiscal Code</label>
                                                                <div class="col-sm-9">
                                                                    {{$user->fiscal_code}}
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-12" style="border-bottom:1px solid #f0eeee;margin-bottom:10px;"   >                                                        
                                                            </div>     
                                                    </div>   
                                                </div>                                        
                                                <!--section user end-->                                                       
                                                <!--section more part-->
                                                <div class="row">
                                                    <div class="col-sm-12" style="margin-bottom:10px;"  >
                                                        <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">More information</b>                                                
                                                    </div>
                                                    <div>
                                                        <!--line -->
                                                        <div class="col-sm-6 form-group">
                                                                <label for="membership_type" class="col-sm-3 control-label">Membership Type</label>
                                                                <div class="col-sm-9">
                                                                    active member
                                                                </div>                                                            
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group">
                                                            <label for="bio" class="col-sm-3 control-label">Bio <small>(brief intro) *</small></label>
                                                            <div class="col-sm-9">
                                                                {{$user->bio}}
                                                            </div>                                                        
                                                        </div>                                                
                                                        <!--line--> 
                                                        <div class="col-sm-6 form-group">
                                                            <label  class="col-sm-3 control-label"> Upload Area</label>
                                                            <div class="col-sm-9">                                                        
                                                                <span class="form-control">This is upload area. </span></div>                                            
                                                        </div>
                                                        <!--line--> 
                                                        <div class="col-sm-6 form-group">
                                                            <label  class="col-sm-3 control-label"> DownLoad Area</label>
                                                            <div class="col-sm-9">                                                        
                                                                <span class="form-control">This is download area. </span></div>                                            
                                                        </div>
                                                        <!--line--> 
                                                        <div class="col-sm-6 form-group">
                                                            <label for="activate" class="col-sm-3 control-label"> Activate User *</label>
                                                            <div class="col-sm-9">
                                                            <input id="activate" name="activate" type="checkbox" class="pos-rel p-l-30 custom-checkbox" value="1" @if($status) checked="checked" @endif disabled >
                                                                <span>To activate user account automatically, click the check box</span></div>                                            
                                                        </div>
                                                        <!--line-->  
                                                    </div>
                                                </div>    
                                                <!--section other end part-->                                             
                                            </div>    
                                    </form>

                                </div>
                            </div>
                            <!--main content end-->                            
                            @if($tech_user)
                                @include('admin/schools/technical_edit')
                            @else
                                @include('admin/schools/technical_create')
                            @endif    
                            
                        </div>
                    </div>               
                </div>                
            </div>            
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- Bootstrap WYSIHTML5 -->
    <script>
        var site_url= '{{URL::to('/')}}';
    </script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/new/showschool.js') }}"></script>   

    <script>     
      $( ".technical" ).click(function() {
            $( "#userpanel" ).toggle( "slow", function() {                
             });
             $( "#technical" ).toggle( "slow", function() {
            
             });
        });
    </script>
@stop
