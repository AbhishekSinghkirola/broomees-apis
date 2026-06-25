<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');

        if (!$header) {
            throw new UnauthorizedHttpException('Bearer', 'Missing Authorization Header');
        }

        $token = str_replace(
            'Bearer ',
            '',
            $header
        );

        $existingToken = ApiToken::where('token_hash', hash('sha256', $token))->first();

        if (!$existingToken || $existingToken->expires_at < now() || $existingToken->is_revoked) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid Token');
        }

        return $next($request);
    }
}
