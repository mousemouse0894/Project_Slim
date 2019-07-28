<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require './vendor/autoload.php';
define("JWT_KEY", "123456781");
$slim = new \Slim\App;

$slim->get('/encode', function (Request $request, Response $response, array $args) {
    try {
        $createJWT = new \Lindelius\JWT\StandardJWT();
        $createJWT->exp = time() + (60 * 1);
        $createJWT->iat = time();
        $createJWT->sub = "GuJame";
        $accessToken = $createJWT->encode(JWT_KEY);
        return $response->getBody()->write($accessToken);
    } catch (\Lindelius\JWT\Exception\Exception $exception) {
        return $response->getBody()->write("สร้าง Token ล้มเหลว");
    }
});

$slim->get('/decode/{key}', function (Request $request, Response $response, array $args) {
    try {
        $decodedJwt = \Lindelius\JWT\StandardJWT::decode($args["key"]);
        $decodedJwt->verify(JWT_KEY);
        return $response->getBody()->write($decodedJwt->sub);
    } catch (\Lindelius\JWT\Exception\Exception $exception) {
        return $response->getBody()->write("หมดเวลา");
    }
});

$slim->run();
