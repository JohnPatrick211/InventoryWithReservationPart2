@include('admin.header')

@include('admin.nav')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          
          <!-- ./col -->
          <div class="col-lg-3 col-6">
          <a href = "/customer-orders">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$orders_today}}</h3>


                <p>Orders Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
            </div>
            </a>
          </div>
     
          <!-- ./col -->
          
          <div class="col-lg-3 col-6">
          <a href = "reports/sales">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>₱{{number_format($online_sales,2,".",",")}}</h3>

                <p>Sales For Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
            </a>
          </div>

          <div class="col-lg-3 col-6">
          <a href = "purchase-order">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3 class="text-white">{{$reorder_count}}</h3>

                <p class="text-white">Reorder</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
            </a>
          </div>

          <div class="col-lg-3 col-6">
          <a href = "/customer-orders">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="text-white">{{$reservation}}</h3>

                <p class="text-white">Reservation</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
            </a>
          </div>
         
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2023 {{ Session::get('cms_name')}}.</strong>
    All rights reserved.
  </footer>

</div>
<!-- ./wrapper -->

@include('admin.scripts')
@include('admin.datatables-scripts')
<script src="{{asset('js/verify_customer.js')}}"></script>

</body>
</html>
