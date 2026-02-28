<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            // Payment Amount Format
            ->editColumn('payment_amount', function ($row) {
                return '$' . number_format((float)$row->payment_amount, 2);
            })
            ->addColumn('gateway', function ($row) {
                return '<span class="badge bg-info text-dark">'
                        . strtoupper($row->gateway) .
                       '</span>';
            })
            ->editColumn('payment_id', function ($row) {
                return '<small class="text-muted">'
                        . Str::limit($row->payment_id, 15) .
                       '</small>';
            })
            ->editColumn('date', function ($row) {
                return \Carbon\Carbon::createFromTimestamp($row->date)
                        ->format('d M Y H:i');
            })
            ->addColumn('action', function ($row) {

                return '
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-danger data_remove"
                        data-id="'.$row->id.'">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['gateway','payment_id','action']);
    }

    public function query(Transaction $model)
    {
        return $model->newQuery()->select('transaction.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('transaction-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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

    protected function getColumns()
    {
        return [

            Column::computed('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),

            Column::make('user_id')->title('User ID'),

            Column::make('email')->title('Email'),

            Column::make('plan_id')->title('Plan ID'),

            Column::make('gateway')
                ->title('Gateway')
                ->orderable(false)
                ->searchable(false),

            Column::make('payment_amount')->title('Amount'),

            Column::make('payment_id')->title('Payment ID'),

            Column::make('date')->title('Transaction Date'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(80),
        ];
    }

    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}