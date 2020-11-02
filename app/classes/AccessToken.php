<?php


class AccessToken extends GenericEntity
{
    public string $token_type = "Bearer";
    public int $expires_in;
    public int $ext_expires_in;
    public string $access_token;

    public function __construct(?array $properties = null)
    {
        parent::__construct($properties);
        $properties = json_decode(static::refreshAccessToken(), JSON_OBJECT_AS_ARRAY);
        $this->initialize($properties);
    }


    public static function refreshAccessToken() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://login.microsoftonline.com/%7B3a652cac-c74f-4bd7-9144-a937afc5b541%7D/oauth2/v2.0/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "client_id=b72205b0-8882-4bb0-96b8-386132efbeab&scope=https%3A//graph.microsoft.com/.default&client_secret=aif-A9bXM9L8Hn3TDyOC%7E3%7EbNaDhO69_m3&grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "Host: login.microsoftonline.com",
                "Content-Type: application/x-www-form-urlencoded",
                "Cookie: fpc=AldV45z1RSZFuBEu3vElkrY"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}
