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
                    <a class="nav-link" id="pre-order-tab" data-toggle="tab" href="#pre-order" role="tab"
                        aria-controls="pre-order" aria-selected="true">Inventory Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab"
                        aria-controls="pending" aria-selected="false">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="prepared-tab" data-toggle="tab" href="#prepared" role="tab"
                        aria-controls="prepared" aria-selected="false">Prepared</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="shipped-tab" data-toggle="tab" href="#shipped" role="tab"
                        aria-controls="shipped" aria-selected="false">Pick-Up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab"
                        aria-controls="completed" aria-selected="false">Completed</a>
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
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade show" id="pre-order" role="tabpanel"
                    aria-labelledby="pre-order-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-pre-order">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Pre-Order Date</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-pending-order">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Date Order</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="prepared" role="tabpanel" aria-labelledby="prepared-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-prepared-order">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Date Order</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-shipped-order">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Date Order</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="mt-4">
                        <table class="table table-hover" id="tbl-completed-order">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Date Order</th>
                                    <th>View</th>
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

    

  <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog">
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
          <button class="btn btn-sm btn-outline-dark btn-confirm-restore" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
@endsection