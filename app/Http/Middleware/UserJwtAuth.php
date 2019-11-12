<?php

namespace App\Http\Middleware;

use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Http\Response as HttpResponse;
use Response;
use Config;
use Cache;
class UserJwtAuth 
{
    /**
     * The names of the route that should not pass to custom auth.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next){
        try {            
            $token = JWTAuth::getToken();
            $user  = JWTAuth::toUser($token);

            if(count($user) == 0){
                // must call the login agi again 
                return Response::json([
                    'custom_message' => Config::get('custom.ApiMessages')[2011]['customMessageEn'],
                    'custom_code'    => Config::get('custom.ApiMessages')[2011]['customCode'],
                    'data'           => [],
                ],HttpResponse::HTTP_UNAUTHORIZED);
            }

            // Validate User
            $userApiIpsList = User::getIpList($user['id']);
            if(count($userApiIpsList) > 0){
                if($userApiIpsList->status == 1){
                    $allowedIps = explode(',',$userApiIpsList->api_ip_white_list);
                    //if(!in_array($request->ip(),$allowedIps)){ //for live 
                    if(empty($allowedIps)){
                        return Response::json([
                            'custom_message' => Config::get('custom.ApiMessages')[2003]['customMessageEn'],
                            'custom_code'    => Config::get('custom.ApiMessages')[2003]['customCode'],
                            'data'           => [],
                        ],HttpResponse::HTTP_FORBIDDEN);
                    }
                }else{
                    return Response::json([
                        'custom_message' => Config::get('custom.ApiMessages')[2040]['customMessageEn'],
                        'custom_code'    => Config::get('custom.ApiMessages')[2040]['customCode'],
                        'data'           => [],
                    ],HttpResponse::HTTP_FORBIDDEN);
                }
            }else{
                return Response::json([
                    'custom_message' => Config::get('custom.ApiMessages')[2003]['customMessageEn'],
                    'custom_code'    => Config::get('custom.ApiMessages')[2003]['customCode'],
                    'data'           => [],
                ],HttpResponse::HTTP_FORBIDDEN);
            }


            //create new token and return it in the cureent respones 
            $newToken = JWTAuth::refresh($token); 

            //send the refreshed token back to the clien
            $request->request->add(['user_id' => $user['id'],'name' => $user['username'],'private_key' => $user['private_key'],'user_id' => $user['user_id']]);
            $response = $next($request);
            $response->headers->set('Authorization',$newToken);

            return $response;
        }catch(TokenExpiredException $e) {
            return Response::json([
                'custom_message' => Config::get('custom.ApiMessages')[2005]['customMessageEn'],
                'custom_code'    => Config::get('custom.ApiMessages')[2005]['customCode'],
                'data'           => [],
            ],HttpResponse::HTTP_UNAUTHORIZED);
		}catch(TokenBlacklistedException $e) {
            return Response::json([
                'custom_message' => Config::get('custom.ApiMessages')[2006]['customMessageEn'],
                'custom_code'     => Config::get('custom.ApiMessages')[2006]['customCode'],
                'data'            => [],
            ],HttpResponse::HTTP_UNAUTHORIZED);
		}catch(TokenInvalidException $e) {
            return Response::json([
                'custom_message' => Config::get('custom.ApiMessages')[2007]['customMessageEn'],
                'custom_code'    => Config::get('custom.ApiMessages')[2007]['customCode'],
                'data'           => [],
            ],HttpResponse::HTTP_UNAUTHORIZED);
        }catch(JWTException $e) {
			return Response::json([
                'custom_message' => Config::get('custom.ApiMessages')[2008]['customMessageEn'],
                'custom_code'    => Config::get('custom.ApiMessages')[2008]['customCode'],
                'data'           => [],
            ],HttpResponse::HTTP_UNAUTHORIZED);
		}catch(Exception $e){	
			return Response::json([
                'custom_message' => Config::get('custom.ApiMessages')[2010]['customMessageEn'],
                'custom_code'    => Config::get('custom.ApiMessages')[2010]['customCode'],
                'data'           => [],
            ],HttpResponse::HTTP_SERVICE_UNAVAILABLE);
        }  
    }
}