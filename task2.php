<?php
    include "./taobao/TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 
    $c = new TopClient;
    $c->appkey = '31082950';
    $c->secretKey = 'cad2e642d965ea167bf4a357533f2fbe';

    // $req = new TbkTpwdCreateRequest;
    // // $req->setUserId("123");
    // $req->setText("緮置内容₴4Wa2c27a0FP₴达开淘tao寳【Septwolves/七匹狼男士粘胶素色内裤平角裤柔和舒适四角裤4条装】");
    // $req->setUrl("https://uland.taobao.com/");
    // $req->setLogo("https://uland.taobao.com/");
    // $req->setExt("{}");

    // var_dump($c->execute($req));

    $req = new TbkTpwdConvertRequest;
    $req->setPasswordContent("￥2k12308DjviP￥");
    $req->setAdzoneId("110802300023");
    $req->setDx("1");

    var_dump($c->execute($req));
?>