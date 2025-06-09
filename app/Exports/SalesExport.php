<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection
{
    protected $search;
    protected $filter;

    public function __construct($search = null, $filter = null)
    {
        $this->search = $search;
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Sale::with(['productable', 'payment']);

        if ($this->search) {
            $query->whereHas('productable', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter) {
            $now = Carbon::now();

            if ($this->filter == 'daily') {
                $query->whereDate('created_at', $now->toDateString());
            } elseif ($this->filter == 'weekly') {
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
            } elseif ($this->filter == 'monthly') {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }
        }

        return $query->latest()->get();
    }
}

