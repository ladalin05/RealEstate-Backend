<?php

namespace App\DataTables\Interaction;

use App\Models\Interaction\TourSchedule;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class TourScheduleDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('property', function ($row) {
                return $row->property_title ?? '-';
            })
            ->addColumn('agent', function ($row) {
                return $row->agent_name ?? '-';
            })
            ->addColumn('requester', function ($row) {
                return $row->user_name ?? $row->name ?? '-';
            })
            ->editColumn('tour_type', function ($row) {
                return ucfirst(str_replace('-', ' ', $row->tour_type));
            })
            ->editColumn('requested_date', function ($row) {
                return $row->requested_date
                    ? date('Y-m-d', strtotime($row->requested_date))
                    : '-';
            })
            ->editColumn('requested_time', function ($row) {
                return $row->requested_time
                    ? date('H:i', strtotime($row->requested_time))
                    : '-';
            })
            ->addColumn('status_badge', function ($row) {
                $colors = [
                    'pending'   => 'warning',
                    'confirmed' => 'success',
                    'rejected'  => 'danger',
                ];
                $color = $colors[$row->status] ?? 'secondary';

                return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? date('Y-m-d H:i', strtotime($row->created_at))
                    : '-';
            })
            ->addColumn('action', fn($row) => view('property.tour-schedules.action', compact('row')))
            ->rawColumns(['status_badge', 'action']);
    }

    public function query(TourSchedule $model)
    {
        return $model->newQuery()
            ->leftJoin('properties', 'tour_schedules.property_id', 'properties.id')
            ->leftJoin('agents', 'tour_schedules.agent_id', 'agents.id')
            ->leftJoin('users', 'tour_schedules.user_id', 'users.id')
            ->select(
                'tour_schedules.*',
                'properties.title_en as property_title',
                DB::raw("CONCAT(agents.first_name, ' ', agents.last_name) as agent_name"),
                'users.name as user_name'
            );
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('tour-schedules-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(9, 'desc')
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

            Column::make('requester')
                ->title('Requested By'),

            Column::make('email')
                ->title('Email'),

            Column::make('phone')
                ->title('Phone'),

            Column::make('tour_type')
                ->title('Tour Type'),

            Column::make('requested_date')
                ->title('Date'),

            Column::make('requested_time')
                ->title('Time'),

            Column::make('status_badge')
                ->title('Status')
                ->orderable(false)
                ->searchable(false),

            Column::make('created_at')
                ->title('Submitted'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'TourSchedules_' . date('YmdHis');
    }
}