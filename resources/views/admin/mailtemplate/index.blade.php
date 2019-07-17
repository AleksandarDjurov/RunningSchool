@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mailtemplate List
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
        .wrapper.wrapper-content{
            padding: 0px !important;
            padding-top:40px !important;
        }
        .container{
            padding:0px !important;
        }
        .tag {
            border: 1px solid #1ab394;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            display: block;
            float: left;
            padding: 0px 5px;
            margin-right:3px;
            text-decoration: none;
            background: #1ab394;
            color: #fff;
            font-family: helvetica;
            font-size: 13px;
            margin-left: 0em !important;
        }
    </style>
@stop

@section('content')
<section class="content-header">
    <h1>Mail templates</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="#"> Mailtemplate</a></li>
        <li class="active">Mailtemplate List</li>
    </ol>
</section>
<section class="content paddingleft_right15">
    <div class="row">           
        <div class="panel panel-primary">
            <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="mail1" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Mail Template
                    <a href="/mailtemplate/new" class="btn btn-sm btn-success pull-right" style="margin-top:-5px;">Create New Template</a>
                    </h4>                    
            </div>
            <br />           
            <div class="panel-body">
                <div class="table-responsive users-table">
                    <table class="table table-striped table-condensed data-table">
                        <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Email Type</th>
                            <th>Subject</th>
                            <th>From</th>
                            <th style="width:40%">Content</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $template)
                            <?php
                            /*if($template->catname == 'ワクチンQ&A'){
                                $template->catname = 'その他の情報';
                            }*/
                            ?>
                            <tr>
                                <td>{{$template->mailname}}</td>
                                <td>{{$template->subject}}</td>
                                <td>{{$template->sender}}</td>
                                <td>{!! $template->content !!}</td>
                                <td>
                                    <a class="btn btn-sm btn-info btn-block" href="/mailtemplate/{{ $template->id }}/edit">
                                        <i class="fa fa-pencil fa-fw" aria-hidden="true"></i>
                                        <span>Edit</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>           
    </div>
</section>
@endsection

@section('footer_scripts')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.tooltips')
    <script>
    </script>
@endsection