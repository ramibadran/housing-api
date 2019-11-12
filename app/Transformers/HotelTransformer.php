<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;



class HotelTransformer extends TransformerAbstract{
    
    public function transform($data){
        return $data;
    }
}