
@php
$page_title =  Session::get('cms_name') . "| Checkout";
@endphp

@include('header')

<!-- Navbar -->
@include('nav')
<!-- /.navbar -->

<style>

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
                    <h3>Checkout (<span class="cart-count"></span> items)</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="items" id="cart-items">
                                <div class="loader-container">
                                    <div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="summary p-2">
                        <h3>Checkout</h3>
                        @php
                            $discount_amount = 0;
                            $wholesale_discount = 0;
                            $user = \Auth::user();
                            $discount = \DB::table('discount')->first();
                        @endphp
                        @php
                        
                            $subtotal = ($subtotal - $discount_amount) - $wholesale_discount;
                            $total = $subtotal+$charge;
                        @endphp
                        <div class="summary-item"><span class="text">Total</span><span class="price">₱{{ number_format($total,2,".",",") }}</span></div>
                        <label class=" mt-3">Payment method</label>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="card" id="opt-card" name="optpayment-method" checked>Credit or Debit Card 
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="gcash" id="opt-gcash" name="optpayment-method">Gcash
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="paymaya" id="opt-paymaya" name="optpayment-method">Maya
                          </label>
                        </div>
                    </div>
                    <small class="text-danger d-none" id="invalid-amount-message"></small><br>
                    <a id="btn-place-order" class="btn btn-primary btn-sm mt-3">Place order</a>
                    <input type="hidden" id="total-amount" value="{{ $total }}">
                    <input type="hidden" id="payment-method">
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

<script src="{{asset('js/checkout.js')}}"></script>


