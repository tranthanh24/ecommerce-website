<?php

namespace App\DataTables;

use App\Models\FlashSaleItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class FlashSaleItemDataTable extends DataTable
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
        $deleteBtn =
          "<a href='" . route('admin.flash-sale.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        return $deleteBtn;
      })
      ->addColumn('product_name', function ($query) {
        return
          "<a href='" . route('admin.products.edit', $query->product_id) . "' class='text-decoration-none'>
          " . optional($query->product)->name . "
        </a>";
      })
      ->addColumn('show_at_home', function ($query) {
        if ($query->show_at_home === 1) {
          $button = '<label class="custom-switch mt-2" style="display: inline-block; transform: scale(1.5);">
            <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '"  class="custom-switch-input change-show-at-home"> <span class="custom-switch-indicator"></span></label>';
        } else {
          $button = '<label class="custom-switch mt-2" style="display: inline-block; transform: scale(1.5);">
            <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '"
            class="custom-switch-input change-show-at-home"> <span class="custom-switch-indicator"></span></label>';
        }
        return $button;
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
      ->rawColumns(['action', 'product_name', 'show_at_home', 'status'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(FlashSaleItem $model): QueryBuilder
  {
    return $model->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('flashsaleitem-table')
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
      Column::make('id')->width(100)->title('Mã')->addClass('text-center'),
      Column::make('product_name')->title('Sản phẩm')->addClass('text-center'),
      Column::make('show_at_home')->title('Gắn lên trang chủ')->width(180)->addClass('text-center'),
      Column::make('status')->title('Trạng thái')->width(180)->addClass('text-center'),
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
    return 'FlashSaleItem_' . date('YmdHis');
  }
}
