<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class NationalExport implements FromCollection,WithHeadings
{
    protected $DateFrom1;
    protected $DateTo1;
    protected $DateFrom2;
    protected $DateTo2;
    protected $CustomerCode;

    function __construct($request) {
        $this->DateFrom1 = $request->DateFrom1;
        $this->DateTo1 = $request->DateTo1;
        $this->DateFrom2 = $request->DateFrom2;
        $this->DateTo2 = $request->DateTo2;
        $this->CustomerCode = $request->CustomerCode;
    }

    public function collection()
    {
        $user_id = Auth::user()->EmpCode;

        $data['rows'] = DB::select("EXEC SP_TreeViewDashBoard '','','','','','','','".$this->DateFrom1."','".$this->DateTo1."','".$this->DateFrom2."','".$this->DateTo2."','$user_id','$this->CustomerCode' ");

        $result = [];
        foreach ($data['rows'] as $row){

            $result[] = [
                'Level1' =>$row->Level1,
                'OutletZone' =>$row->OutletZone,
                'OutletName'=>$row->OutletName,
                'Division'=>$row->Division,
                'Category_Level_1'=>$row->Category_Level_1,
                'Category_Level_2'=>$row->Category_Level_2,
                'Category_Level_3'=>$row->Category_Level_3,
                'Article_Code'=>$row->Category_Level_3,
                'Article_Name'=>$row->Article_Name,
                'ThisSales'=>$row->ThisSales,
                'LastSales'=>$row->LastSales,
                'DiffSales' =>$row->DiffSales,
                'ChangePerctgSales' =>$row->ChangePerctgSales,
                'MixSales' =>$row->MixSales,
                'ThisSalesQTY' =>$row->ThisSalesQTY,
                'LastSalesQTY' =>$row->LastSalesQTY,
                'DiffSalesQTY' =>$row->DiffSalesQTY,
                'ChangePercgSalesQTY' =>$row->ChangePercgSalesQTY,
                'ThisFootfall' =>$row->ThisFootfall,
                'LastFootfall' =>$row->LastFootfall,
                'DiffFootfall' =>$row->DiffFootfall,
                'ChangePercFootfall' => $row->ChangePercFootfall,
                'ThisBasketValue' => $row->ThisBasketValue,
                'LastBasketValue' => $row->LastBasketValue,
                'DiffBasketValue' => $row->DiffBasketValue,
                'ChangePercBasketValue' => $row->ChangePercBasketValue,
                'ThisGPPerc' => $row->ThisGPPerc,
                'LastGPPerc' =>$row->LastGPPerc,
                'DiffGPerc' =>$row->DiffGPerc,
                'ThisGPV' =>$row->ThisGPV,
                'LastGPV' => $row->LastGPV,
                'DiffGPV' => $row->DiffGPV,
                'ChangePercGPV' => $row->ChangePercGPV,
            ];
        }
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
