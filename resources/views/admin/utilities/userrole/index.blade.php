@extends('admin.utilities.userrole.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
    <h3 class="mt-2" id="page-title">User Role Maintenance</h3>
    <hr>
</div>

@if(count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

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
@endif

<div class="row">
    <div class="col-md-12 col-lg-12 mt-3">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover tbl-user" id="unit-table">
                    <tr>
                        <th class="py-2 text-left">Description</th>
                        <th class="py-2 text-left">List of Module</th>
                        <th class="py-2 text-left">Status</th>
                        <th class="py-2 text-left">Action</th>
                    </tr>
                    @foreach($userroles as $userrole)
                        <tr>
                            <td>{{ $userrole->ur_description }}</td>
                            <td>@foreach($userrolemenus as $userrolemenu )
                                @if($userrole->id == $userrolemenu->urm_user_role_id)
                                    {{ $userrolemenu->um_title }} <br />
                                @endif
                    @endforeach
                    </td>
                    <td>{{ $userrole->ur_is_active == true ? "Active" : "Inactive" }}
                    </td>
                    <td>
                        <a class="btn" href="{{ route('user-role.edit',$userrole->id) }}"><i
                                class="fas fa-edit"></i></a>
                    </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
