<?php
//@route GET /
$startMicroTime = microtime(true);

require(__DIR__ . "/../php-require/index.php");

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
        "main" => array(
            "module" => "hoobr-articles",
            "action" => "main"
        ),
        "title" => $req->cfg("public/site-title"),
        "footer" => "",
        "assetsTop" => $assests["render"]("top"),
        "assetsBottom" => $assests["render"]("bottom"),
        "webroot" => $req->cfg("webroot"),
        "start" => $startMicroTime
    )
));
