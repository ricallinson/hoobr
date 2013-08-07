<?php
//@route GET /
$startMicroTime = microtime(true);

if (!isset($require)) require(__DIR__ . "/../php-require/index.php");

/*
    Require modules.
*/

$loaded = $require("hoobr/lib/middleware/bootstrap");
$composite = $require("php-composite");
$req = $require("php-http/request");
$res = $require("php-http/response");

/*
    Work out what to show the user.
*/

$mainModule = $req->param("module");
$mainAction = $req->param("action");

/*
    This dosen't feel right.
*/

if (!$mainModule) {
    $req->query["module"] = "hoobr-articles";
    $mainModule = $req->param("module");
}

if (!$mainAction) {
    $req->query["action"] = "main";
    $mainAction = $req->param("action");
}

/*
    Renders the main page.
*/

$res->send($composite(
    array(
        "main" => array(
            "module" => $mainModule,
            "action" => $mainAction
        )
    )
));
