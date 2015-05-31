<?php
namespace Models\Twitch;

class Twitch
{
    public static $clientId;
    public static $clientSecret;
    public static $redirectUri;

    public static function Login ( )
    {

    }

    public static function RefreshToken ( $refreshToken )
    {
        $fields = array(
            'client_id' => self::$clientId,
            'client_secret' => self::$clientSecret,
            'grant_type' => 'refresh_token',
            'redirect_uri' => self::$redirectUri,
            'refresh_token' => $refreshToken
        );

        $url = "https://api.twitch.tv/kraken/oauth2/token";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.twitchtv.v2+json'
            //'Authorization: OAuth <access_token>'
        ));
        curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($curl);

        return $result;
    }

    public static function RequestToken ( $code )
    {
        $fields = array(
            'client_id' => self::$clientId,
            'client_secret' => self::$clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => self::$redirectUri,
            'code' => $code
        );

        $url = "https://api.twitch.tv/kraken/oauth2/token";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.twitchtv.v2+json'
            //'Authorization: OAuth <access_token>'
        ));
        curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($curl);

        return $result;
    }

    public static function GetRoot ( $accessToken )
    {
        $url = "https://api.twitch.tv/kraken";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.twitchtv.v2+json',
            'Authorization: OAuth ' . $accessToken
        ));
        $result = curl_exec($curl);

        return $result;
    }

    public static function GetChannelFollowers ( $channel, $limit = 25 )
    {
        $url = "https://api.twitch.tv/kraken/channels/$channel/follows?limit=$limit&t=" . time();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.twitchtv.v2+json'
        ));
        $result = curl_exec($curl);

        return $result;
    }

    public static function GetChannelSubscribers ( $accessToken, $channel, $limit = 25 )
    {
        $url = "https://api.twitch.tv/kraken/channels/$channel/subscriptions?limit=$limit&t=" . time();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.twitchtv.v2+json',
            'Authorization: OAuth ' . $accessToken
        ));
        $result = curl_exec($curl);

        return $result;
    }

    public static function GetChannel ( $accessToken )
    {
        $url = "https://api.twitch.tv/kraken/channel";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.twitchtv.v2+json',
            'Authorization: OAuth ' . $accessToken
        ));
        $result = curl_exec($curl);

        return $result;
    }
}

?>