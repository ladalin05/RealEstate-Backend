<?php

namespace App\DataTables\Interaction;

use App\Models\Interaction\Review;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Str;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('user', function ($row) {
                return $row->user?->name ?? '-';
            })

            ->addColumn('agent', function ($row) {
                return $row->agent?->user?->name ?? '-';
            })

            ->addColumn('property', function ($row) {
                return $row->property?->title ?? '-';
            })

            ->editColumn('rating', function ($row) {
                if ($row->rating) {
                    return str_repeat('⭐', $row->rating);
                }
                return '-';
            })

            ->editColumn('comment', function ($row) {
                return Str::limit($row->comment, 60);
            })

            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? date('Y-m-d H:i', strtotime($row->created_at))
                    : '-';
            })

            ->addColumn('action', fn($row) => view('interaction.reviews.action', compact('row')))

            ->rawColumns(['rating','action']);
    }

    public function query(Review $model)
    {
        return $model->newQuery()
            ->with(['user','agent.user','property'])
            ->select('reviews.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('reviews-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1,'desc')
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

            Column::make('user')
                ->title('User'),

            Column::make('agent')
                ->title('Agent'),

            Column::make('property')
                ->title('Property'),

            Column::make('rating')
                ->title('Rating'),

            Column::make('comment')
                ->title('Comment'),

            Column::make('created_at')
                ->title('Date'),

            Column::computed('action')
                ->title('Action')
                ->searchable(false)
                ->orderable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Reviews_' . date('YmdHis');
    }
}