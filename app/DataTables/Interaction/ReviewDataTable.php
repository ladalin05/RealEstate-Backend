<?php

namespace App\DataTables\Interaction;

use App\Models\Interaction\Review;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('user', function ($row) {
                return $row->user_name ?? '-';
            })
            ->addColumn('agent', function ($row) {
                return $row->agent_name ?? '-';
            })

            ->addColumn('property', function ($row) {
                return $row->property_title ?? '-';
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

            ->rawColumns(['rating']);
    }

    public function query(Review $model)
    {
        return $model->newQuery()
            ->leftJoin('users', 'reviews.user_id', 'users.id')
            ->leftJoin('agents', 'reviews.agent_id', 'agents.id')
            ->leftJoin('properties', 'reviews.property_id', 'properties.id')
            ->select('reviews.*', 'users.name as user_name', DB::raw("CONCAT(agents.first_name , ' ', agents.last_name) as agent_name"), 'properties.title as property_title');
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