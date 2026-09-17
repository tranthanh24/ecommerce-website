<?php

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

// Check if the current route is active
function setActive(array $routes)
{
  foreach ($routes as $route) {
    if (request()->routeIs($route)) {
      return 'active';
    }
  }
  return '';
}

// Check products discount
function checkDiscount($product)
{
  $currentDate = date('Y-m-d');

  if ($product->offer_price > 0 && $currentDate >= $product->offer_start_date && $currentDate <= $product->offer_end_date) {
    return true;
  }
  return false;
}

// Caculate discount percentage
function calculateDiscountPercentage($originalPrice, $offerPrice)
{
  return round((($originalPrice - $offerPrice) / $originalPrice) * 100);
}

// Format currency
function formatCurrency($amount)
{
  $formatted = number_format($amount, 0, ',', '.');
  $currencyIcon = $setting->currency_icon ?? '₫';

  return $formatted . ' ' . $currencyIcon;
}

// Check the product type
function checkProductType(?string $type)
{
  switch ($type) {
    case 'new_arrival':
      return 'Mới';
    case 'top_product':
      return 'Nổi bật';
    case 'featured_product':
      return 'Đề cử';
    case 'best_product':
      return 'Bán chạy';
    default:
      return '';
  }
}

// Get total cart amount
function getCartTotal()
{
  $total = 0;
  foreach (Cart::content() as $product) {
    $total += ($product->price + $product->options->variant_total) * $product->qty;
  };

  return $total;
}

// Get payable total cart amount
function getPayableCartTotal()
{
  $subTotal = getCartTotal();

  if (Session::has('coupon')) {
    $coupon = Session::get('coupon');
    if ($coupon['discount_type'] === 'amount') {
      $total = $subTotal - $coupon['discount'];
    } else {
      $total = $subTotal - ($subTotal * $coupon['discount']) / 100;
    }
    return round($total);
  } else {
    return $subTotal;
  }
}

// Get cart discount
function cartDiscount()
{
  if (Session::has('coupon')) {
    $coupon = Session::get('coupon');
    if ($coupon['discount_type'] === 'amount') {
      return formatCurrency($coupon['discount']);
    } else {
      return $coupon['discount'] . ' %';
    }
  } else {
    return 0;
  }
}

// Get shipping fee
function getShippingFee()
{
  if (Session::has('shipping_method')) {
    return Session::get('shipping_method')['cost'];
  } else {
    return 0;
  }
}

// Get final amount
function getFinalPayableAmount()
{
  return getPayableCartTotal() + getShippingFee();
}

// Limit text
function limitText($text, $limit = 20)
{
  $length = mb_strlen($text);

  if ($length > $limit) {
    return Str::limit($text, $limit);
  }

  return e($text) . str_repeat(' &nbsp;', $limit - $length);
}

// Display rating
function rating($reviews, $column)
{
  $avgRating = (float) ($reviews->avg($column) ?? 0);
  $full = floor($avgRating);
  $half = $avgRating - $full >= 0.5 ? true : false;

  $stars = '';

  for ($i = 0; $i < $full; $i++) {
    $stars .= '<i class="fas fa-star"></i>';
  }

  if ($half) {
    $stars .= '<i class="fas fa-star-half-alt"></i>';
  }

  $empty = 5 - $full - ($half ? 1 : 0);
  for ($i = 0; $i < $empty; $i++) {
    $stars .= '<i class="far fa-star"></i>';
  }

  return $stars;
}

function wishlistIcon($productId)
{
  $user = auth()->user();

  if (!$user) return 'fal';

  return $user->wishlist->contains('product_id', $productId) ? 'fas' : 'fal';
}
