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

  .navbar-brand img {
    max-width: 200px; /* Adjust the logo size as needed */
    height: auto;
  }

  .brand-text {
    font-size: 36px; /* Adjust the font size as needed */
    margin-left: 10px; /* Adjust the margin as needed */
  }

  .navbar-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
  }

  .navbar-nav .nav-item {
    margin: 0 10px;
  }
</style>

<div class="main-header navbar navbar-expand-md navbar-light navbar-white">
  <div class="container">
    <div class="navbar-nav">
      <a href="{{ url('/') }}" class="navbar-brand">
        <img src="images/{{Session::get('cms_logo')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light font-weight-normal">{{ Session::get('cms_name')}}</span>
      </a>

      @if($page_title == Session::get('cms_name')) 
      <div class="form-inline ml-0 ml-md-3 mr-3 form-search-product">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" id="input-search-product" type="search" placeholder="Search product..." aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar btn-search-product">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>