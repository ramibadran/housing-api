<?php
namespace App\Builder;

final class BestHotelCaller extends RestCaller{
    
    public function bestHotelCaller($url,$query,$method){
        return $this->handlingResponse($this->callRest($url,$query,$method));
    }
    
    private function handlingResponse($data){       
        $responsData = array();
        foreach($data as $value){
            foreach($value as $result){
                array_push($responsData,[
                    'hotelName' => $result['hotel'],
                    'fare'      => $result['hotelRate'],
                    'amenities' => $result['roomAmenities'],
                ]);
            }
        }
        return $responsData;
    }
    
}