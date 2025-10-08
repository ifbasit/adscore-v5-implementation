<?php
require_once __DIR__ . '/autoload.php';
use AdScore\Common\Signature\Signature5;
use AdScore\Common\Signature\Exception\{VersionException, ParseException, VerifyException};
use AdScore\Common\Definition\Judge;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $adscore_zone_id = null; // add your 6 digits zone id

    $signature = $_POST['signature'] ?? null;
    $cryptKey = \base64_decode($adscore_zone_id);   
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $ipAddresses = [ 
        $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR']
    ];

    $adscore_log = null;

    try {
        $parser = Signature5::createFromRequest($signature, $ipAddresses, $userAgent, $cryptKey);
        $result = $parser->getResult();
        $humanReadable = Judge::RESULTS[$result];
        if($humanReadable['verdict'] == 'ok'){
            $status = 'OK';
            $reason = 'Success';
        } else {
            $status = 'blocked';
            $reason = $humanReadable['verdict'];
        }
        $adscore_log = $humanReadable;
        
    } catch (VerifyException $e) {
        $status = 'blocked';
        $reason = $e->getMessage();
        $adscore_log = $reason;
    } catch (ParseException $e) {
        $status = 'blocked';
        $reason = $e->getMessage();
        $adscore_log = $reason;
    } catch (VersionException $e) {
        $status = 'blocked';
        $reason = $e->getMessage();
        $adscore_log = $reason;
    }


  echo json_encode($adscore_log);
    
    

}


?>
