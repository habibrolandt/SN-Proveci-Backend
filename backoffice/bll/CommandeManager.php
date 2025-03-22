<?php

interface CommandeInterface
{

    public function createOrderExternal($LG_CLIID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $token);

    public function initCommande($LG_COMMID, $LG_AGEID, $STR_COMMNAME, $OUtilisateur);

    public function createCommande($LG_AGEID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $OUtilisateur, $token, $LG_COMMID = null);

    public function getLastCommandeByAgence($LG_AGEID, $STR_COMMSTATUT, $SEVERAL = false);

    public function getCommande($LG_COMMID);

    public function showAllOrOneCommande($search_value, $LG_CLIID, $start, $limit);

    public function totalCommande($search_value, $LG_CLIID);

    public function createOrderProduitExternal($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $token);

    public function initCommandeProduit($LG_CPRID, $LG_COMMID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur);

    public function createCommandeProduit($LG_COMMID, $LG_CLIID, $LG_AGEID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur, $token);

    public function updateCommandeProduit($LG_CPRID, $INT_CPRQUANTITY, $OUtilisateur, $token);

    public function updateOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $INT_CPRQUANTITY, $token);

    public function getCommandeProduit($LG_COMMID, $LG_PROID);

    public function getCommandeProduitLight($LG_CPRID);

    public function getCommandeLight($LG_COMMID);

    public function deleteOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $token);

    public function deleteCommandeProduit($LG_CPRID, $token);

    public function showAllOrOneCommandeproduit($LG_CLIID, $LG_COMMID, $token);

    public function showAllCommandeproduit($FILTERS_OPTIONS, $LIMIT, $PAGE, $ORDER_NOT_ON_LIVRAISON = false);

    public function getClientSolde($LG_CLIID);

    //moi
    public function handleCommande($LG_AGEID, $STR_COMMLIVADRESSE, $LG_ZONLIVID, $token, $OUtilisateur);

    public function getClientPlafond($LG_CLIID, $token = null);

    public function getExternalClientPanier($LG_CLIID, $LG_COMMID, $token = null);

    public function updateCommande($LG_COMMID, $DBL_COMMMTHT, $DBL_COMMMTTTC);

    public function getClientPanier($LG_AGEID);

    //moi
    public function getDeliveryPlace($FILTERS_OPTIONS, $LIMIT, $PAGE);

    //moi
    public function addDeleveryZone($STR_LSTVALUE, $STR_LSTDESCRIPTION, $OUtilisateur);

    //moi
    public function updateDeliveryPlace($LG_LSTID, $STR_LSTVALUE, $STR_LSTDESCRIPTION, $OUtilisateur);

    public function createDeliveryCalendar($STR_LIVNAME, $DT_LIVBEGIN, $DT_LIVEND, $LG_LSTID, $OUtilisateur);

    public function updateDeliveryCalendar($LG_LIVID, $STR_LIVNAME, $DT_LIVBEGIN, $DT_LIVEND, $LG_LSTID, $CMD_LIST = null, $OUtilisateur);

    public function deleteDeleveryDetails($LG_COMMID);

    public function showAllOrOneDeliveryCalendar($FILTER_OPTIONS, $LIMIT, $PAGE);

    public function deleteDeliveryPlace($LG_LSTID = null, $LIST_LSTID = null, $OUtilisateur);

    public function deleteDeliveryCalendar($LIST_LG_LIVID);

    public function closeDeliveryCalendar($LG_LIVID);

    public function getCalendarFrontOfiice();

    public function ExternalValidationCart($token = null, $LG_CLIID, $ADRFAC_ID, $STR_COMMLIVADRESSE, $LG_COMMID);

    public function listClientCommande($token = null, $LG_SOCID, $LG_AGEID);

    public function listProductByCommande($LG_COMMID);

    public function getClientCalendar($LG_AGEID = null);

    public function adminCartValidation($LG_COMMID, $STR_COMMLIVADRESSE, $LG_ZONLIVID, $token, $OUtilisateur);

    /**
     * return orders loading from 8sens
     */
    public function showAllOrOneOrderOrInvoice($FILTER_OPTIONS, $LIMIT, $PAGE, $TABLE);

    public function showAllOrdersByClientExternal($LG_CLIID);

    public function showAllInvoices($params_conditions);

    public function showAllOrOneInvoiceProducts($LG_CLIID, $LG_COMMID, $token = null);

    public function showAllOrders($params_conditions);
}

class CommandeManager implements CommandeInterface
{

    private $Commande = 'commande';
    private $Commproduit = 'commproduit';
    private $Agence = 'agence';
    private $Societe = 'societe';
    private $OCommande = array();
    private $OCommproduit = array();
    private $OAgence = array();

    private $Produit = "produit";

    private $dbconnexion;

    //constructeur de la classe
    private $Liste = "liste";
    private $Livraison = "livraison";

    private $OListe = array();
    private $DetailsLivraion = "liv_commande";

    private $ODetailsLivration = array();
    private $StatDevis = "stat_devis";

    private $StatInvoices = "stat_facture";

    public function __construct()
    {
        $this->dbconnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    public function createOrderExternal($LG_CLIID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $token)
    {
        $ConfigurationManager = new ConfigurationManager();
        $validation = "";
        try {
            $OClient = $ConfigurationManager->getClient($LG_CLIID, $token);
            if ($OClient == null) {
                return $validation;
            }

            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $OClient->CliID . "/carts";
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array(
                "ref" => $STR_COMMNAME,
                "adrfac_id" => $STR_COMMADRESSE,
                "adrliv_id" => $STR_LIVADRESSE
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

//            echo $response;
            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $validation = $obj->PcvID;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function initCommande($LG_COMMID, $LG_AGEID, $STR_COMMNAME, $OUtilisateur)
    {
        $validation = "";
        try {
            $params = array("lg_commid" => $LG_COMMID, "str_commname" => $STR_COMMNAME, "dt_commcreated" => get_now(), "str_commstatut" => Parameters::$statut_process,
                "lg_ageid" => $LG_AGEID, "lg_uticreatedid" => $OUtilisateur[0]['lg_utiid'], "lg_ageoriginid" => $LG_AGEID);
            //var_dump($params);
            if ($this->dbconnexion != null) {
                if (Persist($this->Commande, $params, $this->dbconnexion)) {
                    $validation = $LG_COMMID;
//                    Parameters::buildSuccessMessage("Société " . $STR_SOCDESCRIPTION . " effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de création de la commande");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de création de la commande. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function createCommande($LG_AGEID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $OUtilisateur, $token, $LG_COMMID = null)
    {
        $ConfigurationManager = new ConfigurationManager();
        $validation = array();
        try {

            $OAgence = $ConfigurationManager->getAgence($LG_AGEID);
            if ($OAgence == null) {
                return $validation;
            }

            if ($LG_COMMID == null) {
                $this->OCommande = $this->getLastCommandeByAgence($OAgence[0]["lg_ageid"], Parameters::$statut_process);
                if ($this->OCommande == null) {
                    $LG_COMMID = $this->createOrderExternal($OAgence[0]["lg_socextid"], $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $token);

                    $LG_COMMID = $this->initCommande($LG_COMMID, $OAgence[0]["lg_ageid"], $STR_COMMNAME, $OUtilisateur);
                } else {
                    $LG_COMMID = $this->OCommande[0][0];
                }
            }

            $validation["LG_COMMID"] = $LG_COMMID;
            $validation["LG_CLIID"] = $OAgence[0]["lg_socextid"];
//            var_dump($validation);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getLastCommandeByAgence($LG_AGEID, $STR_COMMSTATUT, $SEVERAL = false)
    {
        $arraySql = array();
        try {
            $query = "
                SELECT t.*, s.lg_socextid, s.lg_socid, s.dbl_socplafond, l.str_lstvalue 
                FROM commande t
                JOIN agence a ON t.lg_ageid = a.lg_ageid
                JOIN societe s ON a.lg_socid = s.lg_socid
                JOIN liste l ON s.lg_lstpayid = l.lg_lstid
                WHERE t.lg_ageid = :LG_AGEID 
                AND t.str_commstatut = :STR_STATUT 
                ORDER BY t.dt_commupdated " . (!$SEVERAL ? "DESC LIMIT " . Parameters::$PROCESS_SUCCESS : "") . ";
            ";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_AGEID" => $LG_AGEID, "STR_STATUT" => $STR_COMMSTATUT));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function getCommandeLight($LG_COMMID)
    {
        $validation = null;
        Parameters::buildSuccessMessage("Commande recuperée avec succès");
        try {
            $query = "
                SELECT *
                FROM commande t
                  JOIN agence a ON t.lg_ageid = a.lg_ageid
                JOIN societe s ON a.lg_socid = s.lg_socid
                WHERE t.lg_commid = :LG_COMMID
            ";
            $res = $this->dbconnexion->prepare($query);
            $res->execute(array("LG_COMMID" => $LG_COMMID));
            $validation = $res->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function getCommande($LG_COMMID)
    {
        $validation = null;
        Parameters::buildSuccessMessage("Commande recuperée avec succès");
        try {
            $qery = "
                SELECT t.*, s.*, l.str_lstdescription, pays.str_lstvalue as str_pays, zone_livraison.lg_lstid as zone_livraison, t.str_commlivadresse as str_livadresse
                FROM commande t
                JOIN agence a ON t.lg_ageid = a.lg_ageid
                JOIN societe s ON a.lg_socid = s.lg_socid
                JOIN liste l ON s.lg_lsttypesocid = l.lg_lstid
                JOIN liste pays ON s.lg_lstpayid = pays.lg_lstid
                JOIN liste zone_livraison ON t.lg_lstid = zone_livraison.lg_lstid
                WHERE t.lg_commid = :LG_COMMID
            ";
            $res = $this->dbconnexion->prepare($qery);
            $res->execute(array("LG_COMMID" => $LG_COMMID));
            $validation = $res->fetch(PDO::FETCH_ASSOC);
//            $params_condition = array("lg_commid" => $LG_COMMID, "str_commname" => $LG_COMMID);
//            $validation = $this->OCommande = Find($this->Commande, $params_condition, $this->dbconnexion, "OR");
//            if ($this->OCommande == null) {
//                Parameters::buildErrorMessage("Numero de commande inconnu");
//                return $validation;
//            }
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function showAllOrOneCommande($search_value, $LG_CLIID, $start, $limit)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        $token = "";
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function totalCommande($search_value, $LG_AGEID)
    {

    }

    public function createCommandeProduit($LG_COMMID, $LG_CLIID, $LG_AGEID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur, $token)
    {
        //$ConfigurationManager = new ConfigurationManager();
        $validation = "";
        $LG_CPRID = "";
        $StockManager = new StockManager();
        try {
            $this->OCommproduit = $this->getCommandeProduit($LG_COMMID, $LG_PROID);

            if ($this->OCommproduit == null) {
                $ArtStk = (float)$StockManager->getProductRemote($LG_PROID, $token)->products[0]->ArtStk;
                if ($INT_CPRQUANTITY > $ArtStk) {
                    Parameters::buildErrorMessage("Echec d'ajout du produit a la commande. La quantité demandé dépasse le stock");
                    return ["true_pro_qty" => $ArtStk];
                }
                $LG_CPRID = $this->createOrderProduitExternal($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $token);


                //echo "====".$LG_CPRID."++++";
                if ($LG_CPRID == "") {
                    Parameters::buildErrorMessage("Echec d'ajout du produit a la commande. Une erreur est survenu sur votre commande");
                    return $validation;
                }
                $validation = $this->initCommandeProduit($LG_CPRID, $LG_COMMID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur);
            } else {
                $LG_CPRID = $this->OCommproduit[0][0];
                $LG_CPRID = $this->updateCommandeProduit($LG_CPRID, (int)$this->OCommproduit[0]["int_cprquantity"] + (int)$INT_CPRQUANTITY, $OUtilisateur, $token);
                $validation = $LG_CPRID;

                //TODO: A faire
//                $PanierClient = $this->getExternalClientPanier($LG_AGEID, $LG_COMMID, $token);
////                var_dump($PanierClient);
//                $this->updateCommande($LG_COMMID, $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);
            }


            $PanierClient = $this->getExternalClientPanier($LG_AGEID, $LG_COMMID, $token);
            $this->updateCommande($LG_COMMID, $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function createOrderProduitExternal($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $token)
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines";
//            var_dump($url);

            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array(
                "art_id" => $LG_PROID,
                "qty" => $INT_CPRQUANTITY
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

//            echo $response;
            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $validation = $obj->PlvID != null ? $obj->PlvID : "";
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getCommandeProduit($LG_COMMID, $LG_PROID)
    {
        $validation = null;
        try {
            $params_condition = array("lg_commid" => $LG_COMMID, "lg_proid" => $LG_PROID);
            $validation = $this->OCommproduit = Find($this->Commproduit, $params_condition, $this->dbconnexion);
            if ($this->OCommproduit == null) {
                return $validation;
            }
            $validation = $this->OCommproduit;
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function getCommandeProduitLight($LG_CPRID)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.*, s.lg_socextid, a.lg_ageid FROM " . $this->Commproduit . " t, " . $this->Commande . " c, " . $this->Agence . " a, " . $this->Societe . " s WHERE t.lg_commid = c.lg_commid and c.lg_ageid = a.lg_ageid and a.lg_socid = s.lg_socid and t.lg_cprid = :LG_CPRID";
            $res = $this->dbconnexion->prepare($query);
            $res->execute(array("LG_CPRID" => $LG_CPRID));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function initCommandeProduit($LG_CPRID, $LG_COMMID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur)
    {
        $validation = "";
        try {
            $params = array("lg_cprid" => $LG_CPRID, "lg_commid" => $LG_COMMID, "lg_proid" => $LG_PROID, "dt_cprcreated" => get_now(), "str_cprstatut" => Parameters::$statut_process,
                "int_cprquantity" => $INT_CPRQUANTITY, "lg_uticreatedid" => $OUtilisateur[0][0]);
//            var_dump($params);
            if ($this->dbconnexion != null) {
                if (Persist($this->Commproduit, $params, $this->dbconnexion)) {
                    $validation = $LG_CPRID;
                    Parameters::buildSuccessMessage("Produit ajouté avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'ajout du produit à la commande");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec d'ajout du produit à la commande. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateCommandeProduit($LG_CPRID, $INT_CPRQUANTITY, $OUtilisateur, $token)
    {
        $validation = [];
        $ArtStk = 0;
        $OProduit = array();
        $StockManager = new StockManager();
        try {
            $this->OCommproduit = $this->getCommandeProduitLight($LG_CPRID);//
            if ($this->OCommproduit == null) {
                Parameters::buildErrorMessage("Echec de mise à jour du produit. Référence inexistante sur la commande");
                return false;
            }
            $OProduit = $StockManager->getProductRemote($this->OCommproduit[0]['lg_proid'], $token)->products;

            $ArtStk = $OProduit != null ? (float)$OProduit[0]->ArtStk : $ArtStk;
            if ($INT_CPRQUANTITY > $ArtStk) {
                Parameters::buildErrorMessage("Echec de mise à de la quantité du produit. La quantité voulue dépasse le stock");
                return false;
            }

            if ($this->updateOrderProduitExternal($LG_CPRID, $this->OCommproduit[0]["lg_commid"], $this->OCommproduit[0]["lg_socextid"], $INT_CPRQUANTITY, $token) == "") {
                Parameters::buildErrorMessage("Echec de mise à de la quantité du produit. Veuillez réessayer svp!");
                return false;
            }

            $params_condition = array("lg_cprid" => $this->OCommproduit[0][0]);
            $params_to_update = array("int_cprquantity" => $INT_CPRQUANTITY, "dt_cprupdated" => get_now(), "lg_utiupdateid" => $OUtilisateur[0][0]);

            if ($this->dbconnexion != null) {
                if (Merge($this->Commproduit, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation["lg_cprid"] = $this->OCommproduit[0]["lg_cprid"];
                    $PanierClient = $this->getExternalClientPanier($this->OCommproduit[0]["lg_ageid"], $this->OCommproduit[0]["lg_commid"], $token);

                    $validation = array_merge($validation, ["PcvMtHT" => $PanierClient->pieces[0]->PcvMtHT, "PcvMtTTC" => $PanierClient->pieces[0]->PcvMtTTC]);

                    $this->updateCommande($this->OCommproduit[0]["lg_commid"], $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);
                    Parameters::buildSuccessMessage("Mise à jour avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de mise à jour du produit");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise à jour du produit. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $INT_CPRQUANTITY, $token)
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines/" . $LG_CPRID;
            //echo $url;
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array(
                "qty" => $INT_CPRQUANTITY
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Méthode HTTP PUT
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

//            echo $response;
            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            //var_dump($obj->qty);

            $validation = $obj->qty;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteCommandeProduit($LG_CPRID, $token)
    {
        $validation = [];
        try {
            $this->OCommproduit = $this->getCommandeProduitLight($LG_CPRID);
//            var_dump($this->OCommproduit);
            if ($this->OCommproduit == null) {
                Parameters::buildErrorMessage("Echec de suppression du produit. Référence inexistante sur la commande");
                return false;
            }
            $this->deleteOrderProduitExternal($this->OCommproduit[0][0], $this->OCommproduit[0]["lg_commid"], $this->OCommproduit[0]["lg_socextid"], $token);

            //Mise à jour de la commande chez nous
            $PanierClient = $this->getExternalClientPanier($this->OCommproduit[0]["lg_ageid"], $this->OCommproduit[0]["lg_commid"], $token);
            $validation = array_merge($validation, ["PcvMtHT" => $PanierClient->pieces[0]->PcvMtHT, "PcvMtTTC" => $PanierClient->pieces[0]->PcvMtTTC]);
            $this->updateCommande($this->OCommproduit[0]["lg_commid"], !empty($PanierClient->pieces[0]->PcvMtHT) ? $PanierClient->pieces[0]->PcvMtHT : 0, !empty($PanierClient->pieces[0]->PcvMtTTC) ? $PanierClient->pieces[0]->PcvMtTTC : 0);

            $params = array("lg_cprid" => $this->OCommproduit[0][0]);
            if (Remove($this->Commproduit, $params, $this->dbconnexion)) {
                $validation['lg_commid'] = $this->OCommproduit[0]["lg_commid"];
                Parameters::buildSuccessMessage("Suppression du produit avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression du produit");
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression du produit. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $token)
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines/" . $LG_CPRID;

            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array();

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Méthode HTTP PUT
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

//            echo $response;
            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $validation = $obj->PlvID;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneCommandeproduit($LG_CLIID, $LG_COMMID, $token)
    {
        $arraySql = array();
        try {
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllOrOneInvoiceProducts($LG_CLIID, $LG_COMMID, $token = null)
    {
        $arraySql = array();
        $ConfigurationManager = new ConfigurationManager();
        try {
            $token = $token ?? $ConfigurationManager->generateToken();
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/invoices/" . $LG_COMMID . "/lines";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllCommandeproduit($FILTERS_OPTIONS, $LIMIT, $PAGE, $ORDER_NOT_ON_LIVRAISON = false)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "soc.*, age.*, com.*, det.*, com.lg_commid";
        Parameters::buildSuccessMessage("Commandes obtenues avec succès .");
        try {
            if (!empty($FILTERS_OPTIONS)) {
                $query = "
                    SELECT $select
                    FROM " . $this->Commande . " com 
                    INNER JOIN " . $this->Agence . " age ON com.lg_ageid = age.lg_ageid
                    INNER JOIN " . $this->Societe . " as soc ON soc.lg_socid = age.lg_socid 
                    LEFT JOIN " . $this->DetailsLivraion . " as det ON det.lg_commid = com.lg_commid
                    WHERE ";

                if ($ORDER_NOT_ON_LIVRAISON) {
                    $WHERE[] = "det.lg_livcommid IS NULL";
                }

                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "dt_order") {
                        $WHERE[] = "com.dt_commupdated BETWEEN ? AND ? ";
                    }
                    if ($key == "str_commstatut") {
                        $WHERE[] = "com.str_commstatut = ?";
                    }
                    if ($key == "search") {
                        $WHERE[] = "(soc.str_socname LIKE ? OR com.lg_commid LIKE ? )";
                    }
                    if ($key == "lg_livcommid") {
                        $WHERE[] = "det.lg_livid = ?";
                    }
                }

                $WHERE[] = "com.str_commstatut != 'process'";
                $query .= implode(" AND ", $WHERE);
                $query .= " ORDER BY dt_commcreated DESC";

                $params = [];

                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "dt_order") {
                        $params[] = $value[0];
                        $params[] = $value[1];
                    }
                    if ($key == "search") {
                        for ($i = 0; $i < 2; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    }
                    if ($key == "lg_livcommid") {
                        $params[] = $value;
                    }
                    if ($key == "str_commstatut") {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "
                    SELECT soc.*, age.*, com.*, det.*, com.lg_commid
                    FROM " . $this->Commande . " com 
                    INNER JOIN " . $this->Agence . " age ON com.lg_ageid = age.lg_ageid
                    INNER JOIN " . $this->Societe . " as soc ON soc.lg_socid = age.lg_socid 
                    LEFT JOIN " . $this->DetailsLivraion . " as det ON det.lg_commid = com.lg_commid
                    WHERE com.str_commstatut != 'process'
                    " . ($ORDER_NOT_ON_LIVRAISON ? "AND det.lg_livcommid IS NULL " : "") . "
                    ORDER BY dt_commcreated DESC";

            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
//            $params[] = Parameters::$statut_enable;
//            var_dump($query);
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);


            $newSelect = "COUNT(*) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

            // Requête externe pour récupérer une information supplémentaire
//            foreach ($arraySql as $cliid => &$commandes) {
//                $data = $this->getClientSolde($commandes['lg_socextid']);
//                $commandes['clientEncours'] = "";
//                if (property_exists($data, "clisolde")) {
//                    $commandes['clientEncours'] = $data->clisolde;
//                }
////                $externalInfo = $this->getClientSolde($commandes['lg_socextid'])->clisolde;
////                $commandes['clientEncours'] = $externalInfo;
//            }
            if (count($arraySql) == 0) {
                Parameters::buildSuccessMessage("Aucune commande trouvée");
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        return ["data" => $arraySql, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function getClientSolde($LG_CLIID)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        $token = "";
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/encours";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function handleCommande($LG_AGEID, $STR_COMMLIVADRESSE, $LG_ZONLIVID, $token, $OUtilisateur)
    {
        $validation = false;
        $mTTC = 0;
        $encours = 0;
        $plafond = 0;
        $list = array();

        try {
            $ConfigurationManager = new ConfigurationManager();
            $list = $ConfigurationManager->getListe($LG_ZONLIVID);
            if ($list == null) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Zone de livraison inexistante");
                return $validation;
            }

            $this->OCommande = $this->getLastCommandeByAgence($LG_AGEID, Parameters::$statut_process);
            if ($this->OCommande == null) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Commande innexistante");
                return $validation;
            }
            $LG_CLIID = $this->OCommande[0]["lg_socextid"];
            $mTTC = $this->getClientPanier($LG_AGEID)['dbl_commmtttc'];
            $encours = $this->getClientSolde($LG_CLIID)->clisolde;
            $plafond = $this->OCommande[0]["dbl_socplafond"];

            if ($mTTC == null) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Veuillez ajouter des produits à la commande");
                return $validation;
            } else if (($mTTC + ($encours != null || $encours != "" ?: 0) > $plafond)) {
                $params_condition = array("lg_commid" => $this->OCommande[0][0]);
                $params_to_update = array("str_commstatut" => Parameters::$statut_waiting, "dt_commupdated" => get_now(), "lg_lstid" => $list[0]['lg_lstid'], "lg_utiupdatedid" => $OUtilisateur[0]["lg_utiid"] ?: 1, "str_commlivadresse" => $STR_COMMLIVADRESSE);

                if ($this->dbconnexion != null) {
                    if (Merge($this->Commande, $params_to_update, $params_condition, $this->dbconnexion)) {
                        $validation = true;
                        Parameters::buildSuccessMessage("Commande prise en charge. Veuillez nous contactez pour la gestion du payement.");
                    } else {
                        Parameters::buildErrorMessage("Echec de l'opération");
                    }
                }
            } else {
                $res = $this->ExternalValidationCart($token, $LG_CLIID, $this->OCommande[0]["str_lstvalue"], $STR_COMMLIVADRESSE, $this->OCommande[0]["lg_commid"]);
                if (property_exists($res, "error")) {
                    Parameters::buildErrorMessage("Echec de validation de la commande. Veuillez réessayer svp!");
                    return $validation;
                }

                $params_condition = array("lg_commid" => $this->OCommande[0][0]);
                $params_to_update = array("str_commstatut" => Parameters::$statut_closed, "dt_commupdated" => get_now(), "lg_lstid" => $list[0]['lg_lstid'], "lg_utiupdatedid" => $OUtilisateur[0]["lg_utiid"] ?: 1, "str_commlivadresse" => $STR_COMMLIVADRESSE, "PcvIDExt" => $res->PcvID);


                if ($this->dbconnexion != null) {
                    if (Merge($this->Commande, $params_to_update, $params_condition, $this->dbconnexion)) {
                        $validation = true;
                        Parameters::buildSuccessMessage("Mise à jour de la commande effectuée avec succès.");
                    } else {
                        Parameters::buildErrorMessage("Echec de l'opération");
                    }
                }

            }

        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function adminCartValidation($LG_COMMID, $STR_COMMLIVADRESSE, $LG_ZONLIVID, $token, $OUtilisateur)
    {
        $validation = false;
        try {
            $ConfigurationManager = new ConfigurationManager();
            $list = $ConfigurationManager->getListe($LG_ZONLIVID);
            if ($list == null) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Zone de livraison inexistante");
                return false;
            }

            $this->OCommande = $this->getCommande($LG_COMMID);
            if ($this->OCommande == null) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Commande innexistante");
                return false;
            }
            $LG_CLIID = $this->OCommande["lg_socextid"];


            $res = $this->ExternalValidationCart($token, $LG_CLIID, $this->OCommande["str_pays"], $STR_COMMLIVADRESSE, $this->OCommande["lg_commid"]);
            if (property_exists($res, "error")) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Veuillez réessayer svp!");
                return $validation;
            }

            $params_condition = array("lg_commid" => $LG_COMMID);
            $params_to_update = array("str_commstatut" => Parameters::$statut_closed, "dt_commupdated" => get_now(), "lg_lstid" => $list[0]['lg_lstid'], "lg_utiupdatedid" => $OUtilisateur[0]["lg_utiid"] ?: 1, "str_commlivadresse" => $STR_COMMLIVADRESSE);

            if ($this->dbconnexion != null) {
                if (Merge($this->Commande, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Mise à jour de la commande effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération");
                }
            }
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function ExternalValidationCart($token = null, $LG_CLIID, $ADRFAC_ID, $STR_COMMLIVADRESSE, $LG_COMMID)
    {
        $ConfigurationManager = new ConfigurationManager();
        $token = $token ?: $ConfigurationManager->generateToken();

        try {
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID;
//            var_dump($url);
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            $data = array(
                'payment_type' => "COMPTANT",
                'adrfac_id' => $ADRFAC_ID,
                "adrliv_id" => $STR_COMMLIVADRESSE,
                "exped" => "-",
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }


        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }

        return $obj;

    }


    public function getClientPlafond($LG_CLIID, $token = null)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        Parameters::buildSuccessMessage("Plafond obtenu avec succès . ");
        try {
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "?nb_by_page=10&page=1&ColSuppl=CliPlaf";
            $token = $token ?: $ConfigurationManager->generateToken();
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }
            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $arraySql;
    }

    public function getExternalClientPanier($LG_CLIID, $LG_COMMID, $token = null)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        try {
//            $value = $this->getLastCommandeByAgence($LG_CLIID, Parameters::$statut_process);
//            if ($value == null) {
//                Parameters::buildErrorMessage("Le client n'a pas de panier en cours");
//                return ["erreur" => "Le client n'as pas de panier en cours"];
//            }
            $value = $this->getCommandeLight($LG_COMMID);
            if ($value == null) {
                Parameters::buildErrorMessage("Le client n'a pas de panier en cours");
                return ["erreur" => "Le client n'as pas de panier en cours"];
            }

            $LG_CLIID = $value['lg_socextid'];
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID;

            $token = $token ?: $ConfigurationManager->generateToken();
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);


// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }
            $arraySql = $obj;
//            var_dump((int)$arraySql->pieces[0]->PcvMtHT);

        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }

        return $arraySql;
    }


    public function updateCommande($LG_COMMID, $DBL_COMMMTHT, $DBL_COMMMTTTC)
    {
        $validation = "";
        Parameters::buildSuccessMessage("Mise à jour de la commande effectuée avec succès . ");
        try {
            $params_condition = array("lg_commid" => $LG_COMMID);
            $params_to_update = array("dbl_commmtttc" => $DBL_COMMMTTTC, "dbl_commmtht" => $DBL_COMMMTHT, "dt_commupdated" => get_now());


            if ($this->dbconnexion != null) {
                if (Merge($this->Commande, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = $LG_COMMID;
                    Parameters::buildSuccessMessage("Mise à jour de la commande effectuée avec succès . ");
                    return $validation;
                } else {
                    Parameters::buildErrorMessage("Echec de la mise à jour de la commande");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getClientPanier($LG_AGEID)
    {
        $validation = array();
        try {
            $value = $this->getLastCommandeByAgence($LG_AGEID, Parameters::$statut_process);
            if (empty($value)) {
                Parameters::buildErrorMessage("Aucun panier ouvert");
                return $validation;
            }

            $query = "SELECT * FROM " . $this->Commande . " c INNER JOIN " . $this->Commproduit . " cp ON c.lg_commid = cp.lg_commid INNER JOIN " . $this->Produit . " p ON cp.lg_proid = p.lg_proid  WHERE c.lg_commid = :LG_COMMID";
            $res = $this->dbconnexion->prepare($query);
            $res->execute(array("LG_COMMID" => $value[0]['lg_commid']));
            $panier = array();
            while ($rowObj = $res->fetch(PDO::FETCH_ASSOC)) {
                if (empty($panier)) {
                    $panier = [
                        'lg_commid' => $rowObj['lg_commid'],
                        'str_commname' => $rowObj['str_commname'],
                        'dt_commcreated' => $rowObj['dt_commcreated'],
                        'dt_commupdated' => $rowObj['dt_commupdated'],
                        'str_commstatut' => $rowObj['str_commstatut'],
                        'lg_ageid' => $rowObj['lg_ageid'],
                        'lg_uticreatedid' => $rowObj['lg_uticreatedid'],
                        'lg_ageoriginid' => $rowObj['lg_ageoriginid'],
                        'dbl_commmtht' => $rowObj['dbl_commmtht'],
                        'dbl_commmtttc' => $rowObj['dbl_commmtttc'],
                    ];
                }
                $panier['produits'][] = [
                    'lg_cprid' => $rowObj['lg_cprid'],
                    'lg_proid' => $rowObj['lg_proid'],
                    'PlvQteUV' => $rowObj['int_cprquantity'],
                    'PlvCode' => $rowObj['str_proname'],
                    'PlvLib' => $rowObj['str_prodescription'],
                    'PlvPUNet' => $rowObj['int_propricevente'],
                    'str_procateg' => $rowObj['str_procateg'],
                    'str_profamille' => $rowObj['str_profamille'],
                    'str_progamme' => $rowObj['str_progamme'],
                    'str_propic' => $rowObj['str_propic'],
                ];
            }
            $validation = $panier;
            $res->closeCursor();

        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $validation;
    }

    public function getDeliveryPlace($FILTERS_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";
        Parameters::buildSuccessMessage("Liste des zones de livraison obtenue avec succès");

        try {
            if (!empty($FILTERS_OPTIONS)) {
                $query = "SELECT * FROM " . $this->Liste . " l WHERE ";
                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "search") {
                        $WHERE[] = "(l.str_lstdescription LIKE ? OR l.str_lstvalue LIKE ? )";
                    }
                }

                $WHERE[] = "lg_tylid = ?";
                $WHERE[] = "l.str_lststatut = ?";
                $query .= implode(" AND ", $WHERE);
                $query .= " ORDER BY l.str_lstdescription";

                $params = [];

                foreach ($FILTERS_OPTIONS as $key => $value) {
                    for ($i = 0; $i < 2; $i++) {
                        $params[] = "%" . $value . "%";
                    }
                }

            } else {
                $query = "SELECT * FROM " . $this->Liste . " t WHERE lg_tylid = ? AND str_lststatut = ? ORDER BY str_lstdescription";
            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = "7";
            $params[] = Parameters::$statut_enable;
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if (!$arraySql) {
                Parameters::buildSuccessMessage("Aucune demandes trouvées");
                $arraySql = [];
            }

            $newSelect = "COUNT(*) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $exc) {
            var_dump($exc->getMessage());
            Parameters::buildErrorMessage("Echec de la recuperation des zones de livraisons");
        }
        if (empty($arraySql)) {
            Parameters::buildSuccessMessage("Aucune zone de livraison trouvée");
        }
        return ["data" => $arraySql, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function addDeleveryZone($STR_LSTVALUE, $STR_LSTDESCRIPTION, $OUtilisateur)
    {
        $newZone = null;
        try {
            $params = array("lg_lstid" => generateRandomNumber(),
                "str_lstdescription" => $STR_LSTDESCRIPTION, "str_lstvalue" => $STR_LSTVALUE, "str_lststatut" => Parameters::$statut_enable, "dt_lstcreated" => get_now(),
                "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "lg_tylid" => "7");

            if ($this->dbconnexion !== null) {
                if (Persist($this->Liste, $params, $this->dbconnexion)) {
                    $newZone = $params;
                    Parameters::buildSuccessMessage("Zone de livraison enregistré avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de l'insertion de la nouvelle zone, veuillez contacter votre administrateur");
                }
            }

            $query = "
                SELECT COUNT(*) count 
                FROM " . $this->Liste . " l 
                WHERE l.lg_tylid = ? AND l.str_lststatut = ?
            ";
            $params = ["7", Parameters::$statut_enable];
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            Parameters::buildErrorMessage("Echec de l'insertion de la nouvelle zone, veuillez contacter votre administrateur");
        }

        return ["data" => $newZone, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function updateDeliveryPlace($LG_LSTID, $STR_LSTVALUE, $STR_LSTDESCRIPTION, $OUtilisateur)
    {
        $validation = false;
        try {
            $params_condition = array("lg_lstid" => $LG_LSTID);
            $params_to_update = array(
                "str_lstdescription" => $STR_LSTDESCRIPTION,
                "str_lstvalue" => $STR_LSTVALUE,
                "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId,
                "dt_lstupdated" => get_now()
            );
            if ($this->dbconnexion != null) {
                if (Merge($this->Liste, $params_to_update, $params_condition, $this->dbconnexion)) {
                    Parameters::buildSuccessMessage("Zone de livraison  mis à jour");
                    $validation = true;
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération, la mise à jour a echoué");
                }
            }
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            Parameters::buildErrorMessage("Impossible de mettre la zone de livraison à jour");
        }

        return $validation;
    }

    public function deleteDeliveryPlace($LG_LSTID = null, $LIST_LSTID = null, $OUtilisateur)
    {
        $validation = false;
        try {
            if ($LIST_LSTID) {
                $LIST_LSTID = json_decode($LIST_LSTID);
                foreach ($LIST_LSTID as $id) {
                    $params_condition = array("lg_lstid" => $id);
                    $params_to_update = array(
                        "str_lststatut" => Parameters::$statut_delete,
                        "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId,
                        "dt_lstupdated" => get_now()
                    );
                    if ($this->dbconnexion != null) {
                        if (Merge($this->Liste, $params_to_update, $params_condition, $this->dbconnexion)) {
                            Parameters::buildSuccessMessage("Suppression de la zone de livraison effectué avec succès");
                            $validation = true;
                        } else {
                            Parameters::buildErrorMessage("Echec de suppression de la zone de livraison");
                        }
                    }
                }
            }

            if ($LG_LSTID) {
                $params_condition = array("lg_lstid" => $LG_LSTID);
                $params_to_update = array(
                    "str_lststatut" => Parameters::$statut_delete,
                    "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId,
                    "dt_lstupdated" => get_now()
                );
                if ($this->dbconnexion != null) {
                    if (Merge($this->Liste, $params_to_update, $params_condition, $this->dbconnexion)) {
                        Parameters::buildSuccessMessage("Suppression de la zone de livraison effectué avec succès");
                        $validation = true;
                    } else {
                        Parameters::buildErrorMessage("Echec de suppression de la zone de livraison");
                    }
                }
            }

            $query = "
                SELECT COUNT(*) count 
                FROM " . $this->Liste . " l 
                WHERE l.lg_tylid = ? AND l.str_lststatut = ?
            ";
            $params = ["7", Parameters::$statut_enable];
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            Parameters::buildErrorMessage("Impossible de supprimer les éléments");
        }

        return ["status" => $validation, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function createDeliveryCalendar($STR_LIVNAME, $DT_LIVBEGIN, $DT_LIVEND, $LG_LSTID, $OUtilisateur)
    {
        $validation = "";
        try {
            $ConfigurationManager = new ConfigurationManager();
            $this->OListe = $ConfigurationManager->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("La zone de livraison choisie n'existe pas. Veuillez contacter votre administrateur");
                return $validation;
            }
            $LG_CALLIVID = generateRandomNumber();
            $params = array(
                "lg_livid" => $LG_CALLIVID,
                "str_livname" => $STR_LIVNAME,
                "lg_lstid" => $this->OListe[0]['lg_lstid'],
                "dt_livbegin" => $DT_LIVBEGIN,
                "dt_livend" => $DT_LIVEND,
                "dt_livcreated" => get_now(),
                "str_livstatut" => Parameters::$statut_enable
            );
            if ($this->dbconnexion != null) {
                if (Persist($this->Livraison, $params, $this->dbconnexion)) {
                    $validation = $LG_CALLIVID;
                    Parameters::buildSuccessMessage("Calendrier de livraison créé avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de la création du calendrier");
                }
            }

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            Parameters::buildErrorMessage("Echec de la creation du calendrier, veuillez contactez votre admin");
        }
        return $validation;
    }


    public function showAllOrOneDeliveryCalendar($FILTER_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";
        Parameters::buildErrorMessage("Echec de la recuperation du calendrier de livraison");
        try {
            if (!empty($FILTER_OPTIONS)) {
                $query = "
                    SELECT $select,
                        lst.str_lstvalue as 'zone',
                        lst.lg_lstid as 'zone_id',
                        GROUP_CONCAT(dl.lg_commid SEPARATOR ', ') as 'commandes',
                        COUNT(dl.lg_livid) as cmd_count
                    FROM " . $this->Livraison . " cl 
                    LEFT JOIN " . $this->DetailsLivraion . " dl ON cl.lg_livid = dl.lg_livid 
                    INNER JOIN " . $this->Liste . " lst ON cl.lg_lstid = lst.lg_lstid
                    WHERE ";
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key == "search") {
                        $WHERE[] = "(lst.str_lstdescription LIKE ? OR lst.str_lstvalue LIKE ?  )";
                    }
                    if ($key == "dt_livbegin" or $key == "dt_livend") {
                        $WHERE[] = "cl." . $key . " BETWEEN ? AND ? ";
                    }
                    if ($key == "nb_order") {
                        $WHERE[] = "cmd_count = ?";
                    }
                    if ($key == "lg_livid") {
                        $WHERE[] = "cl.lg_livid = ?";
                    }
                }

                $WHERE[] = "cl.str_livstatut IN (?, ?)";
                $query .= implode(" AND ", $WHERE);
                $query .= " GROUP BY cl.lg_livid";

                $params = [];

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key == "dt_livbegin" or $key == "dt_livend") {
                        $params[] = $value[0];
                        $params[] = $value[1];
                    }
                    if ($key == "search") {
                        for ($i = 0; $i < 2; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    }
                    if ($key == "nb_order") {
                        $params[] = $value;
                    }
                    if ($key == "lg_livid") {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "SELECT $select,
                        lst.str_lstvalue as 'zone',
                        lst.lg_lstid as 'zone_id',
                        GROUP_CONCAT(dl.lg_commid SEPARATOR ', ') as 'commandes',
                        COUNT(dl.lg_livid) as cmd_count
                    FROM " . $this->Livraison . " cl
                    LEFT JOIN " . $this->DetailsLivraion . " dl ON cl.lg_livid = dl.lg_livid
                    INNER JOIN " . $this->Liste . " lst ON cl.lg_lstid = lst.lg_lstid
                    WHERE cl.str_livstatut IN (?,?)
                    GROUP BY cl.lg_livid";
            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = Parameters::$statut_closed;
            $params[] = Parameters::$statut_enable;
//            var_dump($query);
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);


            $newSelect = "COUNT(DISTINCT lst.str_lstvalue) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
//            var_dump($queryCount);
            $res = $this->dbconnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
            Parameters::buildErrorMessage("Echec de la recuperation du calendrier de livraison");
        }
        return ["data" => $arraySql, "total" => $count[0]["count"] == null ? 0 : ($count[0]["count"] / 2)];
    }

    public function updateDeliveryCalendar($LG_LIVID, $STR_LIVNAME, $DT_LIVBEGIN, $DT_LIVEND, $LG_LSTID, $CMD_LIST = null, $OUtilisateur)
    {
        $validation = false;
        try {
            $ConfigurationManager = new ConfigurationManager();
            $this->OListe = $ConfigurationManager->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("La zone de livraison choisie n'existe pas. Veuillez contacter votre administrateur");
                return $validation;
            }
            $params_condition = array("lg_livid" => $LG_LIVID);
            $params_to_update = array(
                "str_livname" => $STR_LIVNAME,
                "dt_livbegin" => $DT_LIVBEGIN,
                "dt_livend" => $DT_LIVEND,
                "lg_lstid" => $this->OListe[0]['lg_lstid'],
                "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId,
                "dt_livupdated" => get_now()
            );
            if ($this->dbconnexion != null) {
                if (Merge($this->Livraison, $params_to_update, $params_condition, $this->dbconnexion)) {
                    Parameters::buildSuccessMessage("Information du calendrier de livraison  mis à jour");
                    $validation = true;
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération, la mise à jour des informations a echoué");
                }
            }

            if (!empty($CMD_LIST)) {
                //recuperer les commandes liées au calendrier
                $response = $this->showAllOrOneDeliveryCalendar($LG_LIVID);
                if ($response) {
                    $commandes = explode(", ", $response[0]['commandes']);
                    $CMD_LIST = json_decode($CMD_LIST);
                    $toDelete = array_diff($commandes, $CMD_LIST);
                    $toAdd = array_diff($CMD_LIST, $commandes);
                    if (empty($toDelete) && empty($toAdd)) {
                        Parameters::buildSuccessMessage("Aucune modification à apporter");
                        return $validation;
                    }
                    foreach ($toDelete as $id) {
                        $this->deleteDeleveryDetails($id);
                    }
                    foreach ($toAdd as $id) {
                        $this->createDeliveryDetails($LG_LIVID, $id, $OUtilisateur);
                    }
                }
            }
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }

        return $validation;
    }

    public function deleteDeliveryCalendar($LIST_LG_LIVID)
    {
        $validation = false;
        try {
            $LIST_LG_LIVID = json_decode($LIST_LG_LIVID);
            foreach ($LIST_LG_LIVID as $LG_LIVID) {
                $params_condition = array("lg_livid" => $LG_LIVID);
                $params_to_update = array("str_livstatut" => Parameters::$statut_delete, "dt_livupdated" => get_now());
                if (Merge($this->Livraison, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Suppression du calendrier de livraison effectué avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de suppression du calendrier de livraison");
                }
            }

            $query = "
                SELECT COUNT(*) count
                FROM livraison cl 
                WHERE cl.str_livstatut IN (?,?)
            ";
            $params[] = Parameters::$statut_closed;
            $params[] = Parameters::$statut_enable;
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de suppression du calendrier de livraison");
        }
        return ["status" => $validation, "total" => $count[0]["count"] == null ? 0 : ($count[0]["count"])];
    }

    public function createDeliveryDetails($LG_LIVID, $CMD_LIST, $OUtilisateur): string
    {
        $validation = false;
        try {
            if (is_string($CMD_LIST)) {
                $CMD_LIST = json_decode($CMD_LIST);
            }

            if (!is_array($CMD_LIST)) {
                $CMD_LIST = [$CMD_LIST];
            }
            foreach ($CMD_LIST as $commande) {
                $LG_LIVCOMMID = generateRandomString(20);
                $params = array("lg_livcommid" => $LG_LIVCOMMID, "lg_livid" => $LG_LIVID, "lg_commid" => $commande, "dt_livcommcreated" => get_now(), "str_livstatut" => Parameters::$statut_enable, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
                if ($this->dbconnexion != null) {
                    if (Persist($this->DetailsLivraion, $params, $this->dbconnexion)) {
                        $validation = $params['lg_livcommid'];
                        Parameters::buildSuccessMessage("Commande lié au calendrier");
                    } else {
                        Parameters::buildErrorMessage("Echec de l'opération . La commande avec l'id " . $commande . " n'existe pas . ");
                        return $validation;
                    }
                }
            }
            $validation = true;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteDeleveryDetails($LG_COMMID)
    {
        $validation = false;
        try {
            $params = array("lg_commid" => $LG_COMMID);
            if (Remove($this->DetailsLivraion, $params, $this->dbconnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Suppression de la commande sur le calendrier effectué avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression de la commande");
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de suppression de la commande. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function closeDeliveryCalendar($LG_LIVID)
    {
        $validation = false;
        try {
            $params_condition = array("lg_livid" => $LG_LIVID);
            $params_to_update = array("str_livstatut" => Parameters::$statut_closed, "dt_livupdated" => get_now());
            if ($this->dbconnexion != null) {
                if (Merge($this->Livraison, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Calendrier de livraison fermé avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération, la fermeture du calendrier a echoué");
                }
            }
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de la fermeture du calendrier de livraison");
        }
        return $validation;
    }


    public function getCalendarFrontOfiice()
    {
        $arraySql = array();
        $array_place = array();
        $array_delivery = array();
        try {
            $deliveryPlace = $this->getDeliveryPlace([], 99999, 1);
            $calendar = $this->showAllOrOneDeliveryCalendar([], 99999, 1);

            foreach ($deliveryPlace as $place) {
                foreach ($calendar as $cal) {
                    if ($place['lg_lstid'] == $cal['zone_id']) {
                        $cal['zone'] = $place['str_lstdescription'];
                        $array_delivery[$place['str_lstdescription']] = [
                            "lg_callivid" => $cal["lg_callivid"],
                            "date" => $cal["dt_callivbegin"],
                            "deliveryDate" => $cal["dt_callivend"],
                        ];
                    }
                }
            }


            foreach ($deliveryPlace as $place) {
                $array_place[] = [
                    "lg_lstid" => $place['lg_lstid'],
                    "str_lstdescription" => $place['str_lstdescription'],
                ];
            }

            $arraySql[] = $array_delivery;
            $arraySql[] = $array_place;
//            foreach ($calendar as $cal) {
//                $cal['zone'] = $deliveryPlace[array_search($cal['zone_id'], array_column($deliveryPlace, 'lg_lstid'))]['str_lstdescription'];
//                $arraySql[] = $cal;
//            }
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de la recuperation du calendrier de livraison");
        }
        return $arraySql;
    }

    public function listClientCommande($token = null, $LG_SOCID, $LG_AGEID)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        try {
            $Osociete = $ConfigurationManager->getSociete($LG_SOCID);
            if (!$Osociete) {
                Parameters::buildErrorMessage("Société inexistante");
                return [];
            }
            $url = Parameters::$urlRootAPI . "/clients/" . $Osociete[0]['lg_socextid'] . "/orders";
            $token = $token ?: $ConfigurationManager->generateToken();
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Exécution de la requête
            $response = curl_exec($ch);


            // Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

            // Fermeture de la session cURL
            curl_close($ch);

            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }
            $arraySql = $obj;
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
        Parameters::buildSuccessMessage("Commandes recupérées avec succès.");
        return $arraySql;
    }

    public function listProductByCommande($LG_COMMID)
    {
        $arraySql = array();
        try {
            $query = "
                SELECT *
                FROM " . $this->Commproduit . " cp
                INNER JOIN " . $this->Produit . " p ON cp.lg_proid = p.lg_proid
                WHERE cp.lg_commid = :LG_COMMID
            ";

            $stmt = $this->dbconnexion->prepare($query);
            $stmt->execute(array("LG_COMMID" => $LG_COMMID));
            while ($rowObj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arraySql[] = $rowObj;
            }

            $stmt->closeCursor();
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }

        Parameters::buildSuccessMessage("Produits de la commande recupérés avec succès.");
        return $arraySql;
    }

    public function getClientCalendar($LG_AGEID = null)
{
    $arraySql = array();
    $params = array();
    Parameters::buildSuccessMessage("Calendrier de livraison récupéré avec succès");

    try {
        $query = "
            SELECT lst.str_lstvalue, lst.str_lstdescription, l.dt_livbegin, l.dt_livend, l.lg_livid
            FROM livraison l 
            INNER JOIN liv_commande lc ON l.lg_livid = lc.lg_livid 
            INNER JOIN commande c ON lc.lg_commid = c.lg_commid 
            INNER JOIN liste lst ON l.lg_lstid = lst.lg_lstid
            " . ($LG_AGEID !== null ? "WHERE c.lg_ageid = :LG_AGEID" : "") . "
            GROUP BY l.lg_livid 
            ORDER BY l.dt_livbegin ASC 
        ";

        if ($this->dbconnexion) {
            if ($LG_AGEID !== null) {
                $params = array("LG_AGEID" => $LG_AGEID);
            }

            $stmt = $this->dbconnexion->prepare($query);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!is_array($results) || empty($results)) {
                Parameters::buildErrorMessage("Aucun calendrier de livraison trouvé");
                return [];
            }

            $arraySql = $results;
            $stmt->closeCursor();
        }
    } catch (Exception $exc) {
        error_log($exc->getMessage());
        Parameters::buildErrorMessage("Impossible de récupérer le planning de livraison, veuillez contacter votre administrateur.");
    }

    return $arraySql;
}


    public function showAllOrOneOrderOrInvoice($FILTER_OPTIONS, $LIMIT, $PAGE, $TABLE): array
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";
        $params = [];
        Parameters::buildSuccessMessage("Commandes obtenue avec succès");
        try {
            if (!empty($FILTER_OPTIONS)) {
                $query = "
                    SELECT $select
                    FROM $TABLE
                    WHERE                     
                ";

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        $WHERE[] = "(PcvLib LIKE ? OR PcvRef LIKE ? OR PcvID LIKE ?)";
                    } else if ($key === "PcvDate") {
                        $WHERE[] = "YEAR(PcvDate) = ?";
                    } else {
                        $WHERE[] = "$key = ?";
                    }
                }

                $query .= implode(" AND ", $WHERE);

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        for ($i = 0; $i < 3; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    } else if ($key === "PcvDate") {
                        $params[] = $value;
                    } else {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "
                    SELECT $select
                    FROM $this->StatDevis
                ";
            }

            $query .= "ORDER BY PcvDate LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if (!$arraySql) {
                Parameters::buildSuccessMessage("Aucune commandes trouvées");
                $arraySql = [];
            }

            $newSelect = "COUNT(*) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les commandes, veuillez contacter votre administrateur");
        }

        return ["data" => $arraySql, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function showAllInvoices($params_conditions)
    {
        $validation = [];
        try {
            $query = "SELECT * FROM $this->StatInvoices WHERE ";
            $conditions = [];
            $params = [];

            foreach ($params_conditions as $key => $value) {
                if ($key === "PcvDate") {
                    $conditions[] = "$key > ?";
                } else {
                    $conditions[] = "$key = ?";
                }
                $params[] = $value;
            }

            $query .= implode(" AND ", $conditions);

            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $validation = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
        }

        return $validation;
    }

    public function showAllOrders($params_conditions){
        $validation = [];
        try {
            $query = "SELECT * FROM $this->Commande WHERE ";
            $conditions = [];
            $params = [];

            foreach ($params_conditions as $key => $value) {
                if ($key === "dt_commupdated") {
                    $conditions[] = "$key > ?";
                } else {
                    $conditions[] = "$key = ?";
                }
                $params[] = $value;
            }

            $conditions[] = "str_commstatut = ?";
            $params[] = Parameters::$statut_closed;
            $query .= implode(" AND ", $conditions);

            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $validation = $res->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $exception){
            error_log($exception->getMessage());
        }

        return $validation;
    }


    public function showAllOrdersByClientExternal($LG_CLIID)
    {
        $array = array();
        $ConfigurationManager = new ConfigurationManager();
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/orders";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $array = $obj;
            Parameters::buildSuccessMessage("Commandes recuperé avec succès");
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Erreur lors de la récupération des commandes");
        }

        return $array;
    }


}
