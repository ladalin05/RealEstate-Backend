<?php

namespace App\DataTables\Admin;

use App\Models\UserManagement\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->editColumn('name', function ($user) {
                return $user->{'name_' . app()->getLocale()} ?? '-';
            })

            ->addColumn('role', function ($user) {

                $roleName = $user->{'role_name_' . app()->getLocale()} ?? '-';

                if ($user->as_administrator == 1) {
                    return badge($roleName, 'danger');
                }

                return badge($roleName, 'primary');
            })

            ->editColumn('created_at', function ($user) {
                return dateFormat($user->created_at);
            })

            ->editColumn('updated_at', function ($user) {
                return dateFormat($user->updated_at);
            })

            ->addColumn('action', function ($row) {
                $edit = route('users-management.users.edit', $row->id);

                return '
                <div class="d-flex gap-2">
                    <a href="'.$edit.'" class="btn btn-sm btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger data_remove" data-id="'.$row->id.'">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>';
            })

            ->rawColumns(['role', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->select(
                'users.*',
                'roles.name_en as role_name_en',
                'roles.name_kh as role_name_kh',
                'roles.administrator as as_administrator'
            )
            ->distinct() // ✅ use this instead
            ->orderBy('users.id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::computed('DT_RowIndex')
                ->title(__('global.n_o'))
                ->width(60)
                ->addClass('text-center'),

            Column::computed('name')
                ->title(__('global.name'))
                ->orderable(false)
                ->searchable(false),
            Column::make('username')->title(__('global.username')),
            Column::make('email')->title(__('global.email')),

            Column::computed('role')
                ->title(__('global.role'))
                ->orderable(false)
                ->searchable(false),

            Column::make('created_at')->title(__('global.created_at')),
            Column::make('updated_at')->title(__('global.updated_at')),

            Column::computed('action')
                ->title(__('global.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}