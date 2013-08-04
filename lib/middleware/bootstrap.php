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
    Set modroot, webroot, approot & datroot (these seem a bit sketchy).
*/

$req->cfg("modroot", dirname($_SERVER["DOCUMENT_ROOT"] . $_SERVER["PHP_SELF"]));
$req->cfg("webroot", $pathlib->join($pathlib->dirname($req->getServerVar("PHP_SELF")), "..", ".."));
$req->cfg("approot", $pathlib->join($req->cfg("modroot"), "..", ".."));
$req->cfg("datroot", $pathlib->join($req->cfg("approot"), "data"));

/*
    Set the renderer to be used by default.
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
