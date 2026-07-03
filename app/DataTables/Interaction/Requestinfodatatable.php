<?php

namespace App\DataTables\Interaction;

use App\Models\Interaction\RequestInfo;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Services\DataTable;

class RequestInfoDataTable extends DataTable
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
            ->editColumn('role', function ($row) {
                return $row->role ? ucfirst($row->role) : '-';
            })
            ->editColumn('message', function ($row) {
                return Str::limit($row->message, 50);
            })
            ->addColumn('status_badge', function ($row) {
                $colors = [
                    'new'     => 'info',
                    'read'    => 'secondary',
                    'replied' => 'success',
                    'closed'  => 'dark',
                ];
                $color = $colors[$row->status] ?? 'secondary';

                return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? date('Y-m-d H:i', strtotime($row->created_at))
                    : '-';
            })
            ->addColumn('action', fn($row) => view('property.request-infos.action', compact('row')))
            ->rawColumns(['status_badge', 'action']);
    }

    public function query(RequestInfo $model)
    {
        return $model->newQuery()
            ->leftJoin('properties', 'request_infos.property_id', 'properties.id')
            ->leftJoin('agents', 'request_infos.agent_id', 'agents.id')
            ->leftJoin('users', 'request_infos.user_id', 'users.id')
            ->select(
                'request_infos.*',
                'properties.title_en as property_title',
                DB::raw("CONCAT(agents.first_name, ' ', agents.last_name) as agent_name"),
                'users.name as user_name'
            );
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('request-infos-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(8, 'desc')
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

            Column::make('role')
                ->title('Role'),

            Column::make('message')
                ->title('Message'),

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
        return 'RequestInfos_' . date('YmdHis');
    }
}