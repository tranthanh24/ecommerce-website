<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ShippingRuleDataTable;
use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use Illuminate\Http\Request;

class ShippingRuleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(ShippingRuleDataTable $dataTable)
  {
    return $dataTable->render('admin.shipping-rule.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.shipping-rule.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, ShippingRule $shippingRule)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'type' => ['required', 'string', 'in:flat_cost,min_cost'],
      'min_cost' => ['nullable', 'numeric'],
      'cost' => ['required', 'numeric'],
      'status' => ['required', 'boolean'],
    ]);

    $shippingRule->name = $request->name;
    $shippingRule->type = $request->type;
    $shippingRule->min_cost = $request->min_cost;
    $shippingRule->cost = $request->cost;
    $shippingRule->status = $request->status;
    $shippingRule->save();

    toastr()->success('Quy tắc vận chuyển đã được thêm mới thành công');

    return redirect()->back();
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $shippingRule = ShippingRule::findOrFail($id);
    return view('admin.shipping-rule.edit', compact('shippingRule'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $shippingRule = ShippingRule::findOrFail($id);

    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'type' => ['required', 'string', 'in:flat_cost,min_cost'],
      'min_cost' => ['nullable', 'numeric'],
      'cost' => ['required', 'numeric'],
      'status' => ['required', 'boolean'],
    ]);

    $shippingRule->name = $request->name;
    $shippingRule->type = $request->type;
    $shippingRule->min_cost = $request->min_cost;
    $shippingRule->cost = $request->cost;
    $shippingRule->status = $request->status;
    $shippingRule->save();

    toastr()->success('Quy tắc vận chuyển đã được cập nhật thành công');

    return redirect()->route('admin.shipping-rule.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $shippingRule = ShippingRule::findOrFail($id);
    $shippingRule->delete();

    return response(['status' => 'success', 'message' => 'Quy tắc vận chuyển đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $shippingRule = ShippingRule::findOrFail($request->id);

    $shippingRule->status = $request->isChecked === true ? 1 : 0;

    $shippingRule->save();

    return response(['status' => 'success']);
  }
}
