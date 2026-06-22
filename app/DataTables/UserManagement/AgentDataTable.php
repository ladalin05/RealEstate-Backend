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
            ->editColumn('profile_image', function ($row) {
                $src = $row->profile_image
                    ? rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/' . $row->profile_image
                    : null;

                return $src
                    ? '<img src="' . $src . '" width="60" height="60" style="object-fit:cover;border-radius:6px;">'
                    : '<span class="badge bg-light text-dark">No Image</span>';
            })
            ->editColumn('name', function ($row) {
                return trim("{$row->first_name} {$row->last_name}") ?: '-';
            })
            ->editColumn('email', function ($row) {
                return $row->email ?? '-';
            })
            ->editColumn('license_number', function ($row) {
                return $row->license_number ?? '-';
            })
            ->editColumn('rating', function ($row) {
                return $row->rating > 0 ? number_format($row->rating, 2) . ' ⭐' : '0';
            })
            ->editColumn('status', function ($row) {
                $colors = [
                    'active'    => 'success',
                    'inactive'  => 'secondary',
                    'suspended' => 'danger',
                    'pending'   => 'warning',
                ];
                $color = $colors[$row->status] ?? 'secondary';

                return '<span class="badge badge-' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('action', fn ($row) => view('user-management.agents.action', compact('row')))
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('last_name', $order)->orderBy('first_name', $order);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                      ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })

            ->rawColumns(['profile_image', 'action', 'status']);
    }

    public function query(Agent $model)
    {
        return $model->newQuery()
            ->select([
                'agents.id',
                'agents.profile_image',
                'agents.first_name',
                'agents.last_name',
                'agents.email',
                'agents.license_number',
                'agents.experience_years',
                'agents.rating',
                'agents.total_sales',
                'agents.total_rentals',
                'agents.status',
            ]);
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
                Button::make('reload'),
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),
            
            Column::make('profile_image')
                ->title('Profile'),

            Column::make('name')
                ->title('Agent Name'),

            Column::make('email')
                ->title('Email'),

            Column::make('license_number')
                ->title('License'),

            Column::make('experience_years')
                ->title('Experience (Years)'),

            Column::make('rating')
                ->title('Rating'),

            Column::make('total_sales')
                ->title('Total Sales'),

            Column::make('status')
                ->title('Status'),

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