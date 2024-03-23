
@include('admin.header')

@include('admin.navreports')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        @yield('content')
        
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


  <script src="{{asset('js/sales_report.js')}}"></script>


  <script src="{{asset('js/stock_adjustment_report.js')}}"></script>


  <script src="{{asset('js/inventory_report.js')}}"></script>


  <script src="{{asset('js/purchased_order_report.js')}}"></script>


  <script src="{{asset('js/supplier_delivery_report.js')}}"></script>
  

  <script src="{{asset('js/reorder_report.js')}}"></script>
    

  <script src="{{asset('js/fast_and_slow_report.js')}}"></script>

  <script src="{{asset('js/reservation_report.js')}}"></script>




</body>
</html>
