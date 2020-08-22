<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GeneralExport implements FromCollection, WithHeadings, WithMapping
{
    protected $from;        
    protected $to;        
    protected $header = [];        
    protected $model;        
    protected $column;  

    public function __construct($model = null, $column = [], $from = null, $to = null) 
    {
        $this->from = $from;        
        $this->to = $to;
        $this->model = new $model;        
        $this->column = $column;        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        if (!$this->from && !$this->to) {
            $data = $this->model->all();
        } else {
            $data = $this->model->whereBetween('created_at', [$this->from." 00:00:00", $this->to." 23:59:59"])->get();
        }

        return $data;
    }

    /**
    * @var Invoice $invoice
    */
    public function map($data): array
    {
        return $data->getAttributes();
    }

    public function headings(): array
    {
        $listColumn = $this->model->getConnection()
                            ->getSchemaBuilder()
                            ->getColumnListing(
                                $this->model->getTable()
                            );
        $header = [];

        foreach ($listColumn as $key => $value) {
            array_push($header, ucfirst(str_replace("_", " ", $value)));
        }

        return $header;
    }
}
