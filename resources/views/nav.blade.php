<style>
  .nav-link span {
    background-color: #FCE501;
  }
  .main-header {
    background-color: {{Session::get('cms_theme_color')}};
    border-color: {{Session::get('cms_theme_color')}};
  }

  .nav-link, .fas, .brand-text {
    color: #FFF !important;
    white-space: nowrap;
  }

  .btn-load-more .fas {
    color: {{Session::get('cms_theme_color')}} !important;
  }

  .fa-search {
    color: #06513D !important;
  }
</style>

<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <div class="row w-100">
        <!-- Logo and CMS Name on the left -->
        <div class="col-md-6">
          <a href="{{ url('/') }}" class="navbar-brand">
            <img src="images/{{Session::get('cms_logo')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-light font-weight-normal">{{ Session::get('cms_name')}}</span>
          </a>
        </div>

        
        <!-- Right navbar links -->
        <div class="col-md-6 text-right">
          <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item">
              <a href="{{ url('/cart') }}" class="nav-link">
                <i class="fas fa-shopping-cart" style="color: #06513D;"></i>
                <span class="badge badge-warning navbar-badge cart-count">0</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/reservationbook') }}" class="nav-link">
                <i class="fas fa-book" style="color: #06513D;"></i>
                <span class="badge badge-warning navbar-badge reservation-count">0</span>
              </a>
            </li>
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a href="{{ url('/') }}" class="nav-link">Home</a>
                </li>
                @if (Auth::check())
                <li class="nav-item">
                  <a href="{{url('/my-orders')}}" class="nav-link">My Orders</a>
                </li>
                <li class="nav-item">
                  <a href="{{url('/my-reservation')}}" class="nav-link">Reservation</a>
                </li>
                <li class="nav-item dropdown">
                  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">{{ Auth::user()->name }}</a>
                  <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li><a href="{{url('/account')}}" class="dropdown-item">My Account</a></li>
                    <li><a href="{{url('/replacement')}}" class="dropdown-item">My Product Replacement</a></li>
                    <li><a href="{{url('/admin/logout')}}" class="dropdown-item">Logout</a></li>
                  </ul>
                </li>
                @else 
                <li class="nav-item">
                  <a href="{{ url('/login') }}" class="nav-link">Login</a>
                </li>
                @endif
              </ul>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </nav>
