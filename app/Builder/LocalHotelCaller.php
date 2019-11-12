<?php
namespace App\Builder;

final class LocalHotelCaller{
    
    public function handlingResponse($data){       
        $responsData = array();
            foreach($data as $result){
                array_push($responsData,[
                    'id'       => $result->id,
                    'name'     => $result->name,
                    'location' => $result->location,
                ]);
        }
        return $responsData;
    }
    
}