<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DivisionExport implements FromCollection, WithHeadings
{
    protected $DateFrom1;
    protected $DateTo1;
    protected $DateFrom2;
    protected $DateTo2;
    protected $CustomerCode;
    protected $Region;
    protected $Outlet;
    protected $Division;

    function __construct($request) {
        $this->DateFrom1 = $request->DateFrom1;
        $this->DateTo1 = $request->DateTo1;
        $this->DateFrom2 = $request->DateFrom2;
        $this->DateTo2 = $request->DateTo2;
        $this->CustomerCode = $request->CustomerCode;
        $this->Region = $request->Region;
        $this->Outlet = $request->Outlet;
        $this->Division = $request->Division;
    }

    public function collection()
    {
        $user_id = Auth::user()->EmpCode;

        $data['rows'] = DB::select("EXEC SP_TreeViewDashBoard 'D','".$this->Region."','".$this->Outlet."','".$this->Division."','','','',
        '".$this->DateFrom1."','".$this->DateTo1."','".$this->DateFrom2."','".$this->DateTo2."','$user_id','$this->CustomerCode' ");
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
