<?php
// @route GET|POST /admin
$startMicroTime = microtime(true);

if (!isset($require)) require(__DIR__ . "/../php-require/index.php");

/*
    Require modules.
*/

$loaded = $require("./lib/middleware/bootstrap");
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

$lookFeelPackage = $require($req->cfg("admin/site-theme"));
$assests["addBundle"]($lookFeelPackage["config"]);

/*
    If the user is not logged in show only the login form.
*/

if ($req->cfg("loggedin") !== true) {
    $res->render($lookFeelPackage["layout"], $composite(
        array(
            "sidebar" => array(
                "module" => "hoobr-users",
                "action" => "admin-sidebar"
            ),
            "header" => "",
            "main" => "",
            "footer" => "",
            "assetsTop" => $assests["render"]("top"),
            "assetsBottom" => $assests["render"]("bottom"),
            "title" => $req->cfg("admin/site-title"),
            "webroot" => $req->cfg("webroot"),
            "start" => $startMicroTime
        )
    ));
}

/*
    Once the user is logged in work out what to show them.
*/

$mainModule = $req->param("module");
$mainAction = $req->param("action");

/*
    This dosen't feel right.
*/

if (!$mainModule) {
    $req->query["module"] = "hoobr-packages";
    $mainModule = $req->param("module");
}

if (!$mainAction) {
    $req->query["action"] = "main";
    $mainAction = $req->param("action");
}

/*
    Organize the main and sidebar slots.
*/

$header = array(
    array(
        "module" => (!in_array($mainModule, array("hoobr-packages", "hoobr-users")) ? $mainModule : null),
        "action" => "admin-menu"
    ),
    array(
        "module" => "hoobr-users",
        "action" => "admin-menu"
    ),
    array(
        "module" => "hoobr-packages",
        "action" => "admin-menu"
    )
);

$main = array(
    "module" => $mainModule,
    "action" => "admin-" . $mainAction
);

$sidebar = array(
    array(
        "module" => $mainModule,
        "action" => "admin-sidebar"
    )
);

/*
    Show the admin site.
*/

$res->render($lookFeelPackage["layout"], $composite(
    array(
        "header" => $header,
        "main" => $main,
        "sidebar" => $sidebar,
        "title" => $req->cfg("admin/site-title"),
        "footer" => "",
        "assetsTop" => $assests["render"]("top"),
        "assetsBottom" => $assests["render"]("bottom"),
        "webroot" => $req->cfg("webroot"),
        "start" => $startMicroTime
    )
));
