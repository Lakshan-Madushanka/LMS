<?php


namespace App\Helpers;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class PassportHelper
{
    public static function  revokeToken()
    {
        throw_unless(Auth::check(),
            new AuthenticationException(__('messages.userNotLogin')));

        $tokenReository = app(TokenRepository::class);
        $refreshRepository = app(RefreshTokenRepository::class);

        $tokenId = DB::table('oauth_access_tokens')->select('id')
            ->where('user_id', Auth::id())->latest()->value('id');

        $tokenReository->revokeAccessToken($tokenId);
        $refreshRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}