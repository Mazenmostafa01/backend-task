<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return DB::transaction(function() use($row)
        {
            $category = Category::create([
                'title' => $row[0]
            ]);

            $category->image()->create([
                'path' => $row[1]
            ]);
        });
    }
}
