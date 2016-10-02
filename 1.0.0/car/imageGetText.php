<?php

require_once '../lib/apiResult.php';

$apiResult = new ApiResult();
$apiResult->cacheStateOff();
$apiResult->resultService = 'imageGetText';

$request = file_get_contents('php://input');
$request = json_decode($request);

$cacheImageAddState = false;

if(isset($request->fileContent) === true && empty($request->fileContent) !== true) {

    $ext = '.md5';
    $cacheFile = md5($request->fileContent).$ext;
    
    $apiResult->cacheFileSet($cacheFile);
    
    if($cacheImageAddState === true) {
    
        $cacheFileImage = str_replace($ext, '.png', $apiResult->cacheFile);        
        
        if(is_file($cacheFileImage) === true) {
            
            unlink($cacheFileImage);
        }
        file_put_contents($cacheFileImage, base64_decode($request->fileContent));
            
        $cacheFileImage = 'https://appsApi.instriit.com/cache/'.basename($cacheFileImage);
        
        $apiResult->resultDocUrlListAdd($cacheFileImage);
    }
    $apiResult->cacheRun();
    
    $content = new stdClass();
    $content->requests[0] = new stdClass();
    $content->requests[0]->image = new stdClass();
    $content->requests[0]->image->content = $request->fileContent;
    $content->requests[0]->features[0] = new stdClass();
    $content->requests[0]->features[0]->type = 'TEXT_DETECTION';
    $content->requests[0]->features[0]->maxResults = 1;
    $content->requests[0]->imageContext = new stdClass();
    $content->requests[0]->imageContext->languageHints[0] = 'fr';
    $content = json_encode($content);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://vision.googleapis.com/v1/images:annotate?key=AIzaSyA2ytzCiOjp55-QiN8e1tehgdi-ViFI4sk');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($content))
        );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $content);

    $result = curl_exec($ch);
    
    $apiResult->logAdd(curl_error($ch));
    $apiResult->logAdd($result);
    
    $result = json_decode($result);
    
    $apiResult->logAdd($result);

    curl_close($ch);   
    
    if(isset($result->responses) === true) {
    
        $apiResult->logAdd($result->responses);
    
        foreach($result->responses as $responses) {
    
            foreach($responses->textAnnotations as $textAnnotation) {
    
                $apiResult->logAdd($textAnnotation->description);
    
                $textAnnotation->description = strtoupper($textAnnotation->description);
                $textAnnotation->description = str_replace('BZH', '', $textAnnotation->description);
                $textAnnotation->description = preg_replace('/^F /', '', $textAnnotation->description);
                $textAnnotation->description = preg_replace('/[^A-Z0-9]/', '', $textAnnotation->description);
    
                $apiResult->logAdd($textAnnotation->description);
    
                $pattern = "/.*([A-Z]{2}[0-9]{3}[A-Z]{2}).*/";
    
                $apiResult->logAdd($pattern);
    
                $res = preg_match($pattern, $textAnnotation->description, $output_array);
    
                $apiResult->logAdd($res);
                    
                if($res === 1) {
                    $plaque = $output_array[1];

                    $apiResult->plaqueSet($plaque);
                    $apiResult->codeInit(2200, $plaque.' Looks good !');
                }
                break;
            }
            if(count($responses->textAnnotations) === 0) {

                $apiResult->codeInit(2402);
            }
        }
        if(count($result->responses) === 0) {
        
            $apiResult->codeInit(2401);
        }
    }
    else {
        
        $apiResult->codeInit(2500);
    }
}
else {
    $apiResult->codeInit(1502);    
    $apiResult->sendEmpty();
}
$apiResult->send();
?>
