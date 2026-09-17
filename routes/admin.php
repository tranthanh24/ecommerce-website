<?php

use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\AdminListController;
use App\Http\Controllers\Backend\FlashSaleController;
use App\Http\Controllers\Backend\ManageUserController;
use App\Http\Controllers\Backend\VendorListController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\FeaturePageController;
use App\Http\Controllers\Backend\AdminMessageController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\VNPaySettingController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\PaypalSettingController;
use App\Http\Controllers\Backend\SellerProductController;
use App\Http\Controllers\Backend\VendorRequestController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\ProductVariantController;
use App\Http\Controllers\Backend\HomepageSettingController;
use App\Http\Controllers\Backend\VendorConditionController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\ProductVariantItemController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// Simple feature pages
Route::get('/features-activities', [FeaturePageController::class, 'activities'])->name('features.activities');
Route::get('/features-settings', [FeaturePageController::class, 'settings'])->name('features.settings');

// Profile Routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

// Slider Routes
Route::resource('slider', SliderController::class);

// Category Routes
Route::put('change-status', [CategoryController::class, 'changeStatus'])->name('category.change-status');
Route::resource('category', CategoryController::class);

// Sub Category Routes
Route::put('sub-category/change-status', [SubCategoryController::class, 'changeStatus'])->name('sub-category.change-status');
Route::resource('sub-category', SubCategoryController::class);

// Child Category Routes
Route::put('child-category/change-status', [ChildCategoryController::class, 'changeStatus'])->name('child-category.change-status');
Route::get('get-subcategories', [ChildCategoryController::class, 'getSubCategories'])->name('get-subcategories');
Route::resource('child-category', ChildCategoryController::class);

// Brand Routes
Route::put('brand/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
Route::resource('brand', BrandController::class);

// Vendor Profile Routes
Route::resource('vendor-profile', AdminVendorProfileController::class);

// Product Routes
Route::get('product/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('product.get-subcategories');
Route::get('product/get-child-categories', [ProductController::class, 'getChildCategories'])->name('product.get-childcategories');
Route::put('product/change-status', [ProductController::class, 'changeStatus'])->name('product.change-status');
Route::resource('products', ProductController::class);

// Product Image Routes
Route::resource('products-image-gallery', ProductImageGalleryController::class);

// Product Variant Routes
Route::put('products-variant/change-status', [ProductVariantController::class, 'changeStatus'])->name('products-variant.change-status');
Route::resource('products-variant', ProductVariantController::class);

// Product Variant Item Routes
Route::get('products-variant-item/{productId}/{variantId}', [ProductVariantItemController::class, 'index'])->name('products-variant-item.index');
Route::get('products-variant-item/create/{productId}/{variantId}', [ProductVariantItemController::class, 'create'])->name('products-variant-item.create');
Route::put('products-variant-item', [ProductVariantItemController::class, 'store'])->name('products-variant-item.store');
Route::get('products-variant-item-edit/{variantItemId}', [ProductVariantItemController::class, 'edit'])->name('products-variant-item.edit');
Route::put('products-variant-item-update/{variantItemId}', [ProductVariantItemController::class, 'update'])->name('products-variant-item.update');
Route::delete('products-variant-item/{variantItemId}/destroy', [ProductVariantItemController::class, 'destroy'])->name('products-variant-item.destroy');
Route::put('products-variant-item-status', [ProductVariantItemController::class, 'changeStatus'])->name('products-variant-item.change-status');

// Seller Product Routes
Route::get('/seller-products', [SellerProductController::class, 'index'])->name('seller-products.index');
Route::get('/seller-pending-products', [SellerProductController::class, 'pendingProducts'])->name('seller-pending-products.index');
Route::put('/change-approve-status', [SellerProductController::class, 'changeApproveStatus'])->name('change-approve-status');

// Flash Sale Routes
Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale.index');
Route::put('/flash-sale', [FlashSaleController::class, 'update'])->name('flash-sale.update');
Route::post('/flash-sale/add-product', [FlashSaleController::class, 'addProduct'])->name('flash-sale.add-product');
Route::delete('/flash-sale/{id}/destroy', [FlashSaleController::class, 'destroy'])->name('flash-sale.destroy');
Route::put('/flash-sale/change-status', [FlashSaleController::class, 'changeStatus'])->name('flash-sale.change-status');
Route::put('/flash-sale/change-show-at-home', [FlashSaleController::class, 'changeShowAtHome'])->name('flash-sale.change-show-at-home');

// Coupon Routes
Route::put('coupon/change-status', [CouponController::class, 'changeStatus'])->name('coupon.change-status');
Route::resource('coupons', CouponController::class);

// Shipping Rule Routes
Route::put('shipping-rule/change-status', [ShippingRuleController::class, 'changeStatus'])->name('shipping-rule.change-status');
Route::resource('shipping-rule', ShippingRuleController::class);

// Order Routes
Route::put('orders/order-status', [OrderController::class, 'changeOrderStatus'])->name('orders.order-status');
Route::put('orders/payment-status', [OrderController::class, 'changePaymentStatus'])->name('orders.payment-status');
Route::get('orders/pending', [OrderController::class, 'pendingOrders'])->name('pending-orders');
Route::get('orders/confirmed', [OrderController::class, 'confirmedOrders'])->name('confirmed-orders');
Route::get('orders/processing', [OrderController::class, 'processingOrders'])->name('processing-orders');
Route::get('orders/shipped', [OrderController::class, 'shippedOrders'])->name('shipped-orders');
Route::get('orders/completed', [OrderController::class, 'completedOrders'])->name('completed-orders');
Route::get('orders/cancelled', [OrderController::class, 'cancelledOrders'])->name('cancelled-orders');
Route::get('orders/out-for-delivery', [OrderController::class, 'outForDeliveryOrders'])->name('out-for-delivery-orders');
Route::resource('orders', OrderController::class);

// Transaction Route
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

// Homepage Setting Routes
Route::get('homepage/settings', [HomepageSettingController::class, 'index'])->name('homepage.settings.index');
Route::put('popular-category-section', [HomepageSettingController::class, 'updatePopularCategorySection'])->name('popular-category-section');
Route::put('product-slider-one', [HomepageSettingController::class, 'updateProductSliderOne'])->name('product-slider-one');
Route::put('product-slider-two', [HomepageSettingController::class, 'updateProductSliderTwo'])->name('product-slider-two');
Route::put('product-slider-three', [HomepageSettingController::class, 'updateProductSliderThree'])->name('product-slider-three');

// Settings Routes
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/general-settings-update', [SettingController::class, 'generalSettingUpdate'])->name('general-settings.update');
Route::put('/email-settings-update', [SettingController::class, 'emailSettingUpdate'])->name('email-settings.update');
Route::put('/pusher-settings-update', [SettingController::class, 'pusherSettingUpdate'])->name('pusher-settings.update');
Route::put('/logo-update', [SettingController::class, 'logoUpdate'])->name('logo.update');
Route::put('/chatbot-settings-update', [SettingController::class, 'chatbotSettingUpdate'])->name('chatbot-settings.update');

// Vendor Request Routes
Route::get('/vendors-request', [VendorRequestController::class, 'index'])->name('vendors-request.index');
Route::put('vendors-request/status', [VendorRequestController::class, 'changeStatus'])->name('vendors-request.change-status');

// Vendor Routes
Route::get('/vendor', [VendorListController::class, 'index'])->name('vendor.index');
Route::put('vendor/status', [VendorListController::class, 'changeStatus'])->name('vendor.change-status');
Route::get('/vendor-condition', [VendorConditionController::class, 'index'])->name('vendor-condition.index');
Route::post('/vendor-condition/update', [VendorConditionController::class, 'update'])->name('vendor-condition.update');

// Customer List Routes
Route::get('/customer', [CustomerListController::class, 'index'])->name('customer.index');
Route::put('customer/status', [CustomerListController::class, 'changeStatus'])->name('customer.change-status');

// Manage User Routes
Route::get('/manage-user', [ManageUserController::class, 'index'])->name('manage-user.index');
Route::post('/manage-user', [ManageUserController::class, 'create'])->name('manage-user.create');

// Admin List Routes
Route::get('/admin-list', [AdminListController::class, 'index'])->name('admin-list.index');
Route::put('admin-list/status', [AdminListController::class, 'changeStatus'])->name('admin-list.change-status');
Route::delete('admin-list/{id}', [AdminListController::class, 'destroy'])->name('admin-list.destroy');

// Advertisement Routes
Route::get('/advertisement', [AdvertisementController::class, 'index'])->name('advertisement.index');
Route::put('/advertisement/homepage-banner-one', [AdvertisementController::class, 'homepageBannerOne'])->name('advertisement.homepage-banner-one');
Route::put('/advertisement/homepage-banner-two', [AdvertisementController::class, 'homepageBannerTwo'])->name('advertisement.homepage-banner-two');
Route::put('/advertisement/homepage-banner-three', [AdvertisementController::class, 'homepageBannerThree'])->name('advertisement.homepage-banner-three');
Route::put('/advertisement/homepage-banner-four', [AdvertisementController::class, 'homepageBannerFour'])->name('advertisement.homepage-banner-four');
Route::put('/advertisement/product-banner', [AdvertisementController::class, 'productBanner'])->name('advertisement.product-banner');
Route::put('/advertisement/cart-banner', [AdvertisementController::class, 'cartBanner'])->name('advertisement.cart-banner');

// Payment Routes
Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-setting.index');
Route::resource('paypal-setting', PaypalSettingController::class)->only(['update']);
Route::resource('vnpay-setting', VNPaySettingController::class)->only(['update']);

// Blog Category Routes
Route::put('blog-category/change-status', [BlogCategoryController::class, 'changeStatus'])->name('blog-category.change-status');
Route::resource('blog-category', BlogCategoryController::class);

// Blog Routes
Route::put('blog/change-status', [BlogController::class, 'changeStatus'])->name('blog.change-status');
Route::resource('blog', BlogController::class);

// Message Routes
Route::get('messenger', [AdminMessageController::class, 'index'])->name('messenger.index');
Route::post('messenger/send-message', [AdminMessageController::class, 'sendMessage'])->name('send-message');
Route::get('messenger/get-message/{id}', [AdminMessageController::class, 'getMessage'])->name('get-message');
