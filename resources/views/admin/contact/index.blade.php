@extends('layouts/default')

{{-- Web site Title --}}
@section('title')
    Contact Management
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Contact Managment</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> Contact</a></li>
        <li class="active">Contact List</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Contact List
                    </h4>                   
                </div>
                <br />
                <div class="panel-body">
                    @if ($contacts->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead>
                                <tr>                                    
                                    <th>ID</th>
                                    <th>Check Status</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Request Role</th>   
                                    <th>Created Time</th>                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($contacts as $contact)
                                <tr>                                    
                                    <td>{!! $contact->id !!}</td>
                                    <td>
                                        @if($contact->status == '0')       
                                            <i class="livicon" data-name="message-new" data-size="18"  data-c="#ef4422" data-hc="#ef4422"  
                                            data-loop="true" title="New Contact" style="position: relative;top:4px;"></i><span>New Contact</span> 
                                           
                                        @endif
                                        @if($contact->status == '1')                                                                           
                                            <i class="livicon" data-name="checked-on" data-size="18"  data-c="#0d9a28" data-hc="#0d9a28"  
                                            data-loop="true" title="checked" Contact" style="position: relative;top:4px;" ></i> <span>Checked contact</span> 
                                           
                                        @endif
                                        @if($contact->status == '2') Deleted @endif                                        
                                    </td>
                                    <td>{!! $contact->first_name !!}</td>
                                    <td>{!! $contact->last_name !!}</td>
                                    <td>{!! $contact->email !!}</td>
                                    <td>{!! $contact->phone !!}</td>
                                    <td>
                                        @if($contact->role == '2') School admin @endif
                                        @if($contact->role == '3') Technical Trainer @endif
                                        @if($contact->role == '4') Personal Trainer @endif
                                        @if($contact->role == '5') Etherity @endif
                                    </td>                                   
                                    <td>{!! $contact->created_at->diffForHumans() !!}</td>
                                    <td>
                                        <a href="{{URL::to('/')}}/contact/{{ $contact->id }}">
                                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit Contact"></i>
                                        </a>                                           
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        @lang('general.noresults')
                    @endif   
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<div class="modal fade" id="users_exists" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                @lang('groups/message.users_exists')
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>
@stop
