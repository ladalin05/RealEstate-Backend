<?php

namespace App\DataTables\Interaction;

use App\Models\Interaction\RequestInfo;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
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
                return $row->property->title_en ?? '-';
            })
            ->addColumn('agent', function ($row) {
                return $row->agent
                    ? trim($row->agent->first_name . ' ' . $row->agent->last_name)
                    : '-';
            })
            ->addColumn('requester', function ($row) {
                return $row->user->name ?? $row->name ?? '-';
            })
            ->editColumn('role', function ($row) {
                return $row->role ? ucfirst($row->role) : '-';
            })
            ->addColumn('message', function ($row) {
                $count = $row->messages->count();
                $last  = $row->messages->last();
                $preview = $last ? Str::limit($last->message, 50) : '-';

                if ($count === 0) {
                    return $preview;
                }

                return $preview . ' <span class="badge bg-light text-dark border">' . $count . ' msg' . ($count > 1 ? 's' : '') . '</span>';
            })
            ->addColumn('status_badge', function ($row) {
                $colors = [
                    'pending' => 'info',
                    'active'  => 'success',
                    'closed'  => 'dark',
                ];
                $color = $colors[$row->status] ?? 'secondary';

                return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('unread_badge', function ($row) {
                $count = $row->messages
                    ->where('sender', 'user')
                    ->where('is_read', false)
                    ->count();

                if ($count === 0) {
                    return '<span class="text-muted">-</span>';
                }

                return '<span class="badge bg-danger">' . $count . ' new</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('Y-m-d H:i')
                    : '-';
            })
            ->addColumn('action', fn($row) => view('interaction.request-infos.action', compact('row')))
            ->rawColumns(['message', 'status_badge', 'unread_badge', 'action']);
    }

    public function query(RequestInfo $model)
    {
        return $model->newQuery()
            ->with(['property', 'agent', 'user', 'messages']);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('request-infos-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(10, 'desc')
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

            Column::computed('property')
                ->title('Property')
                ->orderable(false)
                ->searchable(false),

            Column::computed('agent')
                ->title('Agent')
                ->orderable(false)
                ->searchable(false),

            Column::computed('requester')
                ->title('Requested By')
                ->orderable(false)
                ->searchable(false),

            Column::make('email')
                ->title('Email'),

            Column::make('phone')
                ->title('Phone'),

            Column::make('role')
                ->title('Role'),

            Column::computed('message')
                ->title('Message')
                ->orderable(false)
                ->searchable(false),

            Column::computed('status_badge')
                ->title('Status')
                ->orderable(false)
                ->searchable(false),

            Column::computed('unread_badge')
                ->title('Unread')
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