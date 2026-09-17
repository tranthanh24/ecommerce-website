<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorShopProfileSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::where('email', 'vendor@gmail.com')->first();

    $vendor = new Vendor();
    $vendor->banner = 'https://byvn.net/HnfM';
    $vendor->shop_name = 'Vendor Shop';
    $vendor->phone = '0123 456 789';
    $vendor->address = 'TP. Hồ Chí Minh';
    $vendor->description = 'Test description.';
    $vendor->email = 'vendor@gmail.com';
    $vendor->user_id = $user->id;
    $vendor->status = 1;
    $vendor->save();
  }
}
