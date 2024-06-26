@extends('admin.reports.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Purchased Order Report</h3>
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
              <a class="btn btn-sm btn-outline-dark float-right m-1 btn-preview-purchased-order-report">Print Preview</a>
              <a class="btn btn-sm btn-outline-success float-right m-1 btn-download-purchased-order-report"><i class="fas fa-download"></i> Download PDF</a>
        </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover tbl-purchased-order-report">
                        <thead>
                            <tr>
                              <th>PO #</th>
                              <th>Product Code</th>     
                              <th>Name</th>   
                              <th>Supplier</th>  
                              <th>Unit</th>                                 
                              <th>Quantity Order</th>        
                              <th>Amount</th>
                              <th>Date Order</th>
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


@endsection