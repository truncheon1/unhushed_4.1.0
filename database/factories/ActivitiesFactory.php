<?php

namespace Database\Factories;

use App\Models\Activities;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivitiesFactory extends Factory
{
    protected $model = Activities::class;

    protected function getColumns()
    {
        return [
            Column::make('image'),
            Column::make('activity'),
            Column::make('session'),
            Column::make('unit'),
            Column::make('curricula'),
            Column::make('size'),
            Column::make('age'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('keywords'),
            Column::computed('action')->title(''),
        ];
    }

}
