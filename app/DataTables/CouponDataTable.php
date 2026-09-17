<?php

namespace App\DataTables;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
{
  /**
   * Build the DataTable class.
   *
   * @param QueryBuilder $query Results from query() method.
   */
  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    return (new EloquentDataTable($query))
      ->editColumn('discount_value', function ($row) {
        if ($row->discount_type === 'amount') {
          return formatCurrency($row->discount_value);
        } else {
          return $row->discount_value . '%';
        }
      })
      ->addColumn('action', function ($query) {
        $editBtn =
          "<a href='" . route('admin.coupons.edit', $query->id) . "' class='btn btn-primary'>
            <i class='fas fa-edit'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('admin.coupons.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        return $editBtn . $deleteBtn;
      })
      ->addColumn('type', function ($query) {
        switch ($query->discount_type) {
          case 'percent':
            return "<i class='badge badge-success'>Phần trăm</>";
            break;
          case 'amount':
            return "<i class='badge badge-info'>Số tiền</>";
            break;
          default:
            return "<i class='badge badge-dark'>Không</>";
            break;
        }
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
      ->rawColumns(['action', 'type', 'status'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Coupon $model): QueryBuilder
  {
    return $model->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('coupon-table')
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
      Column::make('name')->title('Tên')->addClass('text-center'),
      Column::make('type')->title('Loại giảm giá')->width(160)->addClass('text-center'),
      Column::make('discount_value')->title('Giá trị')->width(120)->addClass('text-center'),
      Column::make('start_date')->title('Ngày bắt đầu')->width(160)->addClass('text-center'),
      Column::make('end_date')->title('Ngày kết thúc')->width(160)->addClass('text-center'),
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
    return 'Coupon_' . date('YmdHis');
  }
}
