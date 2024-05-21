<?php
namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\SearchPane;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->setRowId('id')->searchPane(
            /*
             * This is the column for which this SearchPane definition is for
             */
            'user_id',

            /*
             * Here we define the options for our SearchPane. This should be either a collection or an array with the
             * form:
             * [
             *     [
             *          'value' => 1,
             *          'label' => 'display value',
             *          'total' => 5, // optional
             *          'count' => 3 // optional
             *     ],
             *     [
             *          'value' => 2,
             *          'label' => 'display value 2',
             *          'total' => 6, // optional
             *          'count' => 5 // optional
             *     ],
             * ]
             */
            fn() => User::query()->select('id as value', 'name as label')->get(),

            /*
             * This is the filter that gets executed when the user selects one or more values on the SearchPane. The
             * values are always given in an array even if just one is selected
             */
            function (\Illuminate\Database\Eloquent\Builder $query, array $values) {
                return $query->whereIn('id', $values);
            }
        );
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }
    public function html(): HtmlBuilder
    {
        return $this->builder()
            // ->searchPanes(SearchPane::make())
            ->setTableId('users-table')
            ->columns($this->getColumns())

            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->searchPanes(SearchPane::make())
            ->addColumnDef([
                'targets' => '_all',
                'searchPanes' => [
                    'show' => true,
                    'vieTotal' => false,
                    'viewCount' => false,
                ],
            ])
            ->parameters([
                'dom' => 'PBfrtip',
                'buttons' => ['export', 'print', 'reset', 'reload'],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
