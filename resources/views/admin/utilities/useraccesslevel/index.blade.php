@extends('admin.utilities.useraccesslevel.layout')

@section('content')

@php
$page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
    <h3 class="mt-2" id="page-title">Access Level Maintenance</h3>
    <hr>
</div>

@if(count($errors) > 0)
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
@endif

<form action="{{ route('useraccesslevels.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <label class="col-form-label">Access Level</label>
            <input type="text" class="form-control" name="description"  id="description" required>
        </div>
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-sm btn-primary mr-2" id="btn-add-user">Add Access Level</button>
        </div>
    </div>
</form>
        
<div class="row">
    <div class="col-md-12 col-lg-12 mt-3">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover tbl-user" id="unit-table">
                    <tr>
                        <th class="py-2 text-left">Description</th>
                        <th class="py-2 text-left">Status</th>
                    </tr> 
                    @foreach($useraccesslevels as $useraccesslevel)
                    <tr>
                        <td>{{ $useraccesslevel->description }}</td>
                        <td>{{ $useraccesslevel->is_active == true ? "Active" : "Inactive" }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@endsection