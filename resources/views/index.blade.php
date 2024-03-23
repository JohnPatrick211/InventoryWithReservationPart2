@php
  $page_title =  Session::get('cms_name');
@endphp

@include('header')

  <!-- Navbar -->
@include('nav')
  <!-- /.navbar -->

  <style>

    .btn-success, .btn-primary {
      background-color: {{Session::get('cms_theme_color')}} !important;
      border: #06513D !important;
    }

    .btn-outline-success {
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <div ></div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" style="margin-top: -38px">
      <div class="container">
        <div class="row banner" style="background-color: #06513D">
          <!-- <div class="col-md-12 col-lg-3 mt-5 p-3">
            <h5 class="text-light">Exclusive Sales</h5>
            <h4 class="text-light">POWER TOOL AND
                ACCESORIES
                UP TO 50% OFF</h4>
          </div>
          <div class="col-md-12 col-lg-9">
            <img class="cover"  src="{{asset('images/tool-g98333b673_1280.jpg')}}">
          </div> -->
        </div>
        
        <!-- <div class="row pt-2 pb-2" style="background-color: #EFF6EC">
          <div class="col-md-12 text-center">
            <div class="text-muted text-bold">FOR RELIABLE AND QUICK DELIVERY, CHOOSE VAL CONSTRUCTION SUPPLY</div>
          </div>
          <div class="col-sm-12 hidden-xs hidden-sm" id="a1b19e1a">
            <div class="row text-center">
              <div class="col-md-4">
                <img src="https://getmeds.ph/public/front/images/genuine-medicines.png" alt="Genuine Medicines">
                <div class="text-muted">Genuine Materials</div>
              </div>
              <div class="col-md-4">
                <img src="https://getmeds.ph/public/front/images/timely-delivery.png" alt="Timely Delivery">
                <div class="text-muted">Timely Delivery</div>
              </div>
              <div class="col-md-4">
                <img src="https://getmeds.ph/public/front/images/secure-payments.png" alt="Secure Payments">
                <div class="text-muted">Secure Payments</div>
              </div>
            </div>
          </div>
        </div> -->

        <!-- Search bar section wrapped in a container -->
        <div class="container rounded-pill" style="background-color: #F8F9FA; padding-top: 20px;">
          <div class="form-inline d-flex justify-content-center mb-4 form-search-product">
            <div class="input-group input-group-lg">
              <input class="form-control form-control-lg" id="input-search-product" type="search" placeholder="Search product..." aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-lg btn-search-product" type="button">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <h4 class="text-center mt-4 text-dark">Categories:</h4>
        <div class="row pl-3 pr-3 pt-1 pb-1 category-container">
          <a class="col-xs-6 col-sm-4 col-md-3 text-center" href="{{ url('/') }}">
            <div class="text-bold text-muted category-name" data-name="All Products" >
              All Products
            </div> 
          </a>
          @foreach ($categories as $item)
            <a class="col-xs-6 col-sm-4 col-md-3 text-center" href="{{ url('/#a1b19e1a') }}">
              <div class="text-bold text-muted category-name"  data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                {{ $item->name }}
              </div> 
            </a>
          @endforeach
        </div>
         
        <h4 class="text-center mt-4 text-dark" id="product-heading">All Products</h4>

        <hr>

        <div class="loader-container">
          <div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>

        <div class="row" id="product-container" style="min-height: 700px;"></div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@include('footer')
