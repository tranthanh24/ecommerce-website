<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class AdminListDataTable extends DataTable
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
        if ($query->id !== 1) {
          $deleteBtn =
            "<a href='" . route('admin.admin-list.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
              <i class='fas fa-trash-alt'></i>
            </a>";
          return $deleteBtn;
        }
      })
      ->addColumn('status', function ($query) {
        if ($query->id !== 1) {
          if ($query->status === 'active') {
            $button = '<label class="custom-switch mt-2" style="display: inline-block; transform: scale(1.5);">
            <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '"  class="custom-switch-input change-status"> <span class="custom-switch-indicator"></span></label>';
          } else {
            $button = '<label class="custom-switch mt-2" style="display: inline-block; transform: scale(1.5);">
            <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '"
            class="custom-switch-input change-status"> <span class="custom-switch-indicator"></span></label>';
          }
          return $button;
        }
      })
      ->rawColumns(['status', 'action'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(User $model): QueryBuilder
  {
    return $model->newQuery()->where('role', 'admin');
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('adminlist-table')
      ->columns($this->getColumns())
      ->minifiedAjax()
      //->dom('Bfrtip')
      ->language([
        'url' => asset('vendor/datatables/i18n/vi.json'),
      ])
      ->orderBy(1)
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
      Column::make('name')->title('Tên')->width(200)->addClass('text-center'),
      Column::make('email')->title('Email'),
      Column::make('role')->title('Vai trò')->width(160)->addClass('text-center'),
      Column::make('status')->title('Trạng thái')->width(160)->addClass('text-center'),
      Column::computed('action')->title('Thao tác')
        ->exportable(false)
        ->printable(false)
        ->width(80)
        ->addClass('text-center'),
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return 'AdminList_' . date('YmdHis');
  }
}
