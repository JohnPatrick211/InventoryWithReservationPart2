
@php
$page_title =  Session::get('cms_name') . "| My Replacement";
@endphp

<style>

.disabled{
    pointer-events:none; //This makes it not clickable
    opacity:0.6; 
}

</style>
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
                    <h3>My Product Replacement Request</h3>
                    <a href="{{url('/create_request')}}" class="btn btn-primary btn-sm"><span class='fa fa-plus'></span> Create Request</a>

                        <div class="row">

                        <div class="col-md-12 col-lg-12 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped table-hover tbl-replacement" id="replacement-table">
                                      <thead>
                                          <tr>
                                              <th class="text-left">ID</th>
                                              <th class="text-left">Product Name</th>
                                              <th class="text-left">Qty</th>
                                              <th class="text-left">Replacement Reason</th>
                                              <th class="text-left">Status</th>
                                              <th class="text-left">Action</th>
                                          </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        
                     
                </div>
              </div>
            </div>
      </div>
    </main>
  <!-- /.content -->
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
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
          <button class="btn btn-sm btn-outline-dark btn-confirm-archive" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
<!-- /.content-wrapper -->

@include('footer')

<!-- jQuery -->

<!-- overlayScrollbars -->
<!-- AdminLTE App -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('js/replacement.js')}}"></script>


