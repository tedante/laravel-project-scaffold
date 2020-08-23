<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class GeneralImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    protected $model; 

    public function __construct($model = null) 
    {
        $this->model = new $model;      
    }
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $listColumn = $this->model->getConnection()
                            ->getSchemaBuilder()
                            ->getColumnListing(
                                $this->model->getTable()
                            );
        
        $data = [];
 
        foreach ($collection as $row) {
            foreach ($listColumn as $key => $value) {
               $data[$value] = $row[$value];
            }
            
            $this->model->updateOrCreate(
                ['id' => $row['id']], 
                $data
            );
        }
    }
}
