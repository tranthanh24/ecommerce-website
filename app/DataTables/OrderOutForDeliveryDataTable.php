<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;

class OrderOutForDeliveryDataTable extends DataTable
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
        return limitText($query->invoice_id, 25);
      })
      ->addColumn('customer', function ($query) {
        return $query->user->name;
      })
      ->addColumn('date', function ($quert) {
        return date('d-m-Y', strtotime($quert->created_at));
      })
      ->addColumn('amount', function ($query) {
        return formatCurrency($query->amount);
      })
      ->addColumn('order_status', function ($query) {
        switch ($query->order_status) {
          case 'pending':
            return "<span class='badge badge-primary'>⏳ Chờ xác nhận</span>";
          case 'confirmed':
            return "<span class='badge badge-info'>✅ Đã xác nhận</span>";
          case 'processing':
            return "<span class='badge badge-warning'>🔄 Đang xử lý</span>";
          case 'shipped':
            return "<span class='badge badge-light'>🚚 Đã gửi hàng</span>";
          case 'out_for_delivery':
            return "<span class='badge badge-secondary'>📦 Đang giao</span>";
          case 'completed':
            return "<span class='badge badge-success'>🏁 Hoàn thành</span>";
          case 'cancelled':
            return "<span class='badge badge-dark'>❌ Đã hủy</span>";
          default:
            break;
        }
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
      ->addColumn('payment_status', function ($query) {
        return $query->payment_status === 1 ? "<span class='badge badge-success'>Đã thanh toán</span>" : "<span class='badge badge-warning'>Chưa thanh toán</span>";
      })
      ->rawColumns(['action', 'invoice_id', 'order_status', 'payment_method', 'payment_status'])
      ->setRowId('id');
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Order $model): QueryBuilder
  {
    return $model->where('order_status', 'out_for_delivery')->newQuery();
  }

  /**
   * Optional method if you want to use the html builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId('order-table')
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
      Column::make('invoice_id')->width(140)->title('Mã hóa đơn')->addClass('text-center'),
      Column::make('customer')->width(140)->title('Khách hàng')->addClass('text-center'),
      Column::make('date')->width(160)->title('Ngày đặt hàng')->addClass('text-center'),
      Column::make('product_qty')->width(120)->title('Số lượng')->addClass('text-center'),
      Column::make('amount')->width(120)->title('Tổng tiền')->addClass('text-center'),
      Column::make('order_status')->title('Trạng thái đặt hàng')->width(200)->addClass('text-center'),
      Column::make('payment_method')->title('Thanh toán')->width(140)->addClass('text-center'),
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
    return 'Order_' . date('YmdHis');
  }
}
