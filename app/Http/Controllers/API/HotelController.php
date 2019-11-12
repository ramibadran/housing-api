<?php
namespace App\Http\Controllers\API;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Builder\BestHotelCaller;
use App\Builder\CrazyHotelCaller;
use App\Builder\LocalHotelCaller;
use App\Transformers\HotelTransformer;
use App\Transformers\Transformer;
use App\Transformers\Respond;
use Illuminate\Http\Response as HttpResponse;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Flysystem\Exception;
use Config;
use App\Utilities\AES;
use App\Hotel;


class HotelController extends Controller{
    
    private $fractal;
    private $hotelTransformer;
    
    function __construct(Manager $fractal, HotelTransformer $hotelTransformer){
        $this->fractal          = $fractal;        
        $this->hotelTransformer = $hotelTransformer;
    }
   
    
    /*
     * This function responsible to return data from third party providers
     * city           example AMM
     * fromDate       example 2019-2-12
     * toDate         example 2019-2-15
     * numberOfAdults example 2
     */
    public function getThirdPartHotels(Request $request){
        $privateKey = $request->private_key;
        try{          
            $city       = $request->city;
            $fromDate   = $request->fromDate;
            $toDate     = $request->toDate;
            $number     = $request->numberOfAdults;
                            
            $bestHotel  = new BestHotelCaller();
            $query      = $this->buildBestQuery($city,$fromDate,$toDate,$number);
            $bestResult = $bestHotel->bestHotelCaller(config('custom.apiBaseURL').'best-hotels.json',$query,'GET');
            
            $crazyHotel  = new CrazyHotelCaller();
            $query       = $this->buildCrazyQuery($city,$fromDate,$toDate,$number);
            $crazyResult = $crazyHotel->crazyHotelCaller(config('custom.apiBaseURL').'crazy-hotels.json',$query,'GET');
            
            //it will easy to add a new provides since our factory/repository handle that
            
            $responsData = array_merge($bestResult, $crazyResult);
            
            if(empty($responsData)){
                $customError   = Config::get('custom.ApiMessages')[2000]['customCode'];
                $customMessage = Config::get('custom.ApiMessages')[2000]['customMessageEn'];
                $statusCode    = HttpResponse::HTTP_OK;
            }else{
                $customError   = Config::get('custom.ApiMessages')[2002]['customCode'];
                $customMessage = Config::get('custom.ApiMessages')[2002]['customMessageEn'];
                $statusCode    = HttpResponse::HTTP_OK;
            }
        }catch(Exception $e){
            $customError   = Config::get('custom.ApiMessages')[2009]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2009]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_SERVICE_UNAVAILABLE;
        }
        $transformer = new Transformer($responsData, $this->hotelTransformer,$this->fractal,$privateKey);
        $transformer->setStatusCode($statusCode);
        $transformer->setCustomCode($customError);
        $transformer->setCustomMessage($customMessage);
        return $transformer->respond('collection',$request->encrypt);	
    }
    
    public function createHotel(Request $request){
        $responsData = array();
        $privateKey  = $request->private_key;
        try{          
            
            if(isset($request->data) && $request->data != ''){
                $data          = $request->data;
                $aes           = new AES($data,$privateKey);
                $decryptedData = $aes->decrypt();
                if(is_array($decryptedData) && count($decryptedData) > 0){
                    if((isset($decryptedData['name']) &&  trim($decryptedData['name']) == '') || (isset($decryptedData['location']) &&  trim($decryptedData['location']) == '') || !isset($decryptedData['name']) || !isset($decryptedData['location'])){
                        $customError   = Config::get('custom.ApiMessages')[2016]['customCode'];
                        $customMessage = Config::get('custom.ApiMessages')[2016]['customMessageEn'];
                        $statusCode    = HttpResponse::HTTP_PRECONDITION_FAILED;
                    }else{
                        $name     = $decryptedData['name'];
                        $location = $decryptedData['location'];                                             
                        
                        $hotel           = new Hotel;
                        $hotel->name     = trim($name);
                        $hotel->location = trim($location);
                        $hotelInfo       = $hotel->save();
                        
                        if(!$hotelInfo){
                            $customError   = Config::get('custom.ApiMessages')[2017]['customCode'];
                            $customMessage = Config::get('custom.ApiMessages')[2017]['customMessageEn'];
                            $statusCode    = HttpResponse::HTTP_OK;
                        }else{
                            $responsData[0]['name']     = (string)trim($name);
                            $responsData[0]['location'] = (string)trim($location);
                            $customError      = Config::get('custom.ApiMessages')[2018]['customCode'];
                            $customMessage    = Config::get('custom.ApiMessages')[2018]['customMessageEn'];
                            $statusCode       = HttpResponse::HTTP_CREATED;
                        }                                  
                    }
                }else{
                    $customError   = Config::get('custom.ApiMessages')[2012]['customCode'];
                    $customMessage = Config::get('custom.ApiMessages')[2012]['customMessageEn'];
                    $statusCode    = HttpResponse::HTTP_PRECONDITION_FAILED;
                }
            }else{
                $customError   = Config::get('custom.ApiMessages')[2013]['customCode'];
                $customMessage = Config::get('custom.ApiMessages')[2013]['customMessageEn'];
                $statusCode    = HttpResponse::HTTP_PRECONDITION_FAILED;
            }                                            
        }catch(TokenExpiredException $e) {
            $customError   = Config::get('custom.ApiMessages')[2005]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2005]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(TokenBlacklistedException $e) {
            $customError   = Config::get('custom.ApiMessages')[2006]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2006]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(TokenInvalidException $e) {
            $customError   = Config::get('custom.ApiMessages')[2007]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2007]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(JWTException $e) {
            $customError   = Config::get('custom.ApiMessages')[2008]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2008]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(QueryException $e){
            $customError   = Config::get('custom.ApiMessages')[2009]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2009]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_SERVICE_UNAVAILABLE;
        }catch(\Exception $e){
            $customError   = Config::get('custom.ApiMessages')[2009]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2009]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_SERVICE_UNAVAILABLE;
        }
        
        $transformer = new Transformer($responsData,$this->hotelTransformer,$this->fractal,$privateKey);
        $transformer->setStatusCode($statusCode);
        $transformer->setCustomCode($customError);
        $transformer->setCustomMessage($customMessage);
        return $transformer->respond('collection',$request->encrypt);
    }
    
    public function getLocalHotels(Request $request){
        $responsData = array();
        $privateKey = $request->private_key;
        try{
            $responsData = Hotel::get();
            
            $localHotel  = new LocalHotelCaller();
            $data        = $localHotel->handlingResponse($responsData);
            
            if(empty($data)){
                $customError   = Config::get('custom.ApiMessages')[2000]['customCode'];
                $customMessage = Config::get('custom.ApiMessages')[2000]['customMessageEn'];
                $statusCode    = HttpResponse::HTTP_OK;
            }else{
                $customError   = Config::get('custom.ApiMessages')[2019]['customCode'];
                $customMessage = Config::get('custom.ApiMessages')[2019]['customMessageEn'];
                $statusCode    = HttpResponse::HTTP_OK;
            }
        }catch(TokenExpiredException $e) {
            $customError   = Config::get('custom.ApiMessages')[2005]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2005]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(TokenBlacklistedException $e) {
            $customError   = Config::get('custom.ApiMessages')[2006]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2006]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(TokenInvalidException $e) {
            $customError   = Config::get('custom.ApiMessages')[2007]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2007]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(JWTException $e) {
            $customError   = Config::get('custom.ApiMessages')[2008]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2008]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
        }catch(QueryException $e){
            $customError   = Config::get('custom.ApiMessages')[2009]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2009]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_SERVICE_UNAVAILABLE;
        }catch(\Exception $e){
            $customError   = Config::get('custom.ApiMessages')[2009]['customCode'];
            $customMessage = Config::get('custom.ApiMessages')[2009]['customMessageEn'];
            $statusCode    = HttpResponse::HTTP_SERVICE_UNAVAILABLE;
        }
        $transformer = new Transformer($data, $this->hotelTransformer,$this->fractal,$privateKey);
        $transformer->setStatusCode($statusCode);
        $transformer->setCustomCode($customError);
        $transformer->setCustomMessage($customMessage);
        return $transformer->respond('collection',$request->encrypt);
    }
    
    private function buildBestQuery($city,$fromDate,$toDate,$number){
        return '?city='.$city.'&fromDate='.$fromDate.'&toDate='.$toDate.'&numberOfAdults:='.$number;
    }
    
    private function buildCrazyQuery($city,$fromDate,$toDate,$number){
        return '?city='.$city.'&from='.$fromDate.'&To='.$toDate.'&adultsCount:='.$number;
    }
        
}

                