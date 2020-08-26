<?php

require "./jd/JdSdk.php";

$appKey = "eef9cf28b5aebd0fbd78cc9cb72541a7";
$appSecret = "66333d4c579649fb8cfa3ea67e04b08e";
$code_url = "https://open-oauth.jd.com/oauth2/to_login";
$access_token_url = "https://open-oauth.jd.com/oauth2/access_token";

$access_token = "2f52cd0e2efb44fdbc0e1c0f94f3edcbmguy";

$c = new JdClient();
$c->appKey = $appKey;
$c->appSecret = $appSecret;

$c->accessToken = $access_token;

$req = new UnionOpenCouponQueryRequest();

$couponUrls= array();

$req->setCouponUrls($couponUrls);


$resp = $c->execute($req, $c->accessToken);

var_dump($resp);