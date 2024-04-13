@extends('admin.inventory.supplier-delivery.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header">
  <h3 class="mt-2" id="page-title">Supplier Delivery</h3>
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

                <div class="col-12"> <div class="float-left mt-2 ml-3">
                Product
            </div>
            <select class="form-control w-auto m-1 float-left" id="inv_product">
                @foreach ($product as $item)
                <option value="{{ $item->product_ids }}">{{ $item->description }}</option>
            @endforeach
            </select>

            <div class="float-left mt-1 ml-3">
            <p>Reservation Qty: <span style="font-size: 21px;"> <b id="txt-total-sales"></b></span></p>
        </div>

        </div>
        

          <div class="col-md-12 col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item">
                      <a class="nav-link active" id="po-tab" data-toggle="tab" href="#potab" role="tab" aria-controls="purchased" aria-selected="true">Purchased Orders   
    
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="pa-tab" data-toggle="tab" href="#patab" role="tab" aria-controls="partial" aria-selected="true">Remaining Orders  
    
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="pen-tab" data-toggle="tab" href="#pentab" role="tab" aria-controls="pending" aria-selected="true">Pending Orders  
    
                      </a>
                    </li>
    
                    <li class="nav-item">
                      <a class="nav-link" id="delivered-tab" data-toggle="tab" href="#deliveredtab" role="tab" aria-controls="delivered" aria-selected="true">Delivered Products   
    
                      </a>
                    </li>
     
                  </ul>   
    
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="potab" role="tabpanel" aria-labelledby="po-tab">
                      

                      <div class="row mt-4 ml-2">
                        <div class="mt-2">
                          Supplier
                        </div>  
                        
                        <div class="col-sm-2 mb-3">
                          
                          <select class=" form-control col-sm-12 ml-2" id="po_supplier">
                          <option value="All">All Suppliers</option>
                            @foreach ($supplier as $item)
                               <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
                            @endforeach
                          </select>
                          </div> 
              
                        <div class="mt-2 ml-3">
                          Date
                          </div>              
                        
                        <div class="col-sm-2 mb-3">
                          <input data-column="9" type="date" class="form-control" id="po_date_from" value="{{ date('Y-m-d') }}">
                          </div>
              
                          <div class="mt-2">
                            -
                            </div>
              
                          <div class="col-sm-2 mb-3">
                            <input data-column="9" type="date" class="form-control" id="po_date_to" value="{{ date('Y-m-d') }}">
                            </div>  
              
                     </div>

    
                      <table class="table responsive  table-hover mt-2" id="po-table" width="100%">       
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
                              <th>Status</th>
                              <th>Add</th>
                          </tr>
                      </thead>
                      
                      </table> 
                      </div>

                      <div class="tab-pane fade" id="patab" role="tabpanel" aria-labelledby="pa-tab">
    
                        <div class="row mt-4 ml-2">
                          <div class="mt-2">
                            Suppliered
                          </div>  
                          
                          <div class="col-sm-2 mb-3">
                            
                            <select class=" form-control col-sm-12 ml-2" id="pa_supplier">
                            <option value="All">All Supplier</option>
                              @foreach ($supplier as $item)
                                 <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
                              @endforeach
                            </select>
                            </div> 
                
                          <div class="mt-2 ml-3">
                            Date
                            </div>              
                          
                          <div class="col-sm-2 mb-3">
                            <input data-column="9" type="date" class="form-control" id="pa_date_from" value="{{ date('Y-m-d') }}">
                            </div>
                
                            <div class="mt-2">
                              -
                              </div>
                
                            <div class="col-sm-2 mb-3">
                              <input data-column="9" type="date" class="form-control" id="pa_date_to" value="{{ date('Y-m-d') }}">
                              </div>  
                
                        </div>
  
    
                            <table class="table responsive  table-hover" id="pa-table" width="100%">       
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
                                    <th>Add</th>
                                </tr>
                            </thead>
                            <tbody>
                                                  
                           </tbody>
                            
                            </table> 
                           
                          </div>

                          <div class="tab-pane fade" id="pentab" role="tabpanel" aria-labelledby="pen-tab">
    
                          <div class="row mt-4 ml-2">
                            <div class="mt-2">
                              Suppliered
                            </div>  
                            
                            <div class="col-sm-2 mb-3">
                              
                              <select class=" form-control col-sm-12 ml-2" id="pen_supplier">
                              <option value="All">All Supplier</option>
                                @foreach ($supplier as $item)
                                  <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
                                @endforeach
                              </select>
                              </div> 

                            <div class="mt-2 ml-3">
                              Date
                              </div>              
                            
                            <div class="col-sm-2 mb-3">
                              <input data-column="9" type="date" class="form-control" id="pen_date_from" value="{{ date('Y-m-d') }}">
                              </div>

                              <div class="mt-2">
                                -
                                </div>

                              <div class="col-sm-2 mb-3">
                                <input data-column="9" type="date" class="form-control" id="pen_date_to" value="{{ date('Y-m-d') }}">
                                </div>  

                          </div>


                              <table class="table responsive  table-hover" id="pen-table" width="100%">       
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
                                    <th>Update</th>
                                    <th>Remarks</th>
                                  </tr>
                              </thead>
                              <tbody>
                                                    
                            </tbody>
                              
                              </table> 
                            
                            </div>     
                      
                      <div class="tab-pane fade" id="deliveredtab" role="tabpanel" aria-labelledby="delivered-tab">
    
                        <div class="row mt-4 ml-2">
                          <div class="mt-2">
                            Suppliered
                          </div>  
                          
                          <div class="col-sm-2 mb-3">
                            
                            <select class=" form-control col-sm-12 ml-2" id="sd_supplier">
                            <option value="All">All Supplier</option>
                              @foreach ($supplier as $item)
                                 <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
                              @endforeach
                            </select>
                            </div> 
                
                          <div class="mt-2 ml-3">
                            Date
                            </div>              
                          
                          <div class="col-sm-2 mb-3">
                            <input data-column="9" type="date" class="form-control" id="sd_date_from" value="{{ date('Y-m-d') }}">
                            </div>
                
                            <div class="mt-2">
                              -
                              </div>
                
                            <div class="col-sm-2 mb-3">
                              <input data-column="9" type="date" class="form-control" id="sd_date_to" value="{{ date('Y-m-d') }}">
                              </div>  
                
                        </div>
  
    
                            <table class="table responsive  table-hover" id="sd-table" width="100%">       
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
                                    <th>Updated At</th>         
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                                  
                           </tbody>
                            
                            </table> 
                           
                          </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row (main row) -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



<div class="modal fade bd-example-modal-lg" id="ajustQtyModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Adjust Quantity</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Product Code</label>
                        <input type="text" class="form-control" name="product_code" id="product_code" readonly>
                    </div>
                    
                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Name</label>
                        <input type="text" class="form-control" name="description" id="description" readonly>
                    </div>

                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Qty</label>
                        <input type="number" step=".01" class="form-control" name="qty" id="qty" readonly>
                    </div>

                    <div class="col-sm-12 col-md-6 mt-2">
                        <label class="col-form-label">Qty to adjust</label>
                        <input type="number" step=".01" class="form-control" name="qty_to_adjust" id="qty_to_adjust">
                    </div>

                    <div class="col-sm-12 col-md-6  mt-2">    
                        <label class="col-form-label">Remarks</label>
                        <select class="form-control" name="remarks" id="remarks">
                            <option value="Physical count descrepancy">Physical count descrepancy</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Owner used">Owner used</option>
                        </select>
                    </div>

                    
                    <div class="col-sm-12 col-md-6 col-lg-4 mt-2"> 
                        <label class="col-form-label">Action</label>   
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rdo-addless" id="add" value="add" checked required>
                            <label class="form-check-label" for="add">
                              Add
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="rdo-addless" id="less" value="less" required>
                            <label class="form-check-label" for="less">
                              Less
                            </label>
                          </div>
                    </div>

                </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm btn-success btn-confirm-adjust" type="button">Adjust</button>
            <button class="btn btn-sm" data-dismiss="modal">Cancel</button>
        </div>
    </div>
  </div>
</div>

@include('admin.inventory.supplier-delivery.modals')
@endsection