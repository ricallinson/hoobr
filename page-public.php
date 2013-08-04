<?php
//@route GET /
$startMicroTime = microtime(true);

require("../../node_modules/php-require/index.php");

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

// $req = $require("php-http/request");
$res = $require("php-http/response");

/*
    Find our look and feel.
*/

$lookFeelPackage = $require("hoobr-theme-public");
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
        "title" => "Hoobr Site",
        "footer" => "",
        "assetsTop" => $assests["render"]("top"),
        "assetsBottom" => $assests["render"]("bottom"),
        "start" => $startMicroTime
    )
));
