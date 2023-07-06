<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfitabilityExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user_id = Auth::user()->EmpCode;
        $data = DB::select("EXEC SP_OutletWiseProfitability '$user_id' ");
        $profitability = collect($data);
        return $profitability;
    }

    public function headings(): array
    {

        return [
            'OutletCode',
            'OutletName',
            'OutletZone',
            'ProjectedProfit',
            'ProfitLM',
            'ProfitLY',
            'ProjectedSales',
            'SalesLM',
            'SalesLY',
            'GPPerc',
            'TIPerc',
            'SalesPerSFT',
            'FCPerSFT',
            'VCPerc',
            'WastagePerc',
            'ConsumablePerc',
            'StockWriteOffPerc',
            'SalaryProportion',
            'PermanentProportion',
            'ContractualProportion',
            'SecurityProportion',
            'OperationalExpenseProportion',
            'EntertainmentProportion',
            'CleaningProportion',
        ];
    }

}
