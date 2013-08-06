<?php
//@route GET /
$startMicroTime = microtime(true);

if (!isset($require)) require(__DIR__ . "/../php-require/index.php");

/*
    Require modules.
*/

$loaded = $require("hoobr/lib/middleware/bootstrap");
$pathlib = $require("php-path");
$composite = $require("php-composite");
$assests = $require("hoobr-assets");

/*
    Grab the $request, $response objects.
*/

$req = $require("php-http/request");
$res = $require("php-http/response");

/*
    Find our look and feel.
*/

$lookFeelPackage = $require($req->cfg("public/site-theme"));
$assests["addBundle"]($lookFeelPackage["config"]);

/*
    Renders the main page.
*/

$res->render($lookFeelPackage["layout"], $composite(
    array(
        "header" => array(
            "module" => "hoobr-articles",
            "action" => "menu"
        ),
        "splash" => array(
            "module" => "hoobr-articles",
            "action" => "article",
            "params" => array(
                "view" => "splash",
                "article-id" => "0787986e-fa45-11e2-b5a0-000000000000"
            )
        ),
        "main" => array(
            "module" => "hoobr-articles",
            "action" => "",
            "params" => array(
                "category" => "general"
            )
        ),
        "title" => $req->cfg("public/site-title"),
        "footer" => "",
        "assetsTop" => $assests["render"]("top"),
        "assetsBottom" => $assests["render"]("bottom"),
        "webroot" => $req->cfg("webroot"),
        "start" => $startMicroTime
    )
));
