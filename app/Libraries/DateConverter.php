<?php
namespace App\Libraries;
use Carbon\Carbon;

class  DateConverter{
public function getTimeFormat($dateString)
{
    try{
        return Carbon::createFromFormat('Y-m-d\TH:i:s.uT', $dateString);
    }
    catch(\Exception $ex){
        try{
            return Carbon::createFromFormat('Y-m-d', $dateString);
        }
        catch(\Exception $ex){

        }
    }
    return null;
}
}