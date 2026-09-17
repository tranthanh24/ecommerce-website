<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
  public function addToCart(Request $request)
  {
    $product = Product::findOrFail($request->product_id);

    // Check product quantity
    if ($product->qty === 0) {
      return response(['status' => 'stock_out', 'message' => 'Sản phẩm đã hết']);
    } elseif ($product->qty < $request->qty) {
      return response(['status' => 'stock_not_available', 'message' => 'Số lượng không đủ, vui lòng chọn ít hơn']);
    }

    // Calculate total add price from variants
    $variants = [];
    $variantTotalAmount = 0;
    if ($request->has('variants_items')) {
      foreach ($request->variants_items as $item_id) {
        $variantItem = ProductVariantItem::findOrFail($item_id);

        $variants[$variantItem->productVariant->name] = [
          'name' => $variantItem->name,
          'price' => $variantItem->price,
        ];
        $variantTotalAmount += $variantItem->price;
      };
    };

    // Check product price
    $productPrice = 0;
    if (checkDiscount($product)) {
      $productPrice = $product->offer_price;
    } else {
      $productPrice = $product->price;
    };

    $cartData = [
      'id' => $product->id,
      'name' => $product->name,
      'qty' => $request->qty,
      'price' => $productPrice,
      'weight' => $product->weight ?? 0,
      'options' => [
        'variants' => $variants,
        'variant_total' => $variantTotalAmount,
        'image' => $product->thumb_image,
        'slug' => $product->slug,
      ]
    ];

    Cart::add($cartData);

    return response(['status' => 'success', 'message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công']);
  }

  /* Show cart detail */
  public function cartDetail()
  {
    $cartItems = Cart::content();

    if (count($cartItems) === 0) {
      Session::forget('coupon');
    }

    return view('frontend.pages.cart-detail', compact('cartItems'));
  }

  /* Calculate the total price of a product */
  public function getProductTotal($rowId)
  {
    $product = Cart::get($rowId);
    $total = ($product->price + $product->options->variant_total) * $product->qty;

    return $total;
  }

  /* Calculate cart total amount */
  public function cartTotal()
  {
    $total = 0;
    foreach (Cart::content() as $product) {
      $total += $this->getProductTotal($product->rowId);
    };

    return response(['total' => $total]);
  }

  /* Update the quantity */
  public function cartUpdateQty(Request $request)
  {
    // Check product quantity
    $product_id = Cart::get($request->rowId)->id;
    $product = Product::findOrFail($product_id);
    if ($product->qty === 0) {
      return response(['status' => 'stock_out', 'message' => 'Sản phẩm đã hết']);
    } elseif ($product->qty < $request->qty) {
      return response(['status' => 'stock_not_available', 'message' => 'Số lượng không đủ, vui lòng chọn ít hơn']);
    }

    Cart::update($request->rowId, $request->qty);
    $productTotal = $this->getProductTotal($request->rowId);

    return response(['status' => 'success', 'message' => 'Số lượng sản phẩm đã được cập nhật thành công', 'product_total' => $productTotal]);
  }

  /* Remove a product */
  public function removeProduct($rowId)
  {
    Cart::remove($rowId);

    toastr()->success('Sản phẩm đã được xóa thành công');

    return redirect()->back();
  }

  /* Remove a product from sidebar cart */
  public function removeSidebarProduct(Request $request)
  {
    Cart::remove($request->rowId);

    return response(['status' => 'success', 'message' => 'Sản phẩm đã được xóa thành công']);
  }

  /* Clear all items */
  public function clearCart()
  {
    Cart::destroy();

    return response(['status' => 'success', 'message' => 'Giỏ hàng đã được xóa thành công']);
  }

  /* Get the total number of items */
  public function getCartCount()
  {
    $count = Cart::content()->count();

    return response(['count' => $count]);
  }

  /* Get all products */
  public function getCartProducts()
  {
    $products = Cart::content();

    return response(['products' => $products]);
  }

  /* Apply coupon */
  public function applyCoupon(Request $request)
  {
    if ($request->coupon_code === null) {
      return response(['status' => 'error', 'message' => 'Vui lòng nhập mã giảm giá']);
    }

    $coupon = Coupon::where(['code' => $request->coupon_code, 'status' => 1])->first();

    $today = now()->toDateString();
    if ($coupon === null) {
      return response(['status' => 'error', 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn']);
    } elseif ($coupon->start_date > $today) {
      return response(['status' => 'error', 'message' => 'Mã giảm giá chưa bắt đầu']);
    } elseif ($coupon->end_date < $today) {
      return response(['status' => 'error', 'message' => 'Mã giảm giá đã hết hạn']);
    } elseif ($coupon->total_used >= $coupon->quantity) {
      return response(['status' => 'error', 'message' => 'Mã giảm giá đã hết lượt sử dụng']);
    }

    if ($coupon->discount_type === 'amount') {
      Session::put('coupon', [
        'coupon_name' => $coupon->name,
        'coupon_code' => $coupon->code,
        'discount_type' => 'amount',
        'discount' => $coupon->discount_value,
      ]);
    } elseif ($coupon->discount_type === 'percent') {
      Session::put('coupon', [
        'coupon_name' => $coupon->name,
        'coupon_code' => $coupon->code,
        'discount_type' => 'percent',
        'discount' => $coupon->discount_value,
      ]);
    }

    return response(['status' => 'success', 'message' => 'Mã giảm giá đã được áp dụng thành công']);
  }

  /* Calculate coupon discount */
  public function calculateCoupon()
  {
    $subTotal = getCartTotal();

    if (Session::has('coupon')) {
      $coupon = Session::get('coupon');
      if ($coupon['discount_type'] === 'amount') {
        $total = $subTotal - $coupon['discount'];
      } else {
        $total = $subTotal - ($subTotal * $coupon['discount']) / 100;
      }
      $total = max(0, $total);

      return response([
        'status' => 'success',
        'cart_total' => round($total),
        'discount' => $coupon['discount'],
        'discount_type' => $coupon['discount_type']
      ]);
    }

    return response(['status' => 'no_coupon', 'cart_total' => $subTotal]);
  }
}
