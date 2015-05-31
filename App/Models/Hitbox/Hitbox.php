<?php
namespace Models\Hitbox;

class Hitbox
{
    public static function Auth ( $username, $password )
    {
        $fields = array(
            'login' => $username,
            'pass' => $password,
            'app' => 'desktop'
        );

        $url = "http://api.hitbox.tv/auth/token";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($curl);

        return $result;
    }

    public static function User ( $username )
    {
        $url = "http://api.hitbox.tv/user/" . $username;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($curl);

        return $result;
    }

    public static function GetChannelFollowers ( $username, $limit = 25 )
    {
        $url = "http://api.hitbox.tv/followers/user/$username/?limit=$limit&reverse=true&sort=date_added";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($curl);

        return $result;
    }

    public static function GetChannelSubscribers ( $auth, $username, $limit = 25 )
    {
        $url = "https://www.hitbox.tv/api/subscriberlist/$username?authToken=$auth&nocache=true";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($curl);

        // Todo: Fix when subscribers API supports limit
        $data = json_decode ( $result );
        if ( isset ( $data->subscribers ) && is_array ( $data->subscribers ) )
        {
            $data->subscribers = array_slice ( $data->subscribers, 0, $limit );
            $result = json_encode ( $data );
        }

        return $result;
    }
}

?>