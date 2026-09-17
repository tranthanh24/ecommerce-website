<?php

return [
  'order_status_admin' => [
    'pending' => [
      'status' => 'Chờ xác nhận',
      'detail' => 'Đơn hàng của bạn đang chờ xác nhận.'
    ],
    'confirmed' => [
      'status' => 'Đã xác nhận',
      'detail' => 'Đơn hàng của bạn đã được xác nhận.'
    ],
    'processing' => [
      'status' => 'Đang xử lý',
      'detail' => 'Đơn hàng của bạn đang được xử lý.'
    ],
    'shipped' => [
      'status' => 'Đã gửi hàng',
      'detail' => 'Đơn hàng của bạn đã được gửi đi.'
    ],
    'out_for_delivery' => [
      'status' => 'Đang giao',
      'detail' => 'Đơn hàng của bạn đang trên đường giao đến bạn.'
    ],
    'completed' => [
      'status' => 'Hoàn thành',
      'detail' => 'Đơn hàng của bạn đã giao thành công.'
    ],
    'cancelled' => [
      'status' => 'Đã hủy',
      'detail' => 'Đơn hàng của bạn đã bị hủy.'
    ],
  ],

  'order_status_vendor' => [
    'pending' => [
      'status' => 'Chờ xác nhận',
      'detail' => 'Đơn hàng đang chờ bạn xác nhận.'
    ],
    'confirmed' => [
      'status' => 'Đã xác nhận',
      'detail' => 'Bạn đã xác nhận đơn hàng.'
    ],
    'processing' => [
      'status' => 'Đang xử lý',
      'detail' => 'Bạn đang chuẩn bị đơn hàng.'
    ],
    'shipped' => [
      'status' => 'Đã gửi hàng',
      'detail' => 'Bạn đã bàn giao cho đơn vị vận chuyển.'
    ],
  ],
];
