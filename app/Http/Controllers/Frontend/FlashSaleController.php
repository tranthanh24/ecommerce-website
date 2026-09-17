<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Advertisement;
use App\Models\FlashSaleItem;

class FlashSaleController extends Controller
{
  public function index()
  {
    $flashSale = FlashSale::first();
    $flashSaleItems = FlashSaleItem::where('status', 1)->orderBy('id', 'asc')->paginate(20);
    $cart_banner = Advertisement::where('key', 'cart_banner')->first();
    $cart_banner = json_decode($cart_banner?->value, true);

    return view('frontend.pages.flash-sale', compact('flashSale', 'flashSaleItems', 'cart_banner'));
  }
}
