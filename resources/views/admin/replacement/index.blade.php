@extends('admin.replacement.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Product Replacement</h3>
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
        @endif

        <div class="row">

            <div class="col-md-12 col-lg-12 mt-2">
                <div class="card">
                  <div class="card-body">


                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                        <li class="nav-item">
                          <a class="nav-link  active" id="unverified-tab" data-toggle="tab" href="#reordertab" role="tab" aria-controls="contact" aria-selected="true">Pending   
        
                          </a>
                        </li>
        
                        <li class="nav-item">
                          <a class="nav-link" id="verified-tab" data-toggle="tab" href="#orderstab" role="tab" aria-controls="contact" aria-selected="true">Approved   
        
                          </a>
                        </li>
         
                      </ul>
        
                      <div class="tab-content mt-5" id="myTabContent">
                        <div class="tab-pane fade active show" id="reordertab" role="tabpanel" aria-labelledby="unverified-tab">
                          
                          <table class="table table-hover tbl-unverified-users">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Verify</th>
                                </tr>
                            </thead>
                        </table>
                            
                          </div>
        
                          <div class="tab-pane fade" id="orderstab" role="tabpanel" aria-labelledby="verified-tab">
        
        
                              <div class="row mt-4 ml-1">
        
                                  <div class="col-12">        
                                    <table class="table responsive table-hover tbl-verified-users" width="100%">       
                                      <thead>
                                        <tr>
                                        <th>ID</th>
                                        <th>Student Name</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>View</th>
                                        </tr>
                                    </thead>
                                    
                                    </table> 
                                  </div>
        
                               </div>
                              </div>
        
                        </div>
        
                          </div>
                        </div>
                        
                    </div>

                  </div>
                </div>

            </div>


        <!-- /.row (main row) -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



<div class="modal fade bd-example-modal-lg" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Verify Product Replacement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Student Name</label>
                        <input type="text" class="form-control" name="studentname" id="studentname" readonly>
                    </div>
                    
                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Product Name</label>
                        <input type="text" class="form-control" name="producname" id="productname" readonly>
                    </div>

                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Qty</label>
                        <input type="text" class="form-control" name="qty" id="qty" readonly>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                      <label class="col-form-label">Status</label>
                      <input type="text" class="form-control" name="status" id="status" readonly>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-12 mt-2">
                      <label class="col-form-label">Reason for Product Replacement</label>
                      <textarea class="form-control" name="reason" id="reason" rows="3" readonly></textarea>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-12 mt-2">
                      <label class="col-form-label">Reason for Product Replacement</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                    </div>

                    <div class="col-sm-12 mt-2">
                      <label class="col-form-label">Image Receipt</label>
                      <img type="text" class="img-thumbnail" width="100%" name="receipt" id="receipt">
                    </div>
                    

                </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm btn-success" id="btn-approve" type="button">Approve</button>
            <button class="btn btn-sm btn-danger" id="btn-reject" type="button">Reject</button>
            <button class="btn btn-sm" data-dismiss="modal">Cancel</button>
        </div>
    </div>
  </div>
</div>

@endsection