
@php
$page_title =  Session::get('cms_name') . "| My Account";
@endphp

@include('header')

<!-- Navbar -->
@include('nav')
<!-- /.navbar -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <main class="d-flex align-items-center py-3 py-md-0">
      <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                    <h3>My Account</h3>
                    @if ($user->id_type == "Senior Citizen ID/Booklet")
                        <span class="badge badge-success">Verified Senior Citizen</span>
                    @elseif ($user->id_type == "PWD ID")
                        <span class="badge badge-success">Verified PWD</span>
                    @endif
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Name</label><br>
                            <div class="text-muted">{{ $user->name }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Username</label><br>
                            <div class="text-muted">{{ $user->username }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Email</label><br>
                            <div class="text-muted">{{ $user->email }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Contact Number</label><br>
                            <div class="text-muted">{{ $user->phone ? $user->phone : "N/A" }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-4">
                            <a href="{{ url('edit-account') }}" class="btn btn-sm btn-primary">Update account</a>
                        </div>
                        <div class="col-sm-12 mt-4"><hr></div>
                        <div class="col-sm-12 mt-3">
                            <h4>Address</h4>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Municipality</label><br>
                            <div class="text-muted">{{ isset($address->municipality) ? $address->municipality : "" }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Brgy</label><br>
                            <div class="text-muted">{{ isset($address->brgy) ? $address->brgy : "" }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Street</label><br>
                            <div class="text-muted">{{ isset($address->street) ? $address->street : "" }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mt-2">
                            <label class="col-form-label">Nearest landmark</label><br>
                            <div class="text-muted">{{ isset($address->notes) ? $address->notes : "" }}</div>
                        </div>
                        <div class="col-sm-12 mt-4">
                            <a href="{{ route('address.edit',Auth::id()) }}" class="btn btn-sm btn-primary">Update address</a>
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


