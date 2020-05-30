<?php defined("INDEX") or die;

function wanikani_request($endpoint,$api_key,$raw_response=false) {

    $url = "https://api.wanikani.com/v2/".$endpoint;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER,[
        "Authorization: Bearer ". $api_key,
    ]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 1);
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
    curl_setopt($ch, CURLOPT_CAINFO, SSL_CERT);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response["data"]) || $raw_response)
        return $response;
    else
        return false;
}

function endpoint_from_url($url) {
    if (strpos($url, "/") !== false) {
        $url = substr($url, strrpos($url, "/")+1);
    }
    return $url;
}