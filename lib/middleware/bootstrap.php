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

/*
    Prime request/response objects.
*/

$req = $require("php-http/request");
$res = $require("php-http/response");

/*
    Set webroot, approot & datroot (these seem a bit sketchy).
*/

if ($req->cfg("approot") === null) {

    $webroot = $pathlib->join($pathlib->dirname($req->getServerVar("PHP_SELF")), "..", "..");
    $approot = $pathlib->join(dirname($_SERVER["DOCUMENT_ROOT"] . $_SERVER["PHP_SELF"]), "..", "..");
    $datroot = $pathlib->join($approot, "data");

    /*
        Build the default application configuration.
    */

    $req->config = array(
        "webroot" => $webroot,
        "approot" => $approot,
        "datroot" => $datroot
    );
}

/*
    Get overrides from the application directory.
*/

$overrides = $require($pathlib->join($approot, "config"));

/*
    Merge the application overrides into the configuration.
*/

$req->config = array_merge($req->config, $require("../config"), $overrides);

/*
    Set the default renderer to be used.
*/

$res->renderer[".php.html"] = $require("php-render-php");

/*
    Include middleware.
*/

$require("hoobr-users/lib/middleware/auth");

/*
    If we got to here all is good so return true.
*/

$module->exports = true;
