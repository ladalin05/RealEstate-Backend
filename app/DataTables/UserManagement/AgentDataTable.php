<?php

namespace App\DataTables\UserManagement;

use App\Models\UserManagement\Agent;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class AgentDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('name', function ($row) {
                return $row->user?->name ?? '-';
            })

            ->addColumn('agency', function ($row) {
                return $row->agency?->name ?? '-';
            })

            ->editColumn('rating', function ($row) {
                return $row->rating ? $row->rating . ' ⭐' : '0';
            })

            ->addColumn('action', fn($row) => view('user-management.agents.action', compact('row')))

            ->rawColumns(['action']);
    }

    public function query(Agent $model)
    {
        return $model->newQuery()
            ->with(['user','agency'])
            ->select('agents.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('agents-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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

    protected function getColumns()
    {
        return [

            Column::computed('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),

            Column::make('name')
                ->title('Agent Name'),

            Column::make('license_number')
                ->title('License'),

            Column::make('experience_years')
                ->title('Experience (Years)'),

            Column::make('rating')
                ->title('Rating'),

            Column::make('total_sales')
                ->title('Total Sales'),

            Column::make('agency')
                ->title('Agency'),

            Column::computed('action')
                ->title('Action')
                ->searchable(false)
                ->orderable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Agents_' . date('YmdHis');
    }
}