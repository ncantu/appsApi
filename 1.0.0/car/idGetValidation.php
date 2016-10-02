<?php

require_once '../lib/apiResult.php';

$apiResult = new ApiResult();
$apiResult->cacheStateOff();
$apiResult->resultService = 'ifGetValidation';

$request = file_get_contents('php://input');
$request = json_decode($request);

if(isset($request->frenchLicencePlate) === true && empty($request->frenchLicencePlate) !== true) {
    
    $plaque = $request->frenchLicencePlate;
    $cacheFile = $plaque.'.json';    
    $apiResult->cacheFileSet($cacheFile);
    $apiResult->plaqueSet($plaque);
    $apiResult->cacheRun();
    
    $fields_string = '';
    $fields = array(
        'frenchLicencePlate' => urlencode($plaque),
        'genartId' => urlencode('')
    );

    foreach($fields as $key=>$value) { 
     
        $fields_string .= $key.'='.$value.'&'; 
    }
    rtrim($fields_string, '&');

    $apiResult->logAdd($fields_string);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.oscaro.com/Catalog/SearchEngine/LicencePlateJQueryV2');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch,CURLOPT_HTTPHEADER,array(
        'Origin: '.'https://www.oscaro.com',
        'Accept-Language: fr,en-US;q=0.8,en;q=0.6',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/json, text/javascript, */*; q=0.01',
        'X-Requested-With: XMLHttpRequest',
        'Connection: keep-alive'
    ));
    curl_setopt($ch, CURLOPT_COOKIE, '_dc_gtm_UA-24791987-1=1; _pk_ref.815.9612=%5B%22%22%2C%22%22%2C1474447569%2C%22http%3A%2F%2Fwww.palais-de-la-voiture.com%2F2015%2F08%2Fobtenir-des-informations-d-apres-une-plaque-d-immatriculation.html%22%5D; _tlp=608:3107138; __bin_test_v2=23; _cs_v=0; _cs_r=0; __ua55=GA1.2.552846453.1474447569; _pk_id.815.9612=3c0476ec5629638f.1474447569.1.1474449793.1474447569.; _pk_ses.815.9612=*; ry_ry-0sc4r02p_realytics=eyJpZCI6InJ5XzZCODNDRUZDLUUzQjYtNDU0MS1BNEI3LUQyOTc1REEwMkE2RCIsImNpZCI6bnVsbCwiZXhwIjoxNTA1OTgzNTY5NDQ5fQ%3D%3D; ry_ry-0sc4r02p_so_realytics=eyJpZCI6InJ5XzZCODNDRUZDLUUzQjYtNDU0MS1BNEI3LUQyOTc1REEwMkE2RCIsImNpZCI6bnVsbCwib3JpZ2luIjpmYWxzZSwicmVmIjpudWxsLCJjb250IjpudWxsfQ%3D%3D; _cs_id=33eafc90-73ea-a6ff-f9b7-f9b57233ae23.1474447570.1.1474449793.1474447570; _cs_s=11; _tlc=www.google.fr%2F:1474449793:www.oscaro.com%2F%23:oscaro.com; _tlv=1.1474447570.1474447570.1474449793.22.1.22; _tls=*.567853,567854..6063306133947536677; TW_SESSION_ID=22a009e5-4fec-4275-8407-9006e47619e4; TW_SESSION_SEQUENCE=10; TW_VISITOR_ID=5e7e4ee4-df93-47ad-9196-ec726bd73941; _cae=eyJ0IjpbXX0.; __wt1vpc=mail%3D%26bin_test_v2%3D23%26osc_vid%3D0; sbt=a6aa96f16075e015c04e20f6fca91a89; spw={%22views%22:11%2C%22widgets%22:{%2232651%22:{}}}; spv=11; SPREAD_utm={}; etuix=5I7OslhvIIszlokN7QjlrtvI85miAxrOULtJHBj_PieJiZZ6vZGFUA--; __wt1vic=9629cd210043560; __wt1spc=is_new_visitor%3D1; __wt1sic=ddae8511e55bb18; __cfduid=dd93a8655cacf758c81a61d73b63b941b1474450494; ASP.NET_SessionId=5t5ofce0rv02u4kbyarvybgf; _pk_ref.815.9612=%5B%22%22%2C%22%22%2C1474447569%2C%22http%3A%2F%2Fwww.palais-de-la-voiture.com%2F2015%2F08%2Fobtenir-des-informations-d-apres-une-plaque-d-immatriculation.html%22%5D; __bin_test_v2=23; _cs_v=0; _cs_r=0; _pk_id.815.9612=3c0476ec5629638f.1474447569.1.1474449793.1474447569.; etuix=5I7OslhvIIszlokN7QjlrtvI85miAxrOULtJHBj_PieJiZZ6vZGFUA--; _tlp=608:5362013; __cfduid=dc2eed1e539deb119aad8b78fbd3292121474451017; ASP.NET_SessionId=shosuutq5xhac0njmdat0d1k; _dc_gtm_UA-24791987-1=1; _tlc=www.oscaro.com%2F:1474453333:www.oscaro.com%2Faudi-tt-serie-2-coupe-1-8-tfsi-coupe-16v-160-cv-30201-824-t%3FsearchType%3Dimmat:oscaro.com; osctl=30201_824_126268; __ua55=GA1.2.552846453.1474447569; _cs_id=33eafc90-73ea-a6ff-f9b7-f9b57233ae23.1474447570.2.1474453365.1474453310; _cs_s=4; ry_ry-0sc4r02p_realytics=eyJpZCI6InJ5XzZCODNDRUZDLUUzQjYtNDU0MS1BNEI3LUQyOTc1REEwMkE2RCIsImNpZCI6bnVsbCwiZXhwIjoxNTA1OTgzNTY5NDQ5fQ%3D%3D; ry_ry-0sc4r02p_so_realytics=eyJpZCI6InJ5XzZCODNDRUZDLUUzQjYtNDU0MS1BNEI3LUQyOTc1REEwMkE2RCIsImNpZCI6bnVsbCwib3JpZ2luIjpmYWxzZSwicmVmIjpudWxsLCJjb250IjpudWxsfQ%3D%3D; _tlv=2.1474447570.1474450933.1474453366.29.23.6; _tls=*.567853,567855..6063306133947536677; TW_SESSION_ID=b7d8aea2-fe01-414c-8627-df913f471bd1; TW_SESSION_SEQUENCE=14; TW_VISITOR_ID=5e7e4ee4-df93-47ad-9196-ec726bd73941; _cae=eyJ0IjpbXX0.; __wt1vic=9629cd210043560; __wt1sic=376693314de1a3f; __wt1vpc=mail%3D%26bin_test_v2%3D23%26osc_vid%3D0; sbt=a6aa96f16075e015c04e20f6fca91a89; spw={%22views%22:15%2C%22widgets%22:{%2232651%22:{}}}; spv=15; SPREAD_utm={}');
    curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, 'gzip, deflate, br');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    $result = curl_exec($ch);

    $apiResult->logAdd(curl_error($ch));
    $apiResult->logAdd($result);
    
    $result = json_decode($result);
    
    $apiResult->logAdd($result);

    curl_close($ch);

    if(isset($result->types) === true) {
        $apiResult->logAdd($result->types);  
    
        foreach($result->types as $type) { 
            $apiResult->codeInit(1200, $type->Text);
        }
        if(count($result->types) === 0){
            $apiResult->codeInit(1400);
        }
    }
    else {
        $apiResult->codeInit(1500);
    }
}
else {
    $apiResult->codeInit(1502);    
    $apiResult->sendEmpty();
}
$apiResult->send();

?>
