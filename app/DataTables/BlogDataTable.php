<?php

namespace App\DataTables;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class BlogDataTable extends DataTable
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
        $editBtn =
          "<a href='" . route('admin.blog.edit', $query->id) . "' class='btn btn-primary'>
            <i class='fas fa-edit'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('admin.blog.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        return $editBtn . $deleteBtn;
      })
      ->addColumn('image', function ($query) {
        return "<img width='70px' src='" . asset($query->image) . "' />";
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
      ->addColumn('category', function ($query) {
        return $query->category->name;
      })
      ->addColumn('date', function ($query) {
        return date('d-m-Y', strtotime($query->created_at));
      })
      ->rawColumns(['action', 'image', 'status'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Blog $model): QueryBuilder
  {
    return $model::with('category')->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('blog-table')
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
      Column::make('image')->title('Hình ảnh')->width(120)->addClass('text-center'),
      Column::make('title')->title('<span style="display:block;text-align:center">Tiêu đề</span>')
        ->addClass('text-justify'),
      Column::make('category')->title('Danh mục')->width(180)->addClass('text-center'),
      Column::make('date')->title('Ngày đăng')->width(120),
      Column::make('status')->title('Trạng thái')->width(120)->addClass('text-center'),
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
    return 'Blog_' . date('YmdHis');
  }
}
