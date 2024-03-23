@extends('admin.utilities.user.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

    <div class="page-header mb-3">
        <h3 class="mt-2" id="page-title">Update User</h3>
        <hr>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><span class='fas fa-arrow-left'></span></a>
    </div>

        @if(count($errors)>0)
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
            
                        <li>{{$error}}</li>
                            
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    
        @if(\Session::has('success'))
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> </h5>
                    {{ \Session::get('success') }}
                </div>
            </div>
        </div>

       
        @endif


        <div class="row">

          <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update',$user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="col-form-label">Name</label>
                                <input type="text" class="form-control" name="name"  id="name" value="{{ $user->name }}" required>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label class="col-form-label">Email</label>
                                <input type="email" class="form-control" name="email"  id="email" value="{{ $user->email }}" required>
                            </div>
                
                              <div class="col-sm-12 col-md-6 mb-2">    
                                <label class="col-form-label">Access Level</label>
                                <select class="form-control" name="access_level" id="access_level">
                                    @foreach($userroles as $userrole)
                                        <option value="{{$userrole->id}}" {{ $user->access_level == $userrole->id ? 'selected' : '' }}>{{$userrole->ur_description}}</option>
                                    @endforeach
                                </select>
                              </div>
                
                
                              <div class="col-sm-12 col-md-6">
                                <label class="col-form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}" required>
                              </div>

                              <div class="col-sm-12 col-md-6 mb-2">    
                                <label class="col-form-label">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                </select>
                              </div>

                              <div class="col-sm-12 col-md-6 new-password-container d-none">
                                <label class="col-form-label">New password</label>
                                <input type="password" class="form-control" name="password" id="password">
                              </div>

                              <div class="col-sm-12 mt-2 new-password-container d-none">
                                <a class="btn btn-sm btn-default" id="cancel">Cancel</a>
                              </div>
                              
                              <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-sm btn-success mr-2" id="btn-add-user">Save changes</button>
                                <a class="btn btn-sm btn-primary" id="btn-change-password">Change password</a>
                              </div>
                              
                
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
      </div>
    </section>

@endsection