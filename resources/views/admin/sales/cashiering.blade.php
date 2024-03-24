@extends('admin.sales.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<style>
    table,#product-container{
        font-size:19px;
    }
    table td {
        text-align: right !important;
    }
</style>

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Cashiering</h3>
  <hr>
</div>
<!--
<div class="form-inline ml-0 mb-2 form-search-product ">
    <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" id="input-search-lrn" type="number" placeholder="Student LRN Number" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-dark btn-search-userid">
                    <i class="fas fa-search"></i>
                </button>
            </div>
     </div>
</div>-->

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
<style>
    thead{
        position: sticky;
        top: 0;
        background-color: #FFF;
        border-color: #C4BFC2;
        z-index: 999;
    }
</style>
  <div class="row mt-1">
    <div class="col-md-12 col-lg-12 mt-3">
      <div class="card">
          <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <div class="row mr-2">
                            <div class="col-12 tray-container" style="overflow-y: auto; height:auto;">
                                <table class="table responsive table-bordered table-hover tbl-tray" style="margin-bottom:20px;">
                                    <thead>
                                        <th width="150px">Product Code</th>
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th width='130px'>Amount</th>
                                        <th width="50px">Void</th>
                                    </thead>
                                    <tbody>
                                    </tbody>    
                                </table>
                                <div class="loader-container">
                                    <div class="lds-default" id="tray-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <table class="table responsive table-bordered" style=" margin-bottom:20px;">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th><input type="number" id="invoice-no" class="form-control" readonly></th>
                                    </tr>    
                                    <tr>
                                        <th>Tendered</th>
                                        <th>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text">&#8369</span>
                                                </div>
                                                <input  type="number" id="tendered" step=".01" class="form-control">
                                            </div>
                                        </th>
                                    </tr> 
                                    <tr>
                                        <th>Change</th>
                                        <th>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text">&#8369</span>
                                                </div>
                                                <input  type="number" id="change" step=".01" class="form-control" readonly>
                                            </div></th>
                                    </tr>  
                                </table>
                            </div>
                             @php
                                 $discount = \DB::table('discount')->first();
                             @endphp 
                             
                            

                             <div class="col-sm-12 ml-1 mt-1 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="cash-payment">
                                    <label class="form-check-label" for="exampleCheck1">Cash Payment</label>
                                </div>
                            </div>
                            <div class="col-sm-12 ml-1 mt-1 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="gcash-payment">
                                    <label class="form-check-label" for="exampleCheck1">GCash Payment</label>
                                </div>
                            </div>
                            <div class="col-sm-12 ml-1 mt-1 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="card-payment">
                                    <label class="form-check-label" for="exampleCheck1">Card Payment</label>
                                </div>
                            </div>
                            <div class="col-sm-12 ml-1 mt-1 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="paymaya-payment">
                                    <label class="form-check-label" for="exampleCheck1">Maya Payment</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-sm btn-success" id="proccess">Process</button>
                            </div>
                            <div class="col-sm-12 mt-3 img-gcash-qr">
                                <img width="300" height="300" src="{{asset('images/qr-vsc.jpg')}}" alt="">
                            </div>
                        </div>        
                    </div>

                    <div class="col-sm-12 col-md-5 border p-2">
                        <div class="row">
                            <div class="col-12">
                                <form action="">
                                    <div class="form-inline ml-0 mb-2 form-search-product float-right">
                                        <div class="input-group input-group-sm">
                                          <input class="form-control form-control-navbar" id="input-search-product" type="search" placeholder="Search product" aria-label="Search">
                                          <div class="input-group-append">
                                            <button class="btn btn-dark btn-search-product">
                                              <i class="fas fa-search"></i>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="loader-container">
                            <div class="lds-default" id="product-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                        </div>
                        <div class="row" id="product-container" style="overflow-y: auto; height:720px;">
                            
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

@include('admin.sales.modals')
@endsection