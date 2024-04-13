<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Customer
 */
Route::get('/', 'HomePageController@index');
Route::get('/customer/product', 'HomePageController@readAllProduct');
Route::get('/customer/product/search', 'HomePageController@searchProduct');

Route::get('/home/category/{category_id}', 'HomePageController@readProductByCategory');

Route::get('/login', 'UserAuthController@customer_index');
Route::get('/signup', 'UserAuthController@signup_view');
Route::post('/create-account', 'UserAuthController@createAccount')->name('createAccount');
Route::post('/do-login', 'UserAuthController@login')->name('login');

//Replacement
//Student Side
Route::get('/replacement', 'ReplacmentController@index');
Route::get('/create_request', 'ReplacmentController@createindex');
Route::post('/storereplacementrequest', 'ReplacmentController@storerequest');
Route::get('/replacementData','ReplacmentController@fetchReplacementData');
Route::get('/updatereplacement/{id}','ReplacmentController@updateindex');
Route::post('/updatereplacementrequest','ReplacmentController@updaterequest');
Route::post('/replacement/delete/{id}', 'ReplacmentController@deleterequest');

//Admin Side
Route::get('/product-replacement', 'ReplacmentController@adminindex');
Route::get('/approved-replacement', 'ReplacmentController@getApprovedReplacement');
Route::post('/do-approve-request/{user_id}', 'ReplacmentController@Approve');
Route::post('/do-reject-request/{user_id}/{remarks}', 'ReplacmentController@Reject');





/**
 * Admin
 */
Route::get('/admin', 'UserAuthController@index');
//Route::post('/admin/login', 'UserAuthController@login'])->name('login');
Route::get('/admin/logout', 'UserAuthController@logout');


Route::get('/cart', 'CartController@index');
Route::get('/cart/read-items', 'CartController@readCart');
Route::get('/cart-count', 'CartController@cartCount');
Route::post('/add-to-cart', 'CartController@addToCart');

/**
 * Pages
 */
Route::get('/terms-and-condition', function(){
  return view('pages.terms-and-condition');
});

Route::middleware('auth')->group(function () {
    Route::resource('/account', 'AccountController');
    Route::get('/edit-account', 'AccountController@editAccount');
    Route::resource('/address', 'UserAddressController');
    Route::get('/cart-total', 'CartController@cartTotal');
    Route::post('/cart/remove-item/{id}', 'CartController@removeItem');
    Route::post('/cart/change-qty', 'CartController@changeQuantity');
    Route::resource('/checkout', 'CheckoutController');
    Route::get('/place-order-card', 'CheckoutController@placeOrderCard')->name('placeOrderCard');
    Route::get('/place-order-gcash', 'CheckoutController@placeOrderGcash')->name('placeOrderGcash');
    Route::get('/place-order-paymaya', 'CheckoutController@placeOrderPaymaya')->name('placeOrderPaymaya');
    Route::get('/create-source', 'CheckoutController@createSource')->name('createSource');
    Route::get('/create-payment', 'CheckoutController@createPayment')->name('createPayment');
    Route::get('/create-checkout', 'CheckoutController@createCheckout')->name('createCheckout');
    Route::get('/create-payment-method', 'CheckoutController@createPaymayaPaymentMethod')->name('createPaymayaPaymentMethod');
    Route::get('/order-info/{source_id}/{payment_method}', 'CheckoutController@orderInfo');

    Route::get('/my-orders', 'OrderController@index');
    Route::post('/cancel-order/{order_no}', 'OrderController@cancelOrder');
    Route::get('/get-municipality/{province}', 'UserAddressController@getMunicipalityByProvince');
    Route::get('/get-brgy/{municipality}', 'UserAddressController@getBrgyByMunicipality');
    Route::get('/preview-order-ereceipt/{order_no}', 'OrderController@previewOrderEReceipt');
    Route::post('/send-feedback', 'OrderController@sendFeedback');
    Route::get('/read-feedback', 'OrderController@readOneFeedback');

    //Checkout-Reservation
    Route::resource('/checkout-reservation', 'CheckoutReservationController');
    Route::get('/place-order-card-re', 'CheckoutReservationController@placeOrderCard2')->name('placeOrderCard2');
    Route::get('/place-order-gcash-re', 'CheckoutReservationController@placeOrderGcash2')->name('placeOrderGcash2');
    Route::get('/place-order-paymaya-re', 'CheckoutReservationController@placeOrderPaymaya2')->name('placeOrderPaymaya2');
    Route::get('/create-source-re', 'CheckoutReservationController@createSource2')->name('createSource2');
    Route::get('/create-payment-re', 'CheckoutReservationController@createPayment2')->name('createPayment2');
    Route::get('/create-checkout-re', 'CheckoutReservationController@createCheckout2')->name('createCheckout2');
    Route::get('/create-payment-method-re', 'CheckoutReservationController@createPaymayaPaymentMethod2')->name('createPaymayaPaymentMethod2');
    Route::get('/order-info-reserve/{source_id}/{payment_method}', 'CheckoutReservationController@orderInfo');
    
    Route::middleware('access_level:1:2:3:4')->group(function () {
      Route::get('/dashboard', 'Admin\DashboardController@index');
      Route::resource('users', 'Admin\UserController');
      Route::resource('user-role', 'Admin\UserRoleController');
      Route::resource('supplier', 'Admin\SupplierController');
      Route::resource('unit', 'Admin\UnitController');
      Route::post('user/archive/{id}', 'Admin\UserController@archive');
      Route::resource('product', 'Admin\ProductController');
      Route::get('/product-search', 'Admin\ProductController@productSearch');
      Route::post('/product/archive/{id}', 'Admin\ProductController@archive');
      Route::resource('category', 'Admin\CategoryController');
      Route::resource('product-management', 'Admin\ProductManagementController');
      Route::resource('delivery_area', 'Admin\DeliveryAreaController');
      Route::get('delivery_area/brgylist/{municipality}', 'Admin\DeliveryAreaController@getBrgyList');
      Route::resource('stock-adjustment', 'Admin\StockAdjustmentController');
      Route::post('stock-adjustment/adjust/{id}', 'Admin\StockAdjustmentController@adjust');
      Route::resource('purchase-order', 'Admin\PurchaseOrderController');
      Route::get('display-reorders', 'Admin\PurchaseOrderController@displayReorders');
      Route::get('pending-order', 'Admin\PurchaseOrderController@displayPending');
      Route::post('purchase-order/add-order', 'Admin\PurchaseOrderController@addOrder');
      Route::get('request-order', 'Admin\PurchaseOrderController@readRequestOrderBySupplier');
      Route::post('request-order/remove', 'Admin\PurchaseOrderController@removeRequest');
      Route::post('purchase-order', 'Admin\PurchaseOrderController@purchaseOrder');
      Route::get('preview-request-order', 'Admin\PurchaseOrderController@previewRequestPurchaseOrder');
      Route::get('preview-request-ereceipt/{id}/{po}', 'Admin\PurchaseOrderController@previewRequestEReceipt');
      Route::get('download-request-order', 'Admin\PurchaseOrderController@downloadRequestPurchaseOrder');
      Route::get('purchased-order', 'Admin\PurchaseOrderController@readPurchasedOrder');
      Route::get('purchased-order-inpurchase', 'Admin\PurchaseOrderController@readPurchasedOrderInPurchase');
      Route::get('reports/stock-adjustment', 'Admin\StockAdjustmentReportController@index');
      Route::get('reports/stock-adjustment/pdf/{date_from}/{date_to}', 'Admin\StockAdjustmentReportController@pdf');
      Route::get('reports/stock-adjustment/download/{date_from}/{date_to}', 'Admin\StockAdjustmentReportController@downloadPDF');
      //purchase order
      Route::get('display-purchaseorder', 'Admin\PurchaseOrderController@readPurchaseOrder');
      Route::get('cashiering', 'Admin\CashieringController@index');
      Route::post('/record-sale', 'Admin\CashieringController@recordSale');
      Route::post('add-to-tray', 'Admin\CashieringController@addToTray');
      Route::get('read-tray', 'Admin\CashieringController@readTray');
      Route::get('cashiering/read-one-qty/{product_code}', 'Admin\CashieringController@readOneQty');
      Route::post('void/{id}', 'Admin\CashieringController@void');
      Route::get('/update-invoice', 'Admin\CashieringController@updateinvoice');
      Route::get('check-lrn/{id}', 'Admin\CashieringController@checklrn');
      Route::get('preview-invoice/{wholesale_discount_amount}/{senior_pwd_discount_amount}/{studname}', 'Admin\CashieringController@previewInvoice');
      Route::get('/pricing', 'Admin\PricingController@index');
      Route::post('/pricing/update', 'Admin\PricingController@updatePricing');
      Route::resource('supplier-delivery', 'Admin\SupplierDeliveryController');
      Route::post('/create-delivery', 'Admin\SupplierDeliveryController@createDelivery');
      Route::get('/read-supplier-delivery', 'Admin\SupplierDeliveryController@readSupplierDelivery');
      Route::get('/read-supplier-delivery-partial', 'Admin\SupplierDeliveryController@readSupplierDeliveryPartial');
      Route::get('/compute-reservation-qty', 'Admin\SupplierDeliveryController@computeReservationQty');
      
      Route::resource('reports/sales', 'Admin\SalesController');
      Route::get('read-sales', 'Admin\SalesController@readSales');
      Route::get('/compute-total-sales', 'Admin\SalesController@computeTotalSales');
      Route::get('reports/preview-sales/{date_from}/{date_to}/{order_from}/{payment_method}', 'Admin\SalesController@previewSalesReport');
      Route::get('reports/download-sales/{date_from}/{date_to}/{order_from}/{payment_method}', 'Admin\SalesController@downloadSalesReport');
      Route::get('reports/inventory', 'Admin\InventoryReportController@index');
      Route::get('reports/inventory/{category_id}', 'Admin\InventoryReportController@readProductByCategory');
      Route::get('/reports/inventory/preview/{category_id}', 'Admin\InventoryReportController@previewReport');
      Route::get('/reports/inventory/download/{category_id}', 'Admin\InventoryReportController@downloadReport');
      //replacement report
      Route::get('reports/replacement', 'ReplacmentController@indexreport');
      Route::get('/reports/replacement-report', 'ReplacmentController@indexreport');
      Route::post('/reports/replacement/archive/{replacement_id}', 'ReplacmentController@archive');
      Route::get('/reports/replacement/preview', 'ReplacmentController@previewReport');
      Route::get('/reports/replacement/download', 'ReplacmentController@downloadReport');
      //preorder report
      Route::get('reports/preorder', 'Admin\CustomerOrderController@indexreport');
      Route::get('/reports/preorder-report', 'Admin\CustomerOrderController@indexreport');
      Route::get('/reports/preorder/preview', 'Admin\CustomerOrderController@previewReport');
      Route::get('/reports/preorder/download', 'Admin\CustomerOrderController@downloadReport');
      //stock
      Route::post('/reports/stockadjustment/archive/{product_id}', 'Admin\StockAdjustmentReportController@archive');
      //supplier
      Route::post('/reports/supplierdelivery/archive/{supplier_id}', 'Admin\SupplierDeliveryReportController@archive');
      Route::get('/archive/supplier-delivery', 'Admin\ArchiveController@readSupplierDelivery');

      
      Route::get('/reports/reservation', 'Admin\ReservationReportController@index');
      Route::get('/read-reservations', 'Admin\ReservationReportController@readReservations');
      Route::get('reports/preview-reservation/{date_from}/{date_to}/{order_from}/{payment_method}', 'Admin\ReservationReportController@previewReservationsReport');
      Route::get('reports/download-reservation/{date_from}/{date_to}/{order_from}/{payment_method}', 'Admin\ReservationReportController@downloadReservationsReport');

      Route::get('/reports/purchased-order', 'Admin\PurchaseOrderReportController@index');
      Route::get('/purchased-order/preview/{supplier_id}/{date_from}/{date_to}', 'Admin\PurchaseOrderReportController@previewReport');
      Route::get('/purchased-order/download/{supplier_id}/{date_from}/{date_to}', 'Admin\PurchaseOrderReportController@downloadReport');
  
      Route::get('/reports/supplier-delivery', 'Admin\SupplierDeliveryReportController@index');
      Route::get('/supplier-delivery/preview/{supplier_id}/{date_from}/{date_to}', 'Admin\SupplierDeliveryReportController@previewReport');
      Route::get('/supplier-delivery/download/{supplier_id}/{date_from}/{date_to}', 'Admin\SupplierDeliveryReportController@downloadReport');
  
      Route::resource('product-return', 'Admin\ProductReturnController');
      Route::get('/product-return-read-sales', 'Admin\ProductReturnController@readSales');
      Route::post('/return', 'Admin\ProductReturnController@return');
      Route::get('/reports/product-return', 'Admin\ProductReturnReportController@index');
      Route::get('/product-return/preview/{date_from}/{date_to}', 'Admin\ProductReturnReportController@previewReport');
      Route::get('/product-return/download/{date_from}/{date_to}', 'Admin\ProductReturnReportController@downloadReport');
  
      Route::get('/reports/reorder', 'Admin\ReorderListController@index');
      Route::get('/reorder/preview/{supplier_id}', 'Admin\ReorderListController@previewReport');
      Route::get('/reorder/download/{supplier_id}', 'Admin\ReorderListController@downloadReport');

      Route::get('/reports/fast-and-slow', 'Admin\FastAndSlowMovingController@index');
  
      Route::get('/verify-customer', 'Admin\VerifyCustomerController@index');
      Route::get('/verified-customer', 'Admin\VerifyCustomerController@readAllVerifiedCustomer');
      Route::post('/do-verify-customer/{user_id}', 'Admin\VerifyCustomerController@verifyCustomer');

      Route::get('/customer-orders', 'Admin\CustomerOrderController@index');
      Route::get('/read-orders', 'Admin\CustomerOrderController@readOrders');
      Route::get('/read-one-order/{order_no}', 'Admin\CustomerOrderController@readOneOrder');
      Route::post('/order-change-status/{order_no}', 'Admin\CustomerOrderController@orderChangeStatus');
      Route::get('/get-shipping-fee/{order_no}', 'Admin\CustomerOrderController@getShippingFee');
      Route::get('/read-shipping-address/{user_id}', 'Admin\CustomerOrderController@readShippingAddress');

      Route::get('/audit-trail', 'Admin\AuditTrailController@index');
      Route::get('/archive', 'Admin\ArchiveController@index');
      Route::get('/archive/products', 'Admin\ArchiveController@readArchiveProduct');
      Route::get('/archive/users', 'Admin\ArchiveController@readArchiveUsers');
      Route::post('/archive/restore/{id}', 'Admin\ArchiveController@restore');
      Route::post('/archive/replacement-restore/{replacement_id}', 'Admin\ArchiveController@restorereplacement');
      Route::post('/archive/stockadjustment-restore/{stock_id}', 'Admin\ArchiveController@restorestockadjustment');
      Route::post('/archive/supplierdelivery-restore/{supplier_id}', 'Admin\ArchiveController@restoresupplierdelivery');
      Route::post('/archive/sales-restore/{sales_id}', 'Admin\ArchiveController@restoresales');

      Route::get('/archive/sales', 'Admin\ArchiveController@readArchiveSales');
      Route::get('/archive/replacement', 'Admin\ArchiveController@readArchiveReplacement');
      Route::get('/archive/stock-adjustment', 'Admin\ArchiveController@readStockAdjustment');
      Route::post('usermaintenance/export', 'Admin\UserController@export');
      Route::post('usermaintenance/import', 'Admin\UserController@import');

      Route::get('/feedback', 'Admin\FeedbackController@index');

      Route::resource('discount', 'Admin\DiscountController');
      Route::get('/backup-and-restore', 'Admin\BackupAndRestoreController@index');
      Route::post('/backup-and-restore/backup', 'Admin\BackupAndRestoreController@backup')->name('backup');
      Route::post('/backup-and-restore/restore', 'Admin\BackupAndRestoreController@restore')->name('restore');

      Route::get('/notification', 'Admin\NotificationController@index');

      Route::post('/reports/archive/{id}', 'Admin\SalesController@archive');

      Route::resource('/cms', 'Admin\CMSController');
      //Reservation Maintenance
      Route::get('/my-reservation', 'MyReservationController@index');

      //Reservation Book
      Route::get('/reservationbook', 'ReservationBookController@index');
      Route::get('/reservationbook/read-items', 'ReservationBookController@readBook');
      Route::get('/reservationbook-count', 'ReservationBookController@readBookCount');
      Route::post('/add-to-reservation', 'ReservationBookController@addToReservation');

      Route::get('/reservation-total', 'ReservationBookController@reservationTotal');
      Route::post('/reservationbook/remove-item/{id}', 'ReservationBookController@removeItem');
      Route::post('/reservationbook/change-qty', 'ReservationBookController@changeQuantity');

    });
    Route::get('/read-discount', 'Admin\DiscountController@readDiscount');

    
});


Route::get('/contact-us', 'PagesController@contactUs');
Route::get('/about-us', 'PagesController@aboutUs');
Route::get('/privacy-policy', 'PagesController@privacyPolicy');
Route::get('/we-deliver', 'PagesController@weDeliver');
Route::get('/return-and-cancellation-policy', 'PagesController@returnAndCancellationPolicy');