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

$StockManager = new StockManager();
$ConfigurationManager = new ConfigurationManager();

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

if (isset($_REQUEST['search[value]'])) {
    $search_value = $_REQUEST['search[value]'];
}

if (isset($_REQUEST['query'])) {
    $search_value = $_REQUEST['query'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
    $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
}

if (isset($_REQUEST['DT_BEGIN']) && $_REQUEST['DT_BEGIN'] != "") {
    $DT_BEGIN = $_REQUEST['DT_BEGIN'];
}

if (isset($_REQUEST['DT_END']) && $_REQUEST['DT_END'] != "") {
    $DT_END = $_REQUEST['DT_END'];
}

if (isset($_REQUEST['LG_PROID'])) {
    $LG_PROID = $_REQUEST['LG_PROID'];
}
if (isset($_REQUEST['FILTER_OPTIONS'])) {
    $FILTER_OPTIONS = $_REQUEST['FILTER_OPTIONS'];
}

if (isset($_REQUEST['LIMIT'])) {
    $LIMIT = $_REQUEST['LIMIT'];
}

if (isset($_REQUEST['PAGE'])) {
    $PAGE = $_REQUEST['PAGE'];
}

if (isset($_REQUEST['STR_PROGAMME'])) {
    $STR_PROGAMME = $_REQUEST['STR_PROGAMME'];
}

if ($mode == "listProduct") {
    $result = $StockManager->showAllOrOneProduct($FILTER_OPTIONS, $LIMIT, $PAGE);
    $rootFolderRelative = __DIR__ . "/../images/";
    foreach ($result['products'] as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["ArtID"] = $value['lg_proid'];
        $arrayJson_chidren["ArtCode"] = $value['str_proname'];
        $arrayJson_chidren["ArtLib"] = $value['str_prodescription'];
        $arrayJson_chidren["CmtTxt"] = $value['str_procommentaire'];
        $arrayJson_chidren["ArtLastPA"] = $value['int_propriceachat'];
        $arrayJson_chidren["ArtPrixBase"] = $value['int_propricevente'];
        $arrayJson_chidren["ArtGPicID"] = $value['str_propic'] != null ? Parameters::$rootFolderRelative . "produits/" . $value["lg_proid"] . "/" . $value['str_propic'] : "";
        $arrayJson_chidren["ArtCateg"] = $value['str_procateg'];
        $arrayJson_chidren["ArtFamille"] = $value['str_profamille'];
        $arrayJson_chidren["ArtGamme"] = $value['str_progamme'];
        $arrayJson_chidren["ArtSpecies"] = $value['str_proespece'];
        $arrayJson_chidren["ArtStk"] = $value['int_prostock'] ?: 0;
        $arrayJson_chidren["ArtSlug"] = $value['str_proslug'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["products"] = $OJson;
    $arrayJson["total"] = $result['total'];
    $arrayJson["limit"] = (int) $LIMIT;
    $arrayJson["page"] = (int) $PAGE;
} else if ($mode == "listLastestItems") {
    $result = $StockManager->listLastestItems($LIMIT, $PAGE);
    $rootFolderRelative = __DIR__ . "/../images/";
    foreach ($result as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["ArtID"] = $value['lg_proid'];
        $arrayJson_chidren["ArtCode"] = $value['str_proname'];
        $arrayJson_chidren["ArtLib"] = $value['str_prodescription'];
        $arrayJson_chidren["CmtTxt"] = $value['str_procommentaire'];
        $arrayJson_chidren["ArtLastPA"] = $value['int_propriceachat'];
        $arrayJson_chidren["ArtPrixBase"] = $value['int_propricevente'];
        $arrayJson_chidren["ArtGPicID"] = $value['str_propic'] != null ? Parameters::$rootFolderRelative . "produits/" . $value["lg_proid"] . "/" . $value['str_propic'] : "";
        $arrayJson_chidren["ArtCateg"] = $value['str_procateg'];
        $arrayJson_chidren["ArtFamille"] = $value['str_profamille'];
        $arrayJson_chidren["ArtGamme"] = $value['str_progamme'];
        $arrayJson_chidren["ArtSpecies"] = $value['str_proespece'];
        $arrayJson_chidren["ArtStk"] = $value['int_prostock'] ?: 0;
        $arrayJson_chidren["ArtSlug"] = $value['str_proslug'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["limit"] = (int) $LIMIT;
    $arrayJson["page"] = (int) $PAGE;
} else if ($mode == "getProduct") {
    //verifier que la variable ON_FRONT a été envoyé et n'est pas vide
    if (isset($_REQUEST['ON_FRONT']) && $_REQUEST['ON_FRONT'] != "") {
        $ON_FRONT = $_REQUEST['ON_FRONT'];
    }
    $products = $StockManager->getProduct($LG_PROID);
    foreach ($products as $value) {
        $arrayJson["products"][] = array(
            "ArtID" => $value['lg_proid'],
            "ArtCode" => $value['str_proname'],
            "ArtLib" => $value['str_prodescription'],
            "ArtPrixBase" => $value['int_propricevente'],
            "ArtStk" => $value['int_prostock'],
            "ArtLastPA" => $value['int_propriceachat'],
            "ArtCateg" => $value['str_procateg'],
            "CmtTxt" => $value['str_prodetails'],
           // "lg_prosubid" => $value['lg_prosubid'],
            "ArtFamille" => $value['str_profamille'],
            "ArtGamme" => $value['str_progamme'],
            "ArtSpecies" => $value['str_proespece'],
            "ArtGPicID" => $value['str_propic'] != null ? Parameters::$rootFolderRelative . "produits/" . $value["lg_proid"] . "/" . $value['str_propic'] : null,
           // "ArtGPicID" => $value["p_key"] ? Parameters::$rootFolderRelative . "produits/" . "$LG_PROID/" . $value['str_docpath'] : null,
            "str_propic" => $value['str_propic']
        );
    }
} else if ($mode == "getSubstitutionProducts") {
    $substitutionProducts = $StockManager->getSubstitutionProduct($LG_PROID);
    foreach ($substitutionProducts as $product) {
        $arrayJson_chidren["ArtCode"] = $product['str_proname'] ?: null;
        $arrayJson_chidren["ArtLib"] = $product['str_prodescription'] ?: null;
        $arrayJson_chidren["CmtTxt"] = $product['str_prodetails'] ?: null;
        $arrayJson_chidren["lg_prosubid"] = $product['lg_prosubid'] ?: null;
        $arrayJson_chidren["ArtID"] = $product['lg_prokidid'] ?: null;
        $arrayJson_chidren["ArtCategEnu"] = $product['str_procateg'] ?: null;
        $arrayJson_chidren["ArtFamilleEnu"] = $product['str_profamille'] ?: null;
        $arrayJson_chidren["ArtGammeEnu"] = $product['str_progamme'] ?: null;
        $arrayJson_chidren["ArtLastPA"] = $product['int_propriceachat'] ?: null;
        $arrayJson_chidren["ArtGPicID"] = $product['str_propic'] != null ? Parameters::$rootFolderRelative . "produits/" . $product["lg_proid"] . "/" . $product['str_propic'] : null;
        $arrayJson_chidren["ArtGPicID"] = $product['str_proslug'];
        $OJson[] = $arrayJson_chidren;
    }

    $arrayJson['products'] = $OJson;
} else if ($mode == "loadProduct") {
    $StockManager->loadExternalProduct();
} else if ($mode == "getProductListGammeAndCategory") {
    /* $result = $StockManager->getProductListGammeCategoryAndSpecies(); //a decommenter en cas de probleme
      foreach ($result["especes"] as $key => &$value) {
      $items = null;
      if (strpos($value, "/")) {
      $items = explode("/", $value);
      unset($result["especes"][$key]);
      }
      if ($items) {
      foreach ($items as $item) {
      $result["especes"] = array_merge($result["especes"], [$item]);
      }
      }
      }
      $result["especes"] = array_filter($result["especes"]);
      $result["especes"] = array_unique($result["especes"]);
      $result["especes"] = array_values($result["especes"]); 

      $arrayJson["data"] = $result;
          */

    $listFilterProduct = $StockManager->showAllOrOneEspeceproduct();
    $arrayJson_chidren = array();
    foreach ($listFilterProduct as $product) {
        $items = explode("/", $product['str_filter']);
        foreach ($items as $item) {
            $arrayJson_chidren[] = $item;
        }
        $OJson["especes"] = array_values(array_unique(array_filter($arrayJson_chidren)));
    }
    
    $listFilterProduct = $StockManager->showAllOrOneCategoryproduct();
    $arrayJson_chidren = array();
    foreach ($listFilterProduct as $product) {
        $items = explode("/", $product['str_filter']);
        foreach ($items as $item) {
            $arrayJson_chidren[] = $item;
        }
        $OJson["categories"] = array_values(array_unique(array_filter($arrayJson_chidren)));
    }
    
    $listFilterProduct = $StockManager->showAllOrOneGammeproduct();
    $arrayJson_chidren = array();
    foreach ($listFilterProduct as $product) {
        $items = explode("/", $product['str_filter']);
        foreach ($items as $item) {
            $arrayJson_chidren[] = $item;
        }
        $OJson["fournisseurs"] = array_values(array_unique(array_filter($arrayJson_chidren)));
    }

    $arrayJson["data"] = $OJson;
} else if ($mode == "filterProductByGammeOrCategory") {
    $arrayJson["Products"] = $StockManager->filterProductByGammeOrCategory($FILTER_OPTIONS);
} else if ($mode == "getProductByCategory") {
    $result = $StockManager->getProductsByCategory();
    foreach ($result as $key => $value) {
        if (count($value) > 0) {
            $arrayJson["data"][$key] = [];
            foreach ($value as $product) {
                $arrayJson["data"][$key][] = [
                    "ArtID" => $product['lg_proid'],
                    "ArtCode" => $product['str_proname'],
                    "ArtLib" => $product['str_prodescription'],
                    "ArtPrixBase" => $product['int_propricevente'],
                    "ArtStk" => $product['int_prostock'],
                    "ArtLastPA" => $product['int_propriceachat'],
                    "ArtCateg" => $product['str_procateg'],
                    "CmtTxt" => $product['str_prodetails'],
                    "lg_prosubid" => $product['lg_prosubid'],
                    "ArtFamille" => $product['str_profamille'],
                    "ArtGamme" => $product['str_progamme'],
                    "ArtSpecies" => $product['str_proespece'],
                    "ArtGPicID" => $product["p_key"] ? Parameters::$rootFolderRelative . "produits/" . "$LG_PROID/" . $product['str_docpath'] : null,
                    "str_propic" => $product['str_propic'],
                    "ArtSlug" => $product["str_proslug"],
                ];
            }
        }
    }
} else {
    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}
echo json_encode($arrayJson);
