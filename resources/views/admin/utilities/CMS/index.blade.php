@extends('admin.utilities.CMS.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

    <div class="page-header mb-3">
        <h3 class="mt-2" id="page-title">CMS Maintenance</h3>
        <hr>
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
        <div class="col-sm-12 col-md-8">
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> </h5>
            {{ \Session::get('success') }}
            </div>
        </div>
       
        @endif


        <div class="row">

          <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cms.update',$cms->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mt-2">
                                <label class="col-form-label">System Name</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                          <span class="fas fa-percent"></span>
                                        </div>
                                      </div>
                                    <input name="name" type="text" class="form-control" value="{{ $cms->name }}">
                                  </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mt-2">
                                <label class="col-form-label">Address</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                          <span class="fas fa-percent"></span>
                                        </div>
                                      </div>
                                    <input name="address" type="text" class="form-control" value="{{ $cms->address }}">
                                  </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mt-2">
                                <label class="col-form-label">Upload Logo</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                          <span class="fas fa-money-bill"></span>
                                        </div>
                                      </div>
                                      <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="logo" enctype="multipart/form-data">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                      </div>
                                  </div>
                            </div>

                            <div class="col-sm-12"><hr></div>

                            <div class="col-sm-12 col-md-6 mt-2">
                              <label class="col-form-label">Theme Color</label>
                              <div class="input-group">
                                  <div class="input-group-append">
                                      <div class="input-group-text">
                                        <span class="fas fa-percent"></span>
                                      </div>
                                    </div>
                                  <input name="theme_color" type="text" class="form-control" value="{{ $cms->theme_color }}">
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mt-2">
                              <label class="col-form-label">Admin Background Picture</label>
                              <div class="input-group">
                                  <div class="input-group-append">
                                      <div class="input-group-text">
                                        <span class="fas fa-percent"></span>
                                      </div>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="input2" name="bg_admin_login" enctype="multipart/form-data">
                                        <label class="custom-file-label" id="inputlabel2" for="exampleInputFile">Choose file</label>
                                      </div>
                                </div>
                            </div>
    
                              <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-sm btn-primary mr-2" id="btn-add-user">Save changes</button>
                              </div>
                              
                
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
      </div>
    </section>

@endsection