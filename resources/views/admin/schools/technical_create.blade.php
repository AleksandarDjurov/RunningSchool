<!--main techinal panel content-->
<div class="row" id="technical" style="display:none;" >
    <div class="col-md-12">                                       
            <form id="commentForm" action="{{ route('schools.technical') }}"
                method="POST" enctype="multipart/form-data" class="form-horizontal">
                <!-- CSRF Token -->
                <input type="hidden" name="school_id" value="{{$school->id}}" />
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />                                                        
                                                    
                    <div id="rootwizard" class="row">
                        <!--section user part-->
                        <div class="row">
                            <div class="col-sm-12" style="margin-bottom:10px;margin-top:10px;"  >
                                <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">Technical Manager Information 
                                    <a class="technical" ><i class="fas fa-chalkboard-teacher"></i> View School</a>            
                                </b>                                                
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
                                    <div class="col-sm-6 form-group {{ $errors->first('birth_place', 'has-error') }}">
                                        <label for="birth_place" class="col-sm-3 control-label">Birth Place</label>
                                        <div class="col-sm-9">
                                            <input id="birth_place" name="birth_place" type="text"
                                                placeholder="Birth Place" class="form-control required"
                                                value="{!! old('birth_place') !!}"/>

                                            {!! $errors->first('birth_place', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <!--line-->
                                    <div class="col-sm-6 form-group {{ $errors->first('nationality', 'has-error') }}">
                                        <label for="nationality" class="col-sm-3 control-label">Nationality</label>
                                        <div class="col-sm-9">
                                            <input id="nationality" name="nationality" type="text"
                                                placeholder="Nationality" class="form-control required"
                                                value="{!! old('nationality') !!}"/>

                                            {!! $errors->first('nationality', '<span class="help-block">:message</span>') !!}
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
                                    <div class="col-sm-6 form-group {{ $errors->first('certified_type', 'has-error') }}">
                                        <label for="certified_type" class="col-sm-3 control-label">Certified Type</label>
                                        <div class="col-sm-9">
                                            <input id="certified_type" name="certified_type" type="text"
                                                placeholder="certified_type" class="form-control required"
                                                value="{!! old('certified_type') !!}"/>

                                            {!! $errors->first('certified_type', '<span class="help-block">:message</span>') !!}
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
                                    <div class="col-sm-6 form-group {{ $errors->first('card_number', 'has-error') }}">
                                        <label for="card_number" class="col-sm-3 control-label">Card Number</label>
                                        <div class="col-sm-9">
                                            <input id="card_number" name="card_number" type="text"
                                                placeholder="Card Number" class="form-control required"
                                                value="{!! old('card_number') !!}"/>

                                            {!! $errors->first('card_number', '<span class="help-block">:message</span>') !!}
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
<!--main techincal panel end -->
