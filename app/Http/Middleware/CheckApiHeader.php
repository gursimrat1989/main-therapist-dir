<?php

namespace App\Http\Middleware;

use Closure;
use Response;  

class checkApiHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
   public function handle($request, Closure $next)  
    {  
		
		
        if(!isset($_SERVER['HTTP_X_CUSTOM_HEADER'])){  
            return Response::json(array('error'=>'Please set custom header'));  
        }  
        if($_SERVER['HTTP_X_CUSTOM_HEADER'] != '7890abcdefghijkl'){  
            return Response::json(array('error'=>'wrong custom header'));  
        }  
  
        return $next($request);  
    } 
}
