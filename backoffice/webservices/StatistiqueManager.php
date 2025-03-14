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
$OJson = array();
$search_value = "";

$total = 0;
$start = 0;
$length = 25;

$StatistiqueManager = new StatistiqueManager();
$ConfigurationManager = new ConfigurationManager();
$StockManager = new StockManager();
$OneSignal = new OneSignal();

$mode = $_REQUEST['mode'];

if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
}

if (isset($_REQUEST['length'])) {
    $length = $_REQUEST['length'];
}

if (isset($_REQUEST['search_value'])) {
    $search_value = $_REQUEST['search_value'];
}

if (isset($_REQUEST['search_value[value]'])) {
    $search_value = $_REQUEST['search_value[value]'];
}

if (isset($_REQUEST['query'])) {
    $search_value = $_REQUEST['query'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

if (isset($_REQUEST['LG_SOCID'])) {
    $LG_SOCID = $_REQUEST['LG_SOCID'];
}

if (isset($_REQUEST['DT_BEGIN']) && $_REQUEST['DT_BEGIN'] != "") {
    $DT_BEGIN = $_REQUEST['DT_BEGIN'];
}

if (isset($_REQUEST['DT_END']) && $_REQUEST['DT_END'] != "") {
    $DT_END = $_REQUEST['DT_END'];
}

if (isset($_REQUEST['YEAR']) && $_REQUEST['YEAR'] != "") {
    $YEAR = $_REQUEST['YEAR'];
}

if (isset($_REQUEST['FILTER_OPTIONS']) && $_REQUEST['FILTER_OPTIONS'] != "") {
    $FILTER_OPTIONS = $_REQUEST['FILTER_OPTIONS'];
}

if (isset($_REQUEST['LIMIT']) && $_REQUEST['LIMIT'] != "") {
    $LIMIT = $_REQUEST['LIMIT'];
}

if (isset($_REQUEST['PAGE']) && $_REQUEST['PAGE'] != "") {
    $PAGE = $_REQUEST['PAGE'];
}

if (isset($_REQUEST['PCVGCLIID']) && $_REQUEST['PCVGCLIID'] != "") {
    $PCVGCLIID = $_REQUEST['PCVGCLIID'];
}

$OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);


if ($mode == "topOperateur") {
    $listOperateur = $StatistiqueManager->topOperateur($LG_SOCID, $DT_BEGIN, $DT_END, $length);
    foreach ($listOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["NOMBRE"] = $value['NOMBRE'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["MONTANT"] = $value['MONTANT'];
        $arrayJson_chidren["OPEID"] = $value['LG_OPEID'];
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson[] = $arrayJson_chidren;
    }
} else if ($mode == "topTransaction") {
    $listOperateur = $StatistiqueManager->topTransaction($LG_SOCID, $DT_BEGIN, $DT_END, $length);
    foreach ($listOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["TRAID"] = $value['LG_TRAID'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["TRAREFERENCE"] = $value['STR_TRAREFERENCE'];
        $arrayJson_chidren["TRAPHONE"] = $value['STR_TRAPHONE'];
        $arrayJson_chidren["OPEPHONE"] = $value['STR_OPEPHONE'];
        $arrayJson_chidren["TRAAMOUNT"] = $value['DBL_TRAAMOUNT'];
        $arrayJson_chidren["TRAOTHERVALUE"] = $value['STR_TRAOTHERVALUE'];
        $arrayJson_chidren["TRACREATED"] = DateToString($value['DT_TRACREATED'], 'd/m/Y H:i:s');
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson_chidren["TTRDESCRIPTION"] = $value['STR_TTRDESCRIPTION'];
        $arrayJson[] = $arrayJson_chidren;
    }
} else if ($mode == "listProductsStatViewed") {
    $result = $StatistiqueManager->listProductsStatViewed($FILTER_OPTIONS, $LIMIT, $PAGE);
    foreach ($result["data"] as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["PROID"] = $value['lg_proid'];
        $arrayJson_chidren["PRODESCRIPTION"] = $value['str_prodescription'];
        $arrayJson_chidren["PROPICTURE"] = $value['str_propic'] ? Parameters::$rootFolderRelative . "produits/" . $value['lg_proid'] . "/" . $value['str_propic'] : null;
        $arrayJson_chidren["PROPRICE"] = $value['int_propricevente'];
        $arrayJson_chidren["PROSTOCK"] = $value['int_prostock'] ?? 0;
        $arrayJson_chidren["PROVIEWED"] = $value['nombre_de_vues'];
        $arrayJson_chidren["PROSLUG"] = $value["str_proslug"];
        $OJson[] = $arrayJson_chidren;
    }

    $arrayJson["data"] = $OJson;
    $arrayJson["limit"] = $LIMIT;
    $arrayJson["page"] = $PAGE;
    $arrayJson["total"] = $result["total"];
} else if ($mode == "statOrders") {
    $result = $StatistiqueManager->ordersStatByYear($YEAR, $PCVGCLIID);
    foreach ($result as $value) {
        $arrayJson_chidren["MONTH"] = $value['month'];
        $arrayJson_chidren["NB_ORDER"] = $value['totalOrders'];
        $arrayJson_chidren["TOTAL_AMOUNT"] = (int)$value['totalAmount'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;

}else if($mode === "listBestPurchaser"){
    $result = $StatistiqueManager->listBestPurchaser($FILTER_OPTIONS, $LIMIT, $PAGE, $YEAR);
    foreach ($result["data"] as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["CLIID"] = $value['PcvGCliID'];
        $arrayJson_chidren["UTIFIRSTLASTNAME"] = $value['str_utifirstlastname'];
        $arrayJson_chidren["SOCPHONE"] = $value['str_socphone'];
        $arrayJson_chidren["TOTAL_CMD"] = $value['total_cmd'];
        $arrayJson_chidren["TOTAL_AMOUNT"] = (int)$value['total_amout'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["limit"] = $LIMIT;
    $arrayJson["page"] = $PAGE;
    $arrayJson["total"] = $result["total"];
}

$arrayJson["code_statut"] = Parameters::$Message;
$arrayJson["desc_statut"] = Parameters::$Detailmessage;

echo json_encode($arrayJson);


