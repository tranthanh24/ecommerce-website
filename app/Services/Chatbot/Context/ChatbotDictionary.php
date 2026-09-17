<?php

namespace App\Services\Chatbot\Context;

final class ChatbotDictionary
{
  public const QUERY_SIMPLIFY_MODIFIERS = ['ban chay nhat', 'tot nhat', 'hot nhat', 'gia re', 'cao cap', 'chat luong cao', 'noi bat'];
  public const CATALOG_NOISE_TOKENS = ['gb', 'tb', 'mah', 'w', '5g', '4g', '2023', '2024', '2025', '2026', 'usb', 'type', 'inch', 'hz', 'vn', 'propanel'];
  public const RANK_MOST_EXPENSIVE_HINTS = ['dat nhat', 'cao nhat', 'max gia', 'gia cao nhat', 'mac nhat'];
  public const RANK_CHEAPEST_HINTS = ['re nhat', 'thap nhat', 'min gia', 'gia thap nhat'];
  public const CART_CANDIDATE_NOISE = ['them', 'vao', 'bo', 'gio', 'hang', 'mua', 'dat', 'cho', 'toi', 'minh', 'em', 'anh', 'chi', 'nhe', 'di', 'vui', 'long', 'cai', 'chiec', 'sp', 'san', 'pham', 'con', 'dat', 'nhat', 're', 'cao', 'thap', 'gia', 'max', 'min'];
  public const CART_MESSAGE_NOISE = ['them', 'vao', 'gio', 'gio hang', 'di', 'nhe', 'giup', 'cho', 'toi', 'minh', 'em', 'anh', 'chi', 'hay', 'vui long', 'mua', 'dat'];
  public const CART_NOUNS_LOWER = ['giỏ', 'giỏ hàng'];
  public const CART_NOUNS_ASCII = ['gio', 'gio hang', 'cart'];
  public const CART_ADD_VERBS_LOWER = ['thêm', 'bỏ', 'cho vào'];
  public const CART_ADD_VERBS_ASCII = ['them', 'bo', 'cho vao', 'add'];
  public const ADD_TO_CART_PHRASES = ['add to cart'];
  public const ORDER_ACTION_LOWER = ['đặt đơn', 'thanh toán', 'chốt đơn', 'checkout', 'place order'];
  public const ORDER_ACTION_ASCII = ['dat don', 'thanh toan', 'chot don', 'checkout', 'place order'];
  public const PRICE_MOST_EXPENSIVE_HINTS = ['đắt nhất', 'giá cao nhất', 'cao nhất', 'đắt', 'dat nhat', 'gia cao nhat', 'cao nhat', 'mac nhat', 'max'];
  public const PRICE_CHEAPEST_HINTS = ['rẻ nhất', 'giá thấp nhất', 'thấp nhất', 'rẻ', 're nhat', 'gia thap nhat', 'thap nhat', 'min'];
  public const PRICE_MOST_EXPENSIVE_COMPACT_HINTS = ['dat nhat', 'gia cao nhat', 'cao nhat'];
  public const PRICE_CHEAPEST_COMPACT_HINTS = ['re nhat', 'gia thap nhat', 'thap nhat'];
}
