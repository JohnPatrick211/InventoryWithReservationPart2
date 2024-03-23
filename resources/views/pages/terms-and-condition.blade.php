
@php
$page_title =  Session::get('cms_name') . " | Terms and Conditions";
@endphp

@include('header')

<!-- Navbar -->
@include('nav')
<!-- /.navbar -->

<style>

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <div ></div>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <main class="d-flex align-items-center py-3 py-md-0">
      <div class="container">
        <h2>Terms and Conditions</h2>

        <h3>Overview</h3>
        <p>By accessing and using https://onlineinventorywithreservation.online/, you agree to abide by the terms and conditions outlined herein. </p>
        
        <p>1. Availability: The inventory management system allows users to reserve school merchandise based on availability. However, availability is subject to change, and the system does not guarantee the availability of all items at all times.</p>
       
        <p>2. Reservation Process: Users are given an account and must log in to the system to reserve school merchandise. The reservation process is if the product in the system shows out of stock. It will proceed to the payment since the school policies said that the student cannot reserve as long as it is not paid.</p>
        <p>3. No Cancellation and Refunds Policy: Online Inventory Management with Reservation System does not provide cancellations or refunds of any products offered on https://onlineinventorywithreservation.online/ all sales are final.</p>
        <p>4. Condition of Merchandise: Upon receiving a receipt, users are responsibe for inspecting the items for damage or defects. Any issues must be reported within 7 days to the Supplies Department.</p>
        <p>5. Replacement Policy: If the items are found damaged, users can place a request using their account at https://onlineinventorywithreservation.online/ by placing the information about the product and about the damage, the replacement is only available within 7 days after receiving the item.</p>
        <p>6. Payment Methods: The system will accept payments using Gcash, Paymaya, Credit Card, and Cash. </p>
        <p>7. Responsibility of Users: Users are responsible for checking the items after receiving them, they will be given 7 days to return the items to the Supplies Department if there is some damage or defects to be found. If they failed to replace it in the given time or if they damage the product the school is not responsible for it and will not accept any replacement request. </p>
        <p>8. Contact Information: For questions or concerns regarding these terms and conditions, contact as at 09268471883, 09359856191, 09153953034, 09269831350. </p>
      </div>
    </main>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('footer')

