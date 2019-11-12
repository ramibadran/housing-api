<?php
namespace App\Builder;

final class CrazyHotelCaller extends RestCaller{
    
    public function crazyHotelCaller($url,$query,$method){
        return $this->handlingResponse($this->callRest($url,$query,$method));
    }
    
    private function handlingResponse($data){
        //print_r($data);exit;
        $responsData = array();
        foreach($data as $value){
            foreach($value as $result){
                array_push($responsData,[
                    'hotelName' => $result['hotelName'],
                    'fare'      => $this->convertStarsToNumber($result['rate']),
                    'amenities' => $result['amenities'],
                ]);
            }
        }
        return $responsData;
    }
    
    private function convertStarsToNumber($stars){
        return substr_count($stars,'*');
    }
}