<?php
namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'X-Requested-With, Content-Type, Accept, Origin, Authorization',
            'Access-Control-Allow-Origin' => '*'
        ];

        

        $response = $next($request);
        foreach($headers as $key => $value)
            $response->header($key, $value);
        return $response;

        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application');
        // return $next($request);
    }
}