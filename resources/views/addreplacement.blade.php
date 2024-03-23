
@php
$page_title =  Session::get('cms_name') . "| Add Product Replacement";
@endphp

@include('header')

<!-- Navbar -->
@include('nav')
<!-- /.navbar -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

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

  <!-- Main content -->
  <main class="d-flex align-items-center py-3 py-md-0">
      <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                    <h3>Create Product Replacement Request</h3>

                    <div class="row">

                        <div class="col-md-12 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ url('/storereplacementrequest') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                    <div class="col-sm-12 col-md-6">    
                                            <label class="col-form-label">Product Name</label>
                                            <select class="form-control" name="product_name" id="product_name">
                                            @foreach ($product as $item)
                                            <option value="{{$item->product_id}}">{{$item->description}}</option>
                                            @endforeach
                                            </select>
                                            </div>

                                            <div class="col-sm-12 col-md-6">
                                                <label class="col-form-label">Upload Photo</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                        <span class="fas fa-money-bill"></span>
                                                        </div>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="receipt" enctype="multipart/form-data">
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6">    
                                            <label class="col-form-label">Qty</label>
                                            <input  type="number" step="1" name="qty" id="qty" class="form-control" required>
                                            </div>

                                        <div class="col-sm-12 col-md-12">
                                            <label class="col-form-label">Reason for Product Replacement</label>
                                            <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                                        </div>
                                            


                                            <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-sm btn-primary mr-2" id="btn-add-user">Save</button>
                                            <a href="{{ url('/replacement') }}" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</a>
                                            </div>
                                            
                            
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>

                        
                     
                </div>
              </div>
            </div>
      </div>
    </main>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('footer')
<script>

$(".custom-file-input").on("change", function() {
var fileName = $(this).val().split("\\").pop();
$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>


