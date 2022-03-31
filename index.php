<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sierra Test</title>
    <style>
        *{
            font-family: Consolas, monaco, monospace
        }
    </style>
</head>
<body>
<?php


$token = getSecretString();
// This gets the secret key. That key is used to get access tokens. This key is based on your secret and your given API key.
// This is only needed once.
// There is a lot of overlap as it is very similar to getting an access token.
function getSecretString(){
    /*
    $secret = "SEEEECRET";
    $key = "APxH6nFMlfx+PVjj9oJuuEhL9lX/"; //Sierra key
    $encrypted_key = base64_encode($key.":".$secret);
    $url = "https://wncln.wncln.org/iii/sierra-api/v6/token";
    $ch = curl_init($url);
    $request_headers = array(
        'authorization : Basic'.$encrypted_key
    );
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $array = json_decode($result);
    if(property_exists($array, 'access_token')){
        return $array->access_token;
    }else{
        return false;
    }
    */
    return 0;
}
// Handling, Tokenization, and cURL can all be functionalized to cut down on
// duplicate lines within functions.










// This is the token created when you submit the token+secret request This stays with you.
$token = 'blahblahstuffandthings';
$url = 'https://wncln.wncln.org/iii/sierra-api/v6/';
$access_token = "";
getAccessToken();
if($access_token != ""){
    apiCall();
}

/*
 * Get Access Token
 * */
function getAccessToken(){
    // return temporary access token used to make API calls header info can extend that
    global $token;
    global $url;
    $token_url = $url.'token';
    $ch = curl_init($token_url);
    $request_headers = array(
        'Authorization: Basic '.$token  // add token to header
    );
    curl_setopt($ch, CURLOPT_POST, true); // POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers); // add headers
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return output instead of echo it out
    $result = curl_exec($ch);
    if(curl_errno($ch)){
        echo curl_error($ch); // put something here to HTTP error
    }else{
        handleReply($result);
    }
    curl_close($ch);
}



function handleReply($reply){
    $array = json_decode($reply);
    if(property_exists($array, 'access_token')){
        // success
        global $access_token;
        $access_token = $array->access_token;
    }else if(property_exists($array, 'error')){
        // handle general error
        echo $array->error;
    }else if(property_exists($array, 'description')){
        // handle Sierra error
        echo $array->descrption;
    }
}

/*
 * Demonstrate an API call with access token
 */
function apiCall(){
    global $url;
    global $access_token;
    $bib_url = $url.'bibs';
    // cURL defaults to GET;
    $request_headers = array(
        'Authorization: Bearer '.$access_token
    );
    $params = array('limit' => 10,
        'deleted' => 'false',
        'fields' => 'title,author,publishYear',
        'createdDate' => '[2021-04-02T19:20:28Z,]');
    $bib_url .= ($params ? '?'.http_build_query($params) : '');
    $ch = curl_init($bib_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
}
?>
</body>
</html>
