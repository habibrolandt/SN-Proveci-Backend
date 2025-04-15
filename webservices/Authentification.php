<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
/**
 * Created by PhpStorm.
 * User: chaar
 * Date: 14/08/2018
 * Time: 11:31
 */
require '../services/scripts/php/core_transaction.php';
include '../services/scripts/php/lib.php';

$arrayJson = array();

$ConfigurationManager = new ConfigurationManager();
$CommandeManager = new CommandeManager();
$OneSignal = new OneSignal();

$mode = $_REQUEST['mode'];


if (isset($_REQUEST['STR_UTILOGIN'])) {
    $STR_UTILOGIN = $_REQUEST['STR_UTILOGIN'];
}

if (isset($_REQUEST['STR_UTIPASSWORD'])) {
    $STR_UTIPASSWORD = $_REQUEST['STR_UTIPASSWORD'];
}

if (isset($_REQUEST['STR_UTILOGIN'])) {
    $STR_UTILOGIN = $_REQUEST['STR_UTILOGIN'];
}

if (isset($_REQUEST['STR_SOCCODE'])) {
    $STR_SOCCODE = $_REQUEST['STR_SOCCODE'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

if (isset($_REQUEST['STR_SOCCODE'])) {
    $STR_SOCCODE = $_REQUEST['STR_SOCCODE'];
}

if (isset($_REQUEST['IS_ADMIN'])) {
    $IS_ADMIN = $_REQUEST['IS_ADMIN'];
}

if ($mode == "doConnexion") {
    $value = $ConfigurationManager->doConnexion($STR_UTILOGIN, $STR_UTIPASSWORD, $IS_ADMIN ?? 0);
    
    if ($value != null) {
        $arrayJson = [
            "LG_UTIID" => $value[0]['lg_utiid'] ?? null,
            "STR_UTIFIRSTLASTNAME" => $value[0]['str_utifirstlastname'] ?? null,
            "STR_UTIPHONE" => $value[0]['str_utiphone'] ?? null,
            "STR_UTIMAIL" => $value[0]['str_utimail'] ?? null,
            "STR_UTILOGIN" => $value[0]['str_utilogin'] ?? null,
            "STR_UTIPIC" => isset($value[0]['str_utipic']) ? Parameters::$rootFolderRelative . "avatars/" .  $value[0]["lg_utiid"] . "/" . $value[0]['str_utipic'] : null,
            "STR_UTITOKEN" => $value[0]['str_utitoken'] ?? null,
            "LG_PROID" => $value[0]['lg_proid'] ?? null,
            "STR_PRODESCRIPTION" => $value[0]['str_prodescription'] ?? null,
            "STR_SOCNAME" => $value[0]['str_socname'] ?? null,
            "STR_SOCDESCRIPTION" => $value[0]['str_socdescription'] ?? null,
            "STR_SOCLOGO" => $value[0]['str_soclogo'] ?? null,
            "LG_SOCID" => $value[0]['lg_socid'] ?? null,
            "LG_AGEID" => $value[0]['lg_ageid'] ?? null,
            "LG_CLIID" => $value[0]['lg_socextid'] ?? null
        ];
        
        if (!$IS_ADMIN) {
            $arrayJson["STR_SOCSOLDE"] = $value[0]['dbl_socplafond'] ?? null;
        }
    }
} else if ($mode == "doDisConnexion") {
    $ConfigurationManager->doDisConnexion($STR_UTITOKEN);
}

$arrayJson["code_statut"] = Parameters::$Message ?? "Erreur inconnue";
$arrayJson["desc_statut"] = Parameters::$Detailmessage ?? "Aucune information supplémentaire";

echo json_encode($arrayJson);

