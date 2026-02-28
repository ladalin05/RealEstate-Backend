<?php

namespace App\DataTables\Admin;

use App\Models\UserManagement\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RoleDataTable extends DataTable
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
            ->editColumn('name', function ($role) {
                return $role->{'name_' . app()->getLocale()} ?? '-';
            })
            ->editColumn('administrator', function ($role) {
                return $role->administrator
                    ? badge(__('global.yes'), 'primary')
                    : badge(__('global.no'), 'danger');
            })
            ->editColumn('created_at', function ($user) {
                return dateFormat($user->created_at);
            })
            ->editColumn('updated_at', function ($user) {
                return dateFormat($user->updated_at);
            })
            ->addColumn('action', function ($row) {
                $edit = route('users-management.roles.edit', $row->id);

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
            ->rawColumns(['administrator', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('role-table')
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
            Column::computed('name')->title(__('global.name')),
            Column::make('administrator')->title(__('global.administrator')),
            Column::make('order')->title(__('global.order')),
            Column::make('description')->title(__('global.desc')),
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
        return 'Role_' . date('YmdHis');
    }
}
