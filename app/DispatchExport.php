<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DispatchExport implements FromCollection, WithHeadings, WithStyles
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return collect($this->products);
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Cantidad Salida',
            'Cantidad Retorno',
            'Faltante'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'D' => ['font' => ['color' => ['argb' => 'FFFF0000']]]
        ];
    }
}