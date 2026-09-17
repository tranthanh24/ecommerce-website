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

class VendorProductDataTable extends DataTable
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
          "<a href='" . route('vendor.products.edit', $query->id) . "' class='btn btn-primary'>
            <i class='fas fa-edit'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('vendor.products.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        $moreButton =
          '<div class="btn-group dropstart" style="margin-left: 4px">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
               <i class="fas fa-cog"></i>
            </button>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="' . route('vendor.products-image-gallery.index', ['product' => $query->id]) . '"><i class="fas fa-images"></i> Thư viện ảnh</a></li>
              <li><a class="dropdown-item" href="' . route('vendor.products-variant.index', ['product' => $query->id]) . '"><i class="far fa-copy"></i> Phiên bản sản phẩm</a></li>
            </ul>
          </div>';
        return $editBtn . $deleteBtn . $moreButton;
      })
      ->addColumn('image', function ($query) {
        return "<img width='70px' src='" . asset($query->thumb_image) . "' />";
      })
      ->addColumn('type', function ($query) {
        switch ($query->product_type) {
          case 'new_arrival':
            return "<i class='badge bg-success'>Mới</>";
            break;
          case 'top_product':
            return "<i class='badge bg-info'>Nổi bật</>";
            break;
          case 'featured_product':
            return "<i class='badge bg-warning'>Đề cử</>";
            break;
          case 'best_product':
            return "<i class='badge bg-danger'>Bán chạy</>";
            break;
          default:
            return "<i class='badge bg-dark'>Không</>";
            break;
        }
      })
      ->addColumn('is_default', function ($query) {
        $active = "<i class='badge badge-success'>Có</>";
        $inActive = "<i class='badge badge-danger'>Không</>";
        if ($query->is_default === 1) return $active;
        return $inActive;
      })
      ->addColumn('approved', function ($query) {
        if ($query->is_approved === 1) {
          return "<i class='badge bg-success'>Rồi</>";
        } else {
          return "<i class='badge bg-warning'>Chưa</>";
        }
      })
      ->addColumn('status', function ($query) {
        if ($query->status === 1) {
          $button =
            '<div class="d-flex justify-content-center">
              <div class="form-check form-switch">
                <input class="form-check-input change-status" style="border-radius: 10%" checked type="checkbox" id="flexSwitchCheckDefault" data-id="' . $query->id . '">
              </div>
            </div>';
        } else {
          $button =
            '<div class="d-flex justify-content-center">
              <div class="form-check form-switch">
                <input class="form-check-input change-status" type="checkbox" id="flexSwitchCheckDefault" data-id="' . $query->id . '">
              </div>
            </div>';
        }
        return $button;
      })
      ->rawColumns(['action', 'image', 'type', 'approved', 'status', 'is_default'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Product $model): QueryBuilder
  {
    return $model->where("vendor_id", auth()->user()->vendor->id)->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('vendorproduct-table')
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
      Column::make('image')->title('Hình ảnh')->width(160)->addClass('text-center'),
      Column::make('name')->title('Tên'),
      Column::make('price')->title('Giá')->width(120)->addClass('text-center'),
      Column::make('type')->title('Loại')->width(120)->addClass('text-center'),
      Column::make('approved')->title('Phê duyệt')->width(120)->addClass('text-center'),
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
    return 'VendorProduct_' . date('YmdHis');
  }
}
