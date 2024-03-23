
@php
$page_title =  Session::get('cms_name') . "| My Cart";
@endphp

@include('header')

<!-- Navbar -->
@include('nav')
<!-- /.navbar -->

<style>
  .border-md {
    border-width: 2px !important;
  }

  .btn-facebook {
    background: #405D9D;
    border: none;
  }

  .btn-facebook:hover,
  .btn-facebook:focus {
    background: #314879;
  }

  .btn-twitter {
    background: #42AEEC;
    border: none;
  }

  .btn-twitter:hover,
  .btn-twitter:focus {
    background: #1799e4;
  }

  body {
    min-height: 100vh;
  }

  .form-control:not(select) {
    padding: 1.5rem 0.5rem;
  }

  select.form-control {
    height: 52px;
    padding-left: 0.5rem;
  }

  .form-control::placeholder {
    color: #ccc;
    font-weight: bold;
    font-size: 0.9rem;
  }

  .form-check-inline {
    text-align: center;
  }

  .form-check-label {
    font-weight: bold;
    font-size: 1.2rem; /* Increase font size */
  }

  .form-check-input {
    margin-top: 0.4rem; /* Adjust vertical alignment */
  }
</style>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <main class="d-flex align-items-center py-3 py-md-0">
      <div class="container shopping-cart">
        <div class="row mt-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                    <h3>Cart (<span class="cart-count"></span> items)</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="items" id="cart-items">
                            </div>
                            <div class="loader-container">
                              <div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <!--div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="options" id="pickUpOption" value="pickup" checked="checked">
                              <label class="form-check-label" for="pickUpOption">Pick Up</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="options" id="reservationOption" value="reservation">
                              <label class="form-check-label" for="reservationOption">Reservation</label>
                            </div-->
                            
                        
                        <div class="summary">
                            <div class="summary-item"><span style="font-size: 20px" class="text">Total amount </span><span style="font-size: 20px" id="total"></span></div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg btn-block mt-2" id="btn-checkout">Checkout</a>
                        </div>
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

<script src="{{asset('js/cart.js')}}"></script>


