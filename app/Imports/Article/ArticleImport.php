<?php

namespace App\Imports\Article;

use App\Model\ArticleInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ArticleImport implements ToCollection
{
    public function collection(Collection $rows){
        $article = [];
        foreach ($rows as $row)
        {
            $article[] = array(
                'Article_Code'=>$row[0],
                'Article_Name'=>str_replace(',',' ',$row[1]),
                'Division'=>str_replace(',',' ',$row[2]),
                'Category_Level_1'=>str_replace(',',' ',$row[3]),
                'Category_Level_2'=>str_replace(',',' ',$row[4]),
                'Category_Level_3'=>str_replace(',',' ',$row[5]),
                'Category_Level_4'=>str_replace(',',' ',$row[6]),
                'Category_Level_5'=>str_replace(',',' ',$row[7]),
                'Category_Level_6'=>str_replace(',',' ',$row[8]),
                'Category_Level_7'=>str_replace(',',' ',$row[9]),
                'Category_Level_8'=>str_replace(',',' ',$row[10]),
                'Category_Level_9'=>str_replace(',',' ',$row[11]),
                'Category_Level_10'=>str_replace(',',' ',$row[12]),
                'Size'=>$row[13],
                'Packaging_Type'=>str_replace(',',' ',$row[14]),
                'Size_UOM'=>$row[15],
                'UOM'=>$row[16],
                'Article_Status'=>$row[17],
                'Private_Label'=>$row[18],
                'Country_Of_Origin'=>$row[19],
                'Seasonal'=>$row[20],
                'Brand_Name_Code'=>$row[21],
                'Brand_Name_Description'=>$row[22],
                'Vat_Structure'=>$row[23],
                'Colour'=>$row[24],
                'No_of_Sources'=>$row[25],
                'Multiple_Vendor'=>$row[26],
                'Category_Manager_1'=>$row[27],
                'Category_Manager_2'=>$row[28],
                'Category_Manager_3'=>$row[29],
                'Category_Manager_4'=>$row[30],
                'Category_Manager_5'=>$row[31],
                'Buyer_1'=>$row[32],
                'Buyer_2'=>$row[33],
                'Buyer_3'=>$row[34],
                'Buyer_4'=>$row[35],
                'Buying_Manager_1'=>$row[36],
                'Buying_Manager_2'=>$row[37],
                'Buying_Manager_3'=>$row[38],
                'SourceDefn,'=>$row[39],
            );
        }

        return $article;

    }
}
