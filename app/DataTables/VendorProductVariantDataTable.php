<?php

namespace App\DataTables;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class VendorProductVariantDataTable extends DataTable
{
  /**
   * Build the DataTable class.
   *
   * @param QueryBuilder $query Results from query() method.
   */
  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    return (new EloquentDataTable($query))
      ->addColumn('action', function ($query) {
        $variant =
          "<a href='" . route('vendor.products-variant-item.index', ['productId' => request()->product, 'variantId' => $query->id]) . "' class='btn btn-success' style='margin-right: 4px'>
            <i class='fas fa-cogs'></i> Phiên bản
          </a>";
        $editBtn =
          "<a href='" . route('vendor.products-variant.edit', $query->id) . "' class='btn btn-primary ml-2'>
            <i class='fas fa-edit'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('vendor.products-variant.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        return $variant . $editBtn . $deleteBtn;
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
      ->rawColumns(['action', 'status'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(ProductVariant $model): QueryBuilder
  {
    return $model->newQuery()->where('product_id', $this->request->product);
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('vendorproductvariant-table')
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
      Column::make('id')->width(120)->title('Mã')->addClass('text-center'),
      Column::make('name')->title('Tên'),
      Column::make('status')->title('Trạng thái')->width(240)->addClass('text-center'),
      Column::computed('action')->title('Thao tác')
        ->exportable(false)
        ->printable(false)
        ->width(240)
        ->addClass('text-center'),
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return 'VendorProductVariant_' . date('YmdHis');
  }
}
