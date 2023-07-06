<?php

namespace App\Imports;

use App\Model\KPIEntry;
use App\Model\KPIEntryDetails;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class KPIEntryImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value){
            if ($key != 0){
                $exist = KPIEntry::orderBy('KPIEntryMasterCode','desc')->first();
                if ($exist){
                    $KPIEntryMasterCode = $exist->KPIEntryMasterCode + 1;
                }else{
                    $KPIEntryMasterCode = 1;
                }
                $user = KPIEntry::create([
                    'KPIEntryMasterCode' => $KPIEntryMasterCode,
                    'EmpCode' => Auth::user()->EmpCode,
                    'EntryBy' => Auth::user()->EmpCode,
                    'Business' => $value[0],
                    'Period' => $value[3],
                    'EntryDate' => Carbon::now(),
                    'EntrySource' => 'WEB',
                ]);

                $userdetails = KPIEntryDetails::create([
                    'KPIEntryMasterCode' => $KPIEntryMasterCode,
                    'KPICode' => $value[1],
                    'Value' => $value[4],
                ]);
            }
        }
    }
}
