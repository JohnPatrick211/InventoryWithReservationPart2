@extends('admin.inventory.product-management.layout')

@section('content')

@php
$page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Product Management</h3>
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

    

        <div class="row">

          <div class="col-md-12 col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover tbl-product-management" id="product-table-management">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Reorder point</th>
                                <th>Unit</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Original Price</th>
                                <th>Selling Price</th>
                                <th>Edit</th>
                            </tr>
                            <tr>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
                            </td></td>
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

    <!--Confirm Modal-->

@endsection