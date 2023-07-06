<?php

namespace App\Exports\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardQuickExport implements FromCollection, WithHeadings
{
    protected $DateFrom1;
    protected $DateTo1;
    protected $Combo;
    protected $CustomerCode;
    protected $Region;
    protected $Outlet;
    protected $Division;
    protected $Cat1;
    protected $Cat2;
    protected $Cat3;

    function __construct($request) {
        $this->DateFrom1 = $request->DateFrom1;
        $this->DateTo1 = $request->DateTo1;
        $this->Combo = $request->Combo;
        $this->CustomerCode = $request->CustomerCode?implode(",",$request->CustomerCode):$request->CustomerCode;;
        $this->Region = $request->Region;
        $this->Outlet = $request->Outlet;
        $this->Division = $request->Division;
        $this->Cat1 = $request->Cat1;
        $this->Cat2 = $request->Cat2;
        $this->Cat3 = $request->Cat3;
    }

    public function collection()
    {
        $user_id = Auth::user()->EmpCode;
        if ($this->Region) {
            $national = 'D';
        }else{
            $national = '';
        }

        $data['rows'] = DB::select("EXEC SP_TreeViewDashBoardQuick '$national','".$this->Region."','".$this->Outlet."','".$this->Division."','".$this->Cat1."','".$this->Cat2."','".$this->Cat3."','".$this->DateFrom1."','".$this->DateTo1."','".$this->Combo."','$user_id','$this->CustomerCode' ");
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
