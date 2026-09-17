<?php

namespace App\DataTables;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class UserReviewDataTable extends DataTable
{
  /**
   * Build the DataTable class.
   *
   * @param QueryBuilder $query Results from query() method.
   */
  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    return (new EloquentDataTable($query))
      ->addColumn('product', function ($query) {
        return '<a href="' . route('product-detail', $query->product->slug) . '" class="text-primary text-decoration-none">' . $query->product->name . '</a>';
      })
      ->addColumn('rating', function ($query) {
        $stars = '';
        for ($i = 0; $i < $query->rating; $i++) {
          $stars .= '<i class="fas fa-star text-warning"></i>';
        }
        return $stars;
      })
      ->rawColumns(['rating', 'product'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Review $model): QueryBuilder
  {
    return $model->newQuery()->where('user_id', auth()->user()->id);
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('userreview-table')
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
      Column::make('id')->width(40)->title('Mã')->addClass('text-center'),
      Column::make('product')->width(300)->title('Sản phẩm')->addClass('text-center'),
      Column::make('rating')->width(180)->title('Sao')->addClass('text-center'),
      Column::make('review')->title('<span style="display:block;text-align:center">Nội dung đánh giá</span>')
        ->addClass('text-justify'),
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return 'UserReview_' . date('YmdHis');
  }
}
