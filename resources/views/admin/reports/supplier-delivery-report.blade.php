@extends('admin.reports.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Supplier Delivery Report</h3>
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
        
        <div class="row mt-4 ml-2">
          <div class="col-12">

            <div class="float-left mt-2">
              Supplier
          </div>
          <select class="form-control w-auto m-1 float-left" id="supplier">
            @foreach ($supplier as $item)
            <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
         @endforeach
          </select>

            <div class="float-left mt-2 ml-3">
              Date
          </div>
          <input type="date" class="form-control w-auto float-left m-1" name="date_from" id="date_from" value="{{ date('Y-m-d') }}">
          <div class="float-left mt-2">
              -
          </div>
          <input data-column="9" type="date" class="form-control w-auto float-left m-1" name="date_to" id="date_to" value="{{ date('Y-m-d') }}">  
              <a class="btn btn-sm btn-outline-dark float-right m-1 btn-preview-supplier-delivery-report">Print Preview</a>
              <a class="btn btn-sm btn-outline-success float-right m-1 btn-download-supplier-delivery-report"><i class="fas fa-download"></i> Download PDF</a>
        </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover tbl-supplier-delivery-report">
                        <thead>
                            <tr>
                                <th>Delivery #</th>
                                <th>PO #</th>
                                <th>Product Code</th>     
                                <th>Name</th>   
                                <th>Supplier</th> 
                                <th>Unit</th>      
                                <th>Quantity Ordered</th>                              
                                <th>Quantity Delivered</th>   
                                <th>Date Recieved</th>
                                <th>Remarks</th>
                                <th>Archive</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- /.row (main row) -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Supplier Delivery</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-message"></p>
          <small class="validation-text text-danger"></small>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-outline-dark btn-confirm-supplier-delivery" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>


@endsection