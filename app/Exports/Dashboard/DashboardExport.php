<?php

namespace App\Exports\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardExport implements FromCollection, WithHeadings
{
    protected $DateFrom1;
    protected $DateTo1;
    protected $Combo;
    protected $CustomerCode;
    protected $Outlet;

    function __construct($request) {
        $this->DateFrom1 = $request->DateFrom1;
        $this->DateTo1 = $request->DateTo1;
        $this->Combo = $request->Combo;
        $this->CustomerCode = $request->CustomerCode?implode(",",$request->CustomerCode):$request->CustomerCode;;
        $this->Outlet = $request->OutletCode;
    }

    public function collection()
    {
        $user_id = Auth::user()->EmpCode;
        $data['rows'] = DB::select("EXEC SP_TreeViewDashBoardQuickExport '".$this->DateFrom1."','".$this->DateTo1."','".$this->Combo."','".$user_id."','".$this->CustomerCode."','".$this->Outlet."'");
        $data['rows'] = collect($data['rows']);
        return $data['rows'];
    }

    public function headings(): array
    {

        return [
            'Level1',
            'OutletZone',
            'OutletName',
            'Division',
            'Category_Level_1',
            'Category_Level_2',
            'Category_Level_3',
            'Article_Code',
            'Article_Name',
            'ThisSales',
            'LastSales',
            'DiffSales',
            'ChangePerctgSales',
            'MixSales',
            'ThisSalesQTY',
            'LastSalesQTY',
            'DiffSalesQTY',
            'ChangePercgSalesQTY',
            'ThisFootfall',
            'LastFootfall',
            'DiffFootfall',
            'ChangePercFootfall',
            'ThisBasketValue',
            'LastBasketValue',
            'DiffBasketValue',
            'ChangePercBasketValue',
            'ThisGPPerc',
            'LastGPPerc',
            'DiffGPerc',
            'ThisGPV',
            'LastGPV',
            'DiffGPV',
            'ChangePercGPV'
        ];
    }
}
