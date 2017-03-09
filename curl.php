<?php
$url = "https://api.fitbit.com/1/user/-/profile.json";
$authorization = "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIzREg0TEsiLCJhdWQiOilc3NfdG9rZW4iLCJzY29wZXMiOiJyc29jIHJzZXQgcmFjdCBybG9jIHJ3ZWkgcmhyIHJudXQgcnBybyByc2xlIiwiZXhwIjoxNDg1MDcyMDk1LCJpYXQiOjE0ODQ0NjcyOTV9.Uc8bvTL3xh7GhhP4ctMIuStvaK_yAgoFVkE8cXKRv8o";
$params = "";

makeRequest($url, $authorization);

function makeRequest($url, $authorization)
{
    $oauth_profile_header = [$authorization];
    $cu = curl_init($url);
    curl_setopt($cu, CURLOPT_HTTPHEADER, $oauth_profile_header);
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($cu);
    curl_close($cu);
    print_r($result);
}