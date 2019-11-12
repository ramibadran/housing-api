<?php
    
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utilities\AES;
use Illuminate\Http\Request;

class EncryptController extends Controller
{
    public function encryptData(Request $request){
        $apiName = $request->api;

        // provided by API owner to his client;
        $privateKey = '1234567887654321';
        $username   = 'ramibadran';
        $secretKey  = '1234567887654321';

        // prepare data
        if($apiName == 'token'){
            $orginalData = json_encode([
                'username'      => $username, 
                'identifier'    => $secretKey
            ]);
        }elseif($apiName == 'create'){
            $orginalData = json_encode([
                'name'      => 'hiat amman',
                'location'  => 'jordan,amman'
            ]);
        }

        // encrypt data
        $aes = new AES($orginalData,trim($privateKey));
        $encryptedData = $aes->encrypt();

        echo $encryptedData;
    }

    public function decryptData(Request $request){
        // encrypt data
        $data = "9Fss5vTg0m92e3u3td0tqxNbDE5n/TMSU46Ji+zg4sWNzWhM0PEEkdiqcysNNg9c/ojwSj47RV2ToJT6he4AedIzKpZGQv5nBkRBloGxhfjQYPb2X15GLUQfirjF1JxturhsMY1Pfn3+pSGTpEZ6cSsyb/T/Q0u3ulw3M/GKv5wC8wLBixLO3ZjXNDkgRghjbC0gYLd4tN45vDDBQKrCSUr2NV4zZN8mkuKcxKSCUbKC00GckwDgjiVFDpx1C7QhCFQGPl6s0jkGxajZRzM481f2JXZtte5HSZEoGE+JsVJ08dbMxWe32ojb8OOXgjpRo4ipbIueYwBsx+6PWRu1HJu49eRNGcn0PndwsEHumupk7tI0k/miLvxXmjdvURFkoKiSh6r0qk68eoEARIs2LQJ7IVKyxZSFsdWCQa5B6YJBL4AB3+hceaaGznjM5b4Z6Ospa9jybRHy0eHYZidk2tBc08Bts7z3+1cC8pzavBTu0mbAHqjrrXbnW8n/rnfdqucimWkEoZfWHgFjN90AFnP0yBr28LH4Ft8wRKQw9cB71ugrI8Kq3OhmzYJ0ez7e4GrmMU+4Q3tlZeyjJ+OdtzCORA81U2cFopr87UmXrqNnazAGIkKCIbJ1A1EuLg3rLU9xs77JKts0pufYlUCYRE+Nv+Xi/jrNEFwfU6CEWl9QER9IfSMonOdB3NQbFJ3kXNjhTTpYU+kCmXvIUPJwlIlvhTbctRkT/NuVlYeDjIwCR4AxoHcRXVymHTAL+yDQBdAN9wX7R77tOqrYEPyn6f8i0cRm+IJ7lbSW0X5nViYI8PEMm1XpiDI88twGhG7GOYGXC0DywjCHKKRtQ61sCC7mFwGSmG1i7WHGB0Lcit4I/re/bNNaLIYoDOQO5FU4/aDpfADuKxJiC+7jekMEfdPEftV/dj9hDI66vVuadbZApcQt/fg4LslMA2ia75LHXubZKJa0dDF2HV8CO8r1j7f4Rvh1Z7zTSPeM1XeaUhX7zis36EgNKRYzaeWWe8VtAdKXTMoFUdpzXWWHtZYWVg==";
        
        // provided by API owner t0 his client;
        $privateKey  = '1234567887654321';
        
        //decrypt data
        $aes         = new AES($data,trim($privateKey));
        $decryptData = $aes->decrypt();
                
        var_dump($decryptData);
    }
}