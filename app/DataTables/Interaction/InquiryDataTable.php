<?php

namespace App\DataTables\Interaction;

use App\Models\Interaction\Inquiry;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Str;
use Yajra\DataTables\Services\DataTable;

class InquiryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('property', function ($row) {
                return $row->property?->title ?? '-';
            })

            ->addColumn('user', function ($row) {
                return $row->user?->name ?? $row->name ?? '-';
            })

            ->editColumn('message', function ($row) {
                return Str::limit($row->message, 50);
            })

            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? date('Y-m-d H:i', strtotime($row->created_at))
                    : '-';
            })

            ->addColumn('action', fn($row) => view('interaction.inquiries.action', compact('row')))

            ->rawColumns(['action']);
    }

    public function query(Inquiry $model)
    {
        return $model->newQuery()
            ->with(['property','user'])
            ->select('inquiries.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('inquiries-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
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

            Column::make('property')
                ->title('Property'),

            Column::make('user')
                ->title('User'),

            Column::make('email')
                ->title('Email'),

            Column::make('phone')
                ->title('Phone'),

            Column::make('message')
                ->title('Message'),

            Column::make('created_at')
                ->title('Date'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Inquiries_' . date('YmdHis');
    }
}