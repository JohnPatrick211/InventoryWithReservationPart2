  <style>
       .main-footer {
      background-color: {{Session::get('cms_theme_color')}} !important;
      color: #FFF !important;
    }

    .sd{

    }
  </style>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <main class="d-flex align-items-center py-3 py-md-0">
      <div class="container">
        <div class="row text-center">
          
          <div class="col-sm-12 col-md-6">
            <small><a style="color: #FFF;" target="_blank" href="{{ url('/terms-and-condition') }}">Terms and condition</a></small>
          </div>
          <div class="col-sm-12 col-md-6">
            <small><a style="color: #FFF;" target="_blank" href="{{ url('/privacy-policy') }}">Privacy Policy</a></small>
          </div>
          <div class="col-sm-12 col-md-6">
            <small><a style="color: #FFF;" target="_blank" href="{{ url('/return-and-cancellation-policy') }}">Return and Cancellation Policy</a></small>
          </div>
          <!-- <div class="col-sm-12 col-md-6">
            <small><a style="color: #FFF;" target="_blank" href="{{ url('/terms-and-condition') }}">About us</a></small>
          </div> -->
          <!-- <div class="col-sm-12 col-md-6">
            <small><a style="color: #FFF;" target="_blank" href="{{ url('/we-deliver') }}">We deliver in your Area!</a></small>
          </div> -->
          
          <!-- <div class="col-sm-12 col-md-6">
            <small><a style="color: #FFF;" target="_blank" href="{{ url('/terms-and-condition') }}">Contact us</a></small>
          </div> -->
          <div class="col-sm-12 col-md-6">
            <small><strong>Copyright &copy; 2023 {{ Session::get('cms_name')}}</strong></small>
          </div>
        </div>
      </div>
    </main>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{asset('js/homepage.js')}}"></script>

@if(strpos($page_title,"Login") != "")
  <script src="{{asset('js/login.js')}}"></script>
@endif

@include('scripts._global')

</body>
</html>