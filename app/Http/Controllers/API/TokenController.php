<?php
namespace App\Http\Controllers\API;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Transformers\AuthTokenTransformer;
use App\Transformers\Transformer;
use App\Transformers\Respond;
use Illuminate\Http\Response as HttpResponse;
use League\Fractal\Resource\Collection;
use DB;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Flysystem\Exception;
use Illuminate\Database\QueryException;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Config;
use App\Client;
use App\Utilities\AES;
use App\User;

class TokenController extends Controller{
	private $fractal;
	private $authTokenTransfromer;

    function __construct(Manager $fractal, AuthTokenTransformer $authTokenTransfromer){
        $this->fractal              = $fractal;
        $this->authTokenTransfromer = $authTokenTransfromer;
	}
	
	public function token(Request $request){
	    $data = array();
	    try{
	        $publicKey  = $request->header('key');
	        $privateKey = '';
	        if(!is_null($publicKey)){
	            $user = User::getUserDetails($publicKey);
	            if(count($user) > 0){
	                if($user->status == 1){
	                    if(isset($request->data) && $request->data != ''){
	                        $privateKey    = $user->private_key;
	                        $key           = $request->data;
	                        $aes           = new AES($key,$privateKey);
	                        $decryptedData = $aes->decrypt();
	                        
	                        if(is_array($decryptedData) && count($decryptedData) > 0){
	                            if((isset($decryptedData['username']) &&  trim($decryptedData['username']) == '') || (isset($decryptedData['identifier']) &&  trim($decryptedData['identifier']) == '') || !isset($decryptedData['username']) || !isset($decryptedData['identifier'])){
	                                $customError   = Config::get('custom.ApiMessages')[2001]['customCode'];
	                                $customMessage = Config::get('custom.ApiMessages')[2001]['customMessageEn'];
	                                $statusCode    = HttpResponse::HTTP_PRECONDITION_FAILED;
	                            }else{
	                                $username   = $decryptedData['username'];
	                                $identifier = $decryptedData['identifier'];
	                                //instead of DB query we can handle from the first query by matching the IP
	                                $allowed    = User::isUserHasApiAccess($username,$identifier,$request->ip());
                                    if($allowed > 0){
                                        //$userData = ['user_id' => $user->id,'username' => $user->username,'name' => $user->name,'status' => $user->status,'secret_key' => $user->secret_key,'api_ip_white_list' => $user->api_ip_white_list,'private_key' => $user->private_key];
                                        //$payload  = JWTFactory::sub($userData)->user($userData)->make();
                                        //$token    = JWTAuth::encode($payload);
                                        //print_r($user);
                                        
                                        //$user1 = User::where('username','=',$username)->where('secret_key','=',$user->secret_key)->where('status','=','1')->first();
                                        $token = JWTAuth::fromUser($user);
                                       
                                        if(!$token) {
                                            $customError   = Config::get('custom.ApiMessages')[2004]['customCode'];
                                            $customMessage = Config::get('custom.ApiMessages')[2004]['customMessageEn'];
                                            $statusCode    = HttpResponse::HTTP_OK;
                                        }else{
                                            $data['token'] = (string)$token;
                                            $customError   = Config::get('custom.ApiMessages')[2010]['customCode'];
                                            $customMessage = Config::get('custom.ApiMessages')[2010]['customMessageEn'];
                                            $statusCode    = HttpResponse::HTTP_CREATED;
                                        }
                                    }else{
                                        $customError   = Config::get('custom.ApiMessages')[2003]['customCode'];
                                        $customMessage = Config::get('custom.ApiMessages')[2003]['customMessageEn'];
                                        $statusCode    = HttpResponse::HTTP_FORBIDDEN;
                                    }	                             
	                            }
	                        }else{
	                            $customError   = Config::get('custom.ApiMessages')[2012]['customCode'];
	                            $customMessage = Config::get('custom.ApiMessages')[2012]['customMessageEn'];
	                            $statusCode    = HttpResponse::HTTP_PRECONDITION_FAILED;
	                        }
	                    }else{
	                        $customError   = Config::get('custom.ApiMessages')[2012]['customCode'];
	                        $customMessage = Config::get('custom.ApiMessages')[2012]['customMessageEn'];
	                        $statusCode    = HttpResponse::HTTP_PRECONDITION_FAILED;
	                    }
	                }else{
	                    $customError   = Config::get('custom.ApiMessages')[2015]['customCode'];
	                    $customMessage = Config::get('custom.ApiMessages')[2015]['customMessageEn'];
	                    $statusCode    = HttpResponse::HTTP_FORBIDDEN;
	                }
	            }else{
	                $customError   = Config::get('custom.ApiMessages')[2001]['customCode'];
	                $customMessage = Config::get('custom.ApiMessages')[2001]['customMessageEn'];
	                $statusCode    = HttpResponse::HTTP_UNAUTHORIZED;
	            }
	        }else{
	            $customError   = Config::get('custom.ApiMessages')[2014]['customCode'];
	            $customMessage = Config::get('custom.ApiMessages')[2014]['customMessageEn'];
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
	    
	    $transformer = new Transformer($data,$this->authTokenTransfromer,$this->fractal,$privateKey);
	    $transformer->setStatusCode($statusCode);
	    $transformer->setCustomCode($customError);
	    $transformer->setCustomMessage($customMessage);
	    return $transformer->respond('collection',$request->encrypt);
	}
}