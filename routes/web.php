<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ChatbotController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\FlashSaleController;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Http\Controllers\Frontend\OrderTrackController;
use App\Http\Controllers\Frontend\UserAddressController;
use App\Http\Controllers\Frontend\UserMessageController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\FrontendProductController;
use App\Http\Controllers\Frontend\UserVendorRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Flash Sale Route
Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale');

// Product Routes
Route::get('/product-detail/{slug}', [FrontendProductController::class, 'showProduct'])->name('product-detail');
Route::get('/products', [FrontendProductController::class, 'productsIndex'])->name('products.index');
Route::post('/products-list-view', [FrontendProductController::class, 'productsListView'])->name('products-list-view');

// Cart Routes
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::post('/cart/update-qty', [CartController::class, 'cartUpdateQty'])->name('cart.update-qty');
Route::post('/cart/remove-sidebar-product', [CartController::class, 'removeSidebarProduct'])->name('cart.remove-sidebar-product');
Route::post('/cart/sidebar-product-total', [CartController::class, 'cartTotal'])->name('cart.sidebar-product-total');
Route::post('/calculate-coupon', [CartController::class, 'calculateCoupon'])->name('calculate-coupon');
Route::get('/cart-detail', [CartController::class, 'cartDetail'])->name('cart-detail');
Route::get('/cart/remove-product/{rowId}', [CartController::class, 'removeProduct'])->name('cart.remove-product');
Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart-count');
Route::get('/cart-products', [CartController::class, 'getCartProducts'])->name('cart-products');
Route::get('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::delete('/clear-cart', [CartController::class, 'clearCart'])->name('clear-cart');

// Vendor Routes
Route::get('vendors', [HomeController::class, 'vendorPage'])->name('vendors.index');
Route::get('vendors-product/{id}', [HomeController::class, 'vendorProductPage'])->name('vendors.product');

// Contact Routes
Route::get('contact', [HomeController::class, 'contact'])->name('contact.index');
Route::post('contact', [HomeController::class, 'handleContact'])->name('contact.submit-form');

// Chatbot Route
Route::post('chatbot/reply', [ChatbotController::class, 'reply'])
  ->middleware('throttle:chatbot')
  ->name('chatbot.reply');

// Order Track Routes
Route::get('order-track', [OrderTrackController::class, 'index'])->name('order-track.index');

// Blogs Routes
Route::get('blog', [BlogController::class, 'blog'])->name('blog');
Route::get('blog-detail/{slug}', [BlogController::class, 'blogDetail'])->name('blog-detail');

// Newsletter Route
Route::post('news', [NewsController::class, 'newsSubscribe'])->name('news.subscribe');
Route::get('news/verify/{token}', [NewsController::class, 'newsVerify'])->name('news.verify');

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'user', 'as' => 'user.'], function () {
  Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
  Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
  Route::put('/profile', [UserProfileController::class, 'updateProfile'])->name('profile.update');
  Route::post('/profile', [UserProfileController::class, 'updatePassword'])->name('profile.update.password');

  // User Address Routes
  Route::resource('/address', UserAddressController::class);

  // Order Routes
  Route::get('orders', [UserOrderController::class, 'index'])->name('orders.index');
  Route::get('orders/show/{id}', [UserOrderController::class, 'show'])->name('orders.show');

  // Wishlist Routes
  Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
  Route::post('wishlist/add', [WishlistController::class, 'addToWishList'])->name('wishlist.add');
  Route::delete('wishlist/remove/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

  // Product Review Routes
  Route::get('review', [ReviewController::class, 'index'])->name('review.index');
  Route::post('review', [ReviewController::class, 'create'])->name('review.create');

  // Vendor Request Routes
  Route::get('vendor-request', [UserVendorRequestController::class, 'index'])->name('vendor-request.index');
  Route::post('vendor-request', [UserVendorRequestController::class, 'create'])->name('vendor-request.create');

  // Checkout Controller
  Route::get('checkout', [CheckOutController::class, 'index'])->name('checkout');
  Route::post('checkout/address-create', [CheckOutController::class, 'createAddress'])->name('checkout.address.create');
  Route::post('checkout/form-submit', [CheckOutController::class, 'checkoutFormSubmit'])->name('checkout.form-submit');

  // Payment Routes
  Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
  Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

  // Paypal Routes
  Route::get('paypal/payment', [PaymentController::class, 'payWithPaypal'])->name('paypal.payment');
  Route::get('paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
  Route::get('paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');

  // VNPay Routes
  Route::get('vnpay/payment', [PaymentController::class, 'payWithVNPay'])->name('vnpay.payment');
  Route::get('vnpay/success', [PaymentController::class, 'vnpaySuccess'])->name('vnpay.success');
  Route::get('vnpay/cancel', [PaymentController::class, 'vnpayCancel'])->name('vnpay.cancel');

  // Cod Route
  Route::get('cod/payment', [PaymentController::class, 'payWithCod'])->name('cod.payment');

  // Blog Comment Routes
  Route::post('comment', [BlogController::class, 'comment'])->name('comment');

  // Messenger Route
  Route::get('messenger', [UserMessageController::class, 'index'])->name('messenger.index');
  Route::post('messenger/send-message', [UserMessageController::class, 'sendMessage'])->name('send-message');
  Route::get('messenger/get-message/{id}', [UserMessageController::class, 'getMessage'])->name('get-message');
});
