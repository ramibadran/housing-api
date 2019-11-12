<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class AuthTokenTransformer.
 *
 * @package app\Transformer
 *
 * @author Rami Badran <ramibadran.82@gmail.com>
 */


class AuthTokenTransformer extends TransformerAbstract{
    
    public function transform($data){       
        return [
            'access_token'   => $data,
            'token_type'     => 'Bearer'
        ];
    }
}