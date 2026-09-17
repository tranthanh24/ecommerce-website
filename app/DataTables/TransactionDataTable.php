<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
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
        $showBtn =
          "<a href='" . route('admin.orders.show', $query->id) . "' class='btn btn-success'>
            <i class='fas fa-eye'></i>
          </a>";
        $deleteBtn =
          "<a href='" . route('admin.orders.destroy', $query->id) . "' class='btn btn-danger delete-item ml-2'>
            <i class='fas fa-trash-alt'></i>
          </a>";
        return $showBtn . $deleteBtn;
      })
      ->addColumn('invoice_id', function ($query) {
        return $query->order->invoice_id;
      })
      ->addColumn('amount', function ($query) {
        return formatCurrency($query->amount);
      })
      ->addColumn('amount_real_currency', function ($query) {
        $currency = $query->amount_real_currency_name;
        if ($currency === 'VND') {
          return formatCurrency($query->amount_real_currency);
        }
        return '$' . number_format($query->amount_real_currency, 2);
      })
      ->addColumn('payment_method', function ($query) {
        switch ($query->payment_method) {
          case 'paypal':
            return "<span class='badge badge-primary'>PayPal</span>";
          case 'vnpay':
            return "<span class='badge badge-info'>VNPay</span>";
          case 'sepay':
            return "<span class='badge badge-success'>Sepay</span>";
          default:
            return "<span class='badge badge-secondary'>Cod</span>";
        }
      })
      ->filterColumn('invoice_id', function ($query, $keyword) {
        $query->whereHas('order', function ($qr) use ($keyword) {
          $qr->where('invoice_id', 'like', "%{$keyword}%");
        });
      })
      ->rawColumns(['action', 'payment_method'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Transaction $model): QueryBuilder
  {
    return $model->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('transaction-table')
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
      Column::make('id')->width(20)->title('Mã')->addClass('text-center'),
      Column::make('invoice_id')->width(340)->title('Mã hóa đơn'),
      Column::make('transaction_id')->width(240)->title('Mã giao dịch'),
      Column::make('payment_method')->title('Thanh toán')->addClass('text-center'),
      Column::make('amount')->title('Tổng tiền')->addClass('text-center'),
      Column::make('amount_real_currency')->title('Số tiền thực tế')->addClass('text-center'),
      Column::computed('action')->title('Thao tác')
        ->exportable(false)
        ->printable(false)
        ->width(120)
        ->addClass('text-center'),
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return 'Transaction_' . date('YmdHis');
  }
}
