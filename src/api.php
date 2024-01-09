<?php
function searchHOTPEPPER($keyword){
    $api_Key='fe9331a5a63d72f1';
    $api_End='http://webservice.recruit.co.jp/hotpepper/shop/v1/';
    $search=[
        'key'=>$api_Key,
        'keyword'=>$keyword,
        'format'=>'json',
    ];
    //リクエスト
    $api_url=$api_End.'?'.http_build_query($search);
    $response=file_get_contents($api_url);
    $data=json_decode($response, true);
    //検索結果
    if($data&&isset($data['results']['shop'])){
        return $data['results']['shop'];
    }else{
        return [];
    }
}
?>