<?php

namespace App\Exports;

use App\Models\Selling;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromArray;

class SellingExport implements FromArray
{
    public function array(): array
    {
        return Selling::with('member', 'user')->get()->map(function ($selling) {
            return [
                'Tanggal' => $selling->created_at->format('Y-m-d'),
                'Nama Member' => $selling->member ? $selling->member->name : 'Non Member',
                'Total Harga' => $selling->total_price,
                'Kasir' => $selling->user->name,
            ];
        })->toArray();
    }
}
