<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class VerifyJwtAndScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $scope)
    {
        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAuYQTOo+++bpK0B5mibMi
        Jb/ksUAh8NgPKM9tqHhYRdHAGTMPyoN47C9LwCksAzlMwaksL2ZxZYU10lph7Y2i
        eXaYmIs/QgRaA95UFTzvMs00IKWr7+MsyMWjkXQu+l4gHJ5oIoM9KAzbl5d5akEV
        BvKofKqalRxyEoIJGOLU+L/r57DZVenB9A0xX1dHHfvStdGkRzPBCiOWTg5JZBet
        98zbz5EJ4LZ7h11sRU4Czwx4jWSiCqZY0HL4gxHyyh1j8VOzCsfCod7KjTF8w4xX
        U0fuUcUWKRa9R6n6pIFbrbZ7TuqpFzDPw6jJluc0pKUavo0C83hNDCev34sUPCBA
        Ip4be94b91Z1QGHPMG37vHEoe5svCi87lZ0XiRMoRYGlHsncHhZksmhIovD6kNEH
        BmWE4aIvseNmjA7IKRqn8Eo5r8SCyoC8KX9AwVChhCKbw5AeAV8A7TyjETF4eHWI
        oBQP+s6FOVEhhxl+3Mq/TgwXzTBWyiLLVg8Ctfd1g4UP5APIZ1WN1EzrZWfUw/KF
        ku9/4rGvMO8GKiBOmMObi6dqPmRLbuXC/FRdlmYLAZTiWPEc1P98jQZu4Biod8hh
        smcb1AgpVGxIgYBMT3v47NAbr5AqlRDlXK9TnRQ1J6OGXXwPLR495rnAWe5sRn49
        sz/sxx2ZqS97OLz4HdjWQT0CAwEAAQ==
        -----END PUBLIC KEY-----
        EOD;

        $jwt = $request->bearerToken();

        // Validate Bearer Token (Authentication)
        try {
            $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Verify Scopes (Authorization)
        $allowedScopes = $decoded->scopes;
        if (!in_array($scope, $allowedScopes)) {
            return response()->json(['error' => 'Missing scope: ' . $scope], 401);
        }

        return $next($request);
    }
}
