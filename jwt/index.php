<?php
require './vendor/autoload.php';



use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;


$signer = new Sha256();
$time = time();

$token = (new Builder())->issuedBy('https://zz.kkk.gg') // Configures the issuer (iss claim)
                        ->permittedFor('https://zz.kkk.gg') // Configures the audience (aud claim)
                        ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                        ->issuedAt($time) // Configures the time that the token was issue (iat claim)
                        ->canOnlyBeUsedAfter($time+20) // Configures the time that the token can be used (nbf claim)
                        ->expiresAt($time + 600) // Configures the expiration time of the token (exp claim)
                        ->withClaim('uid',1) // Configures a new claim, called "uid"
                        ->getToken($signer, new Key('testing')); // Retrieves the generated token

echo $token;







