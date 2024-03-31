@extends('admin.utilities.archive.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Archive</h3>
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
    

        <div class="row">

<div class="col-md-12 col-lg-12 mt-2">
    <div class="card">
        <div class="card-body">

            <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="salesreport-tab" data-toggle="tab" href="#salesreport" role="tab"
                        aria-controls="salesreport" aria-selected="true">Sales Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stockreport-tab" data-toggle="tab" href="#stockreport" role="tab"
                        aria-controls="stockreport" aria-selected="false">Stock Adjustment Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="supplierreport-tab" data-toggle="tab" href="#supplierreport" role="tab"
                        aria-controls="supplierreport" aria-selected="false">Supplier Delivery Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="replacementreport-tab" data-toggle="tab" href="#replacementreport" role="tab"
                        aria-controls="replacementreport" aria-selected="false">Product Replacement Report</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</a>
                  </li> -->

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="salesreport" role="tabpanel"
                    aria-labelledby="salesreport-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="sales-archive-table">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Invoice #</th>
                                <th>Product Code</th>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Payment method</th>
                                <th>Order from</th>
                                <th>Date time archived</th>
                                <th>Restore</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="stockreport" role="tabpanel" aria-labelledby="stockreport-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-stock-report">
                            <thead>
                                <tr>
                                <th>Product Code</th>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Quantity adjusted</th>
                                <th>Remarks</th>
                                <th>Date adjusted</th>
                                <th>Restore</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="supplierreport" role="tabpanel" aria-labelledby="supplierreport-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-supplier-archive">
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
                                    <th>Restore</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="replacementreport" role="tabpanel" aria-labelledby="replacementreport-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-replacement-archive">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Restore</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                    <div class="mt-4">
                      <table class="table table-hover" id="tbl-cancelled-order">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Phone number</th>
                                <th>Date Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    </div>
                  </div> -->
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

    <div class="modal fade" id="restoreModal-sales" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-message"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-outline-dark btn-confirm-restore-sales" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div> 

  <div class="modal fade" id="restoreModal-replacement" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-message"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-outline-dark btn-confirm-restore-replacement" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="restoreModal-stockadjustment" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-message"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-outline-dark btn-confirm-restore-stockadjustment" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="restoreModal-supplierdelivery" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-message"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-outline-dark btn-confirm-restore-supplierdelivery" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
@endsection