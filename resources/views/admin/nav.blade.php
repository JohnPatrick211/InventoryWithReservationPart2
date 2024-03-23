  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links  -->
    <ul class="navbar-nav">
        <h3>@php echo date("F d, Y");  @endphp</h3>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
   
      @php
          $orders_count = \DB::table('product as P')
            ->select("P.*", DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'description',
                    'reorder', 
                    'qty', 
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category'
                    )
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.status', 1)
            ->whereColumn('P.reorder','>=', 'P.qty')
            ->where('P.qty', '!=', 0)
            ->count();

          $to_verify_count = \DB::table('product as P')
            ->select("P.*", DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'description',
                    'reorder', 
                    'qty', 
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category'
                    )
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.status', 1)
            ->whereColumn('P.reorder','>=', 'P.qty')
            ->where('P.qty', '==', 0)
            ->count();
          
          $notification_count = $orders_count + $to_verify_count;

      @endphp

      @php
          $access_level = Auth::user()->access_level;
          $user_menus = \DB::table('user_roles as ur')
                    ->select('um.id','um_title', 'um_url','um_class','um_icon','um_has_sub_menu')
                    ->leftJoin('user_role_menus as urm','ur.id','=','urm.urm_user_role_id')
                    ->leftJoin('ui_menus as um','um.id','=','urm.urm_menu_id')
                    ->where('um.um_is_active',true)
                    ->where('ur.id',$access_level)->get();
      @endphp

      @if(in_array($access_level, array(1, 2)))
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown"> 
        <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
          @if($notification_count == 0)

          @else
          <span class="badge badge-warning navbar-badge">{{$notification_count}}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{$notification_count}} Notification{{$notification_count > 1 ? 's' : ''}}</span>
          <div class="dropdown-divider"></div>
          <a href="{{url('/purchase-order')}}" class="dropdown-item">
            @if ($orders_count > 0)
              {{$orders_count}} Reorder{{$orders_count > 1 ? 's' : ''}}
            @else
                No Reorder
            @endif
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{url('/purchase-order')}}" class="dropdown-item">
            @if ($to_verify_count > 0)
            {{$to_verify_count}} Out of Stock{{$to_verify_count > 1 ? 's' : ''}}
          @else
              No Out of Stock
          @endif
          </a>
        </div>
      </li>
      @endif
      <li class="nav-item dropdown">
        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">{{ Auth::user()->name }}</a>
        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
          <li><a href="{{url('/admin/logout')}}" class="dropdown-item">Logout </a></li>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4"  style="background-color: {{ Session::get('cms_theme_color')}};">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="images/{{Session::get('cms_logo')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-normal" style="font-size: 13px;">{{ Session::get('cms_name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) 
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>-->

      <!-- 
      **ACCESS LEVELS**
        Sales Clerk = 1
        Inventory Clerk = 2
        Owner = 3
        Administrator = 4

        Updated
        System Administrator = 3
        Assistant Proware = 1
        Proware Specialist = 2
      -->
      
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @foreach ($user_menus as $user_menu)
          <li class="nav-item">
              <a href="{{ $user_menu->um_url }}" class="{{$user_menu->um_class}}">
                  <i class="{{$user_menu->um_icon}}"></i>
                  <p>
                  {{$user_menu->um_title}} @if($user_menu->um_has_sub_menu) <i class="fas fa-angle-left right"></i> @endif
                  </p>
                </a>

                @if($user_menu->um_has_sub_menu)
                  @php
                    $user_sub_menus = \DB::table('ui_sub_menus as usm')
                        ->select('usm_title', 'usm_url','usm_class','usm_icon')
                        ->where('usm.usm_is_active',true)
                        ->where('usm.usm_menu_id',$user_menu->id)->get()
                  @endphp
                  @foreach($user_sub_menus as $user_sub_menu)
                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                      
                      <a href="{{$user_sub_menu->usm_url}}" class="{{$user_sub_menu->usm_class}}">
                      <i class="{{$user_sub_menu->usm_icon}}"></i>
                        <p>{{$user_sub_menu->usm_title}}</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <!-- <a href="{{url('/verify-customer')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Verify Student</p>
                      </a> -->
                    </li>
                  </ul>
                  @endforeach
                @endIf 

            </li>
            
        @endforeach

          

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>