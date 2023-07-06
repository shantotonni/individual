<?php

namespace App\Exports\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Cat1Export implements FromCollection, WithHeadings
{
    protected $DateFrom1;
    protected $DateTo1;
    protected $DateFrom2;
    protected $DateTo2;
    protected $CustomerCode;
    protected $Region;
    protected $Outlet;
    protected $Division;
    protected $Cat1;

    function __construct($request) {
        $this->DateFrom1 = $request->DateFrom1;
        $this->DateTo1 = $request->DateTo1;
        $this->DateFrom2 = $request->DateFrom2;
        $this->DateTo2 = $request->DateTo2;
        $this->CustomerCode = $request->CustomerCode;
        $this->Region = $request->Region;
        $this->Outlet = $request->Outlet;
        $this->Division = $request->Division;
        $this->Cat1 = $request->Cat1;
    }

    public function collection()
    {
        $user_id = Auth::user()->EmpCode;

        $data['rows'] = DB::select("EXEC SP_TreeViewDashBoard 'D','".$this->Region."','".$this->Outlet."','".$this->Division."','".$this->Cat1."','','','".$this->DateFrom1."','".$this->DateTo1."','".$this->DateFrom2."','".$this->DateTo2."','$user_id','$this->CustomerCode' ");
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
