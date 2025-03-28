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
$INT_CPRQUANTITY = 1;
$LG_COMMID = "";

$ConfigurationManager = new ConfigurationManager();
$CommandeManager = new CommandeManager();
$StockManager = new StockManager();
//$OneSignal = new OneSignal();

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : null; // Définit $mode avec une valeur par défaut (null)


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

if (isset($_REQUEST['LG_UTIID'])) {
    $LG_UTIID = $_REQUEST['LG_UTIID'];
}

if (isset($_REQUEST['LG_CLIID'])) {
    $LG_CLIID = $_REQUEST['LG_CLIID'];
}

if (isset($_REQUEST['LG_COMMID']) && $_REQUEST['LG_COMMID'] != "") {
    $LG_COMMID = $_REQUEST['LG_COMMID'];
}

if (isset($_REQUEST['LG_AGEID']) && !empty($_REQUEST['LG_AGEID'])) {
    $LG_AGEID = htmlspecialchars(trim($_REQUEST['LG_AGEID']));
} else {
    $LG_AGEID = null; // Définit une valeur par défaut en cas d'absence de la clé
}


if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
    $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
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


if ($mode == "listCommande") {
    $arrayJson = $CommandeManager->showAllOrOneCommande($search_value, $LG_CLIID, $start, $length);
} else if ($mode == "listCommandeproduct") {
    $STR_COMMSTATUT = Parameters::$statut_process;
    if (isset($_REQUEST['STR_COMMSTATUT']) && $_REQUEST['STR_COMMSTATUT'] != "") {
        $STR_COMMSTATUT = $_REQUEST['STR_COMMSTATUT'];
    }

    $token = $ConfigurationManager->generateToken();
    $value = $CommandeManager->getLastCommandeByAgence($LG_AGEID, $STR_COMMSTATUT);

    if (empty($value)) {
        $arrayJson = ['lines' => []];
    } else {
        $arrayJson = $CommandeManager->showAllOrOneCommandeproduit($value[0]["lg_socextid"], $value[0]["lg_commid"], $token);
        foreach ($arrayJson->lines as $key => $line) { // Changed $value to $line
            $OProduit = $StockManager->showAllOrOneProduct_legacy($line->PlvCode);
            if ($OProduit != null) {
                $line->str_propic = $OProduit[0]['str_propic'] ? Parameters::$rootFolderRelative . "produits/" . $OProduit[0]['lg_proid'] . "/" . $OProduit[0]['str_propic'] : null;
                $line->str_proslug = $OProduit[0]['str_proslug'];
            }
        }
        $arrayJson->clisolde = (int)$CommandeManager->getClientSolde($value[0]["lg_socextid"])->clisolde;
    }
} else if ($mode === "listProductOnOrder") {
    $token = $ConfigurationManager->generateToken();
    $response = $CommandeManager->showAllOrOneCommandeproduit($LG_CLIID, $LG_COMMID, $token);
    foreach ($response->lines as $key => $line) { // Changed $value to $line
        $OProduit = $StockManager->getProduct($line->PlvCode);
        $line->str_propic = $OProduit["str_propic"] ? Parameters::$rootFolderRelative . "produits/" . $OProduit[0]['lg_proid'] . "/" . $OProduit[0]['str_propic'] : null;
        $arrayJsonChildren['ArtLib'] = $line->PlvLib;
        $arrayJsonChildren['int_cprquantity'] = (int)$line->PlvQteUV;
        $arrayJsonChildren['ArtCode'] = $line->PlvCode;
        $arrayJsonChildren['ArtPrixBase'] = $line->PlvPUNet;
        $OJson[] = $arrayJsonChildren;
    }
    $arrayJson["data"] = $OJson;
}else if ($mode == "getCalendar") {
    $listZonelivraison = $CommandeManager->showAllOrOneZonelivraisonActive();
    $tabsData = array();
    $listsData = array();

    foreach ($listZonelivraison as $value) {
        // Ajout des données dans tabsData
        $tabsData[] = array(
            "id" => "product-tab-" . strtolower(str_replace(" ", "-", $value['str_lstvalue'])), // Génération d'un ID unique
            "title" => $value['str_lstvalue'] // Titre de la région
        );

        // Initialisation de la liste pour cette région
        $listLivraison = $CommandeManager->showAllOrOneLivraison("", $value['lg_lstid']);
        $regionData = array();

        foreach ($listLivraison as $v) {
            $regionData[] = array(
                "id" => $v['lg_livid'], // ID de livraison
                "date" => "01/07 avant 12H00", // Exemple de date limite (à remplacer par votre logique métier)
                "deliveryDate" => $v['dt_livbegin'], // Date de livraison prévue
                "areas" => isset($v['str_lstdescription']) ? $v['str_lstdescription'] : "Zone inconnue" // Zone géographique
            );
        }

        // Ajout des livraisons à listsData
        $listsData["product-tab-" . strtolower(str_replace(" ", "-", $value['str_lstvalue']))] = $regionData;
    }

    // Construction du JSON final
    $arrayJson = array(
        "tabsData" => $tabsData, // Données des onglets
        "listsData" => $listsData // Données des livraisons par région
    );

    echo json_encode($arrayJson); // Encodage en JSON
}



else if ($mode == "getClientPanier") {
    $value = $CommandeManager->getClientPanier($LG_AGEID);
    if ($value) {
        $arrayJson["data"] = $value;
    }
} else if ($mode == "getExternalClientPanier") {
    $arrayJson = $CommandeManager->getExternalClientPanier($LG_AGEID, $LG_COMMID);
} else if ($mode == "listDeliveryCalendar") {
    isset($_REQUEST['LG_LIVID']) ? $LG_LIVID = $_REQUEST['LG_LIVID'] : $LG_LIVID = null;
    $result = $CommandeManager->showAllOrOneDeliveryCalendar($FILTER_OPTIONS, $LIMIT, $PAGE);
//    var_dump($result);
    foreach ($result['data'] as $item) {
        $arrayJsonChildren["zone"] = $item["zone"];
        $arrayJsonChildren["zone_id"] = $item["zone_id"];
        $arrayJsonChildren["commandes"] = $item["commandes"];
        $arrayJsonChildren["cmd_count"] = $item["cmd_count"];
        $arrayJsonChildren["dt_livbegin"] = $item["dt_livbegin"];
        $arrayJsonChildren["dt_livend"] = $item["dt_livend"];
        $arrayJsonChildren["str_livstatut"] = $item["str_livstatut"];
        $arrayJsonChildren["str_livname"] = $item["str_livname"];
        $arrayJsonChildren["lg_livid"] = $item["lg_livid"];
        $OJson[] = $arrayJsonChildren;
    }

    $arrayJson["data"] = $OJson;
    $arrayJson["total"] = $result['total'];
    $arrayJson["limit"] = (int)$LIMIT;
    $arrayJson["page"] = (int)$PAGE;
}  else if ($mode === "listOrdersByClient") {
    $orders = $CommandeManager->showAllOrdersByClientExternal($LG_CLIID)->pieces;
    $sumAmountOrders = 0;
    foreach ($orders as $order) {
        $sumAmountOrders += (int)$order->PcvMtTTC;
    }
    $arrayJson["data"]["sumAmountOrders"] = $sumAmountOrders;


} else {

    if (isset($_REQUEST['STR_COMMNAME'])) {
        $STR_COMMNAME = $_REQUEST['STR_COMMNAME'];
    }

    if (isset($_REQUEST['STR_COMMADRESSE'])) {
        $STR_COMMADRESSE = $_REQUEST['STR_COMMADRESSE'];
    }

    if (isset($_REQUEST['STR_LIVADRESSE'])) {
        $STR_LIVADRESSE = $_REQUEST['STR_LIVADRESSE'];
    }

    if (isset($_REQUEST['LG_PROID'])) {
        $LG_PROID = $_REQUEST['LG_PROID'];
    }

    if (isset($_REQUEST['INT_CPRQUANTITY']) && $_REQUEST['INT_CPRQUANTITY'] != "") {
        $INT_CPRQUANTITY = (int)$_REQUEST['INT_CPRQUANTITY'];
    }

    if (isset($_REQUEST['LG_CPRID'])) {
        $LG_CPRID = $_REQUEST['LG_CPRID'];
    }

    if (isset($_REQUEST['STR_LSTDESCRIPTION'])) {
        $STR_LSTDESCRIPTION = $_REQUEST['STR_LSTDESCRIPTION'];
    }

    if (isset($_REQUEST['DT_LIVBEGIN'])) {
        $DT_LIVBEGIN = $_REQUEST['DT_LIVBEGIN'];
    }

    if (isset($_REQUEST['DT_LIVEND'])) {
        $DT_LIVEND = $_REQUEST['DT_LIVEND'];
    }
    if (isset($_REQUEST['LG_LSTID'])) {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }

    if (isset($_REQUEST['LG_LIVID'])) {
        $LG_LIVID = $_REQUEST['LG_LIVID'];
    }

    if (isset($_REQUEST['$LG_DETLIVID'])) {
        $LG_DETLIVID = $_REQUEST['$LG_DETLIVID'];
    }

    if (isset($_REQUEST['LIST_LG_LIVID'])) {
        $LIST_LG_LIVID = $_REQUEST['LIST_LG_LIVID'];
    }

    if (isset($_REQUEST["STR_COMMLIVADRESSE"])) {
        $STR_COMMLIVADRESSE = $_REQUEST["STR_COMMLIVADRESSE"];
    }

    if (isset($_REQUEST['STR_LSTVALUE'])) {
        $STR_LSTVALUE = $_REQUEST['STR_LSTVALUE'];
    }

    if (isset($_REQUEST['STR_LIVNAME'])) {
        $STR_LIVNAME = $_REQUEST['STR_LIVNAME'];
    }

    if (isset($_REQUEST['LG_ZONLIVID'])) {
        $LG_ZONLIVID = $_REQUEST['LG_ZONLIVID'];
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

    if ($mode == "getTypetransaction") {
        $value = $ConfigurationManager->getTypetransaction($LG_AGEID);
        if ($value != null) {
            $arrayJson["TTRNAME"] = $value[0]['STR_TTRNAME'];
            $arrayJson["TTRDESCRIPTION"] = $value[0]['STR_TTRDESCRIPTION'];
        }
    } else if ($mode == "createCommproduit") {
        $LG_CPRID = null;
        $token = $ConfigurationManager->generateToken();
        $OJson = $CommandeManager->createCommande($LG_AGEID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $OUtilisateur, $token, $LG_COMMID);
        isset($_REQUEST['CMD_DATA']) ? $CMD_DATA = $_REQUEST['CMD_DATA'] : $CMD_DATA = null;
        if ($CMD_DATA != null) {
            $arrayJson["product_added_to_cart"] = 0;
            if ($OJson["LG_COMMID"] != "") {
                foreach (json_decode($CMD_DATA) as $item) {
                    $product = $StockManager->getProduct($item->str_proname);

                    if ($product != null) {
                        $result = $CommandeManager->createCommandeProduit($OJson["LG_COMMID"], $OJson["LG_CLIID"], $LG_AGEID, $product[0]['lg_proid'], $item->int_cprquantity, $OUtilisateur, $token);

                        if (is_array($result)) {
                            $arrayJson["product_unavailable"][] = $item->str_proname;
                        } else {
                            $arrayJson["product_added_to_cart"] += 1;
                            $arrayJson["product_added_data"][] = [
                                'LG_CPRID' => $result,
                                'ArtID' => $product[0]['lg_proid'],
                                "int_cprquantity" => $item->int_cprquantity,
                                "ArtPrixBase" => $product[0]['int_propricevente'],
                                "ArtLib" => $product[0]['str_prodescription'], "ArtGPicID" => $product[0]['str_propic'] != null ? Parameters::$rootFolderRelative . "produits/" . $product[0]["lg_proid"] . "/" . $product[0]['str_propic'] : ""
                            ];
                        }
                    }
                }
                Parameters::buildSuccessMessage("Opération traitée avec succès");
                $arrayJson["ITEMS_COUNT"] = count(json_decode($CMD_DATA));
            }
        } else {
            if ($OJson["LG_COMMID"] != "") {
                $LG_CPRID = $CommandeManager->createCommandeProduit($OJson["LG_COMMID"], $OJson["LG_CLIID"], $LG_AGEID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur, $token);
            }
        }
        $arrayJson["LG_COMMID"] = $OJson["LG_COMMID"];
        if ($LG_CPRID) {
            $arrayJson['LG_CPRID'] = $LG_CPRID;
        }
    } else if ($mode == "updateCommproduit") {
        $token = $ConfigurationManager->generateToken();
        $result = $CommandeManager->updateCommandeProduit($LG_CPRID, $INT_CPRQUANTITY, $OUtilisateur, $token);
        if (is_array($result)) {
            $arrayJson["LG_COMMID"] = $result['lg_cprid'];
            $arrayJson["PcvMtHT"] = $result['PcvMtHT'];
            $arrayJson["PcvMtTTC"] = $result['PcvMtTTC'];
        }

    } else if ($mode == "deleteCommproduit") {
        $token = $ConfigurationManager->generateToken();
        $result = $CommandeManager->deleteCommandeProduit($LG_CPRID, $token);
        if (is_array($result)) {
            $arrayJson["LG_COMMID"] = $result["lg_commid"];
            $arrayJson["PcvMtHT"] = $result['PcvMtHT'];
            $arrayJson["PcvMtTTC"] = $result['PcvMtTTC'];
        }
    } else if ($mode == "updateCommande") {
        $value = $CommandeManager->updateCommande($LG_COMMID, "111111", "111111");
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } //moi
    else if ($mode == "listeCommandeLocal") {
        isset($_REQUEST['ORDER_NOT_ON_LIVRAISON']) ? $ORDER_NOT_ON_LIVRAISON = $_REQUEST['ORDER_NOT_ON_LIVRAISON'] : $ORDER_NOT_ON_LIVRAISON = false;
        $result = $CommandeManager->showAllCommandeproduit($FILTER_OPTIONS, $LIMIT, $PAGE, $ORDER_NOT_ON_LIVRAISON);

        $arrayJson["data"] = $result['data'];
        $arrayJson["total"] = $result['total'];
        $arrayJson["limit"] = (int)$LIMIT;
        $arrayJson["page"] = (int)$PAGE;
    } else if ($mode == "getCommande") {
        $result = $CommandeManager->getCommande($LG_COMMID);
        $arrayJson['data'] = [
            "lg_commid" => $result['lg_commid'],
            "str_commname" => $result["str_commname"],
            "dt_commupdated" => $result["dt_commupdated"],
            "str_commstatut" => $result["str_commstatut"],
            "dbl_commmtht" => $result["dbl_commmtht"],
            "dbl_commmtttc" => $result["dbl_commmtttc"],
            "lg_ageid" => $result["lg_ageid"],
            "str_socname" => $result["str_socname"],
            "str_socdescription" => $result["str_socdescription"],
            'str_socphone' => $result['str_socphone'],
            'str_socmail' => $result['str_socmail'],
            'str_socadresse' => $result['str_socadresse'],
            'type_societe' => $result['str_lstdescription'],
            "lg_socextid" => $result["lg_socextid"],
            "str_pays" => $result["str_pays"],
            "lg_livid" => $result["zone_livraison"],
            "str_livadresse" => $result["str_livadresse"],
        ];
    } //moi
    else if ($mode == "validationCommande") {
        $token = $ConfigurationManager->generateToken();
        $value = $CommandeManager->handleCommande($LG_AGEID, $STR_COMMLIVADRESSE, $LG_ZONLIVID, $token, $OUtilisateur);
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } else if ($mode == "adminCartValidation") {
        $token = $ConfigurationManager->generateToken();
        $value = $CommandeManager->adminCartValidation($LG_COMMID, $STR_COMMLIVADRESSE, $LG_ZONLIVID, $token, $OUtilisateur);
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } else if ($mode == "addDeliveryPlace") {
        $result = $CommandeManager->addDeleveryZone($STR_LSTVALUE, $STR_LSTDESCRIPTION, $OUtilisateur);
        if ($result["data"]) {
            $array[] = [
                "id" => $result["data"]["lg_lstid"],
                "name" => $result["data"]["str_lstvalue"],
                "description" => $result["data"]["str_lstdescription"],
                "created_at" => $result["data"]["dt_lstcreated"]
            ];
            $arrayJson["zone_de_livraison"] = $array;
            $arrayJson["total"] = $result['total'];
        }
    } else if ($mode == "getDeliveryPlace") {
        $result = $CommandeManager->getDeliveryPlace($FILTER_OPTIONS, $LIMIT, $PAGE);
//    var_dump($result["data"]);
        foreach ($result['data'] as $item) {
            $arrayJsonChildren[] = [
                "id" => $item["lg_lstid"],
                "name" => $item["str_lstvalue"],
                "description" => $item["str_lstdescription"],
                "created_at" => $item["dt_lstcreated"]
            ];
        }
        $arrayJson["data"] = $arrayJsonChildren == null ? [] : $arrayJsonChildren;
        $arrayJson["total"] = $result['total'];
        $arrayJson["limit"] = (int)$LIMIT;
        $arrayJson["page"] = (int)$PAGE;

    } else if ($mode == "updateDeliveryPlace") {
        $value = $CommandeManager->updateDeliveryPlace($LG_LSTID, $STR_LSTVALUE, $STR_LSTDESCRIPTION, $OUtilisateur);
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } else if ($mode == "deleteDeliveryPlace") {
        isset($_REQUEST['LG_LSTID']) ? $LG_LSTID = $_REQUEST['LG_LSTID'] : $LG_LSTID = null;
        isset($_REQUEST['LIST_LSTID']) ? $LIST_LSTID = $_REQUEST['LIST_LSTID'] : $LIST_LSTID = null;
        $result = $CommandeManager->deleteDeliveryPlace($LG_LSTID, $LIST_LSTID, $OUtilisateur);
        if ($result["status"]) {
            $arrayJson["data"] = $result["status"];
        }
        $arrayJson["total"] = $result['total'];
    } else if ($mode == "createDeliveryCalendar") {
        $LG_LIVID = $CommandeManager->createDeliveryCalendar($STR_LIVNAME, $DT_LIVBEGIN, $DT_LIVEND, $LG_LSTID, $OUtilisateur);
        if (isset($_REQUEST['CMD_LIST'])) {
            $CMD_LIST = $_REQUEST['CMD_LIST'];
            $CommandeManager->createDeliveryDetails($LG_LIVID, $CMD_LIST, $OUtilisateur);
        }
    } else if ($mode == "updateDeliveryCalendar") {
        isset($_REQUEST['CMD_LIST']) ? $CMD_LIST = $_REQUEST['CMD_LIST'] : $CMD_LIST = null;
        $CommandeManager->updateDeliveryCalendar($LG_LIVID, $STR_LIVNAME, $DT_LIVBEGIN, $DT_LIVEND, $LG_LSTID, $CMD_LIST, $OUtilisateur);
    } else if ($mode == "deleteDeliveryCalendar") {
        $result = $CommandeManager->deleteDeliveryCalendar($LIST_LG_LIVID);
        $arrayJson["total"] = $result['total'];
    } else if ($mode == "closeDeliveryCalendar") {
        $CommandeManager->closeDeliveryCalendar($LG_LIVID);
    } else if ($mode == "createDeliveryDetails") {
        isset($_REQUEST['CMD_LIST']) ? $CMD_LIST = $_REQUEST['CMD_LIST'] : $CMD_LIST = null;
        $CommandeManager->createDeliveryDetails($LG_LIVID, $CMD_LIST, $OUtilisateur);
    } else if ($mode == "deleteDeleveryDetails") {
        $CommandeManager->deleteDeleveryDetails($LG_LIVID);
    } else if ($mode == "getCalendarFrontOfiice") {
        $arrayJson = $CommandeManager->getCalendarFrontOfiice();
    } else if ($mode == "listClientCommande") {
        $arrayJsonChildren = $CommandeManager->getLastCommandeByAgence($LG_AGEID, Parameters::$statut_closed, $SEVERAL = true);
        foreach ($arrayJsonChildren as $key => $item) {
            $arrayJson["pieces"][] = [
                "PcvID" => $item['lg_commid'],
                "PcvDate" => $item['dt_commcreated'],
                "PcvMtHT" => (float)$item['dbl_commmtht'],
                "PcvMtTTC" => (float)$item['dbl_commmtttc'],
                "etat" => $item['str_commstatut'],
            ];
        }
        $value = $CommandeManager->getLastCommandeByAgence($LG_AGEID, Parameters::$statut_waiting);

        foreach ($value as $item) {
            $arrayJson["pieces"][] = [
                "PcvID" => $item['lg_commid'],
                "PcvDate" => $item['dt_commcreated'],
                "PcvMtHT" => (float)$item['dbl_commmtht'],
                "PcvMtTTC" => (float)$item['dbl_commmtttc'],
                "etat" => $item['str_commstatut'],
            ];
        }
    } else if ($mode == "listProductByCommande") {
        $rootFolderRelative = __DIR__ . "/../images/";
        $arrayJsonChildren = $CommandeManager->listProductByCommande($LG_COMMID);
        foreach ($arrayJsonChildren as $item) {
            $arrayJson['products'][] = [
                "LG_CPRID" => $item['lg_cprid'],
                "ArtID" => $item['lg_proid'],
                "ArtCode" => $item["str_proname"],
                "ArtLib" => $item['str_prodescription'],
                "ArtPrixBase" => $item['int_propricevente'],
                "int_cprquantity" => $item['int_cprquantity'],
                "ArtCateg" => $item['str_procateg'],
                "ArtFamille" => $item['str_profamille'],
                "ArtGamme" => $item['str_progamme'],
                "ArtSpecies" => $item['str_proespece'],
                "ArtGPicID" => $item['str_propic'] != null ? Parameters::$rootFolderRelative . "produits/" . $item["lg_proid"] . "/" . $item['str_propic'] : ""
            ];
        }
    } else if ($mode === "listOrdersIn") {
        $result = $CommandeManager->showAllOrOneOrderOrInvoice($FILTER_OPTIONS, $LIMIT, $PAGE, "stat_devis");
        $arrayJson["data"] = $result['data'];
        $arrayJson["total"] = $result['total'];
        $arrayJson["limit"] = (int)$LIMIT;
        $arrayJson["page"] = (int)$PAGE;
    } else if ($mode === "listInvoices") {
        $result = $CommandeManager->showAllOrOneOrderOrInvoice($FILTER_OPTIONS, $LIMIT, $PAGE, "stat_facture");
        $arrayJson["data"] = $result['data'];
        $arrayJson["total"] = $result['total'];
        $arrayJson["limit"] = (int)$LIMIT;
        $arrayJson["page"] = (int)$PAGE;
    }else if ($mode == "getClientCalendar") { // Correction ici : utilisation de "==" au lieu de "="
    $arrayJsonChildren = $CommandeManager->getClientCalendar($LG_AGEID); // On suppose que $LG_AGEID est bien défini
    $tabsData = [];
    $listsData = [];

    foreach ($arrayJsonChildren as $row) {
        // Vérification de l'existence des indices avant leur utilisation
        if (isset($row['str_lstvalue'])) {
            $tabId = 'product-tab-' . strtolower(str_replace(' ', '-', $row['str_lstvalue']));

            if (!isset($tabsData[$tabId])) {
                $tabsData[$tabId] = [
                    'id' => $tabId,
                    'title' => ucfirst(strtolower($row['str_lstvalue'])),
                ];
            }

            if (!isset($listsData[$tabId])) {
                $listsData[$tabId] = [];
            }

            // Vérification de l'existence des autres indices utilisés
            if (isset($row['lg_livid'], $row['dt_livbegin'], $row['dt_livend'], $row['str_lstdescription'])) {
                $listsData[$tabId][] = [
                    'id' => $row['lg_livid'],
                    'date' => date('d/m H:i', strtotime($row['dt_livbegin'])),
                    'deliveryDate' => date('d/m', strtotime($row['dt_livend'])),
                    'areas' => $row['str_lstdescription'], // Zone géographique
                ];
            }
        }
    }

    // Conversion en tableau indexé pour tabsData
    $tabsData = array_values($tabsData);

    // Ajout des données au tableau JSON
    $arrayJson["data"] = ["tabsData" => $tabsData, "listsData" => $listsData];

    // Initialisation de $arrayJson["code_statut"] et $arrayJson["desc_statut"]
    $arrayJson["code_statut"] = Parameters::$Message ?? "Code statut non défini"; // Valeur par défaut
    $arrayJson["desc_statut"] = Parameters::$Detailmessage ?? "Message non défini";
}

// Conversion en JSON et affichage
echo json_encode($arrayJson);
}