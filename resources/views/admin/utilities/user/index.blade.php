@extends('admin.utilities.user.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">User Maintenance</h3>
          <hr>
      </div>

        @if(count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
    
                <li>{{$error}}</li>
                    
                @endforeach
            </ul>
        </div>
        @endif
    
        @if(\Session::has('success'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h5><i class="icon fas fa-check"></i> </h5>
          {{ \Session::get('success') }}
        </div>

        @if(\Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h5><i class="icon fas fa-check"></i> </h5>
          {{ \Session::get('error') }}
        </div>

       
        @endif

        

        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><span class='fa fa-plus'></span> Create User</a>
        <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal"><span class='fa fa-file-excel'></span> Import</a>
        <a class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#exportModal"><span class='fa fa-file-export'></span> Export</a>

        <div class="row">

          <div class="col-md-12 col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover tbl-user" id="unit-table">
                        <tr>
                            <th class="py-2 text-left">Name</th>
                            <th class="py-2 text-left">Email</th>
                            <th class="py-2 text-left">Access Code</th>
                            <th class="py-2 text-left">Access Level</th>
                            <th class="py-2 text-left">Status</th>
                            <th class="py-2 text-left">Edit and Archive</th>
                        </tr>
                        @foreach ($user as $users)
                        <tr>
                            <td>{{ $users->name }}</td>
                            <td>{{ $users->email }}</td>
                            
                            <!-- @php
                            $access_level = "";
                                switch($users->access_level) {
                                    case 1:
                                        $access_level = "Assistant Proware Specialist";
                                        break;
                                    case 2:
                                        $access_level = "Proware Specialist";
                                        break;
                                    case 3:
                                        $access_level = "System Administrator";
                                        break;
                                    case 4:
                                        $access_level = "Student";
                                        break;
                                }
                            @endphp
                                <td>{{ $access_level }}</td> -->
                                @php
                                $access_level = "";
                                switch($users->access_level) {
                                    case 1:
                                        $access_level  = "ADM-001";
                                        break;
                                    case 2:
                                        $access_level  = "PRO-002";
                                        break;
                                    case 3:
                                        $access_level  = "ASS-003";
                                        break; 
                                    case 4:
                                        $access_level  = "STU-004";
                                        break;       
                                }
                            @endphp
                                <td> {{$access_level}}</td>
                               
                                <td> @foreach($userroles as $userrole){{ $users->access_level == $userrole->id ? $userrole->ur_description : '' }}  @endforeach</td>
                              
                                @php
                            $status = "";
                                switch($users->status) {
                                    case 0:
                                        $status = "Inactive";
                                        break;
                                    case 1:
                                        $status = "Active";
                                        break;
                                }
                            @endphp
                                <td>{{ $status }}</td>
                                <td>
                                    <a class="btn" href="{{ route('users.edit',$users->id) }}"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-archive-user" data-id="{{ $users->id }}"><i class="fas fa-archive"></i></button>   
                                </td>
                        </tr>
                        @endforeach
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $user->links() !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row (main row) -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    @include('admin.utilities.user.modals')

@endsection