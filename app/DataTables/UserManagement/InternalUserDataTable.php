<?php

namespace App\DataTables\UserManagement;

use App\Models\Admin\Admin;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class InternalUserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('image', function ($row) {
                $src = $row->profile_picture ?? null;

                return $src
                    ? '<img src="' . $src . '" width="60" height="60" style="object-fit:cover;border-radius:6px;">'
                    : '<span class="badge bg-light text-dark">No Image</span>';
            })
            ->editColumn('role', function ($user) {
                $colors = [
                    'super-admin' => 'danger',
                    'admin'       => 'primary',
                    'cashier'     => 'info',
                ];

                $color = $colors[$user->role] ?? 'secondary';

                return $user->role
                    ? badge(ucwords(str_replace('-', ' ', $user->role)), $color)
                    : '-';
            })
            ->editColumn('active', function ($user) {
                return $user->active
                    ? badge(__('global.active'), 'primary')
                    : badge(__('global.inactive'), 'danger');
            })
            ->editColumn('phone', function ($user) {
                return $user->phone ?? '-';
            })
            ->editColumn('last_login_at', function ($user) {
                return $user->last_login_at ? dateFormat($user->last_login_at) : '-';
            })
            ->editColumn('created_at', function ($user) {
                return dateFormat($user->created_at);
            })
            ->editColumn('updated_at', function ($user) {
                return dateFormat($user->updated_at);
            })
            ->addColumn('action', function ($row) {
                return view('admin.users.action', compact('row'))->render();
            })
            ->rawColumns(['image', 'role', 'active', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Admin $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('internal-user-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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
            Column::computed('DT_RowIndex', __('global.n_o'))->width(60)->addClass('text-center'),
            Column::computed('image')->title(__('global.image'))->exportable(false)->printable(false)->orderable(false)->searchable(false)->addClass('text-center'),
            Column::make('name')->title(__('global.name')),
            Column::make('username')->title(__('global.username')),
            Column::make('email')->title(__('global.email')),
            Column::make('phone')->title(__('global.phone')),
            Column::make('role')->title(__('global.role')),
            Column::make('active')->title(__('global.status')),
            Column::make('last_login_at')->title(__('global.last_login')),
            Column::make('created_at')->title(__('global.created_at')),
            Column::make('updated_at')->title(__('global.updated_at')),
            Column::make('action')->title(__('global.action'))->exportable(false)->printable(false)->width(60)->addClass('text-center')->searchable(false)->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InternalUser_' . date('YmdHis');
    }
}