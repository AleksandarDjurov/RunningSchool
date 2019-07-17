@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Add School
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
    <link href="{{ asset('assets/css/pages/new/school.css') }}" rel="stylesheet">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>Add New School</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li><a href="#"> Schools</a></li>
            <li class="active">Add New school</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Add New School
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <form id="commentForm" action="{{ route('schools.store') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />                                                        
                                                                   
                                <div id="rootwizard" class="row">
                                    <!--section sschool part-->
                                    <div class="row">
                                        <div class="col-sm-12" style="margin-bottom:10px;"  >
                                            <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">School information</b>                                                
                                        </div>
                                        <div>
                                                <!--line 1-->
                                                <div class="col-sm-6 form-group {{ $errors->first('logo_file', 'has-error') }}">
                                                    <label for="pic" class="col-sm-3 control-label">School Logo</label>
                                                    <div class="col-sm-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 350px; height:65px;">
                                                                <img src="http://placehold.it/350x65" alt="school logo">
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
                                                            value="{!! old('school_name') !!}"/>

                                                        {!! $errors->first('school_name', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('reference_asd', 'has-error') }}">
                                                    <label for="reference_asd" class="col-sm-3 control-label">Reference ASD</label>
                                                    <div class="col-sm-9">
                                                        <input id="reference_asd" name="reference_asd" type="text"
                                                            placeholder="Reference ASD" class="form-control required"
                                                            value="{!! old('reference_asd') !!}"/>

                                                        {!! $errors->first('reference_asd', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <!--line-->

                                                <div class="col-sm-6 form-group {{ $errors->first('company_code', 'has-error') }}">
                                                    <label for="company_code" class="col-sm-3 control-label">Company Code</label>
                                                    <div class="col-sm-9">
                                                        <input id="company_code" name="company_code" type="text"
                                                            placeholder="Company Code" class="form-control required"
                                                            value="{!! old('company_code') !!}"/>

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
                                                                <option value="{{$st->id}}"> {{$st->state_name}}</option>                                                                    
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('school_state', ':message') }}</span>
                                                </div>
                                                
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('school_region', 'has-error') }}">
                                                    <label for="school_region" class="col-sm-3 control-label">School Region</label>
                                                    <div class="col-sm-9">
                                                        {!! Form::select('school_region', array() , null,['class' => 'form-control select2', 'id' => 'school_region']) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('school_region', ':message') }}</span>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('school_province', 'has-error') }}">
                                                    <label for="school_province" class="col-sm-3 control-label">School Province</label>
                                                    <div class="col-sm-9">
                                                        {!! Form::select('school_province', array(), null,['class' => 'form-control select2', 'id' => 'school_province']) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('school_province', ':message') }}</span>
                                                </div>                        
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('school_city', 'has-error') }}">
                                                    <label for="school_city" class="col-sm-3 control-label">School City</label>
                                                    <div class="col-sm-9">
                                                        {!! Form::select('school_city', array(), null,['class' => 'form-control select2', 'id' => 'school_city']) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('school_city', ':message') }}</span>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('school_address', 'has-error') }}">
                                                    <label for="school_address" class="col-sm-3 control-label">School Address</label>
                                                    <div class="col-sm-9">
                                                        <input id="school_address" name="school_address" type="text"
                                                            placeholder="School Address" class="form-control required"
                                                            value="{!! old('school_address') !!}"/>

                                                        {!! $errors->first('school_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('postal_code', 'has-error') }}">
                                                    <label for="postal_code" class="col-sm-3 control-label">School Postal Code</label>
                                                    <div class="col-sm-9">
                                                        <input id="postal_code" name="postal_code" type="text"
                                                            placeholder="Postal Code" class="form-control required"
                                                            value="{!! old('postal_code') !!}"/>

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
                                                                <img src="http://placehold.it/150x150" alt="profile pic">
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
                                                            value="{!! old('first_name') !!}"/>

                                                        {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('last_name', 'has-error') }}">
                                                    <label for="last_name" class="col-sm-3 control-label">Last Name *</label>
                                                    <div class="col-sm-9">
                                                        <input id="last_name" name="last_name" type="text"
                                                            placeholder="Last name" class="form-control required"
                                                            value="{!! old('last_name') !!}"/>

                                                        {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('email', 'has-error') }}">
                                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                                    <div class="col-sm-9">
                                                        <input id="email" name="email" type="text"
                                                            placeholder="E-mail" class="form-control required"
                                                            value="{!! old('email') !!}"/>

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
                                                                data-date-format="YYYY-MM-DD"
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
                                                            value="{!! old('mobile_phone') !!}"/>

                                                        {!! $errors->first('mobile_phone', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('gender', 'has-error') }}">
                                                        <label for="gender" class="col-sm-3 control-label">Gender *</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" title="Select Gender..." name="gender">
                                                                <option value="">Select</option>
                                                                <option value="male"
                                                                        @if(old('gender') === 'male') selected="selected" @endif >Male
                                                                </option>
                                                                <option value="female"
                                                                        @if(old('gender') === 'female') selected="selected" @endif >
                                                                    Female
                                                                </option>
                                                                <option value="other"
                                                                        @if(old('gender') === 'other') selected="selected" @endif >Other
                                                                </option>
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
                                                            <option value=""> Select State</option>
                                                            @foreach($states as $st)
                                                                <option value="{{$st->id}}" > {{$st->state_name}}</option>
                                                            @endforeach
                                                        </select>                                                            
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('state', ':message') }}</span>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('region', 'has-error') }}">
                                                    <label for="region" class="col-sm-3 control-label">Region</label>
                                                    <div class="col-sm-9">
                                                        {!! Form::select('region', array(), null,['class' => 'form-control select2', 'id' => 'region']) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('region', ':message') }}</span>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('province', 'has-error') }}">
                                                    <label for="province" class="col-sm-3 control-label">Province</label>
                                                    <div class="col-sm-9">
                                                        {!! Form::select('province', array(), null,['class' => 'form-control select2', 'id' => 'province']) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('province', ':message') }}</span>
                                                </div>                        
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('city', 'has-error') }}">
                                                    <label for="city" class="col-sm-3 control-label">City</label>
                                                    <div class="col-sm-9">
                                                        {!! Form::select('city', array(), null,['class' => 'form-control select2', 'id' => 'city']) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('city', ':message') }}</span>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('address', 'has-error') }}">
                                                    <label for="user_address" class="col-sm-3 control-label">Address</label>
                                                    <div class="col-sm-9">
                                                        <input id="address" name="address" type="text"
                                                            placeholder="Address" class="form-control required"
                                                            value="{!! old('address') !!}"/>

                                                        {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('postal', 'has-error') }}">
                                                    <label for="postal" class="col-sm-3 control-label">Postal Code</label>
                                                    <div class="col-sm-9">
                                                        <input id="postal" name="postal" type="text"
                                                            placeholder="Postal Code" class="form-control required"
                                                            value="{!! old('postal') !!}"/>

                                                        {!! $errors->first('postal', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group" >
                                                    <label for="group" class="col-sm-3 control-label">Group *</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control required" title="Select group..." name="group"
                                                                id="group">                                                        
                                                            @foreach($groups as $group)
                                                                <option value="{{ $group->id }}"
                                                                        @if($group->id == old('group')) selected="selected" @endif >{{ $group->name}}</option>
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
                                                            value="{!! old('vat_number') !!}"/>

                                                        {!! $errors->first('vat_number', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <!--line-->
                                                <div class="col-sm-6 form-group {{ $errors->first('fiscal_code', 'has-error') }}">
                                                    <label for="fiscal_code" class="col-sm-3 control-label">Fiscal Code</label>
                                                    <div class="col-sm-9">
                                                        <input id="fiscal_code" name="fiscal_code" type="text"
                                                            placeholder="Fiscal Code" class="form-control required"
                                                            value="{!! old('fiscal_code') !!}"/>

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
                                                        rows="2">{!! old('bio') !!}</textarea>
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
                                                    <input id="activate" name="activate" type="checkbox"
                                                        class="pos-rel p-l-30 custom-checkbox"
                                                        value="1" @if(old('activate')) checked="checked" @endif >
                                                    <span>To activate user account automatically, click the check box</span></div>                                            
                                            </div>
                                            <!--line-->  
                                        </div>
                                    </div>    
                                    <!--section other end part-->                                       
                                
                                    <div class="col-sm-6  form-group pager wizard" style="padding-right:25px !important; " >                                        
                                        <li class="next finish"><a href="javascript:;">Register</a></li>
                                    </div> 
                                </div>                                                                 
                                                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts') 
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
    <script src="{{ asset('assets/js/pages/new/addschool.js') }}"></script>       
@stop
