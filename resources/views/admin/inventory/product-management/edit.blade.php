@extends('admin.inventory.product-management.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

    <div class="page-header mb-3">
        <h3 class="mt-2" id="page-title">Update Product</h3>
        <hr>
        <a href="{{ route('product-management.index') }}" class="btn btn-secondary btn-sm"><span class='fas fa-arrow-left'></span></a>
    </div>

        @if(count($errors)>0)
        <div class="row">
            <div class="col-sm-12 col-md-12">
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
        <div class="col-sm-12 col-md-12">
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> </h5>
            {{ \Session::get('success') }}
          </div>
        </div>
       
        @endif


        <div class="row">

          <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('product-management.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                        <input type="hidden" class="form-control" name="status" value="1" readonly>
                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                                <label class="col-form-label">Name</label>
                                <input type="text" class="form-control" name="description" value="{{ $product->description }}" readonly>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Quantity</label>
                              <input type="text" class="form-control" name="qty" value="{{ $product->qty }}" required>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Average Daily Unit Sales</label>
                              <input type="text" class="form-control" name="avg" value="" required>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Lead Days</label>
                              <input type="text" class="form-control" name="lead_days" value="" required>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Safety Stocks</label>
                              <input type="text" class="form-control" name="safety_stocks" value="" required>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Reorder point</label>
                              <input type="text" class="form-control" name="reorder" value="{{ $product->reorder }}" required>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Original Price</label>
                              <input type="number" step=".01" class="form-control" name="orig_price" id="orig_price" value="{{ $product->orig_price }}">
                            </div>
        
                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                              <label class="col-form-label">Markup</label>
                              <input type="number" step=".01" class="form-control" name="markup" id="markup" min="0" value="{{ $product->markup }}">
                            </div>
        
                            <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                                <label class="col-form-label">Selling Price</label>
                                <input type="number" step=".01" class="form-control" name="selling_price" id="selling_price"  value="{{ $product->selling_price }}" readonly>
                            </div>


                            <div class="col-sm-12 col-md-6 col-lg-4 mt-4">
                              @if ($product->image)
                                <img src="{{asset('images/'.$product->image)}}" width="350" height="350" class="img-thumbnail" alt="">
                              @else
                                <img src="{{asset('images/no-image.png')}}" width="350" height="350" class="img-thumbnail" alt="">
                              @endif
                            </div>
    
                              <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-sm btn-primary mr-2" id="btn-add-user">Save changes</button>
                                <a href="{{ route('product-management.index') }}" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</a>
                              </div>
                              
                
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
      </div>
    </section>

@endsection