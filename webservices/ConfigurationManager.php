<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
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
$STR_UTITOKEN = "";
$OUtilisateur = null;

$ConfigurationManager = new ConfigurationManager();
$OneSignal = new OneSignal();

$mode = $_REQUEST['mode'];

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
    $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
}

if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
}


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

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
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


if ($mode == "listTypetransaction") {
    $listTypetransaction = $ConfigurationManager->showAllOrOneTypetransaction($search_value);

    foreach ($listTypetransaction as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["TTRID"] = $value['LG_TTRID'];
        $arrayJson_chidren["TTRDESCRIPTION"] = $value['STR_TTRDESCRIPTION'];
        $arrayJson[] = $arrayJson_chidren;
    }
} else if ($mode == "listOperateur") {
    $listOperateur = $ConfigurationManager->showAllOrOneOperateur($search_value);

    foreach ($listOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["OPEID"] = $value['LG_OPEID'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else if ($mode == "listSociete") {
    $listSociete = $ConfigurationManager->showAllOrOneSocieteLimit($search_value, $start, $length);
    $total = $ConfigurationManager->totalSociete($search_value);
    foreach ($listSociete as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson_chidren["SOCNAME"] = $value['STR_SOCNAME'];
        $arrayJson_chidren["SOCDESCRIPTION"] = $value['STR_SOCDESCRIPTION'];
        $arrayJson_chidren["SOCLOGO"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_SOCLOGO'];
        $arrayJson_chidren["SOCCREATED"] = $value['DT_SOCCREATED'];
        $arrayJson_chidren["SOCADDRESS"] = $value['STR_SOCADDRESS'];
        $arrayJson_chidren["SOCMAIL"] = $value['STR_SOCMAIL'];
        $arrayJson_chidren["SOCPHONE"] = $value['STR_SOCPHONE'];
        $arrayJson_chidren["SOCNOTIFICATION"] = ($value['BOOL_SOCNOTIFICATION'] == Parameters::$PROCESS_FAILED ? false : true);
        $arrayJson_chidren["SOCLASTABONNEMENT"] = ($value['DT_SOCLASTABONNEMENT'] != null ? DateToString($value['DT_SOCLASTABONNEMENT'], 'd/m/Y') : "");
        $arrayJson_chidren["str_ACTION"] = "<span class='text-warning' title='Mise à jour de la société " . $value['STR_SOCDESCRIPTION'] . "'></span>";
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["recordsTotal"] = $total;
    $arrayJson["recordsFiltered"] = $total;
} else if ($mode == "listSocieteUtilisateur") {
    $LG_SOCID = "%";
    if (isset($_REQUEST['LG_SOCID']) && $_REQUEST['LG_SOCID'] != "") {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    $listSocieteUtilisateur = $ConfigurationManager->showAllOrOneSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID);

    foreach ($listSocieteUtilisateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["SUTID"] = $value['LG_SUTID'];
        $arrayJson_chidren["SOCNAME"] = $value['STR_SOCNAME'];
        $arrayJson_chidren["SOCDESCRIPTION"] = $value['STR_SOCDESCRIPTION'];
        $arrayJson_chidren["UTIFIRSTLASTNAME"] = $value['STR_UTIFIRSTLASTNAME'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else if ($mode == "listSocieteOperateur") {
    $LG_OPEID = "%";
    if (isset($_REQUEST['LG_OPEID']) && $_REQUEST['LG_OPEID'] != "") {
        $LG_OPEID = $_REQUEST['LG_OPEID'];
    }

    if (isset($_REQUEST['LG_SOCID'])) {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    $listSocieteOperateur = $ConfigurationManager->showAllOrOneSocieteOperateur($search_value, $LG_SOCID, $LG_OPEID);

    foreach ($listSocieteOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["SOPID"] = $value['LG_SOPID'];
        $arrayJson_chidren["SOPPHONE"] = $value['STR_SOPPHONE'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else {
    if (isset($_REQUEST['LG_TTRID'])) {
        $LG_TTRID = $_REQUEST['LG_TTRID'];
    }

    if (isset($_REQUEST['LG_OPEID'])) {
        $LG_OPEID = $_REQUEST['LG_OPEID'];
    }

    if (isset($_REQUEST['LG_SOCID'])) {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    if (isset($_REQUEST['STR_SOCNAME'])) {
        $STR_SOCNAME = $_REQUEST['STR_SOCNAME'];
    }

    if (isset($_REQUEST['STR_SOCDESCRIPTION'])) {
        $STR_SOCDESCRIPTION = $_REQUEST['STR_SOCDESCRIPTION'];
    }

    if (isset($_REQUEST['STR_SOCADDRESS'])) {
        $STR_SOCADDRESS = $_REQUEST['STR_SOCADDRESS'];
    }

    if (isset($_REQUEST['STR_SOCMAIL'])) {
        $STR_SOCMAIL = $_REQUEST['STR_SOCMAIL'];
    }

    if (isset($_REQUEST['STR_SOCPHONE'])) {
        $STR_SOCPHONE = $_REQUEST['STR_SOCPHONE'];
    }

    if (isset($_REQUEST['BOOL_SOCNOTIFICATION'])) {
        $BOOL_SOCNOTIFICATION = $_REQUEST['BOOL_SOCNOTIFICATION'];
    }

    if (isset($_REQUEST['STR_SOPPHONE'])) {
        $STR_SOPPHONE = $_REQUEST['STR_SOPPHONE'];
    }

    if (isset($_REQUEST['LG_SOPID'])) {
        $LG_SOPID = $_REQUEST['LG_SOPID'];
    }

    if (isset($_REQUEST['TO'])) {
        $TO = $_REQUEST['TO'];
    }

    if (isset($_REQUEST['STR_SOPSTATUT'])) {
        $STR_SOPSTATUT = $_REQUEST['STR_SOPSTATUT'];
    }

    if (isset($_REQUEST['LG_PROID'])) {
        $LG_PROID = $_REQUEST['LG_PROID'];
    }

    if (isset($_REQUEST['LG_SUTID'])) {
        $LG_SUTID = $_REQUEST['LG_SUTID'];
    }

    if (isset($_REQUEST['LG_CLIID'])) {
        $LG_CLIID = $_REQUEST['LG_CLIID'];
    }

    if (isset($_REQUEST['STR_SOCSIRET'])) {
        $STR_SOCSIRET = $_REQUEST['STR_SOCSIRET'];
    }

    if (isset($_REQUEST['LG_LSTTYPESOCID'])) {
        $LG_LSTTYPESOCID = $_REQUEST['LG_LSTTYPESOCID'];
    }

    if (isset($_REQUEST['LG_LSTPAYID'])) {
        $LG_LSTPAYID = $_REQUEST['LG_LSTPAYID'];
    }

    if (isset($_REQUEST['STR_SOCCODE'])) {
        $STR_SOCCODE = $_REQUEST['STR_SOCCODE'];
    }

    if (isset($_FILES['STR_SOCLOGO'])) {
        $STR_SOCLOGO = $_FILES['STR_SOCLOGO'];
    }

    if (isset($_REQUEST['STR_AGENAME'])) {
        $STR_AGENAME = $_REQUEST['STR_AGENAME'];
    }

    if (isset($_REQUEST['STR_AGEDESCRIPTION'])) {
        $STR_AGEDESCRIPTION = $_REQUEST['STR_AGEDESCRIPTION'];
    }

    if (isset($_REQUEST['STR_AGELOCALISATION'])) {
        $STR_AGELOCALISATION = $_REQUEST['STR_AGELOCALISATION'];
    }

    if (isset($_REQUEST['STR_AGEPHONE'])) {
        $STR_AGEPHONE = $_REQUEST['STR_AGEPHONE'];
    }

    if (isset($_REQUEST['LG_LSTID'])) {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }
    if (isset($params['p_key'])) {
        $p_key = $params['p_key'];
    }

    //moi
    if (isset($_REQUEST['STR_UTIFIRSTLASTNAME'])) {
        $STR_UTIFIRSTLASTNAME = $_REQUEST['STR_UTIFIRSTLASTNAME'];
    }

    if (isset($_REQUEST['STR_UTIPHONE'])) {
        $STR_UTIPHONE = $_REQUEST['STR_UTIPHONE'];
    }

    if (isset($_REQUEST['STR_UTISTATUT'])) {
        $STR_UTISTATUT = $_REQUEST['STR_UTISTATUT'];
    }


    if (isset($_REQUEST['STR_UTILOGIN'])) {
        $STR_UTILOGIN = $_REQUEST['STR_UTILOGIN'];
    }

    if (isset($_REQUEST['STR_UTIPASSWORD'])) {
        $STR_UTIPASSWORD = $_REQUEST['STR_UTIPASSWORD'];
    }
    if (isset($_REQUEST['STR_UTIMAIL'])) {
        $STR_UTIMAIL = $_REQUEST['STR_UTIMAIL'];
    }
    //moi
    if (isset($_REQUEST['LG_SOCEXTID'])) {
        $LG_SOCEXTID = $_REQUEST['LG_SOCEXTID'];
    }

    if (isset($_REQUEST['LG_AGEID'])) {
        $LG_AGEID = $_REQUEST['LG_AGEID'];
    }

    if (isset($_REQUEST['LG_PROID'])) {
        $LG_PROID = $_REQUEST['LG_PROID'];
    }
    //

    if (isset($_REQUEST['STR_DOCNAME'])) {
        $STR_DOCNAME = $_REQUEST['STR_DOCNAME'];
    }

    if (isset($_REQUEST['LG_DOCPKEY'])) {
        $LG_DOCPKEY = $_REQUEST['LG_DOCPKEY'];
    }

    if (isset($_FILES['STR_UTIPIC'])) {
        $STR_UTIPIC = $_FILES['STR_UTIPIC'];
    }
    if (isset($_REQUEST['CMD_DATA'])) {
        $CMD_DATA = $_REQUEST['CMD_DATA'];
    }

    if (isset($_REQUEST['SEARCH_VALUE'])) {
        $SEARCH_VALUE = $_REQUEST['SEARCH_VALUE'];
    }

    if (isset($_REQUEST['STR_SOCSTATUT'])) {
        $STR_SOCSTATUT = $_REQUEST['STR_SOCSTATUT'];
    }

    if (isset($_REQUEST['STR_UTIPIC'])) {
        $STR_UTIPIC = $_FILES['STR_UTIPIC'];
    }

    if (isset($_REQUEST['LG_DOCID'])) {
        $LG_DOCID = $_POST['LG_DOCID'];
    }

    if (isset($_REQUEST['LG_PROSUBID'])) {
        $LG_PROSUBID = $_REQUEST['LG_PROSUBID'];
    }

    if (isset($_REQUEST['LG_PROKIDIDS'])) {
        $LG_PROKIDIDS = $_REQUEST['LG_PROKIDIDS'];
    }

    if (isset($_REQUEST['LOCAL_DIRECTORY'])) {
        $LOCAL_DIRECTORY = $_REQUEST['LOCAL_DIRECTORY'];
    }

    if (isset($_REQUEST['LG_TYLID'])) {
        $LG_TYLID = $_REQUEST['LG_TYLID'];
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

    if (isset($_REQUEST['table'])) {
        $table = $_REQUEST['table'];
    }

    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
    }

    if (isset($_REQUEST['date'])) {
        $date = $_REQUEST['date'];
    }

    if (isset($_REQUEST['LG_PRIID'])) {
        $LG_PRIID = $_REQUEST['LG_PRIID'];
    }
    if (isset($_REQUEST['STR_PRINAME'])) {
        $STR_PRINAME = $_REQUEST['STR_PRINAME'];
    }
    if (isset($_REQUEST['STR_PRIDESCRIPTION'])) {
        $STR_PRIDESCRIPTION = $_REQUEST['STR_PRIDESCRIPTION'];
    }
    if (isset($_REQUEST['STR_PROIURL'])) {
        $STR_PROIURL = $_REQUEST['STR_PROIURL'];
    }
    if (isset($_REQUEST['STR_PRITYPE'])) {
        $STR_PRITYPE = $_REQUEST['STR_PRITYPE'];
    }
    if (isset($_REQUEST['STR_PRIKIND'])) {
        $STR_PRIKIND = $_REQUEST['STR_PRIKIND'];
    }
    if (isset($_REQUEST['STR_PRICLASS'])) {
        $STR_PRICLASS = $_REQUEST;
    }
    if (isset($_REQUEST['INT_PRIPRIORITY'])) {
        $INT_PRIPRIORITY = $_REQUEST['INT_PRIPRIORITY'];
    }
    if (isset($_REQUEST['LG_PRIPARENTID'])) {
        $LG_PRIPARENTID = $_REQUEST['LG_PRIPARENTID'];
    }
    if (isset($_REQUEST['LG_PRIGROUPID'])) {
        $LG_PRIGROUPID = $_REQUEST['LG_PRIGROUPID'];
    }
    if (isset($_REQUEST['STR_PRONAME'])) {
        $STR_PRONAME = $_REQUEST['STR_PRONAME'];
    }
    if (isset($_REQUEST['STR_PRODESCRIPTION'])) {
        $STR_PRODESCRIPTION = $_REQUEST['STR_PRODESCRIPTION'];
    }
    if (isset($_REQUEST['STR_PROTYPE'])) {
        $STR_PROTYPE = $_REQUEST['STR_PROTYPE'];
    }
    if (isset($_REQUEST['LG_PRIIDS'])) {
        $LG_PRIIDS = $_REQUEST['LG_PRIIDS'];
    }
    if (isset($_REQUEST['STR_LSTDESCRIPTION'])) {
        $STR_LSTDESCRIPTION = $_REQUEST['STR_LSTDESCRIPTION'];
    }
    if (isset($_REQUEST['STR_LSTVALUE'])) {
        $STR_LSTVALUE = $_REQUEST['STR_LSTVALUE'];
    }

    if (isset($_REQUEST['STATUT'])) {
        $STATUT = $_REQUEST['STATUT'];
    }

    if ($mode == "getTypetransaction") {
        $value = $ConfigurationManager->getTypetransaction($LG_TTRID);
        if ($value != null) {
            $arrayJson["TTRNAME"] = $value[0]['STR_TTRNAME'];
            $arrayJson["TTRDESCRIPTION"] = $value[0]['STR_TTRDESCRIPTION'];
        }
    } else if ($mode == "getOperateur") {
        $value = $ConfigurationManager->getOperateur($LG_OPEID);
        if ($value != null) {
            $arrayJson["OPENAME"] = $value[0]['STR_OPENAME'];
            $arrayJson["OPEDESCRIPTION"] = $value[0]['STR_OPEDESCRIPTION'];
            $arrayJson["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value[0]['STR_OPEPIC'];
        }
    } else if ($mode == "getSociete") {
        $value = $ConfigurationManager->getSociete($LG_SOCID);
        if ($value != null) {
            $arrayJson["str_socname"] = $value[0]['str_socname'];
            $arrayJson["str_socdescription"] = $value[0]['str_socdescription'];
            $arrayJson["str_soclogo"] = Parameters::$rootFolderAbsolute . "logos/" . $value[0]['str_soclogo'];
            $arrayJson["dt_soccreated"] = $value[0]['dt_soccreated'];
            $arrayJson["str_socmail"] = $value[0]['str_socmail'];
            $arrayJson["str_socphone"] = $value[0]['str_socphone'];
//            $arrayJson["SOCLASTABONNEMENT"] = ($value[0]['DT_SOCLASTABONNEMENT'] != null ? DateToString($value[0]['DT_SOCLASTABONNEMENT'], 'd/m/Y') : "");
        }
    } else if ($mode == "getProfile") {
        $value = $ConfigurationManager->getProfile($LG_PROID);
        if ($value != null) {
            $liste = $ConfigurationManager->getListe($value[0]['lg_lstid']);
            $arrayJson["data"]["PRONAME"] = $value[0]['str_proname'];
            $arrayJson["data"]["PRODESCRIPTION"] = $value[0]['str_prodescription'];
            $arrayJson["data"]["PROTYPE"] = (strtoupper($liste[0]['str_lstdescription']) == strtoupper(Parameters::$type_system) ? "Système" : "Standard");
            $arrayJson["data"]["PROTYPEID"] = $liste[0]["lg_lstid"];
        }
        Parameters::buildSuccessMessage("Profil trouvé");
    } else if ($mode == "getSocieteOperateur") {
        $value = $ConfigurationManager->getSocieteOperateurUnique($LG_SOPID);
        if ($value != null) {
            $arrayJson["SOPPHONE"] = $value[0]['STR_SOPPHONE'];
        }
    } else if ($mode == "getClient") {
//        var_dump($LG_CLIID);
        $value = $ConfigurationManager->getClient($LG_CLIID);
        foreach ($value as $k => $v) {
            $arrayJson[$k] = $v;
        }
        /* if ($value != null) {
          $arrayJson = $value;
          } */
    }//moi
    else if ($mode == 'getDocument') {
        $value = $ConfigurationManager->getDocument($LG_DOCID);
        if ($value != null) {
            $arrayJson = $value[0];
        }
    } else if ($mode == "getProducts") {
        $StockManager = new StockManager();
        $arrayJson["products_not_found"] = [];

        foreach (json_decode($CMD_DATA) as $item) {
            // Vérification de l'existence de la propriété str_proname
            if (!isset($item->str_proname) || empty($item->str_proname)) {
                $arrayJson["products_not_found"][] = "Nom du produit manquant";
                continue;
            }

            $product = $StockManager->getProduct($item->str_proname);
            if ($product != null) {
                foreach ($product as $value) {
                    $arrayJson_chidren = array();
                    $arrayJson_chidren['str_proname'] = $value['str_proname'];
                    $arrayJson_chidren['str_prodescription'] = $value['str_prodescription'];
                    $arrayJson_chidren['int_propricevente'] = $value['int_propricevente'];
                    $arrayJson_chidren['int_cprquantity'] = (int) $item->int_cprquantity;
                    $arrayJson_chidren['dbl_montant'] = (int) $value['int_propricevente'] * (int) $item->int_cprquantity;
                    $arrayJson["products"][] = $arrayJson_chidren;
                }
            } else {
                $arrayJson["products_not_found"][] = $item->str_proname;
            }
        }
    }
//moi
    else if ($mode == "listUsers") {
    $result = $ConfigurationManager->showAllOrOneBackUsers($FILTER_OPTIONS ?? null, $LIMIT ?? 25, $PAGE ?? 1);

    foreach ($result['data'] as $value) {
        $profil = $ConfigurationManager->getProfile($value['lg_proid']);

        $arrayJson_chidren = [
            "UTIID" => $value["lg_utiid"],
            "UTIFIRSTLASTNAME" => $value["str_utifirstlastname"],
            "UTIMAIL" => $value["str_utimail"],
            "UTIPHONE" => $value["str_utiphone"],
            "UTICREATED" => $value["dt_uticreated"],
            "UTIPROFIL" => $profil[0]['str_prodescription'] ?? "Profil inconnu",
            "UTIPIC" => $profil[0]['str_utipic'] ?? null
        ];

        $OJson[] = $arrayJson_chidren;
    }

    if (!empty($result) && isset($result['data'])) {
        Parameters::buildSuccessMessage("Utilisateurs trouvés");
    } else {
        Parameters::buildSuccessMessage("Aucun utilisateur trouvé");
    }

    $arrayJson["data"] = $OJson;
    $arrayJson["total"] = $result['total'] ?? 0;
    $arrayJson["limit"] = (int) ($LIMIT ?? 25);
    $arrayJson["page"] = (int) ($PAGE ?? 1);
}
 else if ($mode == 'getClientDemandes') {
        $result = $ConfigurationManager->showAllOrOneClientRequest($FILTER_OPTIONS, $LIMIT, $PAGE);
        foreach ($result['data'] as $val) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["str_utifirstlastname"] = $val['str_utifirstlastname'];
            $arrayJson_chidren["str_utimail"] = $val['str_utimail'];
            $arrayJson_chidren["str_utiphone"] = $val['str_utiphone'];
            $arrayJson_chidren["str_utilogin"] = $val['str_utilogin'];
            $arrayJson_chidren["lg_socid"] = $val['lg_socid'];
            $arrayJson_chidren["str_socname"] = $val['str_socname'];
            $arrayJson_chidren["dt_soccreated"] = $val['dt_soccreated'];
            $arrayJson_chidren["str_typesociete"] = $ConfigurationManager->getListe($val['lg_lsttypesocid'])[0]['str_lstdescription'];
            $arrayJson_chidren["str_socsiret"] = $val['str_socsiret'];
            $arrayJson_chidren["str_paysfacturation"] = $ConfigurationManager->getListe($val['lg_lstpayid'])[0]['str_lstdescription'];
            $arrayJson_chidren["str_socphone"] = $val['str_socphone'];
            $arrayJson_chidren["str_socmail"] = $val['str_socmail'];
            $arrayJson_chidren["str_socdescription"] = $val['str_socdescription'];
            $arrayJson_chidren["str_socstatut"] = $val['str_socstatut'];
            $OJson[] = $arrayJson_chidren;
        }

        if (!$result['data']) {
            Parameters::buildSuccessMessage("Aucune demandes trouvées");
        } else {
            Parameters::buildSuccessMessage("Demandes trouvées");
        }

        $arrayJson["demandes"] = $OJson;
        $arrayJson["total"] = $result['total'];
        $arrayJson["limit"] = (int) $LIMIT;
        $arrayJson["page"] = (int) $PAGE;
    } //moi
    else if ($mode == "getUtilisateur") {
        $value = $ConfigurationManager->getUtilisateur($LG_UTIID);
        if ($value != null) {
            $profil = $ConfigurationManager->getProfile($value[0]['lg_proid']);
            $arrayJson["data"]['LG_UTIID'] = $value[0]["lg_utiid"];
            $arrayJson["data"]["STR_UTIFIRSTLASTNAME"] = $value[0]['str_utifirstlastname'];
            $arrayJson["data"]["STR_UTIMAIL"] = $value[0]['str_utimail'];
            $arrayJson["data"]["STR_UTIPHONE"] = $value[0]['str_utiphone'];
            $arrayJson["data"]["STR_UTILOGIN"] = $value[0]['str_utilogin'];
            $arrayJson["data"]["STR_UTIPIC"] = $value[0]['str_utipic'] ? Parameters::$rootFolderRelative . "avatars/" . $value[0]["lg_utiid"] . "/" . $value[0]['str_utipic'] : null;
            $arrayJson["data"]["LG_PROID"] = $profil[0]['lg_proid'];
            $arrayJson["data"]["STR_PRODESCRIPTION"] = $profil[0]['str_prodescription'];
            $arrayJson["data"]["STR_UTIPASSWORD"] = $value[0]['str_utipassword'];
            Parameters::buildSuccessMessage("Utilisateur trouvé");
        } else {
            Parameters::buildSuccessMessage("Aucun utilisateur trouvé");
        }
    } else if ($mode == "getClientDemande") {
        $STATUT = isset($STATUT) ? $STATUT : 'default_value'; 
        $value = $ConfigurationManager->getClientDemande($LG_SOCID, $STATUT);
        if ($value) {
            $Images = $ConfigurationManager->showAllOrOneDocumentAndType($LG_SOCID);
            $galerie = "";
            foreach ($Images[0] as $image) {
                $galerie .= $image['str_lstdescription'] . ':' . $image['str_docpath'] . ',';
            }
            $arrayJson_chidren = array();
            $arrayJson_chidren["str_utifirstlastname"] = $value[0][0]['str_utifirstlastname'];
            $arrayJson_chidren["str_utimail"] = $value[0][0]['str_utimail'];
            $arrayJson_chidren["str_utiphone"] = $value[0][0]['str_utiphone'];
            $arrayJson_chidren["str_utilogin"] = $value[0][0]['str_utilogin'];
            $arrayJson_chidren["lg_socid"] = $value[0][0]['lg_socid'];
            $arrayJson_chidren["str_socname"] = $value[0][0]['str_socname'];
            $arrayJson_chidren["str_typesociete"] = $value[0][0]["str_typesociete"];
            $arrayJson_chidren["str_socsiret"] = $value[0][0]['str_socsiret'];
            $arrayJson_chidren["str_paysfacturation"] = $value[0][0]['str_paysfacturation'];
            $arrayJson_chidren["str_socphone"] = $value[0][0]['str_socphone'];
            $arrayJson_chidren["str_socmail"] = $value[0][0]['str_socmail'];
            $arrayJson_chidren["str_socdescription"] = $value[0][0]['str_socdescription'];
            $arrayJson_chidren["str_socstatut"] = $value[0][0]['str_socstatut'];
            $arrayJson_chidren["str_soccode"] = $value[0][0]['str_soccode'];
            $arrayJson_chidren["lg_socextid"] = $value[0][0]['lg_socextid'];
            $arrayJson_chidren["gallery"] = $galerie !== "" ? $galerie : null;
            $OJson[] = $arrayJson_chidren;
            $arrayJson['demande'] = $OJson;
        }
//        var_dump($arrayJson);
    } //moi
    else if ($mode == "showAllOrOneSociete") {
        $value = $ConfigurationManager->showAllOrOneSociete($SEARCH_VALUE, $STR_SOCSTATUT);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    } else if ($mode == "createSociete") {
        // $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO ?? null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);
    } else if ($mode == "updateSociete") {
        //$OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->updateSociete($LG_SOCID, $LG_SOCEXTID, $STR_SOCDESCRIPTION, $STR_SOCNAME, $STR_SOCLOGO ?? null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);
    } else if ($mode == "deleteSociete") {
        $ConfigurationManager->deleteSociete($LG_SOCID, $OUtilisateur);
    } else if ($mode == "createSocieteOperateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createSocieteOperateur($LG_SOCID, $LG_OPEID, $STR_SOPPHONE, $OUtilisateur);
    } else if ($mode == "updateSocieteOperateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->updateSocieteOperateur($LG_SOPID, $STR_SOPPHONE, $OUtilisateur);
    } else if ($mode == "deleteSocieteOperateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteSocieteOperateur($LG_SOPID, $STR_SOPSTATUT, $OUtilisateur);
    } else if ($mode == "createSocieteUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createSocieteUtilisateur($LG_SOCID, $LG_UTIID, $OUtilisateur);
    } else if ($mode == "deleteSocieteUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteSocieteUtilisateur($LG_SUTID, $OUtilisateur);
    }//moi
    else if ($mode == "createAgence") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createAgence($STR_AGENAME, $STR_AGEDESCRIPTION, $STR_AGELOCALISATION,
                $STR_AGEPHONE, $LG_SOCID, $OUtilisateur);
    } //moi
    else if ($mode == 'createUtilisateur') {
        $STR_UTISTATUT = $_REQUEST['STR_UTISTATUT'] ?? null;
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, sha1($STR_UTIPASSWORD), $LG_AGEID, $STR_UTIPIC ?? null, $LG_PROID, $OUtilisateur, $STR_UTISTATUT);
    } //moi
    else if ($mode == "updateUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->updateUtilisateur($LG_UTIID, $STR_UTISTATUT, $STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $LG_AGEID, $_FILES['STR_UTIPIC'] ?? null, $LG_PROID, $OUtilisateur, $STR_UTIPASSWORD);
        $OUtilisateur = $ConfigurationManager->getUtilisateur($LG_UTIID);
        $arrayJson["data"]["STR_UTIPIC"] = $OUtilisateur[0]['str_utipic'] ? Parameters::$rootFolderRelative . "avatars/" . $OUtilisateur[0]["lg_utiid"] . "/" . $OUtilisateur[0]['str_utipic'] : null;
        Parameters::buildSuccessMessage("Mise à jour des données l'utilisateur effectuée avec succès.");
    } //moi
    else if ($mode === "deleteUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteUtilisateur($LG_UTIID, $OUtilisateur);
    } else if ($mode == "createDocument") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $rootFolderRelative = __DIR__ . "/../";
        $STR_DOCPATH = uploadFile($rootFolderRelative . "documents/" . $LG_SOCID . "/", $_FILES['STR_DOCPATH'], false);
        $ConfigurationManager->createDocument($LG_SOCID, $STR_DOCPATH, $LG_LSTID, $OUtilisateur);
    } //moi
    else if ($mode == "createClientExternal") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $value = $ConfigurationManager->createClientExternal($LG_SOCID, $OUtilisateur);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    } else if ($mode == "uploadOneOrSeveralDocuments") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        isset($_POST['LG_SOCID']) ? $LG_SOCID = $_POST['LG_SOCID'] : $LG_SOCID = null;
        $arrayJson["data"] = $ConfigurationManager->uploadOneOrSeveralDocuments([$_FILES['documents'], $_POST['documents']], $LG_SOCID ?? Parameters::$SN_PROVECI_ID, $OUtilisateur);
    } else if ($mode === "changeDocumentStatut") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->changeDocumentStatut($LG_DOCID, $OUtilisateur);
    } else if ($mode == "registerClient") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

        $ConfigurationManager->createDemande($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $STR_UTIFIRSTLASTNAME, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $STR_UTIPHONE, $LG_PROID, [$_FILES['documents'], $_POST['documents']], $OUtilisateur, null, null, null, null);
    } //moi
    else if ($mode == "rejectRegistration") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $value = $ConfigurationManager->rejectRegistration($LG_SOCID, $OUtilisateur);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    } //moi
    else if ($mode == "markProductAsViewed") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->markProductAsViewed($LG_PROID, $OUtilisateur);
    } //moi
    else if ($mode == "uploadMainImageProduct") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        isset($_FILES['images']) ? $PICTURE = $_FILES['images'] : $PICTURE = null;
        $result = $ConfigurationManager->uploadMainImageProduct($PICTURE, $LG_PROID, $OUtilisateur);
        $arrayJson['data'] = [[
        "id" => $LG_PROID,
        "src" => Parameters::$rootFolderRelative . "produits/" . $LG_PROID . "/" . $result,
        ]];
    } else if ($mode == "uploadThumbImagesProduct") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        isset($_FILES['images']) ? $PICTURES = $_FILES['images'] : $PICTURES = null;
        $result = $ConfigurationManager->uploadThumbImagesProduct($PICTURES, $LG_PROID, $OUtilisateur);
        foreach ($result as $value) {
            $arrayJson_chidren['id'] = $value['id'];
            $arrayJson_chidren['src'] = Parameters::$rootFolderRelative . "produits/" . $LG_PROID . "/" . $value['url'];
            $OJson[] = $arrayJson_chidren;
        }
        $arrayJson["data"] = $OJson;
    } else if ($mode == "createProductSubstitution") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $result = $ConfigurationManager->createProductSubstitution($LG_PROID, $LG_PROKIDIDS, $OUtilisateur);
        $arrayJson["data"] = [
            "success" => $result["validation"],
            "fails" => $result["fails"]
        ];
    } else if ($mode == "uploadLocalImageProduct") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $arrayJson["failedUpload"] = $ConfigurationManager->uploadLocalImageProduct($LOCAL_DIRECTORY, $OUtilisateur);
    } //moi
    else if ($mode == "deleteProduitSubstitution") {
        $ConfigurationManager->deleteProduitSubstitution($LG_PROSUBID);
    } else if ($mode == "deleteProductImage") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteDocument($LG_DOCID, $OUtilisateur);
    } else if ($mode == "deleteProductMainImage") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteProductMainImage($LG_PROID, $OUtilisateur);
    } else if ($mode == "loadCustomer") {
        $ConfigurationManager->loadExternalCustomer();
    } else if ($mode == "sendEmail") {
        $NOTIF = $ConfigurationManager->getListe($LG_LSTID);
        if ($NOTIF) {
            $ConfigurationManager->sendEmail($NOTIF[0]['str_lstdescription'], $NOTIF[0]['str_lstvalue'], $TO ?? "saoured11@gmail.com", "saoured11@gmail.com");
        }
    } else if ($mode == "listDocuments") {
        $listDocuments = $ConfigurationManager->showAllOrOneDocument($FILTER_OPTIONS);

        foreach ($listDocuments as $value) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["LG_DOCID"] = $value['lg_docid'];
            $arrayJson_chidren["STR_DOCPATH"] = Parameters::$rootFolderRelative . "documents/" . $value['p_key'] . "/" . $value['str_docpath'];
            $arrayJson_chidren["DT_DOCCREATED"] = $value['dt_doccreated'];
            $arrayJson_chidren["str_ACTION"] = "<span class='text-warning' title='Mise à jour du document "
                    . (!empty($value['str_docname']) ? $value['str_docname'] : "") . "'></span>";

            $arrayJson_chidren["STR_DOCSTATUT"] = $value['str_docstatut'];
            $OJson[] = $arrayJson_chidren;
        }
        $arrayJson["data"] = $OJson;
    } else if ($mode == "listElements") {
        $listElements = $ConfigurationManager->showAllOrOneListe($search_value, $LG_TYLID);

        foreach ($listElements as $value) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["LG_LSTID"] = $value['lg_lstid'];
            $arrayJson_chidren["STR_LSTDESCRIPTION"] = $value['str_lstdescription'];
            $arrayJson_chidren["STR_LSTVALUE"] = $value['str_lstvalue'];
            $arrayJson_chidren["STR_LSTOTHERVALUE"] = $value['str_lstothervalue'];
            $OJson[] = $arrayJson_chidren;
        }
        $arrayJson["data"] = $OJson;
    } else if ($mode == "showAllProductImages") {
        $result = $ConfigurationManager->showAllProductImages($LG_PROID);
        $OJson = [];

        foreach ($result as $value) {
            $arrayJson_chidren['id'] = $value['lg_proid'] ?? $value["lg_docid"];
<<<<<<< HEAD
            $arrayJson_chidren['src'] = Parameters::$rootFolderRelative . "produits/" . "$LG_PROID/" . ($value['str_propic'] ?? $value["str_docpath"]);
            if (isset($value['str_propic']) && $value['str_propic']) {
                $arrayJson_chidren['isMain'] = true;
            } else {
                $arrayJson_chidren['isMain'] = false;
            }
=======
            $arrayJson_chidren['src'] = Parameters::$rootFolderRelative . "produits/" . "$LG_PROID/" . (isset($value['str_propic']) ? $value['str_propic'] : $value["str_docpath"]);

            $arrayJson_chidren['isMain'] = isset($value['str_propic']) && !empty($value['str_propic']);

>>>>>>> 4e428fc (modif)
            $OJson[] = $arrayJson_chidren;
        }

        $arrayJson["data"] = $OJson;
    } else if ($mode === "loadExternalDocuments") {
        $ConfigurationManager->loadExternalDocuments($table, $search, $date);
    } else if ($mode === "loadInvoiceProduct") {
        $CommandeManager = new CommandeManager();

        $list = $ConfigurationManager->getListe(Parameters::$LAST_BACKUP_DATE);

        $lastInvoices = $CommandeManager->showAllInvoices(["PcvDate" => $list[0]['str_lstvalue']]);
        foreach ($lastInvoices as $invoice) {
            $ConfigurationManager->loadExternalProductsByInvoice($invoice);
        }
    } else if ($mode === "createProfil") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $result = $ConfigurationManager->createProfil($STR_PRONAME, $STR_PRODESCRIPTION, $LG_LSTID, $OUtilisateur);
        $arrayJson["LG_PROID"] = $result;

        if (isset($LG_PRIIDS)) {
            $result = $ConfigurationManager->assignPrivilegesToProfile($result, $LG_PRIIDS);
            $arrayJson["data"]["failed"] = $result;
            Parameters::buildSuccessMessage("Profil créé avec succès");
        }
    } else if ($mode === "updateProfil") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

        $result = $ConfigurationManager->updateProfil($LG_PROID, $STR_PRONAME, $STR_PRODESCRIPTION, $LG_LSTID, $OUtilisateur);
        $arrayJson["LG_PROID"] = $result;

        $privileges = $ConfigurationManager->showAllProfilPrivileges($LG_PROID);
        $listPrivileges = array_column($privileges, "lg_priid");
        if (isset($LG_PRIIDS)) {
            $removePrivileges = array_diff($listPrivileges, $LG_PRIIDS);
            $addPrivileges = array_diff($LG_PRIIDS, $listPrivileges);

            if (count($removePrivileges) > 0) {
                $ConfigurationManager->removePrivilegesFromProfile($LG_PROID, $removePrivileges);
            }
            if (count($addPrivileges) > 0) {
                $ConfigurationManager->assignPrivilegesToProfile($LG_PROID, $addPrivileges);
            }
        } else {
            $ConfigurationManager->removePrivilegesFromProfile($LG_PROID, $listPrivileges);
        }
        Parameters::buildSuccessMessage("Profil mis à jour avec succès");
    } else if ($mode == "deleteProfil") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

        $result = $ConfigurationManager->deleteProfil($LG_PROID, $OUtilisateur);
        //Suppression des privilèges accordés au profil
        $privileges = $ConfigurationManager->showAllProfilPrivileges($LG_PROID);
        if ($privileges) {
            $listPrivileges = array_column($privileges, "lg_priid");
            $ConfigurationManager->removePrivilegesFromProfile($LG_PROID, $listPrivileges);
        }

        $arrayJson["LG_PROID"] = $result;
    } else if ($mode === "createPrivilege") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

        $result = $ConfigurationManager->createPrivilege($STR_PRINAME, $STR_PRIDESCRIPTION, $STR_PROIURL, $STR_PRITYPE, $STR_PRIKIND, $STR_PRICLASS, $INT_PRIPRIORITY, $LG_PRIPARENTID, $LG_PRIGROUPID, $OUtilisateur);
        $arrayJson["LG_PRIID"] = $result;
    } else if ($mode === "updatePrivilege") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

        $result = $ConfigurationManager->updatePrivilege($LG_PRIID, $STR_PRINAME, $STR_PRIDESCRIPTION, $STR_PROIURL, $STR_PRITYPE, $STR_PRIKIND, $STR_PRICLASS, $INT_PRIPRIORITY, $LG_PRIPARENTID, $LG_PRIGROUPID, $OUtilisateur);
        $arrayJson["LG_PRIID"] = $result;
    } else if ($mode === "showAllDocumentRemote") {
        $result = $ConfigurationManager->showAllDocumentRemote($search, $date);
        $arrayJson["data"] = $result;
    } else if ($mode === "deletePrivilege") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

        $result = $ConfigurationManager->deletePrivilege($LG_PRIID, $OUtilisateur);
        $arrayJson["LG_PRIID"] = $result;
    } else if ($mode == "listPrivilege") {
        $result = $ConfigurationManager->showAllOrOnePrivilege($FILTER_OPTIONS, $LIMIT, $PAGE);

        foreach ($result["data"] as $value) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["PRIID"] = $value['lg_priid'];
            $arrayJson_chidren["PRINAME"] = $value['str_priname'];
            $arrayJson_chidren["PRIDESCRIPTION"] = $value['str_pridescription'];
            $arrayJson_chidren["PRIURL"] = $value['str_priurl'];
            $arrayJson_chidren["PRITYPE"] = $value['str_pritype'];
            $arrayJson_chidren["PRIKIND"] = $value['str_prikind'];
            $arrayJson_chidren["PRICLASS"] = $value['str_priclass'];
            $arrayJson_chidren["PRISTATUT"] = $value['str_pristatut'];
            $arrayJson_chidren["PRIPRIORITY"] = $value['int_pripriority'];
            $arrayJson_chidren["PRICREATED"] = $value['dt_pricreated'];
            $arrayJson_chidren["PRIUPDATED"] = $value['dt_priupdated'];
            $arrayJson_chidren["PRIPARENTID"] = $value['lg_priparentid'];
            $arrayJson_chidren["PRIGROUPID"] = $value['lg_prigroupid'];
            $arrayJson_chidren["UTICREATEDID"] = $value['lg_uticreatedid'];
            $arrayJson_chidren["UTIUPDATEDID"] = $value['lg_utiupdatedid'];
            $OJson[] = $arrayJson_chidren;
        }
        $arrayJson["data"] = $OJson;
        $arrayJson['limit'] = (int) $LIMIT;
        $arrayJson['page'] = (int) $PAGE;
        $arrayJson['total'] = $result['total'];
    } else if ($mode === "showPrivileges") {
        $privileges = $ConfigurationManager->showAllOrOnePrivilege(null, 999999999, 1)["data"];
        $userPrivileges = $ConfigurationManager->showAllProfilPrivileges($LG_PROID);

        foreach ($privileges as $privilege) {
            if (in_array($privilege['lg_priid'], array_column($userPrivileges, 'lg_priid'))) {
                $arrayJson["data"][$privilege['str_priaction']] = true;
            } else {
                $arrayJson["data"][$privilege['str_priaction']] = false;
            }
        }
    } else if ($mode == "slugifyProductName") {
        $ConfigurationManager->slugifyProductName();
    } else if ($mode === "assignPrivilegesToProfil") {
        $result = $ConfigurationManager->assignPrivilegesToProfile($LG_PROID, $LG_PRIIDS);
        $arrayJson["data"]["failed"] = $result;
    } else if ($mode === "updatePrivilegesToProfil") {
        $privileges = $ConfigurationManager->showAllProfilPrivileges($LG_PROID);
        $listPrivileges = array_column($privileges, "lg_priid");

        $removePrivileges = array_diff($listPrivileges, $LG_PRIIDS);
        $addPrivileges = array_diff($LG_PRIIDS, $listPrivileges);

        if (count($removePrivileges) > 0) {
            $ConfigurationManager->removePrivilegesFromProfile($LG_PROID, $removePrivileges);
        }
        if (count($addPrivileges) > 0) {
            $ConfigurationManager->assignPrivilegesToProfile($LG_PROID, $addPrivileges);
        }
    } else if ($mode === "createListe") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $result = $ConfigurationManager->createListe($LG_TYLID, $STR_LSTDESCRIPTION, $STR_LSTVALUE, $OUtilisateur);
        $arrayJson["LG_LSTID"] = $result;
    } else if ($mode == "updateLastBackUpDate") {
        $ConfigurationManager->updateLastBackUpDate();
    } else if ($mode === "listProfilPrivileges") {
        $result = $ConfigurationManager->showAllProfilPrivileges($LG_PROID);

        foreach ($result as $value) {
            $arrayJson_chidren["PPRID"] = $value["lg_pprid"];
            $arrayJson_chidren["PRIID"] = $value["lg_priid"];
            $arrayJson_chidren["PRINAME"] = $value["str_priname"];
            $arrayJson_chidren["PRIDESCRIPTION"] = $value["str_pridescription"];
            $arrayJson_chidren["PRIPRIORITY"] = $value["int_pripriority"];
            $OJson[] = $arrayJson_chidren;
        }

        $arrayJson["data"] = $OJson;
    } else if ($mode == "listProfile") {
        $FILTER_OPTIONS = $_REQUEST['FILTER_OPTIONS'] ?? null;
        $result = $ConfigurationManager->showAllOrOneProfile($FILTER_OPTIONS, $LIMIT, $PAGE);
        foreach ($result["data"] as $value) {
            $liste = $ConfigurationManager->getListe($value['lg_lstid']);
            $arrayJson_chidren = array();
            $arrayJson_chidren["PROID"] = $value['lg_proid'];
            $arrayJson_chidren["PRONAME"] = $value['str_proname'];
            $arrayJson_chidren["PRODESCRIPTION"] = $value['str_prodescription'];
            $arrayJson_chidren["PROTYPE"] = (strtoupper($liste[0]['str_lstdescription']) == strtoupper(Parameters::$type_system) ? "Système" : "Standard");
            $OJson[] = $arrayJson_chidren;
        }
        if (count($OJson) > 0) {
            Parameters::buildSuccessMessage("Profils obtenus avec succès");
        } else {
            Parameters::buildSuccessMessage("Aucun profil obtenue");
        }


        $arrayJson["data"] = $OJson;
        $arrayJson["total"] = $result['total'];
        $arrayJson["limit"] = (int) $LIMIT;
        $arrayJson["page"] = (int) $PAGE;
    } else if ($mode == "resetPasswordUtilisateur") {
        $ConfigurationManager->resetPasswordUtilisateur($STR_UTIMAIL, $OUtilisateur);
    }

    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
    echo json_encode($arrayJson, JSON_PRETTY_PRINT);
}



