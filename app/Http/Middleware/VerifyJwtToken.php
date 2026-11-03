<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class VerifyJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        JWT::$leeway = 60;
        
        $contentTypes = $request->getAcceptableContentTypes();
        if ( !in_array('application/json',$contentTypes)) {
            return response()->json([
                'code' => 201, 
                'messages'=> 'Request header not accepted application/json'
            ],201);
        }

        if (!$request->bearerToken()) {
            return response()->json([
                'code' => 401, 
                'messages'=> 'Request header not authorization'
            ],401);
        }
 
        $jwt_key  = env('JWT_SECRET');
        $jwt_data = $request->bearerToken();
        
        try {
            $data = JWT::decode($jwt_data, new Key($jwt_key, 'HS256'));
            if(isset($data->nbf) > time() ) {
                throw new Exception("Authorization Expired.");
            }
        } catch (\Exception $e) {
            return response()->json([
                'code'      => 401, 
                'messages'  => ($e->getMessage()) ? $e->getMessage() : 'No Valid Authorization!.'
            ],401);
        }
        return $next($request);
    }
}
