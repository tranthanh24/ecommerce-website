<?php

namespace App\DataTables;

use App\Models\ProductVariantItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class VendorProductVariantItemDataTable extends DataTable
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
          "<a href='" . route('vendor.products-variant-item.edit', $query->id) . "' class='btn btn-primary ml-2'>
            <i class='fas fa-edit'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('vendor.products-variant-item.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        return $editBtn . $deleteBtn;
      })
      ->addColumn('product_variant', function ($query) {
        return optional($query->productVariant)->name;
      })
      ->addColumn('is_default', function ($query) {
        $active = "<i class='badge bg-success'>Có</>";
        $inActive = "<i class='badge bg-danger'>Không</>";
        if ($query->is_default === 1) return $active;
        return $inActive;
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
      ->rawColumns(['action', 'status', 'is_default'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(ProductVariantItem $model): QueryBuilder
  {
    return $model->where('product_variant_id', request()->variantId)->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('vendorproductvariantitem-table')
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
      Column::make('id')->width(80)->title('Mã')->addClass('text-center'),
      Column::make('name')->title('Tên'),
      Column::make('product_variant')->title('Phiên bản')->addClass('text-center'),
      Column::make('price')->title('Giá')->addClass('text-center')->width(160),
      Column::make('is_default')->title('Mặc định')->addClass('text-center')->width(160),
      Column::make('status')->title('Trạng thái')->width(160)->addClass('text-center'),
      Column::computed('action')->title('Thao tác')
        ->exportable(false)
        ->printable(false)
        ->width(160)
        ->addClass('text-center'),
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return 'VendorProductVariantItem_' . date('YmdHis');
  }
}
