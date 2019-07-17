@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Package
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
    <!--end of page level css-->

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>Edit School</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Packages</li>
            <li class="active">Add New Package</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"> <i class="livicon" data-name="users" data-size="16" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Editing School : <p class="user_name_max">{!! $school->name!!}</p>
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">

                            <div class="col-md-12">

                                {!! Form::model($user, ['url' => URL::to('schools/'. $school->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                                    {{ csrf_field() }}                                       
                                   
                                        <div id="rootwizard" class="row" >
                                            <!--section sschool part-->
                                            <div class="row">
                                                <div class="col-sm-12" style="margin-bottom:10px;"  >
                                                    <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">School information</b>                                                
                                                </div>
                                                <div>
                                                        <!--line 1-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('logo_file', 'has-error') }}">
                                                            <label for="logo" class="col-sm-3 control-label">School Logo</label>
                                                            <div class="col-sm-9">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="width: 350px; height:65px;">
                                                                        @if($school->logo_path)
                                                                            <img src="{!! url('/').'/uploads/schools/'.$school->logo_path !!}"
                                                                                    alt="img"
                                                                                    class="img-responsive"  />
                                                                        @else            
                                                                            <img src="http://placehold.it/350x65" alt="..." class="img-responsive" >
                                                                        @endif
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 350px; max-height: 65px;"></div>
                                                                    <div>
                                                                        <span class="btn btn-default btn-file">
                                                                            <span class="fileinput-new">Select image</span>
                                                                            <span class="fileinput-exists">Change</span>
                                                                            <input id="logo" name="logo_file" type="file" class="form-control"/>
                                                                        </span>
                                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                                        data-dismiss="fileinput">Remove</a>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">{{ $errors->first('logo_file', ':message') }}</span>
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('school_name', 'has-error') }}">
                                                            <label for="school_name" class="col-sm-3 control-label">School Name *</label>
                                                            <div class="col-sm-9">
                                                                <input id="school_name" name="school_name" type="text"
                                                                    placeholder="School Name" class="form-control required"
                                                                    value="{!! old('school_name', $school->name) !!}"/>

                                                                {!! $errors->first('school_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('reference_asd', 'has-error') }}">
                                                            <label for="reference_asd" class="col-sm-3 control-label">Reference ASD</label>
                                                            <div class="col-sm-9">
                                                                <input id="reference_asd" name="reference_asd" type="text"
                                                                    placeholder="Reference ASD" class="form-control required"
                                                                    value="{!! old('reference_asd', $school->reference_asd) !!}"/>

                                                                {!! $errors->first('reference_asd', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <!--line-->

                                                        <div class="col-sm-6 form-group {{ $errors->first('company_code', 'has-error') }}">
                                                            <label for="company_code" class="col-sm-3 control-label">Company Code</label>
                                                            <div class="col-sm-9">
                                                                <input id="company_code" name="company_code" type="text"
                                                                    placeholder="Company Code" class="form-control required"
                                                                    value="{!! old('company_code', $school->company_code) !!}"/>

                                                                {!! $errors->first('company_code', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>                                                   
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('school_state', 'has-error') }}">
                                                            <label for="school_state" class="col-sm-3 control-label">School State</label>
                                                            <div class="col-sm-9">                                                                                                                        
                                                                <select class="form-control" name="school_state" id="school_state"  >
                                                                    <option value="0" > Select school state </option>
                                                                    @foreach($states as $st)
                                                                        @php
                                                                            $selected = '';
                                                                            if($school->state == $st->state_name ) $selected = 'selected';
                                                                        @endphp
                                                                        <option value="{{$st->id}}" {{$selected}} > {{$st->state_name}}</option>                                                                    
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('school_state', ':message') }}</span>
                                                        </div>
                                                        
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('school_region', 'has-error') }}">
                                                            <label for="school_region" class="col-sm-3 control-label">School Region</label>
                                                            <div class="col-sm-9">                                                               
                                                                <select name="school_region" id="school_region" class="form-control select2" >
                                                                    <option value="{{$school->region_id}}" > {{$school->region}} </option>
                                                                </select>   
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('school_region', ':message') }}</span>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('school_province', 'has-error') }}">
                                                            <label for="school_province" class="col-sm-3 control-label">School Province</label>
                                                            <div class="col-sm-9">
                                                                <select name="school_province" id="school_province" class="form-control select2" >
                                                                    <option value="{{$school->province_id}}" > {{$school->province}} </option>
                                                                </select> 
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('school_province', ':message') }}</span>
                                                        </div>                        
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('school_city', 'has-error') }}">
                                                            <label for="school_city" class="col-sm-3 control-label">School City</label>
                                                            <div class="col-sm-9">
                                                                <select name="school_city" id="school_city" class="form-control select2" >
                                                                    <option value="{{$school->city}}" > {{$school->city}} </option>
                                                                </select> 
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('school_city', ':message') }}</span>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('school_address', 'has-error') }}">
                                                            <label for="school_address" class="col-sm-3 control-label">School Address</label>
                                                            <div class="col-sm-9">
                                                                <input id="school_address" name="school_address" type="text"
                                                                    placeholder="School Address" class="form-control required"
                                                                    value="{!! old('school_address', $school->address) !!}"/>

                                                                {!! $errors->first('school_address', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('postal_code', 'has-error') }}">
                                                            <label for="postal_code" class="col-sm-3 control-label">School Postal Code</label>
                                                            <div class="col-sm-9">
                                                                <input id="postal_code" name="postal_code" type="text"
                                                                    placeholder="Postal Code" class="form-control required"
                                                                    value="{!! old('postal_code', $school->postal_code) !!}"/>

                                                                {!! $errors->first('postal_code', '<span class="help-block">:message</span>') !!}
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
                                                        <div class="col-sm-6 form-group {{ $errors->first('pic_file', 'has-error') }}">
                                                            <label for="pic" class="col-sm-3 control-label">User picture</label>
                                                            <div class="col-sm-9">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
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
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;"></div>
                                                                    <div>
                                                                        <span class="btn btn-default btn-file">
                                                                            <span class="fileinput-new">Select image</span>
                                                                            <span class="fileinput-exists">Change</span>
                                                                            <input id="pic" name="pic_file" type="file" class="form-control"/>
                                                                        </span>
                                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                                        data-dismiss="fileinput">Remove</a>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">{{ $errors->first('pic_file', ':message') }}</span>
                                                            </div>
                                                        </div>

                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('first_name', 'has-error') }}">
                                                            <label for="first_name" class="col-sm-3 control-label">First Name *</label>
                                                            <div class="col-sm-9">
                                                                <input id="first_name" name="first_name" type="text"
                                                                    placeholder="Name" class="form-control required"
                                                                    value="{!! old('first_name', $user->first_name) !!}"/>

                                                                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('last_name', 'has-error') }}">
                                                            <label for="last_name" class="col-sm-3 control-label">Last Name *</label>
                                                            <div class="col-sm-9">
                                                                <input id="last_name" name="last_name" type="text"
                                                                    placeholder="Last name" class="form-control required"
                                                                    value="{!! old('last_name', $user->last_name) !!}"/>

                                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('email', 'has-error') }}">
                                                            <label for="email" class="col-sm-3 control-label">Email</label>
                                                            <div class="col-sm-9">
                                                                <input id="email" name="email" type="text"
                                                                    placeholder="E-mail" class="form-control required"
                                                                    value="{!! old('email',$user->email) !!}"/>

                                                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group ">
                                                            <h2 class="hidden">&nbsp;</h2> 
                                                            <div class=" {{ $errors->first('dob', 'has-error') }}">
                                                                <label for="dob" class="col-sm-3 control-label">Date of Birth</label>
                                                                <div class="col-sm-9">
                                                                    <input id="dob" name="dob" type="text" class="form-control"
                                                                        data-date-format="YYYY-MM-DD"  value="{!! old('dob', $user->dob) !!}"
                                                                        placeholder="yyyy-mm-dd"/>
                                                                </div>
                                                                <span class="help-block">{{ $errors->first('dob', ':message') }}</span>
                                                            </div>    
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('mobile_phone', 'has-error') }}">
                                                            <label for="mobile_phone" class="col-sm-3 control-label">Mobile phone</label>
                                                            <div class="col-sm-9">
                                                                <input id="mobile_phone" name="mobile_phone" type="text"
                                                                    placeholder="Mobile phone" class="form-control required"
                                                                    value="{!! old('mobile_phone', $user->mobile_phone) !!}"/>

                                                                {!! $errors->first('mobile_phone', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('gender', 'has-error') }}">
                                                                <label for="gender" class="col-sm-3 control-label">Gender *</label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control" title="Select Gender..." name="gender">
                                                                        <option value="">Select</option>
                                                                        <option value="male" @if($user->gender === 'male') selected="selected" @endif >Male</option>
                                                                        <option value="female" @if($user->gender === 'female') selected="selected" @endif >Female</option>
                                                                        <option value="other" @if($user->gender === 'other') selected="selected" @endif >Other</option>

                                                                    </select>
                                                                </div>
                                                                <span class="help-block">{{ $errors->first('gender', ':message') }}</span>
                                                        </div>
                                                        <!--country italy line-->                                                   
                                                        <input id="country" name="country" type="hidden" value="IT"/>                                                   
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('state', 'has-error') }}">
                                                            <label for="state" class="col-sm-3 control-label">State</label>
                                                            <div class="col-sm-9">
                                                                <select name="state" id= "state" class="form-control">                                                                    
                                                                    @foreach($states as $st)
                                                                        @php
                                                                            $selected = "";
                                                                            if($user->state == $st->state_name) $selected = "selected";
                                                                        @endphp
                                                                        <option value="{{$st->id}}" {{$selected}} > {{$st->state_name}}</option>
                                                                    @endforeach
                                                                </select>                                                            
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('state', ':message') }}</span>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('region', 'has-error') }}">
                                                            <label for="region" class="col-sm-3 control-label">Region</label>
                                                            <div class="col-sm-9">                                                               
                                                                <select name="region" id="region" class="form-control select2" >
                                                                    <option value="{{$user->region_id}}" > {{$user->region}} </option>
                                                                </select>   
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('region', ':message') }}</span>
                                                        </div>
                                                        <!--line-->                                                        
                                                        <div class="col-sm-6 form-group {{ $errors->first('province', 'has-error') }}">
                                                            <label for="province" class="col-sm-3 control-label">Province</label>
                                                            <div class="col-sm-9">
                                                                <select name="province" id="province" class="form-control select2" >
                                                                    <option value="{{$user->province_id}}" > {{$user->province}} </option>
                                                                </select> 
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('province', ':message') }}</span>
                                                        </div>                          
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('city', 'has-error') }}">
                                                            <label for="city" class="col-sm-3 control-label">City</label>
                                                            <div class="col-sm-9">
                                                                <select name="city" id="city" class="form-control select2" >
                                                                    <option value="{{$user->city}}" > {{$user->city}} </option>
                                                                </select> 
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('city', ':message') }}</span>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('address', 'has-error') }}">
                                                            <label for="user_address" class="col-sm-3 control-label">Address</label>
                                                            <div class="col-sm-9">
                                                                <input id="address" name="address" type="text"
                                                                    placeholder="Address" class="form-control required"
                                                                    value="{!! old('address', $user->address) !!}"/>

                                                                {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('postal', 'has-error') }}">
                                                            <label for="postal" class="col-sm-3 control-label">Postal Code</label>
                                                            <div class="col-sm-9">
                                                                <input id="postal" name="postal" type="text"
                                                                    placeholder="Postal Code" class="form-control required"
                                                                    value="{!! old('postal', $user->postal) !!}"/>

                                                                {!! $errors->first('postal', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group" >
                                                            <label for="group" class="col-sm-3 control-label">Group *</label>
                                                            <div class="col-sm-9">
                                                                <select class="form-control " title="Select group..." name="groups[]" id="groups" required>                                                           
                                                                    @foreach($roles as $role)
                                                                        <option value="{!! $role->id !!}" {{ (array_key_exists($role->id, $userRoles) ? ' selected="selected"' : '') }}>{{ $role->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                {!! $errors->first('group', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('group', ':message') }}</span>                                                                                                      
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('vat_number', 'has-error') }}">
                                                            <label for="vat_number" class="col-sm-3 control-label">Vat number</label>
                                                            <div class="col-sm-9">
                                                                <input id="vat_number" name="vat_number" type="text"
                                                                    placeholder="Vat Number" class="form-control required"
                                                                    value="{!! old('vat_number', $user->vat_number) !!}"/>

                                                                {!! $errors->first('vat_number', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-6 form-group {{ $errors->first('fiscal_code', 'has-error') }}">
                                                            <label for="fiscal_code" class="col-sm-3 control-label">Fiscal Code</label>
                                                            <div class="col-sm-9">
                                                                <input id="fiscal_code" name="fiscal_code" type="text"
                                                                    placeholder="Fiscal Code" class="form-control required"
                                                                    value="{!! old('fiscal_code', $user->fiscal_code) !!}"/>

                                                                {!! $errors->first('fiscal_code', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!--line-->
                                                        <div class="col-sm-12" style="border-bottom:1px solid #f0eeee;margin-bottom:10px;"   >                                                        
                                                        </div>     
                                                </div>   
                                            </div>                                        
                                            <!--section user end-->          
                                             <!--section login part-->
                                            <div class="row">
                                                <div class="col-sm-12" style="margin-bottom:10px;"  >
                                                    <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">Login information</b>                                                
                                                </div>
                                                <div>
                                                    <!--line-->
                                                    <div class="col-sm-12" >
                                                        <p class="row">
                                                            <div class="text-warning col-sm-offset-2" style="padding-left:15px;"> If you don't want to change password... please leave them empty</div>
                                                        </p> 
                                                    </div>
                                                    <div class="col-sm-6 form-group {{ $errors->first('password', 'has-error') }}">                                                       
                                                        <label for="password" class="col-sm-3 control-label">Password *</label>
                                                        <div class="col-sm-9">
                                                            <input id="password" name="password" type="password" placeholder="Password"
                                                                class="form-control required" value="{!! old('password') !!}"/>
                                                            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <!--line 3-->
                                                    <div class="col-sm-6 form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                                        <label for="password_confirm" class="col-sm-3 control-label">Confirm Password *</label>
                                                        <div class="col-sm-9">
                                                            <input id="password_confirm" name="password_confirm" type="password"
                                                                placeholder="Confirm Password " class="form-control required"/>
                                                            {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <!--line-->
                                                    <div class="col-sm-12" style="border-bottom:1px solid #f0eeee;margin-bottom:10px;"   >                                                        
                                                    </div>

                                                </div>
                                            </div>    
                                            <!--section login end part-->
                                             <!--section more part-->
                                            <div class="row">
                                                <div class="col-sm-12" style="margin-bottom:10px;"  >
                                                    <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">More information</b>                                                
                                                </div>
                                                <div>
                                                    <!--line -->
                                                    <div class="col-sm-6 form-group {{ $errors->first('membership_type', 'has-error') }}">
                                                            <label for="membership_type" class="col-sm-3 control-label">Membership Type</label>
                                                            <div class="col-sm-9">
                                                                <select class="form-control" title="Select Membership..." name="membership_type">
                                                                    <option value="">Select the type of member</option>
                                                                    <option value="1"  >active member</option>
                                                                    <option value="2">perspective member</option>
                                                                    <option value="3"> alumni member </option>
                                                                </select>
                                                            </div>
                                                            <span class="help-block">{{ $errors->first('membership_type', ':message') }}</span>
                                                    </div>
                                                    <!--line-->
                                                    <div class="col-sm-6 form-group">
                                                        <label for="bio" class="col-sm-3 control-label">Bio <small>(brief intro) *</small></label>
                                                        <div class="col-sm-9">
                                                            <textarea name="bio" id="bio" class="form-control resize_vertical"
                                                                rows="2">{!! old('bio',$user->bio) !!}</textarea>
                                                        </div>
                                                        {!! $errors->first('bio', '<span class="help-block">:message</span>') !!}
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
                                                        <input id="activate" name="activate" type="checkbox" class="pos-rel p-l-30 custom-checkbox" value="1" @if($status) checked="checked" @endif  >
                                                            <span>To activate user account automatically, click the check box</span></div>                                            
                                                    </div>
                                                    <!--line-->  
                                                </div>
                                            </div>    
                                            <!--section other end part--> 
                                            <div class="row">
                                                <div class="col-sm-12 pager wizard" style="padding-right: 70px;margin-top:-40px;">                                               
                                                    <li class="next finish" style="margin-right:30px;"><a href="javascript:;">Update</a></li>
                                                </div>
                                            </div>
                                        </div>    
                                </form>

                            </div>
                        </div>
                        <!--main content end-->
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/new/editschool.js') }}"></script>
    <script>    
        var site_url= '{{URL::to('/')}}';    
        function formatState (state) {
            if (!state.id) { return state.text; }
            var $state = $(
                '<span>' + state.text + '</span>'
            );
            return $state;

            }
            $("#school_region").select2({
                templateResult: formatState,
                templateSelection: formatState,
                placeholder: "select a country",
                theme:"bootstrap"
            });
            $("#school_province").select2({
                templateResult: formatState,
                templateSelection: formatState,
                placeholder: "select a country",
                theme:"bootstrap"
            });

            $("#school_city").select2({
                templateResult: formatState,
                templateSelection: formatState,
                placeholder: "select a country",
                theme:"bootstrap"
            });

            $("#region").select2({
                templateResult: formatState,
                templateSelection: formatState,
                placeholder: "select a country",
                theme:"bootstrap"
            });
            $("#province").select2({
                templateResult: formatState,
                templateSelection: formatState,
                placeholder: "select a country",
                theme:"bootstrap"
            });

            $("#city").select2({
                templateResult: formatState,
                templateSelection: formatState,
                placeholder: "select a country",
                theme:"bootstrap"
            });


    </script>
@stop
