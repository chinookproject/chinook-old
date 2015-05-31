<?php
namespace Models;

use Config\App;
use Models\Twitch\Twitch;

class TwitchModel
{
    public function __construct ( )
    {
        Twitch::$clientId = 'fjgjflh706udeiwwonvjpd3teg3u22i';
        Twitch::$clientSecret = 'fpp7kietj0gw9z6uu19vy3wvcr1kxmv';
        Twitch::$redirectUri = App::$apiUrl . '/twitch';
    }

    public function getUserData ( $token, $isRefreshToken = false )
    {
        $twitch = array ( );

        $result = null;
        if ( $isRefreshToken )
        {
            $result = Twitch::RefreshToken ( $token );
        }
        else
        {
            $result = Twitch::RequestToken ( $token );
        }

        $twitch['requestToken'] = json_decode ( $result );

        if( isset ( $twitch['requestToken']->access_token ) )
        {
            $result = Twitch::GetRoot($twitch['requestToken']->access_token);
            $twitch['root'] = json_decode ( $result );

            $result = Twitch::GetChannel($twitch['requestToken']->access_token);
            $twitch['channel'] = json_decode ( $result );
            if ( !isset ( $twitch['channel'] ) || !$twitch['channel']->logo )
            {
                $twitch['channel']->logo = 'http://sandbox.twitchplus.com/img/logo.png';
            }

            return $twitch;
        }

        return null;
    }

    public function GetChannelFollowers ( $username, $limit = 25 )
    {
        return json_decode ( Twitch::GetChannelFollowers ( $username, $limit ) );
    }

    public function GetChannelSubscribers ( $accessToken, $username, $limit = 25 )
    {
        return json_decode ( Twitch::GetChannelSubscribers ( $accessToken, $username, $limit ) );
    }
}

?>