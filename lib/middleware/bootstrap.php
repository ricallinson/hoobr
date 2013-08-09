<?php
namespace php_require\hoobr_admin\lib\middleware;

/*
    Do some error logging.
*/

error_reporting(E_ALL);
ini_set('display_errors', 'on');

/*
    Require modules.
*/

$pathlib = $require("php-path");
$configReader = $require("hoobr-config-manager/lib/parser");

/*
    Prime request/response objects.
*/

$req = $require("php-http/request");
$res = $require("php-http/response");

/*
    Bucket selection has to happen before we read any configuration.
*/

$require("hoobr-bucket-tester/lib/middleware/bucket");

/*
    Set webroot, approot & datroot (these seem a bit sketchy).
*/

if ($req->cfg("approot") === null) {

    $webroot = $pathlib->join($pathlib->dirname($req->getServerVar("PHP_SELF")), "..", "..");
    $approot = $pathlib->join(dirname($_SERVER["DOCUMENT_ROOT"] . $_SERVER["PHP_SELF"]), "..", "..");
    $datroot = $pathlib->join($approot, "data");
    $cfgroot = $pathlib->join($approot, "config");

    /*
        Build the default application configuration.
    */

    $req->config = array(
        "webroot" => $webroot,
        "approot" => $approot,
        "datroot" => $datroot,
        "cfgroot" => $cfgroot
    );
}

/*
    Merge the application overrides into the configuration.
*/

$req->config = array_merge($req->config, $configReader("hoobr", $req->cfg("cfgroot"))->get());

/*
    If hoobr is offline then show the page and exit.
*/

if ($req->cfg("site/offline") === "true" && $req->param("page") !== "admin") {
    $require($req->cfg("site/offline-module"));
    exit();
}

/*
    Include middleware.

    These are modules which perform chaecks and setup the request.
*/

$require("hoobr-users/lib/middleware/auth");

/*
    Set the default renderer to be used.
*/

$res->renderer[".php.html"] = $require("php-render-php");

/*
    If we got to here all is good so return true.
*/

$module->exports = true;
