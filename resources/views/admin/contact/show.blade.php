@extends('layouts/default')

{{-- Page title --}}
@section('title')
    View Contact information
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet"/>

    <link href="{{ asset('assets/css/pages/user_profile.css') }}" rel="stylesheet"/>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>Contact View</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#">Cotnact</a>
            </li>
            <li class="active">Contact View</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <div class="row">
        <div class="col-md-12">    
                <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"> <i class="livicon" data-name="users" data-size="16" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                View Contact : <p class="user_name_max">{!! $contact->first_name!!}</p>
                            </h3>
                            <span class="pull-right clickable">
                                <i class="glyphicon glyphicon-chevron-up"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <!--main userpanel content-->
                            <div class="row" id="userpanel"  >
                                <div class="col-md-12">

                                    {!! Form::model($contact, ['url' => URL::to('contacts/'. $contact->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'viewForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                                                                    
                                            <div id="rootwizard" class="row" >
                                                <!--section contact part-->
                                                <div class="row" >
                                                    <div class="col-sm-12" style="margin-bottom:10px; margin-top:10px;"  >
                                                        <b style="border-bottom:1px solid #f0eeee;font-size:18px;margin-bottom:5px;">Contact information                                                           
                                                        </b>                                                                                                        
                                                    </div>
                                                    <div>                                                            
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group">
                                                                <label for="first_name" class="col-sm-3 control-label">First Name</label>
                                                                <div class="col-sm-9">
                                                                <span class="form-control">{{$contact->first_name}} </span>                                                                    
                                                                </div>
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group ">
                                                                <label for="last_name" class="col-sm-3 control-label">Last Name</label>
                                                                <div class="col-sm-9">
                                                                <span class="form-control">{{$contact->last_name}} </span>                                                                        
                                                                </div>
                                                            </div>

                                                            <!--line-->
                                                            <div class="col-sm-6 form-group ">
                                                                <label for="email" class="col-sm-3 control-label">Email</label>
                                                                <div class="col-sm-9">
                                                                <span class="form-control">{{$contact->email}} </span>                                                                    
                                                                </div>
                                                            </div>                                                   
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group">
                                                                <label for="phone" class="col-sm-3 control-label">Phone</label>
                                                                <div class="col-sm-9">                               
                                                                <span class="form-control">{{$contact->phone}} </span>                                                                                                                                                             
                                                                </div>                                                            
                                                            </div>
                                                            
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group">
                                                                <label for="comment" class="col-sm-3 control-label">Role</label>
                                                                <div class="col-sm-9">                                                               
                                                                <span class="form-control">
                                                                    @if($contact->role == '2') School admin @endif
                                                                    @if($contact->role == '3') Technical Trainer @endif
                                                                    @if($contact->role == '4') Personal Trainer @endif
                                                                    @if($contact->role == '5') Etherity @endif
                                                                </span>                                                                    
                                                                </div>                                                            
                                                            </div>
                                                            <!--line-->
                                                            <div class="col-sm-6 form-group">
                                                                <label for="comment" class="col-sm-3 control-label">Comment</label>
                                                                <div class="col-sm-9">                                                               
                                                                <span class="form-control">{{$contact->comment}} </span>                                                                    
                                                                </div>                                                            
                                                            </div>
                                                            
                                                            <!--line-->    
                                                            <div class="col-sm-12" style="border-bottom:1px solid #f0eeee;margin-bottom:10px;"   >                                                        
                                                            </div>     
                                                    </div>   
                                                </div>                                        
                                                <!--section contact end-->                                           
                                            </div>    
                                    </form>

                                </div>
                            </div>
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
    <script  src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
@stop
