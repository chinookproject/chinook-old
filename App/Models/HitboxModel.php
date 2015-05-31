<?php
namespace Models;

use Models\Hitbox\Hitbox;

class HitboxModel
{
    public function __construct ( )
    {
    }

    public function auth ( $username, $password )
    {
        $result = Hitbox::Auth ( $username, $password );
        if ( $result == 'auth_failed' )
            return null;

        return json_decode ( $result );
    }

    public function getUserData ( $username )
    {
        $result = Hitbox::User ( $username );
        return json_decode ( $result );
    }

    public function getChannelFollowers ( $username, $limit = 25 )
    {
        $result = Hitbox::GetChannelFollowers ( $username, $limit );
        return json_decode ( $result );
    }

    public function getChannelSubscribers ( $auth, $username, $limit = 25 )
    {
        $result = Hitbox::GetChannelSubscribers ( $auth, $username, $limit );
        return json_decode ( $result );
    }
}

?>