<?php
require './vendor/autoload.php';

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$signer = new Sha256();
$signer_key = 'testing';

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjRmMWcyM2ExMmFhIn0.eyJpc3MiOiJodHRwczpcL1wvenoua2trLmdnIiwiYXVkIjoiaHR0cHM6XC9cL3p6Lmtray5nZyIsImp0aSI6IjRmMWcyM2ExMmFhIiwiaWF0IjoxNjAzOTM1ODkzLCJuYmYiOjE2MDM5MzU5MTMsImV4cCI6MTYwMzkzNjQ5MywidWlkIjoxfQ.Msn3f6E4eyEI8uLYky-0-Ry2L6KFCsGB38YGURg6F7Q';


        $tokenArr = explode('.', $token);

        if (count($tokenArr) != 3) {
            die('非法token');
        }

$token = (new Parser())->parse((string) $token);
//var_dump($token);
$data = new ValidationData();

        $data->setIssuer($token->getClaim('iss'));
        $data->setAudience($token->getClaim('aud'));
        //$data->setId($token->getHeader('jti'));
		$data->setId('4f1g23a12aa ');

            if (!$token->verify($signer, $signer_key)) {
                die('密钥不对');
            }

        if (!$token->validate($data)) {
            die('签名错误');
        }
        
        echo $token->getClaim('uid');
        echo date('Y-m-d H:i:s',$token->getClaim('exp'));
        
        

