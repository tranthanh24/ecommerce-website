<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class SellerProductDataTable extends DataTable
{
  /**
   * Build the DataTable class.
   *
   * @param QueryBuilder $query Results from query() method.
   */
  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    return (new EloquentDataTable($query))
      ->editColumn('price', function ($row) {
        return formatCurrency($row->price);
      })
      ->addColumn('action', function ($query) {
        $editBtn =
          "<a href='" . route('admin.products.edit', $query->id) . "' class='btn btn-primary'>
            <i class='fas fa-edit'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('admin.products.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        $moreButton =
          '<div class="dropdown dropleft d-inline">
            <button class="btn btn-primary dropdown-toggle ml-1" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-cog"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item has-icon" href="' . route('admin.products-image-gallery.index', ['product' => $query->id]) . '"><i class="fas fa-images"></i> Thư viện ảnh</a>
              <a class="dropdown-item has-icon" href="' . route('admin.products-variant.index', ['product' => $query->id]) . '"><i class="far fa-copy"></i> Phiên bản sản phẩm</a>
            </div>
          </div>';
        return $editBtn . $deleteBtn . $moreButton;
      })
      ->addColumn('image', function ($query) {
        return "<img width='70px' src='" . asset($query->thumb_image) . "' />";
      })
      ->addColumn('vendor', function ($query) {
        return optional($query->vendor)->shop_name;
      })
      ->addColumn('type', function ($query) {
        switch ($query->product_type) {
          case 'new_arrival':
            return "<i class='badge badge-success'>Mới</>";
            break;
          case 'top_product':
            return "<i class='badge badge-info'>Nổi bật</>";
            break;
          case 'featured_product':
            return "<i class='badge badge-warning'>Đề cử</>";
            break;
          case 'best_product':
            return "<i class='badge badge-danger'>Bán chạy</>";
            break;
          default:
            return "<i class='badge badge-dark'>Không</>";
            break;
        }
      })
      ->addColumn('category', function ($query) {
        return optional($query->category)->name;
      })
      ->addColumn('sub_category', function ($query) {
        return optional($query->subCategory)->name;
      })
      ->addColumn('child_category', function ($query) {
        return optional($query->childCategory)->name;
      })
      ->addColumn('approved', function ($query) {
        return
          "<select class='form-control is_approved' name='is_approved' data-id='" . $query->id . "'>
            <option value='0' " . ($query->is_approved == 0 ? 'selected' : '') . ">Chờ duyệt</option>
            <option value='1' " . ($query->is_approved == 1 ? 'selected' : '') . ">Đã duyệt</option>
          </select>";
      })
      ->addColumn('status', function ($query) {
        if ($query->status === 1) {
          $button = '<label class="custom-switch mt-2" style="display: inline-block; transform: scale(1.5);">
            <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '"  class="custom-switch-input change-status"> <span class="custom-switch-indicator"></span></label>';
        } else {
          $button = '<label class="custom-switch mt-2" style="display: inline-block; transform: scale(1.5);">
            <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '"
            class="custom-switch-input change-status"> <span class="custom-switch-indicator"></span></label>';
        }
        return $button;
      })
      ->rawColumns(['action', 'image', 'vendor', 'type', 'approved', 'status'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Product $model): QueryBuilder
  {
    return $model->where("vendor_id", '!=', auth()->user()->vendor->id)->where('is_approved', 1)->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('sellerproduct-table')
      ->columns($this->getColumns())
      ->minifiedAjax()
      //->dom('Bfrtip')
      ->language([
        'url' => asset('vendor/datatables/i18n/vi.json'),
      ])
      ->orderBy(0)
      ->selectStyleSingle()
      ->buttons([
        Button::make('excel'),
        Button::make('csv'),
        Button::make('pdf'),
        Button::make('print'),
        Button::make('reset'),
        Button::make('reload')
      ]);
  }

  /**
   * Get the dataTable columns definition.
   */
  public function getColumns(): array
  {
    return [
      Column::make('id')->width(60)->title('Mã')->addClass('text-center'),
      Column::make('vendor')->title('Cửa hàng')->width(120)->addClass('text-center'),
      Column::make('image')->title('Hình ảnh')->width(120)->addClass('text-center'),
      Column::make('name')->title('<span style="display:block;text-align:center">Tên</span>')
        ->addClass('text-justify'),
      Column::make('price')->title('Giá')->width(100)->addClass('text-center'),
      Column::make('type')->title('Loại')->addClass('text-center'),
      Column::make('approved')->title('Đã duyệt')->width(120)->addClass('text-center'),
      Column::make('status')->title('Trạng thái')->width(120)->addClass('text-center'),
      Column::computed('action')->title('Thao tác')
        ->exportable(false)
        ->printable(false)
        ->width(180)
        ->addClass('text-center'),
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return 'SellerProduct_' . date('YmdHis');
  }
}
