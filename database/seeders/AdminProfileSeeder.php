<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminProfileSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::where('email', 'admin@gmail.com')->first();

    $vendor = new Vendor();
    $vendor->banner = 'https://byvn.net/HnfM';
    $vendor->shop_name = 'Admin Shop';
    $vendor->phone = '0123 456 789';
    $vendor->address = 'TP. Hồ Chí Minh';
    $vendor->description = 'Test description.';
    $vendor->email = 'admin@gmail.com';
    $vendor->user_id = $user->id;
    $vendor->status = 1;
    $vendor->save();
  }
}
