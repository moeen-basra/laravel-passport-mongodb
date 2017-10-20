<?php

namespace MoeenBasra\LaravelPassportMongoDB;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class TokenRepository
{
    /**
     * Creates a new Access Token.
     *
     * @param  array  $attributes
     * @return \MoeenBasra\LaravelPassportMongoDB\Token
     */
    public function create($attributes)
    {
        return Token::create($attributes);
    }

    /**
     * Get a token by the given ID.
     *
     * @param  string  $id
     * @return \MoeenBasra\LaravelPassportMongoDB\Token
     */
    public function find($id)
    {
        return Token::where('id', $id)->first();
    }

    /**
     * Get a token by the given user ID and token ID.
     *
     * @param  string  $id
     * @param  int  $userId
     * @return \MoeenBasra\LaravelPassportMongoDB\Token|null
     */
    public function findForUser($id, $userId)
    {
        return Token::where('_id', $id)->where('user_id', $userId)->first();
    }

    /**
     * Get the token instances for the given user ID.
     *
     * @param  mixed  $userId
     * @return \Jenssegers\Mongodb\Eloquent\Collection
     */
    public function forUser($userId)
    {
        return Token::where('user_id', $userId)->get();
    }

    /**
     * Get a valid token instance for the given user and client.
     *
     * @param  \Jenssegers\Mongodb\Eloquent\Model  $user
     * @param  \MoeenBasra\LaravelPassportMongoDB\Client  $client
     * @return \MoeenBasra\LaravelPassportMongoDB\Token|null
     */
    public function getValidToken($user, $client)
    {
        return $client->tokens()
                    ->whereUserId($user->getKey())
                    ->whereRevoked(0)
                    ->where('expires_at', '>', Carbon::now())
                    ->first();
    }

    /**
     * Store the given token instance.
     *
     * @param  \MoeenBasra\LaravelPassportMongoDB\Token  $token
     * @return void
     */
    public function save(Token $token)
    {
        $token->save();
    }

    /**
     * Revoke an access token.
     *
     * @param  string  $id
     * @return mixed
     */
    public function revokeAccessToken($id)
    {
        return Token::where('_id', $id)->update(['revoked' => true]);
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param  string  $id
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($id)
    {
        if ($token = $this->find($id)) {
            return $token->revoked;
        }

        return true;
    }

    /**
     * Find a valid token for the given user and client.
     *
     * @param  \Jenssegers\Mongodb\Eloquent\Model  $user
     * @param  \MoeenBasra\LaravelPassportMongoDB\Client  $client
     * @return \MoeenBasra\LaravelPassportMongoDB\Token|null
     */
    public function findValidToken($user, $client)
    {
        return $client->tokens()
                      ->whereUserId($user->getKey())
                      ->whereRevoked(0)
                      ->where('expires_at', '>', Carbon::now())
                      ->latest('expires_at')
                      ->first();
    }
}
