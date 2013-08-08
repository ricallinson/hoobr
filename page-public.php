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
    Find our look and feel.
*/

$lookFeelPackage = $require($req->cfg("public/site-theme"));
$assests["addBundle"]($lookFeelPackage["config"]);

/*
    Foreach module on the page read its config for assets.
*/

foreach (array($mainModule) as $moduleName) {
    $module = $require($moduleName);
    if (isset($module["config"])) {
        $assests["addBundle"]($module["config"]);
    }
}

/*
    Renders the main page.
*/

$res->render($lookFeelPackage["layout"], $composite(
    array(
        "header" => "",
        "splash" => array(
            "module" => "hoobr-articles",
            "action" => "article",
            "params" => array(
                "view" => "splash",
                "article-id" => "1375921077-15202e3b504e49"
            )
        ),
        "main" => array(
            "module" => $mainModule,
            "action" => $mainAction,
            "params" => array(
                "category" => "general"
            )
        ),
        "sidebar" => array(
            array(
                "module" => $mainModule,
                "action" => "sidebar",
                "params" => array(
                    "title" => "Articles",
                    "category" => "general"
                )
            ),
            array(
                "module" => $mainModule,
                "action" => "sidebar",
                "params" => array(
                    "title" => "Alice's Adventures in Wonderland",
                    "category" => "alice"
                )
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
