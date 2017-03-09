<?php
require_once 'vendor/autoload.php';

use djchen\OAuth2\Client\Provider\Fitbit;

$provider = new Fitbit([
    'clientId'          => '2287S3',
    'clientSecret'      => '5d779706cc7e0f25e15d1e1166b0df70',
    'redirectUri'       => 'https://example.com/callback-url'
]);

// start the session
session_start();


// Set variables
$hostname = "localhost";
$username = "root";
$password = "root";
$database = "fitbit";

$userId = "3DH4LK";
$date = date('Y-m-d');
$accessToken = "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIzREg0TEsiLCJhdWQiOiIyMjg3UzMiLCJpc3MiOiJGaXRiaXQiLCJ0eXAiOiJhY2Nlc3NfdG9rZW4iLCJzY29wZXMiOiJyc29jIHJzZXQgcmFjdCBybG9jIHJ3ZWkgcmhyIHJudXQgcnBybyByc2xlIiwiZXhwIjoxNDg1MDcyMDk0LCJpYXQiOjE0ODQ0NzA1MDZ9.8Fw-JV2z8GQ_acfFnt219a8cIKqOKvVNvokge51hKcQ";

$apiUrls = [
    "/1/user/-/profile.json",
    "/1/user/$userId/profile.json",
    "/1/user/$userId/activities/date/$date.json",
    "/1/user/$userId/body/log/fat/date/$date.json",
    "/1/user/-/devices.json",
    "/1/foods/locales.json",
    "/1/user/$userId/friends.json",
    "/1/user/$userId/activities/heart/date/$date/[period].json",
    "/1/user/$userId/sleep/date/$date.json",
];

foreach ($apiUrls as $type => $url) {
    $request = $provider->getAuthenticatedRequest(
        Fitbit::METHOD_GET,
        Fitbit::BASE_FITBIT_API_URL . $url,
        $accessToken,
        ['headers' => [Fitbit::HEADER_ACCEPT_LANG => 'en_US'], [Fitbit::HEADER_ACCEPT_LOCALE => 'en_US']]
    );
    $response = $provider->getResponse($request);
    echo '<h4>' . Fitbit::BASE_FITBIT_API_URL . $url . '</h4>';
    echo '<pre>' . print_r($response, true) . '</pre>';

    $data_json = json_encode($response);
    $created = date('Y-m-d H:i:s');

    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO fitbit_data (user_id, data_type, data_json, created) VALUES ('$userId', '$type', '$data_json', '$created')";
        $conn->exec($sql);
        echo "New record created successfully.\n";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}