<?php

namespace App\Http\Middleware;

use App\Http\Traits\GeneralTrait;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AssignGuard extends BaseMiddleware
{

    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next , $guard = null)
    {
        if($guard != null){
            auth()->shouldUse($guard); // should use guard
            $token = $request->header('auth-token');
            $request->headers->set('auth-token',(string) $token , true);
            $request->headers->set('Authorization','Bearer'.$token, true);

            try{
                $user = $this->auth->authenticate($request); // check authenticated user
            }catch (TokenExpiredException $e){
                return $this->returnError('401', 'unauthenticated USer');
            } catch (JWTException $e){
                return $this->returnError('', 'token_invalid', $e->getMessage());
            }
            return $next($request);
        }

        return $next($request);
    }
}
