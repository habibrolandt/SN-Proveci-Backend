<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';

interface ConfigurationInterface
{

    //code ajouté
    public function doConnexion($STR_UTILOGIN, $STR_UTIPASSWORD, $IS_ADMIN = 0);

    public function doDisConnexion($STR_UTITOKEN);

    public function updateTokenUtilisateur($OUtilisateur, $STR_UTITOKEN);

    public function getUtilisateur($LG_UTIID);

    public function getProfile($LG_PROID);

    public function showAllOrOneProfile($FILTER_OPTIONS, $LIMIT, $PAGE);


    public function getProfilePrivilege($LG_PROID, $LG_PRIID);

    public function getTypetransaction($LG_TTRID);

    public function showAllOrOneTypetransaction($search_value);

    public function getOperateur($LG_OPEID);

    public function showAllOrOneOperateur($search_value);

    //Moi
    public function createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur, $LG_SOCEXTID = null, $DBL_SOCPLAFOND = null, $STR_SOCSTATUT = null);

    public function updateSociete($LG_SOCID, $LG_SOCEXTID, $STR_SOCSTATUT, $STR_SOCDESCRIPTION, $STR_SOCNAME, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $DBL_SOCPLAFOND = null, $OUtilisateur);

    public function getSociete($LG_SOCID);

    public function deleteSociete($LG_SOCID, $OUtilisateur);

    public function showAllOrOneSociete($search_value, $statut);

    public function showAllOrOneSocieteLimit($search_value, $start, $limit);

    public function totalSociete($search_value);

    public function createSocieteOperateur($LG_SOCID, $LG_OPEID, $STR_SOPPHONE, $OUtilisateur);

    public function updateSocieteOperateur($LG_SOPID, $STR_SOPPHONE, $OUtilisateur);

    public function getSocieteOperateur($LG_SOCID, $LG_OPEID);

    public function getSocieteOperateurUnique($LG_SOPID);

    public function deleteSocieteOperateur($LG_SOPID, $STR_SOPSTATUT, $OUtilisateur);

    public function showAllOrOneSocieteOperateur($search_value, $LG_SOCID, $LG_OPEID);

    public function createSocieteUtilisateur($LG_SOCID, $LG_UTIID, $OUtilisateur);

    public function getSocieteUtilisateur($LG_SOCID, $LG_UTIID);

    public function deleteSocieteUtilisateur($LG_SUTID, $OUtilisateur);

    public function showAllOrOneSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID);

    public function showAllOrOneSocieteUtilisateurLimit($search_value, $LG_SOCID, $LG_UTIID, $start, $limit);

    public function totalSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID);

    public function generateToken();

    public function getClient($LG_CLIID, $token = null);

    public function getAgence($LG_AGEID);

    //fin code ajouté

    public function createListe($LG_TYLID, $STR_LSTDESCRIPTION, $STR_LSTVALUE, $OUtilisateur);

    //moi
    public function getListe($LG_LSTID);

    public function showAllOrOneListe($search_value, $LG_TYLID);

    //Moi
    public function createAgence($STR_AGENAME, $STR_AGEDESCRIPTION, $STR_AGELOCALISATION,
                                 $STR_AGEPHONE, $LG_SOCID, $OUtilisateur, $STR_AGESTATUT = null);

    //moi
    public function getTrueAgence($LG_AGEID);

    //moi
    public function createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur, $STR_UTISTATUT = null);

    public function updateUtilisateur($LG_UTIID, $STR_UTISTATUT, $STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur, $STR_UTIPASSWORD = null);

    public function deleteUtilisateur($LG_UTIID, $OUtilisateur);

    //moi
    public function createDocument($P_KEY, $STR_DOCPATH, $LG_LSTID, $OUtilisateur);

    //moi
    public function getDocument($LG_DOCID);

    public function showAllOrOneDocument($FILTER_OPTIONS);

    //moi
    public function showAllOrOneClientRequest($FILTERS_OPTIONS, $LIMIT, $PAGE);

    //moi
    public function createClientExternal($LG_SOCID, $OUtilisateur);

    //moi
//    public function createDemande($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE,
//                                  $STR_UTIFIRSTLASTNAME, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $STR_UTIPHONE, $LG_PROID, $DOCUMENTS, $OUtilisateur, $LG_SOCEXTID = null, $STR_SOCLOGO = null, $STR_UTIPIC = null, $DBL_SOCPLAFOND = null);

    public function createDemande($value);

    //moi
    public function uploadOneOrSeveralDocuments($Documents, $LG_SOCID, $OUtilisateur);

    //moi
    public function getClientDemande($LG_SOCID, $STATUT = null);

    //moi
    public function rejectRegistration($LG_SOCID, $OUtilisateur);

    public function showAllOrOneBackUsers($FILTER_OPTIONS, $LIMIT, $PAGE);

    //moi
    public function markProductAsViewed($LG_PROID, $OUtilisateur);

    //moi
    public function uploadMainImageProduct($PICTURE, $LG_PROID, $OUtilisateur);

    public function uploadThumbImagesProduct($PICTURES, $LG_PROID, $OUtilisateur);

    //moi
    public function createProductSubstitution($LG_PROPARENTID, $LG_PROKIDIDS, $OUtilisateur);

    public function deleteProduitSubstitution($LG_PROSUBID);

    public function getProduitSubstitution($LG_PROSUBID);

    public function deleteDocument($LG_DOCID, $OUtilisateur);

    public function deleteProductMainImage($LG_PROID, $OUtilisateur);

    public function uploadLocalImageProduct($DIRECTORY, $OUtilisateur);

    public function sendEmail($SUBJECT, $BODY, $TO, $FROM);

    public function showAllProductImages($LG_PROID);

    public function createStat($table, $PCVID, $PCVGCLIID, $PCVDATE, $PCVLIB, $PCVREF, $PCVMTHT, $PCVMTTTC, $PCVMTTOTAL, $PCVETATFNUF = null);

    public function getStat($table, $PCVID);

    public function updateStat($table, $PCVID, $PCVGCLIID, $PCVDATE, $PCVLIB, $PCVREF, $PCVMTHT, $PCVMTTTC, $PCVMTTOTAL, $PCVETATFNUF = null);

    public function showAllDocumentRemote($search_value, $date = null);

    public function loadExternalDocuments($table, $search, $date);


    public function createProfil($STR_PRONAME, $STR_PRODESCRIPTION, $LG_LSTID, $OUtilisateur);

    public function updateProfil($LG_PROID, $STR_PRONAME, $STR_PRODESCRIPTION, $LG_LSTID, $OUtilisateur);

    public function deleteProfil($LG_PROID, $OUtilisateur);

    public function getPrivilege($LG_PRIID);

    public function createPrivilege($STR_PRINAME, $STR_PRIDESCRIPTION, $STR_PROIURL, $STR_PRITYPE, $STR_PRIKIND, $STR_PRICLASS, $INT_PRIPRIORITY, $LG_PRIPARENTID, $LG_PRIGROUPID, $OUtilisateur);

    public function updatePrivilege($LG_PRIID, $STR_PRINAME, $STR_PRIDESCRIPTION, $STR_PROIURL, $STR_PRITYPE, $STR_PRIKIND, $STR_PRICLASS, $INT_PRIPRIORITY, $LG_PRIPARENTID, $LG_PRIGROUPID, $OUtilisateur);

    public function deletePrivilege($LG_PRIID, $OUtilisateur);

    public function getProfilPrivilege($LG_PROID, $LG_PRIID);

    public function showAllProfilPrivileges($LG_PROID);

    public function assignPrivilegesToProfile($LG_PROID, $LG_PRIIDS);

    public function removePrivilegesFromProfile($LG_PROID, $LG_PRIIDS);

    public function showAllOrOnePrivilege($FILTER_OPTIONS, $LIMIT, $PAGE);

    public function loadExternalProductsByInvoice($invoice);

    public function createHistory($params);

    public function showAllOrOneDocumentAndType($P_KEY);

    public function updateLastBackUpDate();

    public function slugifyProductName();

    public function updateDemande($value, $LG_SOCID);

    public function changeDocumentStatut($LG_DOCID, $OUtilisateur);
    public function resetPasswordUtilisateur($STR_UTIMAIL, $OUtilisateur = null);

}

class ConfigurationManager implements ConfigurationInterface
{

    private $Typetransaction = 'typetransaction';
    private $OTypetransaction = array();
    private $Operateur = 'operateur';
    private $OOperateur = array();
    private $Societe = 'societe';
    private $OSociete = array();
    private $Utilisateur = 'utilisateur';
    private $OUtilisateur = array();
    private $SocieteOperateur = 'societe_operateur';
    private $OSocieteOperateur = array();
    private $SocieteUtilisateur = 'societe_utilisateur';
    private $OSocieteUtilisateur = array();
    private $ProfilePrivilege = 'profile_privilege';
    private $OProfilePrivilege = array();
    private $Profile = 'profile';
    private $OAgence = array();
    private $Agence = 'agence';
    private $OProfile = array();
    private $dbconnnexion;

    //constructeur de la classe
    private $Liste = "liste";
    private $OListe = array();

    private $Document = "document";
    private $ODocument = array();
    private $ODemandes = array();

    private $Piste_audit = "piste_audit";
    private $Produit = "produit";

    private $ProduitSubstitution = "produit_substitution";
    private $OProduitSubstitution = array();
    private $StatDevis = "stat_devis";
    private $Privilege = "privilege";
    private $OPrivilege = [];


    public function __construct()
    {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    //connexion d'un utilisateur
    public function doConnexion($STR_UTILOGIN, $STR_UTIPASSWORD, $IS_ADMIN = 0)
    {
        $validation = array();
        $Object = null;
        $STR_UTITOKEN = generateRandomString(20);
        if ($IS_ADMIN == '1') {
            Parameters::buildErrorMessage("Échec de connexion. Identifiant ou mot de passe incorrecte de l'administrateur");
        } else {
            Parameters::buildErrorMessage("Échec de connexion. Identifiant ou mot de passe incorrecte");
        }
        try {
            if ($IS_ADMIN == '1') {
                $query = "SELECT t.*, p.str_prodescription
                FROM " . $this->Utilisateur . " t
                JOIN " . $this->Profile . " p ON t.lg_proid = p.lg_proid
                JOIN " . $this->Liste . " l ON p.lg_lstid = l.lg_lstid
                WHERE t.str_utilogin = :STR_UTILOGIN AND 
                    t.str_utipassword = :STR_UTIPASSWORD AND 
                    t.str_utistatut = :STR_UTISTATUT AND
                    p.lg_lstid = :LG_LSTID
                LIMIT 1";
                $res = $this->dbconnnexion->prepare($query);
                //exécution de la requête
                $params = array('STR_UTILOGIN' => $STR_UTILOGIN, 'STR_UTIPASSWORD' => sha1($STR_UTIPASSWORD), 'STR_UTISTATUT' => Parameters::$statut_enable, "LG_LSTID" => Parameters::$defaultSystemProfilID);
                $res->execute($params);

                $data = $res->fetch();

            } else {
                $query = "
                        SELECT t.*, p.str_prodescription, s.lg_socid, s.str_socname, s.str_socdescription, s.str_soclogo, s.dbl_socplafond, s.lg_socextid 
                        FROM " . $this->Utilisateur . " t 
                            INNER JOIN " . $this->Profile . " p ON t.lg_proid = p.lg_proid 
                            INNER JOIN " . $this->Agence . " a ON a.lg_ageid = t.lg_ageid 
                            INNER JOIN " . $this->Societe . " s ON s.lg_socid = a.lg_socid 
                        WHERE t.str_utilogin = :STR_UTILOGIN AND 
                            t.str_utipassword = :STR_UTIPASSWORD AND 
                            t.str_utistatut = :STR_UTISTATUT
                        LIMIT 1
                            ";
                $res = $this->dbconnnexion->prepare($query);
                $res->execute(array('STR_UTILOGIN' => $STR_UTILOGIN, 'STR_UTIPASSWORD' => sha1($STR_UTIPASSWORD),
                    'STR_UTISTATUT' => Parameters::$statut_enable));

                $data = $res->fetch();
                if ($data && ($data['lg_socextid'] == null || $data['lg_socextid'] == '')) {
                    Parameters::buildErrorMessage("Échec de connexion. Demande client rejetté ou en cours de validation");
                    return $validation;
                }
            }


            if ($data) {
                $Object[] = $data;
            }

            $this->OUtilisateur = $Object;

            if ($this->OUtilisateur == null) {
                return $validation;
            }

            $this->updateTokenUtilisateur($this->OUtilisateur, $STR_UTITOKEN);
            if ($IS_ADMIN == '1') {
                $this->OUtilisateur[0]["admin"] = true;
            }
            Parameters::buildSuccessMessage("Bienvenu " . $this->OUtilisateur[0]['str_utifirstlastname']);
            $this->OUtilisateur[0]['str_utitoken'] = $STR_UTITOKEN;
            $validation = $this->OUtilisateur;
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function createProductSubstitution($LG_PROPARENTID, $LG_PROKIDIDS, $OUtilisateur): array
    {
//        var_dump($LG_PROKIDIDS);

        $validation = [];
        $fails = [];
        $LG_PROKIDIDS = is_array($LG_PROKIDIDS) ? $LG_PROKIDIDS : [$LG_PROKIDIDS];
        $StockManager = new StockManager();

        foreach ($LG_PROKIDIDS as $LG_PROKIDID) {
            try {
                $this->OProduit = $StockManager->getProduct($LG_PROKIDID);

                if ($this->OProduit == null) {
                    $fails[] = $LG_PROKIDID;
                } else {
                    $LG_PROSUBID = generateRandomString(20);
                    $params = array("lg_prosubid" => $LG_PROSUBID, "lg_proparentid" => $LG_PROPARENTID, "lg_prokidid" => $LG_PROKIDID, "dt_prosubcreated" => get_now(), "str_prosubstatut" => Parameters::$statut_enable, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
                    if ($this->dbconnnexion != null) {
                        if (Persist($this->ProduitSubstitution, $params, $this->dbconnnexion)) {
                            $validation[] = ['lg_prosubid' => $params['lg_prosubid'], 'lg_proparentid' => $params['lg_proparentid'], 'ArtID' => $params['lg_prokidid'], "ArtLib" => $this->OProduit[0]['str_prodescription']];
                            Parameters::buildSuccessMessage("Produit de substitution lié avec succès");
                        } else {
                            Parameters::buildErrorMessage("Échec de l'opération");
                        }
                    }
                }
            } catch (\Exception $exc) {
                error_log($exc->getTraceAsString());
                Parameters::buildErrorMessage("Échec de l'opération du document . Veuillez contacter votre administrateur");
            }
        }
        return ["validation" => $validation, "fails" => $fails];
    }

    public function getProduitSubstitution($LG_PROSUBID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Produit de substitution inexistant");
        try {
            $params_condition = array("lg_prosubid" => $LG_PROSUBID);
            $validation = $this->OProduitSubstitution = Find($this->ProduitSubstitution, $params_condition, $this->dbconnnexion);
            if ($this->OProduitSubstitution == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Produit de substitution " . $this->OProduitSubstitution[0]['lg_prosubid'] . " trouvé");
            $validation = $this->OProduitSubstitution;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $validation;
    }

    //fin connexion d'un utilisateur
    //déconnexion d'un utilisateur
    public function doDisConnexion($STR_UTITOKEN)
    {
        $validation = false;
        Parameters::buildErrorMessage("Échec de déconnexion. Veuillez réessayer svp!");
        try {
            $params_condition = array('STR_UTITOKEN' => $STR_UTITOKEN);
            $this->OUtilisateur = Find($this->Utilisateur, $params_condition, $this->dbconnnexion);

            if ($this->OUtilisateur != null) {
                $this->updateTokenUtilisateur($this->OUtilisateur, "");
                Parameters::buildSuccessMessage("Déconnexion de " . $this->OUtilisateur[0]['str_utifirstlastname'] . " effectuée avec succès");
            }
            $validation = true;
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible de vous déconnectez. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin déconnexion d'un utilisateur
    //mise à jour de token de l'utilisateur
    public function updateTokenUtilisateur($OUtilisateur, $STR_UTITOKEN)
    {
        $validation = false;
        try {
            $params_condition = array("lg_utiid" => $OUtilisateur[0]['lg_utiid']);
            $params_to_update = array("str_utitoken" => $STR_UTITOKEN, "dt_utilastconnected" => get_now());

            if (Merge($this->Utilisateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    //recherche d'un utilisateur
    public function getUtilisateur($LG_UTIID) {
        $validation = null;
        Parameters::buildErrorMessage("Utilisateur inexistant");
        try {
            $params_condition = array("lg_utiid" => $LG_UTIID, "str_utitoken" => $LG_UTIID, "str_utimail" => $LG_UTIID);
            $validation = $this->OUtilisateur = Find($this->Utilisateur, $params_condition, $this->dbconnnexion, "OR");
            if ($this->OUtilisateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Utilisateur " . $this->OUtilisateur[0]['str_utifirstlastname'] . " trouvé");
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
        }
        return $validation;
    }

    public function showAllOrOneBackUsers($FILTER_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";

        Parameters::buildSuccessMessage("Utilisateurs trouvés");
        try {
            $p = $this->showAllOrOneProfile(["lg_lstid" => "0000000000000000000000000000000000000793"], 99999, 1);
            if (empty($p["data"])) {
                return ["data" => [], "total" => 0];
            }
            if (!empty(($FILTER_OPTIONS))) {
                $query = "
                    SELECT $select
                    FROM " . $this->Utilisateur . " uti
                    WHERE
                ";

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        $WHERE[] = "(uti.str_utifirstlastname LIKE ? OR uti.str_utimail LIKE ? OR uti.str_utiphone LIKE ?)";
                    }
                }

                $ids = array_column($p["data"], "lg_proid");
                $WHERE[] = "lg_proid IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
                $query .= implode(" AND ", $WHERE);

                $params = [];
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        for ($i = 0; $i < 3; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    }
                }
            } else {
                $ids = array_column($p["data"], "lg_proid");
                $query = "
    SELECT $select
    FROM " . $this->Utilisateur . " uti
    WHERE str_utistatut = ? AND lg_proid IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params = [Parameters::$statut_enable];
            $params = array_merge($params, $ids);
            $res = $this->dbconnnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if (empty($arraySql)) {
                Parameters::buildSuccessMessage("Aucun utilisateurs trouvés");
                $arraySql = [];
            }

            $newSelect = "COUNT(*) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);


        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir la liste des utilisateurs, veuillez contacter votre administrateur.");
        }
        return ["data" => $arraySql, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    //fin gestion des utilisateurs
    //gestion des profils
    //recherche de profil
    public function getProfile($LG_PROID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Profil inexistant");
        try {
            $params_condition = array("LG_PROID" => $LG_PROID, "STR_PRODESCRIPTION" => $LG_PROID);
            $validation = $this->OProfile = Find($this->Profile, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OProfile == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Profil " . $this->OProfile[0]['lg_proid'] . " trouvé");
        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    //liste de profils
    public function showAllOrOneProfile($FILTER_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $params = [];
        $select = "*";
        try {
            if (!empty($FILTER_OPTIONS)) {
                $query = "SELECT $select FROM " . $this->Profile . " t WHERE ";
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        $WHERE[] = "(t.str_proname LIKE ? OR t.str_prodescription LIKE ?)";
                    } else {
                        $WHERE[] = "t." . $key . " = ?";
                    }
                }

                $WHERE[] = "t.str_prostatut = ?";
                $query .= implode(" AND ", $WHERE);
                $query .= " ORDER BY t.str_prodescription";

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        for ($i = 0; $i < 2; $i++) {
                            $params[] = "%" . $FILTER_OPTIONS['search'] . "%";
                        }
                    } else {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "SELECT $select FROM " . $this->Profile . " t WHERE t.str_prostatut = ? ORDER BY t.str_prodescription";
            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = Parameters::$statut_enable;
            $res = $this->dbconnnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);
//            var_dump($arraySql);


            $newSelect = "COUNT(*) total";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les profils, veuillez contactez votre administrateur");
        }
        return ["data" => $arraySql, "total" => $count[0]["total"] == null ? 0 : ($count[0]["total"])];
    }

    //fin gestion des profils
    //gestion des privileges
    public function getProfilePrivilege($LG_PROID, $LG_PRIID)
    {
        $validation = null;
        try {
            $params_condition = array("LG_PROID" => $LG_PROID, "LG_PRIID" => $LG_PRIID);
            $validation = $this->OProfilePrivilege = Find($this->ProfilePrivilege, $params_condition, $this->dbconnnexion);

            if ($this->OProfilePrivilege == null) {
                return $validation;
            }
            $validation = $this->OProfilePrivilege;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin gestion des privileges
    //Gestion des types de transactions
    public function getTypetransaction($LG_TTRID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Type de transaction inexistante");
        try {
            $params_condition = array("LG_TTRID" => $LG_TTRID, "STR_TTRDESCRIPTION" => $LG_TTRID);
            $validation = $this->OTypetransaction = Find($this->Typetransaction, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OTypetransaction == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Type de transaction " . $this->OTypetransaction[0][2] . " trouvé");
            $validation = $this->OTypetransaction;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneTypetransaction($search_value)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Typetransaction . " t WHERE (t.STR_TTRNAME LIKE :search_value OR t.STR_TTRDESCRIPTION LIKE :search_value) AND t.STR_TTRSTATUT = :STR_STATUT ORDER BY t.STR_TTRDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des types de transaction
    //gestion des opérateurs
    public function getOperateur($LG_OPEID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Opérateur inexistant");
        try {
            $params_condition = array("LG_OPEID" => $LG_OPEID, "STR_OPEDESCRIPTION" => $LG_OPEID);
            $validation = $this->OOperateur = Find($this->Operateur, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OOperateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opérateur " . $this->OOperateur[0][2] . " trouvé");
            $validation = $this->OOperateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneOperateur($search_value)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Operateur . " t WHERE (t.STR_OPENAME LIKE :search_value OR t.STR_OPEDESCRIPTION LIKE :search_value) AND t.STR_OPESTATUT = :STR_STATUT ORDER BY t.STR_OPEDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des opérateurs
    //gestion des sociétés
    //creation d'une société
    //moi
    public function createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur, $LG_SOCEXTID = null, $DBL_SOCPLAFOND = null, $STR_SOCSTATUT = null): string
    {
        $validation = "";
        $LG_SOCID = generateRandomString(20);
        $LG_LSTTYPESOCID = $this->getListe($LG_LSTTYPESOCID);
        if ($LG_LSTTYPESOCID == null) {
            Parameters::buildErrorMessage("Type société inexistant");
            return "";
        }

        $LG_LSTPAYID = $this->getListe($LG_LSTPAYID);
        if ($LG_LSTPAYID == null) {
            Parameters::buildErrorMessage("Id du pays de facturation inexistant");
            return "";
        }
        try {
            if ($STR_SOCLOGO != null) {
                $rootFolderRelative = __DIR__ . "/../images/";
                $STR_SOCLOGO = uploadFile($rootFolderRelative . "logos/", $_FILES['STR_SOCLOGO']);
            }
            $params = array("lg_socid" => $LG_SOCID, "str_socname" => $STR_SOCNAME, "str_socdescription" => $STR_SOCDESCRIPTION, "str_soclogo" => $STR_SOCLOGO, "str_soccode" => $STR_SOCCODE,
                "str_socstatut" => $STR_SOCSTATUT != null ? $STR_SOCSTATUT : Parameters::$statut_process, "str_socmail" => $STR_SOCMAIL, "str_socphone" => $STR_SOCPHONE, "dt_soccreated" => get_now(),
                "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "str_socsiret" => $STR_SOCSIRET, "lg_lsttypesocid" => $LG_LSTTYPESOCID[0]['lg_lstid'], "lg_lstpayid" => $LG_LSTPAYID[0]['lg_lstid'], 'lg_socextid' => $LG_SOCEXTID, "dbl_socplafond" => $DBL_SOCPLAFOND == "" ? null : $DBL_SOCPLAFOND);

            if ($this->dbconnnexion != null) {//
                if (Persist($this->Societe, $params, $this->dbconnnexion)) {
                    $validation = $LG_SOCID;
                    Parameters::buildSuccessMessage("Société " . $STR_SOCDESCRIPTION . " effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Échec de création de la société");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de création de la société. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin creation d'une société
    //mise à jour d'une société
    public function updateSociete($LG_SOCID, $LG_SOCEXTID, $STR_SOCSTATUT, $STR_SOCDESCRIPTION, $STR_SOCNAME, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $DBL_SOCPLAFOND = null, $OUtilisateur = null)
    {
        $validation = false;
        $LG_LSTTYPESOCID = $this->getListe($LG_LSTTYPESOCID);
        if ($LG_LSTTYPESOCID == null) {
            Parameters::buildErrorMessage("Type société inexistant");
            return "";
        }
        $LG_LSTPAYID = $this->getListe($LG_LSTPAYID);
        if ($LG_LSTPAYID == null) {
            Parameters::buildErrorMessage("Id du pays de facturation inexistant");
            return "";
        }
        try {
            $this->OSociete = $this->getSociete($LG_SOCID);

            if ($this->OSociete == null) {
                Parameters::buildErrorMessage("Échec de mise à jour. Société inexistante");
                return $validation;
            }

            $params_condition = array("lg_socid" => $this->OSociete[0]['lg_socid']);
            if ($STR_SOCLOGO) {
                $STR_SOCLOGO = uploadFile(Parameters::$rootFolderAbsolute . "logos/" . $LG_SOCID . "/", $_FILES['STR_SOCLOGO']);
            }
            $params_to_update = array("str_socname" => $STR_SOCNAME, "str_socdescription" => $STR_SOCDESCRIPTION, "lg_socextid" => $LG_SOCEXTID, "str_socstatut" => $STR_SOCSTATUT, "str_soclogo" => (!$STR_SOCLOGO ? $this->OSociete[0]["str_soclogo"] : $STR_SOCLOGO),
                "str_socmail" => $STR_SOCMAIL, "str_socphone" => $STR_SOCPHONE, "dt_socupdated" => get_now(),
                "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "lg_lsttypesocid" => $LG_LSTTYPESOCID[0]['lg_lstid'], "lg_lstpayid" => $LG_LSTPAYID[0]['lg_lstid'], 'str_soccode' => $STR_SOCCODE, 'str_socsiret' => $STR_SOCSIRET,
                "dbl_socplafond" => $DBL_SOCPLAFOND == "" ? $this->OSociete[0]["dbl_socplafond"] : $DBL_SOCPLAFOND); 

            if ($this->dbconnnexion != null) {
                if (Merge($this->Societe, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Société " . $STR_SOCDESCRIPTION . " mise à jour avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de mise à jour de la société");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de mise à jour de la société. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin mise à jour de société
    //suppression de société
    public function deleteSociete($LG_SOCID, $OUtilisateur)
    {
        $validation = false;
        try {
            $this->OSociete = $this->getSociete($LG_SOCID);

            if ($this->OSociete == null) {
                Parameters::buildErrorMessage("Échec de suppression. Société inexistante");
                return $validation;
            }

            $params_condition = array("LG_SOCID" => $this->OSociete[0][0]);
            $params_to_update = array("STR_SOCSTATUT" => Parameters::$statut_delete, "DT_SOCUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Societe, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Société " . $this->OSociete[0][2] . " supprimée avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de suppression de la société");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de suppression de la société. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin suppression de société
    //recherche de société
    public function getSociete($LG_SOCID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Société inexistante");
        try {
            $params_condition = array("lg_socid" => $LG_SOCID, "str_socdescription" => $LG_SOCID);
            $validation = $this->OSociete = Find($this->Societe, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OSociete == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Société " . $this->OSociete[0]["lg_socid"] . " trouvée");
            $validation = $this->OSociete;
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    //fin recherche de société
    //liste des sociétés
    public function showAllOrOneSociete($search_value, $statut)
    {
        if ($statut != Parameters::$statut_process and $statut != Parameters::$statut_enable and $statut != Parameters::$statut_delete and $statut != Parameters::$statut_closed) {
            Parameters::buildErrorMessage("Statut incorrecte");
            return [];
        }
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Societe . " t WHERE (t.str_socname LIKE :search_value OR t.str_socdescription LIKE :search_value) AND t.str_socstatut = :STR_STATUT ORDER BY t.str_socdescription";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => $statut));
            while ($rowObj = $res->fetchAll()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllOrOneSocieteLimit($search_value, $start, $limit)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Societe . " t WHERE (t.STR_SOCNAME LIKE :search_value OR t.STR_SOCDESCRIPTION LIKE :search_value) AND t.STR_SOCSTATUT = :STR_STATUT ORDER BY t.STR_SOCDESCRIPTION LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalSociete($search_value)
    {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.LG_SOCID) NOMBRE FROM " . $this->Societe . " t WHERE (t.STR_SOCNAME LIKE :search_value OR t.STR_SOCDESCRIPTION LIKE :search_value) AND t.STR_SOCSTATUT = :STR_STATUT";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }

    public function createSocieteOperateur($LG_SOCID, $LG_OPEID, $STR_SOPPHONE, $OUtilisateur)
    {
        $validation = false;
        $LG_SOPID = generateRandomString(20);
        try {
            $params_condition = array("LG_SOCID" => $LG_SOCID, "LG_OPEID" => $LG_OPEID, "STR_SOPSTATUT" => Parameters::$statut_enable);
            $this->OSocieteOperateur = Find($this->SocieteOperateur, $params_condition, $this->dbconnnexion);

            if ($this->OSocieteOperateur != null) {
                Parameters::buildErrorMessage("Échec d'ajout de l'opérateur. Celui existe déjà pour cette société");
                return $validation;
            }

            $params = array("LG_SOPID" => $LG_SOPID, "LG_SOCID" => $LG_SOCID, "LG_OPEID" => $LG_OPEID, "STR_SOPPHONE" => $STR_SOPPHONE, "STR_SOPSTATUT" => Parameters::$statut_enable,
                "DT_SOPCREATED" => get_now(), "LG_UTICREATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Persist($this->SocieteOperateur, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Échec de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateSocieteOperateur($LG_SOPID, $STR_SOPPHONE, $OUtilisateur)
    {
        $validation = false;
        try {
            $this->OSocieteOperateur = $this->getSocieteOperateurUnique($LG_SOPID);

            if ($this->OSocieteOperateur == null) {
                Parameters::buildErrorMessage("Échec de mise à jour. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_SOPID" => $this->OSocieteOperateur[0][0]);
            $params_to_update = array("STR_SOPPHONE" => $STR_SOPPHONE, "DT_SOPUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->SocieteOperateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Échec de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getSocieteOperateur($LG_SOCID, $LG_OPEID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Opérateur inexistante sur la société");
        try {
            $params_condition = array("LG_SOCID" => $LG_SOCID, "LG_OPEID" => $LG_OPEID);
            $validation = $this->OSocieteOperateur = Find($this->SocieteOperateur, $params_condition, $this->dbconnnexion);

            if ($this->OSocieteOperateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opérateur trouvé");
            $validation = $this->OSocieteOperateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getSocieteOperateurUnique($LG_SOPID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Opérateur inexistante sur la société");
        try {
            $params_condition = array("LG_SOPID" => $LG_SOPID, "STR_SOPPHONE" => $LG_SOPID);
            $validation = $this->OSocieteOperateur = Find($this->SocieteOperateur, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OSocieteOperateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opérateur trouvé");
            $validation = $this->OSocieteOperateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteSocieteOperateur($LG_SOPID, $STR_SOPSTATUT, $OUtilisateur)
    {
        $validation = false;
        try {
            $this->OSocieteOperateur = $this->getSocieteOperateurUnique($LG_SOPID);

            if ($this->OSocieteOperateur == null) {
                Parameters::buildErrorMessage("Échec de l'opération. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_SOPID" => $this->OSocieteOperateur[0][0]);
            $params_to_update = array("STR_SOPSTATUT" => $STR_SOPSTATUT, "DT_SOPUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->SocieteOperateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération effectuée avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneSocieteOperateur($search_value, $LG_SOCID, $LG_OPEID)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.LG_SOPID, t.STR_SOPPHONE, o.STR_OPENAME, o.STR_OPEDESCRIPTION FROM " . $this->SocieteOperateur . " t, " . $this->Operateur . " o WHERE t.LG_OPEID = o.LG_OPEID AND (t.STR_SOPPHONE LIKE :search_value OR o.STR_OPENAME LIKE :search_value OR o.STR_OPEDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_OPEID LIKE :LG_OPEID AND o.STR_OPESTATUT = :STR_STATUT AND t.STR_SOPSTATUT = :STR_STATUT ORDER BY o.STR_OPEDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_OPEID' => $LG_OPEID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function createSocieteUtilisateur($LG_SOCID, $LG_UTIID, $OUtilisateur)
    {
        $validation = false;
        $LG_SUTID = generateRandomString(20);
        try {
            $this->OSocieteUtilisateur = $this->getSocieteUtilisateur($LG_SOCID, $LG_UTIID);

            if ($this->OSocieteUtilisateur != null) {
                Parameters::buildErrorMessage("Échec d'ajout de l'ajout de la société. Celui existe déjà pour cet utilisateur");
                return $validation;
            }

            $params = array("LG_SUTID" => $LG_SUTID, "LG_SOCID" => $LG_SOCID, "LG_UTIID" => $LG_UTIID, "STR_SUTSTATUT" => Parameters::$statut_enable,
                "DT_SUTCREATED" => get_now(), "LG_UTICREATEDID" => $OUtilisateur[0][0]);
            if ($this->dbconnnexion != null) {
                if (Persist($this->SocieteUtilisateur, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Échec de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getSocieteUtilisateur($LG_SOCID, $LG_UTIID)
    {
        $validation = null;
        try {
            $params_condition = array("LG_SOCID" => $LG_SOCID, "LG_UTIID" => $LG_UTIID);
            $validation = $this->OSocieteUtilisateur = Find($this->SocieteUtilisateur, $params_condition, $this->dbconnnexion);

            if ($this->OSocieteUtilisateur == null) {
                return $validation;
            }
            $validation = $this->OSocieteUtilisateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteSocieteUtilisateur($LG_SUTID, $OUtilisateur)
    {
        $validation = false;
        try {
            $params = array("LG_SUTID" => $LG_SUTID);
            if ($this->dbconnnexion != null) {
                if (Remove($this->SocieteUtilisateur, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Suppression effectuée avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de suppression");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de suppression. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID)
    {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT t.LG_SUTID, u.LG_UTIID, u.STR_UTIFIRSTLASTNAME, u.STR_UTIPHONE, u.STR_UTIMAIL, u.STR_UTILOGIN, u.STR_UTIPIC, s.STR_SOCNAME, s.STR_SOCDESCRIPTION, s.STR_SOCLOGO FROM " . $this->SocieteUtilisateur . " t, " . $this->Utilisateur . " u, " . $this->Societe . " s, " . $this->Profile . " p "
                . "WHERE t.LG_UTIID = u.LG_UTIID AND t.LG_SOCID = s.LG_SOCID AND u.LG_PROID = p.LG_PROID AND (u.STR_UTIFIRSTLASTNAME LIKE :search_value OR u.STR_UTIPHONE LIKE :search_value OR s.STR_SOCNAME LIKE :search_value OR s.STR_SOCDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_UTIID LIKE :LG_UTIID AND u.STR_UTISTATUT = :STR_STATUT AND t.STR_SUTSTATUT = :STR_STATUT ORDER BY s.STR_SOCDESCRIPTION, u.STR_UTIFIRSTLASTNAME";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_UTIID' => $LG_UTIID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function showAllOrOneSocieteUtilisateurLimit($search_value, $LG_SOCID, $LG_UTIID, $start, $limit)
    {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT t.LG_SUTID, u.LG_UTIID, u.STR_UTIFIRSTLASTNAME, u.STR_UTIPHONE, u.STR_UTIMAIL, u.STR_UTILOGIN, u.STR_UTIPIC, s.STR_SOCNAME, s.STR_SOCDESCRIPTION, s.STR_SOCLOGO FROM " . $this->SocieteUtilisateur . " t, " . $this->Utilisateur . " u, " . $this->Societe . " s, " . $this->Profile . " p "
                . "WHERE t.LG_UTIID = u.LG_UTIID AND t.LG_SOCID = s.LG_SOCID AND u.LG_PROID = p.LG_PROID AND (u.STR_UTIFIRSTLASTNAME LIKE :search_value OR u.STR_UTIPHONE LIKE :search_value OR s.STR_SOCNAME LIKE :search_value OR s.STR_SOCDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_UTIID LIKE :LG_UTIID AND u.STR_UTISTATUT = :STR_STATUT AND t.STR_SUTSTATUT = :STR_STATUT ORDER BY u.STR_UTIFIRSTLASTNAME LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_UTIID' => $LG_UTIID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID)
    {
        $result = 0;
        try {
            $query = "SELECT COUNT(DISTINCT(u.LG_UTIID)) NOMBRE FROM " . $this->SocieteUtilisateur . " t, " . $this->Utilisateur . " u, " . $this->Societe . " s, " . $this->Profile . " p "
                . "WHERE t.LG_UTIID = u.LG_UTIID AND t.LG_SOCID = s.LG_SOCID AND u.LG_PROID = p.LG_PROID AND (u.STR_UTIFIRSTLASTNAME LIKE :search_value OR u.STR_UTIPHONE LIKE :search_value OR s.STR_SOCNAME LIKE :search_value OR s.STR_SOCDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_UTIID LIKE :LG_UTIID AND u.STR_UTISTATUT = :STR_STATUT AND t.STR_SUTSTATUT = :STR_STATUT";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_UTIID' => $LG_UTIID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }

    //fin gestion des sociétés

    public function generateToken()
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/login/user";

// Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey
            );

            // Données à envoyer
            $data = array(
                'login' => Parameters::$apiusername,
                'password' => Parameters::$apipassword
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

            $validation = $obj->access_token;
            // Accéder aux propriétés de l'objet JSON
            /* echo "ID: " . $obj->id . "<br>";
              echo "Name: " . $obj->name . "<br>";
              echo "Age: " . $obj->age . "<br>";
              echo "Email: " . $obj->email . "<br>"; */

// Affichage de la réponse
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $exc->getTraceAsString();
//            Parameters::buildErrorMessage("Échec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getClient($LG_CLIID, $token = null)
    {
        $validation = null;
        try {
            $token = $token == null ? $this->generateToken() : $token;

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID;

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                Parameters::buildErrorMessage("Client inexistant. Veuille vérifier votre sélection");
                return $validation;
                //die('Erreur lors du décodage JSON');
            }

            $obj = $obj->clients;
            if (is_object($obj) && empty((array)$obj)) {
                Parameters::buildErrorMessage("Client inexistant. Veuille vérifier votre sélection");
                return $validation;
            }

            $validation = $obj[0];
            Parameters::buildSuccessMessage("Client " . $validation->CliLib . " trouvé");
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $validation;
    }

    public function getAgence($LG_AGEID)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.*, s.lg_socid, s.lg_socextid FROM " . $this->Agence . " t, " . $this->Societe . " s WHERE t.lg_socid = s.lg_socid and (t.lg_ageid = :lg_ageid or t.str_agename = :lg_ageid) and t.str_agestatut != :str_agestatut";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("lg_ageid" => $LG_AGEID, 'str_agestatut' => Parameters::$statut_delete));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function createListe($LG_TYLID, $STR_LSTDESCRIPTION, $STR_LSTVALUE, $OUtilisateur)
    {
        $validation = "";
        try {
            $LG_LSTID = generateRandomNumber();
            $params = [
                "lg_lstid" => $LG_LSTID,
                "lg_tylid" => $LG_TYLID,
                "str_lstdescription" => $STR_LSTDESCRIPTION,
                "str_lstvalue" => $STR_LSTVALUE,
                "dt_lstcreated" => get_now(),
                "lg_uticreatedid" => $OUtilisateur[0][0] ?: Parameters::$defaultAdminId,
                "str_lststatut" => Parameters::$statut_enable
            ];

            if ($this->dbconnnexion !== null) {
                if (Persist($this->Liste, $params, $this->dbconnnexion)) {
                    $validation = $LG_LSTID;
                    Parameters::buildSuccessMessage("Élement crée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Échec de la création de l'élément.");
                }
            }

        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible de créer l'élément, veuillez contacter votre administrateur");
        }

        return $validation;
    }

    public function updateLastBackUpDate()
    {
        $validation = false;
        try {
            $params_to_update = [
                "str_lstvalue" => get_now(),
            ];

            if ($this->dbconnnexion !== null) {
                if (Merge($this->Liste, $params_to_update, ["lg_lstid" => Parameters::$LAST_BACKUP_DATE], $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Date mise à jour");
                } else {
                    Parameters::buildErrorMessage("Echec de la mise à jour de la date");
                }
            }

        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'effectuer cette opération, veuillez contacter votre administrateur");
        }

        return $validation;
    }

    //Moi
    public function getListe($LG_LSTID): ?array
    {
        $validation = null;
        Parameters::buildErrorMessage("Item inexistant");

        try {
            $params_condition = array("lg_lstid" => $LG_LSTID);
            $validation = $this->OListe = Find($this->Liste, $params_condition, $this->dbconnnexion);
//            var_dump($validation);
            if ($this->OListe == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Item" . $this->OListe[0]['lg_lstid'] . "trouvée");
            $validation = $this->OListe;
        } catch (\Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $validation;
    }


    //moi
    public function createAgence($STR_AGENAME, $STR_AGEDESCRIPTION, $STR_AGELOCALISATION,
                                 $STR_AGEPHONE, $LG_SOCID, $OUtilisateur, $STR_AGESTATUT = null): string
    {
        $validation = "";
        $LG_AGEID = generateRandomString(20);
        $LG_SOCID = $this->getSociete($LG_SOCID);
        if ($LG_SOCID == null) {
            Parameters::buildErrorMessage("Id de la société introuvable");
            return $validation;
        }

        try {
            $params = array("lg_ageid" => $LG_AGEID, "str_agename" => $STR_AGENAME, "str_agedescription" =>
                $STR_AGEDESCRIPTION, "str_agelocalisation" => $STR_AGELOCALISATION, "dt_agecreated" => get_now(), "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "str_agestatut" => $STR_AGESTATUT != null ? $STR_AGESTATUT : Parameters::$statut_process, "str_agephone" => $STR_AGEPHONE,
                "lg_socid" => $LG_SOCID[0]['lg_socid'],
            );

            if ($this->dbconnnexion != null) {
                if (Persist($this->Agence, $params, $this->dbconnnexion)) {
                    $validation = $LG_AGEID;
                    Parameters::buildSuccessMessage(("Agence " . $STR_AGEDESCRIPTION . " créer avec succès"));
                } else {
                    Parameters::buildErrorMessage("Échec de création de l'agence");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Échec de création de l'agence. Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    //moi
    public function getTrueAgence($LG_AGEID): ?array
    {
        $validation = null;
        Parameters::buildErrorMessage("Agence inexistante");

        try {
            $params_condition = array("lg_ageid" => $LG_AGEID);
            $validation = $this->OAgence = Find($this->Agence, $params_condition, $this->dbconnnexion);
            if ($this->OAgence == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Agence" . $this->OAgence[0]['str_agename'] . "trouvée");
            $validation = $this->OAgence;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //moi
    public function createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur, $STR_UTISTATUT = null): string
    {
        $validation = "";
        $LG_UTIID = generateRandomNumber(20);
        $LG_AGEID = $this->getTrueAgence($LG_AGEID);
        $LG_PROID = $this->getProfile($LG_PROID);
        if ($LG_AGEID == null) {
            Parameters::buildErrorMessage("Id de l'agence incorrecte");
            return "";
        }
        if ($LG_PROID == null) {
            Parameters::buildErrorMessage("Profil inexistant");
            return "";
        }
        try {
            if ($STR_UTIPIC) {
                $rootFolderRelative = __DIR__ . "/../images/";
                $STR_UTIPIC = uploadFile($rootFolderRelative . "avatars/" . $LG_UTIID . "/", $STR_UTIPIC);
            }
            $params = array("lg_utiid" => $LG_UTIID, "str_utifirstlastname" => $STR_UTIFIRSTLASTNAME, "str_utiphone" => $STR_UTIPHONE, "str_utimail" => $STR_UTIMAIL, "str_utilogin" => $STR_UTILOGIN, "str_utipassword" => $STR_UTIPASSWORD, "str_utipic" => $STR_UTIPIC, "str_utitoken" => generateRandomString(), "str_utionesignalid" => "", "dt_uticreated" => get_now(), "str_utistatut" => $STR_UTISTATUT ?? Parameters::$statut_process, "lg_ageid" => $LG_AGEID[0]['lg_ageid'],
                "lg_proid" => $LG_PROID[0]['lg_proid'], "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Persist($this->Utilisateur, $params, $this->dbconnnexion)) {
                    $validation = $LG_UTIID;
                    Parameters::buildSuccessMessage("Utilisateur créé avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de création de l'utilisateur");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de création de l'utilisateur. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //moi
    public function updateUtilisateur($LG_UTIID, $STR_UTISTATUT, $STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur = null, $STR_UTIPASSWORD = null): bool
    {
        $validation = false;
        $LG_AGEID = $this->getTrueAgence($LG_AGEID);
        $LG_PROID = $this->getProfile($LG_PROID);
        if ($LG_AGEID == null) {
            Parameters::buildErrorMessage("Id de l'agence incorrecte");
            return "";
        }
        if ($LG_PROID == null) {
            Parameters::buildErrorMessage("Profil inexistant");
            return "";
        }
        try {
            $this->OUtilisateur = $this->getUtilisateur($LG_UTIID);

            if ($this->OUtilisateur == null) {
                Parameters::buildErrorMessage("Échec de mise à jour. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_UTIID" => $this->OUtilisateur[0]['lg_utiid']);
            if ($STR_UTIPIC) {
                $STR_UTIPIC = uploadFile(Parameters::$rootFolderAbsolute . "avatars/" . $LG_UTIID . "/", $STR_UTIPIC);
            }
            $params_to_update = array("str_utifirstlastname" => $STR_UTIFIRSTLASTNAME, "str_utiphone" => $STR_UTIPHONE, "str_utimail" => $STR_UTIMAIL, "str_utilogin" => $STR_UTILOGIN, "str_utipic" => $STR_UTIPIC, "str_utionesignalid" => "", "dt_utiupdated" => get_now(), "str_utistatut" => $STR_UTISTATUT, "lg_ageid" => $LG_AGEID[0]['lg_ageid'],
                "lg_proid" => $LG_PROID[0]['lg_proid'], "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($STR_UTIPASSWORD) {
                $params_to_update["str_utipassword"] = sha1($STR_UTIPASSWORD);
            }

            if ($this->dbconnnexion != null) {
                if (Merge($this->Utilisateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Mise à jour des données l'utilisateur effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Échec de l'opération");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de l'opération. Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    public function deleteUtilisateur($LG_UTIID, $OUtilisateur)
    {
        $validation = false;
        Parameters::buildErrorMessage("Échec de suppression de l'utilisateur");
        try {
            $OUtilisateur = $this->getUtilisateur($LG_UTIID);
            if ($OUtilisateur == null) {
                Parameters::buildErrorMessage("Utilisateur inexistant");
                return false;
            }

            $params_condition = array("LG_UTIID" => $OUtilisateur[0]['lg_utiid']);
            $params_to_update = ["str_utistatut" => Parameters::$statut_delete, "lg_utiupdatedid" => $OUtilisateur[0]['lg_utiid'] ?? Parameters::$defaultAdminId, "dt_utiupdated" => get_now()];

            if ($this->dbconnnexion !== null) {
                if (Merge($this->Utilisateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Utilisateur supprimé avec succès");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de suppression de l'utilisateur. Veuillez contacter votre administrateur");
        }
        return $validation;
    }


    //moi
    public function createDocument($P_KEY, $STR_DOCPATH, $LG_LSTID, $OUtilisateur): string
    {
        $validation = "";
        $LG_DOCID = generateRandomString(20);
        $LG_LSTID = $this->getListe($LG_LSTID);

        try {
            if ($LG_LSTID == null) {
                Parameters::buildErrorMessage("Type de document inexistant");
                return "";
            }
            $params = array("lg_docid" => $LG_DOCID, "p_key" => $P_KEY, "str_docpath" => $STR_DOCPATH, "dt_doccreated" => get_now(), "str_docstatut" => Parameters::$statut_enable, "lg_lstid" => $LG_LSTID[0]['lg_lstid'], "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Persist($this->Document, $params, $this->dbconnnexion)) {
                    $validation = $params['lg_docid'];
                    Parameters::buildSuccessMessage("Document uploadé avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de création du document");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de création du document. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //moi
    public function getDocument($LG_DOCID): ?array
    {
        $validation = null;
        Parameters::buildErrorMessage("Document inexistant");

        try {
            $params_condition = array("lg_docid" => $LG_DOCID, "p_key" => $LG_DOCID);
            $validation = $this->ODocument = Find($this->Document, $params_condition, $this->dbconnnexion, "OR");
            if ($this->ODocument == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Document " . $this->ODocument[0]['lg_docid'] . " trouvé");
            $validation = $this->ODocument;
        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $validation;
    }

    public function showAllOrOneClientRequest($FILTERS_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";
        Parameters::buildSuccessMessage("Demandes trouvées");

        try {
            if (!empty($FILTERS_OPTIONS)) {
                $query = "
                    select $select
                    from utilisateur as uti
                        inner join agence as age on uti.lg_ageid = age.lg_ageid
                        inner join societe as soc on age.lg_socid = soc.lg_socid
                    where ";

                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "search") {
                        $WHERE[] = "(soc.str_socname LIKE ? OR soc.str_socmail LIKE ?)";
                    }
                    if ($key == "status") {
                        $WHERE[] = "uti.str_utistatut = ? AND soc.str_socstatut = ?";
                    }
                }

                $WHERE[] = "age.str_agestatut = ? AND uti.lg_proid = ? AND soc.lg_socid != ?";
                $query .= implode(" AND ", $WHERE);

                $params = [];

                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "search") {
                        for ($i = 0; $i < 2; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    }
                    if ($key == "status") {
                        for ($i = 0; $i < 2; $i++) {
                            $params[] = $value;
                        }
                    }
                }
            } else {
                $query = "
                select distinct $select
                from utilisateur as uti
                     inner join agence as age on uti.lg_ageid = age.lg_ageid
                     inner join societe as soc on age.lg_socid = soc.lg_socid
            where  
                uti.str_utistatut = ?
                and soc.str_socstatut = ?
                and age.str_agestatut = ?
                and uti.lg_proid = ?
                and soc.lg_socid != ?
                ";

                $params[] = Parameters::$statut_enable;
                $params[] = Parameters::$statut_enable;
            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = Parameters::$statut_enable;
            $params[] = Parameters::$client_profil_ID;
            $params[] = "1";
//            var_dump($query);
            $res = $this->dbconnnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if (!$arraySql) {
                Parameters::buildSuccessMessage("Aucune demandes trouvées");
                $arraySql = [];
            }

            $newSelect = "COUNT(*) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir toutes les demandes");
        }
        return ["data" => $arraySql, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function getClientDemande($LG_SOCID, $STATUT = null)
    {
        $validation = "";
        try {
            $query = "select distinct *,
                (select lst.str_lstdescription
                 from liste as lst
                 where soc.lg_lstpayid = lst.lg_lstid and lst.str_lststatut = 'enable')     as str_paysfacturation,
                (select lst.str_lstdescription
                 from liste as lst
                 where soc.lg_lsttypesocid = lst.lg_lstid and lst.str_lststatut = 'enable') as str_typesociete
from utilisateur as uti
                         inner join agence as age on uti.lg_ageid = age.lg_ageid
                         inner join societe as soc on age.lg_socid = soc.lg_socid
                where soc.lg_socid = :LG_SOCID
group by uti.str_utifirstlastname, soc.str_socname, soc.str_socsiret, soc.str_soccode, soc.str_socphone, soc.str_socmail, soc.str_socdescription, soc.str_socstatut, soc.lg_socid
                ";
            $demandeData = Finds($query, $this->dbconnnexion, ['LG_SOCID' => $LG_SOCID]);

            if ($demandeData) {
                Parameters::buildSuccessMessage("Demande trouvée");
                $validation = $demandeData;
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Impossible de trouver la demande");
        }

        return $validation;
    }

    public function createClientExternal($LG_SOCID, $OUtilisateur)
    {
        $validation = "";
        $error = "";
        Parameters::buildSuccessMessage("Création du client chez 8sens réussi . ");
        $url = Parameters::$urlRootAPI . "/clients";
        try {
            $header = array(
                "Accept: application/json",
                "api_key: ZghY887665YhGH",
                "Content-Type: application/json",
                "token: " . $this->generateToken()
            );


            $demandeData = $this->getClientDemande($LG_SOCID);
            if ($demandeData == null) {
                Parameters::buildErrorMessage("Échec de la création du client chez 8sens. Veuillez contacté votre administrateur");
                return $validation;
            }
            $data = array(
                "clilib" => $demandeData[0][0]['str_socname'],//STR_SOCNAME
                "clilogin" => $demandeData[0][0]['str_utilogin'],//str_utilogin
                "moctel" => $demandeData[0][0]['str_socphone'],
                "mocport" => $demandeData[0][0]['str_socphone'],
                "mocmail" => $demandeData[0][0]['str_socmail'],
                "clicategenu" => $demandeData[0][0]['str_typesociete'],
                "clisiret" => $demandeData[0][0]['str_socsiret'],
                "pyscode" => $demandeData[0][0]['str_paysfacturation'],
                "prsprenom" => $demandeData[0][0]['str_utifirstlastname'],
                "prsname" => $demandeData[0][0]['str_utifirstlastname'],
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            $response = curl_exec($ch);
            $obj = json_decode($response);
            curl_close($ch);
//            var_dump($obj);

            if (!property_exists($obj, 'error')) {
                $validation = $obj->CliID;
                //Obtenir les infos de l'utilisateur
                $uti_data = $this->getUtilisateur($demandeData[0][0]['lg_utiid']);
                //mise à jour
                $this->updateUtilisateur($uti_data[0]['lg_utiid'], Parameters::$statut_enable, $uti_data[0]['str_utifirstlastname'], $uti_data[0]['str_utiphone'], $uti_data[0]['str_utimail'], $uti_data[0]['str_utilogin'], $uti_data[0]['lg_ageid'], null, $uti_data[0]['lg_proid'], $OUtilisateur ?: null, $uti_data[0]['str_utipassword']);

                //Obtenir les infos de la societe
                $soc_data = $this->getSociete($demandeData[0][0]['lg_socid']);
                $this->updateSociete($soc_data[0]['lg_socid'], $obj->CliID, Parameters::$statut_enable, $soc_data[0]['str_socdescription'], $soc_data[0]['str_socname'], null, $soc_data[0]['str_socmail'], $soc_data[0]['str_socphone'], $soc_data[0]["str_socsiret"], $soc_data[0]['lg_lsttypesocid'], $soc_data[0]['lg_lstpayid'], $soc_data[0]['str_soccode'], $soc_data[0]['dbl_socplafond'], $OUtilisateur ?: null);

                Parameters::buildSuccessMessage("Création du client réussi avec ID: " . $obj->CliID);

                $validation = $this->getClientDemande($demandeData[0][0]['lg_socid']);

            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    //moi
    public function deleteProduitSubstitution($LG_PROSUBID)
    {
        $validation = "";
        try {
            $this->OProduitSubstitution = $this->getProduitSubstitution($LG_PROSUBID);
            if ($this->OProduitSubstitution == null) {
                Parameters::buildErrorMessage("Échec de la mise à jour du produit de substitution, ID inexistant");
                return $validation;
            }
            $params = array("lg_prosubid" => $this->OProduitSubstitution[0]['lg_prosubid']);
            if (Remove($this->ProduitSubstitution, $params, $this->dbconnnexion)) {
                $validation = $this->OProduitSubstitution[0]["lg_prosubid"];
                Parameters::buildSuccessMessage("Suppression du produit avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de suppression du produit");
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de la suppression du produit de substitution" . $this->OProduitSubstitution[0]['lg_prosubid'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }


    public function createDemande($value)
    {
        $validation = false;

        try {
            $LG_SOCID = $this->createSociete($value->CliLib, $value->CliLib, null, $value->MocMail, $value->MocTel, $value->CliSiret, "0000000000000000000000000000000000000788", "0000000000000000000000000000000000000650", $value->CliNaf, 3, $value->CliID, $value->CliPlaf, Parameters::$statut_enable);

            if ($LG_SOCID == null) {
                Parameters::buildErrorMessage("Échec de l'enregistrement du client. Erreur: La création de la société à echouer");
                return $validation;
            }
            $LG_AGEID = $this->createAgence($value->CliLib, $value->CliLib, null, $value->MocTel, $LG_SOCID, 3, Parameters::$statut_enable);
            if ($LG_AGEID == null) {
                Parameters::buildErrorMessage("Échec de l'enregistrement du client. Erreur: La création de l'agence à echouer");
                return $validation;
            }
            $LG_UTIID = $this->createUtilisateur($value->CliLib, $value->MocTel, $value->MocMail, $value->CliCode, sha1("password"), $LG_AGEID, null, Parameters::$client_profil_ID, 3, Parameters::$statut_enable);
//            if ($DOCUMENTS) {
//
//                $this->uploadOneOrSeveralDocuments($DOCUMENTS, $LG_SOCID, $OUtilisateur);
//            }
            $validation = true;
            Parameters::buildSuccessMessage("Inscription réussi");
        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
        }

        return $validation;
    }

    public function createStat($table, $PCVID, $PCVGCLIID, $PCVDATE, $PCVLIB, $PCVREF, $PCVMTHT, $PCVMTTTC, $PCVMTTOTAL, $PCVETATFNUF = null)
    {
        $validation = "";
        Parameters::buildErrorMessage("Échec de création du stat devis");

        try {
            $params = array("PcvID" => $PCVID, "PcvGCliID" => $PCVGCLIID, "PcvDate" => $PCVDATE, "PcvLib" => $PCVLIB, "PcvRef" => $PCVREF, "PcvMtHT" => $PCVMTHT == "" ? 0 : $PCVMTHT, "PcvMtTTC" => $PCVMTTTC == "" ? 0 : $PCVMTTTC, "PcvMtTotal" => $PCVMTTOTAL == "" ? 0 : $PCVMTTOTAL);

            if ($table === "stat_facture") {
                $params["PcvEtatFNuf"] = $PCVETATFNUF;
            }

            if ($this->dbconnnexion != null) {
                if (Persist($table, $params, $this->dbconnnexion)) {
                    $validation = $PCVID;
                    Parameters::buildSuccessMessage("Stat devis créé avec succès");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de création du stat devis. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getStat($table, $PCVID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Item inexistant");
        try {
            $params_condition = array("PcvID" => $PCVID);
            $validation = Find($table, $params_condition, $this->dbconnnexion);
            if ($validation == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Item trouvé");
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
        }
        return $validation;
    }

    public function updateStat($table, $PCVID, $PCVGCLIID, $PCVDATE, $PCVLIB, $PCVREF, $PCVMTHT, $PCVMTTTC, $PCVMTTOTAL, $PCVETATFNUF = null)
    {
        $validation = "";
        Parameters::buildErrorMessage("Échec de la mise à jour");
        try {
            $params_to_condition = array("PcvID" => $PCVID);
            $params_to_update = array("PcvGCliID" => $PCVGCLIID, "PcvDate" => $PCVDATE, "PcvLib" => $PCVLIB, "PcvRef" => $PCVREF, "PcvMtHT" => $PCVMTHT == "" ? 0 : $PCVMTHT, "PcvMtTTC" => $PCVMTTTC == "" ? 0 : $PCVMTTTC, "PcvMtTotal" => $PCVMTTOTAL == "" ? 0 : $PCVMTTOTAL);

            if ($table === "stat_facture") {
                $params_to_update["PcvEtatFNuf"] = $PCVETATFNUF;
            }

            if ($this->dbconnnexion != null) {
                if (Merge($table, $params_to_update, $params_to_condition, $this->dbconnnexion)) {
                    $validation = $PCVID;
                    Parameters::buildSuccessMessage("Item créé avec succès");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de création. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function uploadOneOrSeveralDocuments($DOCUMENTS, $LG_SOCID, $OUtilisateur): array
    {
        $files = $DOCUMENTS[0];
        $filesCount = count($files['name']);
        $post = $DOCUMENTS[1];

        $array = [];

        for ($i = 0; $i < $filesCount; $i++) {
            $fileTmpName = $files['tmp_name'][$i]['file'];
//            var_dump($fileTmpName);
            $fileName = $files['name'][$i]['file'];
//            var_dump($fileName);
            $fileSize = $files['size'][$i]['file'];
            $LG_LSTID = $post[$i]['LG_LSTID'];

            $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            $is_IMG = in_array(strtolower($fileExtension), $imageTypes);

            $STR_DOCPATH = uploadFile(Parameters::$rootFolderAbsolute . "documents/" . $LG_SOCID . "/", ['tmp_name' => $fileTmpName, 'name' => $fileName, 'size' => $fileSize], $is_IMG);
            $ID = $this->createDocument($LG_SOCID, $STR_DOCPATH, $LG_LSTID, $OUtilisateur);
            $array[] = [
                "LG_DOCID" => $ID,
                "STR_DOCPATH" => Parameters::$rootFolderRelative . "documents/" . $LG_SOCID . "/" . $STR_DOCPATH,
                "DT_DOCCREATED" => get_now(),
                "str_ACTION" => '<span class="text-warning" title="Mise à jour du document"></span>'
            ];
        }

        return $array;
    }

    //moi
    public function rejectRegistration($LG_SOCID, $OUtilisateur)
    {
        $validation = null;
        try {
            $this->OSociete = $this->getSociete($LG_SOCID);

            if ($this->OSociete == null) {
                Parameters::buildErrorMessage("Échec du rejet du client, ID inexistant");
                return $validation;
            }

            $params_condition = array("lg_socid" => $this->OSociete[0]['lg_socid']);
            $params_to_update = array("str_socstatut" => Parameters::$statut_delete, "dt_socupdated" => get_now(),
                "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Societe, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = $this->getClientDemande($LG_SOCID);
                    Parameters::buildSuccessMessage("Demande du" . $this->OSociete[0]['lg_socid'] . " rejetée avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec du rejet du client" . $this->OSociete[0]['lg_socname']);
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec du rejet du client" . $this->OSociete[0]['lg_socname'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function markProductAsViewed($LG_PROID, $OUtilisateur)
{
    $validation = false;
    $StockManger = new StockManager();
    $product = $StockManger->showAllOrOneProduct_legacy($LG_PROID);

    if ($product == null || !isset($product[0]['str_prodescription'])) {
        Parameters::buildErrorMessage("Produit inexistant ou description manquante");
        return $validation;
    }

    try {
        $params = array(
            "lg_pistid" => generateRandomNumber(),
            "p_key" => $product[0]['lg_proid'],
            "lg_lstid" => Parameters::$lst_viewed_product,
            "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]["lg_utiid"] : Parameters::$defaultAdminId,
            "dt_pistcreated" => get_now(),
            "str_piststatut" => Parameters::$statut_enable
        );

        if ($this->dbconnnexion != null) {
            if (Persist($this->Piste_audit, $params, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Produit " . $product[0]['str_prodescription'] . " marqué comme vu avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de marquage du produit comme vu");
            }
        }
    } catch (Exception $exc) {
        error_log($exc->getTraceAsString());
        Parameters::buildErrorMessage("Échec de marquage du produit comme vu. Veuillez contacter votre administrateur");
    }

    return $validation;
}


    //moi
    public function uploadMainImageProduct($PICTURE, $LG_PROID, $OUtilisateur)
    {
        $validation = false;
        Parameters::buildErrorMessage("Échec de l'upload de l'image principale du produit " . $LG_PROID);
        $rootFolderRelative = __DIR__ . "/../images/";
        try {
            if ($PICTURE != null && isset($PICTURE['name']['main'])) {
                $mainImage = [
                    'tmp_name' => $PICTURE['tmp_name']['main'],
                    'name' => $PICTURE['name']['main'],
                    'size' => $PICTURE['size']['main'],
                    'error' => $PICTURE['error']['main']
                ];

                $mainFileExtension = pathinfo($mainImage['name'], PATHINFO_EXTENSION);
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                if (in_array(strtolower($mainFileExtension), $imageExtensions)) {
                    $STR_PROPIC_MAIN = uploadFile($rootFolderRelative . "produits/" . $LG_PROID . "/", $mainImage, true);


                    $params_condition = array("lg_proid" => $LG_PROID);
                    $params_to_update = array(
                        "str_propic" => $STR_PROPIC_MAIN,
                        "dt_proupdated" => get_now(),
                        "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId
                    );

                    if ($this->dbconnnexion != null) {
                        if (Merge($this->Produit, $params_to_update, $params_condition, $this->dbconnnexion)) {
                            Parameters::buildSuccessMessage("Image principale du produit " . $LG_PROID . " uploadée avec succès");
                            $validation = $STR_PROPIC_MAIN;
                        } else {
                            Parameters::buildErrorMessage("Échec de l'upload de l'image principale du produit " . $LG_PROID);
                        }
                    }
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    public function uploadThumbImagesProduct($PICTURES, $LG_PROID, $OUtilisateur)
    {
        $validation = [];
        $rootFolderRelative = __DIR__ . "/../images/";
        try {
            if ($PICTURES != null && isset($PICTURES['name']['thumbnail']) && is_array($PICTURES['name']['thumbnail'])) {
                foreach ($PICTURES['name']['thumbnail'] as $index => $thumbnailName) {
                    $thumbnailImage = [
                        'tmp_name' => $PICTURES['tmp_name']['thumbnail'][$index],
                        'name' => $thumbnailName,
                        'size' => $PICTURES['size']['thumbnail'][$index],
                        'error' => $PICTURES['error']['thumbnail'][$index]
                    ];
                    $thumbnailFileExtension = pathinfo($thumbnailImage['name'], PATHINFO_EXTENSION);
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    if (in_array(strtolower($thumbnailFileExtension), $imageExtensions)) {
                        $STR_PROPIC_THUMBNAIL = uploadFile($rootFolderRelative . "produits/" . $LG_PROID . "/", $thumbnailImage, true);
                        $LG_DOCID = generateRandomNumber(20);
                        $params = array("lg_docid" => $LG_DOCID, "p_key" => $LG_PROID, "str_docpath" => $STR_PROPIC_THUMBNAIL, "dt_doccreated" => get_now(), "str_docstatut" => Parameters::$statut_enable, "lg_lstid" => 5, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
                        if ($this->dbconnnexion != null) {
                            if (Persist($this->Document, $params, $this->dbconnnexion)) {
                                $validation[] = ['id' => $LG_DOCID, 'url' => $STR_PROPIC_THUMBNAIL];
                                Parameters::buildSuccessMessage("Images secondaires téléchargées avec succès");
                            } else {
                                Parameters::buildErrorMessage("Échec du téléchargement des images secondaires");
                            }
                        }
                    }
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    public function deleteDocument($LG_DOCID, $OUtilisateur)
    {
        $validation = "";
        try {
            $this->ODocument = $this->getDocument($LG_DOCID);
            if ($this->ODocument == null) {
                Parameters::buildErrorMessage("Échec de la suppression de l'image, ID inexistant");
                return $validation;
            }
            $params = array("lg_docid" => $this->ODocument[0]['lg_docid']);
            $params_to_update = array("str_docstatut" => Parameters::$statut_delete, "dt_docupdated" => get_now(), "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
            if (Merge($this->Document, $params_to_update, $params, $this->dbconnnexion)) {
                $validation = $this->ODocument[0]["lg_docid"];
                Parameters::buildSuccessMessage("Image supprimée avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de suppression de l'image");
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de la suppression de l'image" . $this->ODocument[0]['lg_docid'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteProductMainImage($LG_PROID, $OUtilisateur)
    {
        $validation = false;
        $StockManager = new StockManager();
        try {
            $product = $StockManager->getProduct($LG_PROID);
            if (!$product) {
                Parameters::buildErrorMessage("Produit inexistant");
                return $validation;
            }

            $params_condition = array("lg_proid" => $LG_PROID);
            $params_to_update = array("str_propic" => null, "dt_proupdated" => get_now(), "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
            if (Merge($this->Produit, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Image principale du produit supprimée avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de la suppression de l'image principale du produit");
            }
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Échec de la suppression de l'image principale du produit Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    public function showAllOrOneCustomerRemote($search_value = null)
    {

        $arraySqlloadExternalCustomer = array();
        $token = "";
        try {
            $token = $this->generateToken();

            $url = Parameters::$urlRootAPI . "/clients?nb_by_page=1000&ColSuppl=CliPlaf";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

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
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllDocumentRemote($search_value, $date = null)
    {

        $array = array();
        try {
            $token = $this->generateToken();
            $backUpDate = $this->getListe(Parameters::$LAST_BACKUP_DATE);
            
            //echo "===" . $backUpDate[0]['str_lstvalue'] . "---";
//            echo ($date !== null ? "Year(PcvDate)=$date" : "PcvDate > '" . $backUpDate[0]['str_lstvalue']);
            $url = "http://160.120.155.165/v1/reqsel?select=" . urlencode("Select PcvID,PcvGCliID,PcvDate,PcvLib,PcvRef,PcvMtHT,PcvMtTTC,PcvMtTotal, PcvEtatFNuf FROM PCV where " . ($date !== null ? "Year(PcvDate)='".$date : "PcvDate > '" . $backUpDate[0]['str_lstvalue']) . "' AND PcvPnaNuf='".$search_value."'");//a decommenter en cas d'urgence
//            $url = "http://160.120.155.165/v1/reqsel?select=" . ("Select PcvID,PcvGCliID,PcvDate,PcvLib,PcvRef,PcvMtHT,PcvMtTTC,PcvMtTotal, PcvEtatFNuf FROM PCV where " . ($date !== null ? "Year(PcvDate)='".$date : "PcvDate > '" . $backUpDate[0]['str_lstvalue']) . "' AND PcvPnaNuf='".$search_value."'");

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

//            echo "Token:::" . $token;
//            echo "URL:::" . $url;
            
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);
            
//            var_dump($response);

            $obj = json_decode($response);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $array = $obj;

        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $array;
    }

    public function loadExternalCustomer()
    {
        $array = array();
        try {
            $array = $this->showAllOrOneCustomerRemote();

            foreach ($array->clients as $value) {
                $societe = Find("societe", ["str_socname" => $value->CliLib], $this->dbconnnexion);

                if ($societe === null) {
                    $this->createDemande($value);
                } else {
                    $this->updateDemande($value, $societe[0]["lg_socid"]);
                }
            }

        } catch (\Exception $exc) {
            error_log($exc->getMessage());
        }
        return $array;

    }

    public function updateDemande($value, $LG_SOCID)
    {
        $validation = false;
        try {
//            $this->updateSociete($LG_SOCID, $value->CliID, Parameters::$statut_enable, $value->CliLib, $value->CliLib, null, $value->MocMail, $value->MocTel, $value->CliSiret, "0000000000000000000000000000000000000788", "0000000000000000000000000000000000000650", $value->CliNaf, 3); //a decommenter en cas de probleme
            $this->updateSociete($LG_SOCID, $value->CliID, Parameters::$statut_enable, $value->CliLib, $value->CliLib, null, $value->MocMail, $value->MocTel, $value->CliSiret, "0000000000000000000000000000000000000788", "0000000000000000000000000000000000000650", $value->CliNaf, $value->CliPlaf);

            $agence = Find($this->Agence, ["lg_socid" => $LG_SOCID], $this->dbconnnexion);

            $utilisateur = Find($this->Utilisateur, ["lg_ageid" => $agence[0]["lg_ageid"]], $this->dbconnnexion);
            $this->updateUtilisateur($utilisateur[0]["lg_utiid"], Parameters::$statut_enable, $value->CliLib, $value->MocTel, $value->MocMail, $value->CliCode, $agence[0]["lg_ageid"], null, Parameters::$client_profil_ID, 3, "password");

            $validation = true;
            Parameters::buildSuccessMessage("Mise à jour de la demande réussie");
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Échec de la mise à jour de la demande");
        }
        return $validation;
    }

    public function loadExternalDocuments($table, $search, $date)
    {
        $array = array();
        try {
            $array = $this->showAllDocumentRemote($search, $date);


            foreach ($array->reqsel as $value) {
                if ($table === "commande") {
                    $order = Find("commande", ["PcvIDExt" => $value->PcvID], $this->dbconnnexion);
                    $user = Find("utilisateur", ["str_utifirstlastname" => $value->PcvLib], $this->dbconnnexion);
                    if ($order === null) {
                        if ($this->dbconnnexion) {
                            Persist("commande", ["lg_commid" => $value->PcvID, "dt_commcreated" => $value->PcvDate, "dt_commupdated" => $value->PcvDate, "str_commstatut" => Parameters::$statut_closed, "dbl_commmtht" => $value->PcvMtHT, "dbl_commmtttc" => $value->PcvMtTTC, "lg_ageid" => $user[0]["lg_ageid"], "lg_uticreatedid" => $user[0]['lg_utiid'], "lg_ageoriginid" => $user[0]['lg_ageid']], $this->dbconnnexion);
                        }
                    }

                } else {
                    $item = $this->getStat($table, $value->PcvID);


                    $PCVETATFNUF = $table == "stat_facture" ? $value->PcvEtatFNuf : null;

                    if ($item !== null) {
                        $this->updateStat($table, $value->PcvID, $value->PcvGCliID, $value->PcvDate, $value->PcvLib, $value->PcvRef, $value->PcvMtHT, $value->PcvMtTTC, $value->PcvMtTotal, $PCVETATFNUF);
                    } else {
                        $this->createStat($table, $value->PcvID, $value->PcvGCliID, $value->PcvDate, $value->PcvLib, $value->PcvRef, $value->PcvMtHT, $value->PcvMtTTC, $value->PcvMtTotal, $PCVETATFNUF);
                    }
                }
            }

            Parameters::buildSuccessMessage("Documents chargés avec succès");

        } catch (\Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $array;

    }


    public function uploadLocalImageProduct($DIRECTORY, $OUtilisateur)
    {
        $localImagesFolder = __DIR__ . "/../images/images-produits-extranet/";
        $validDirectory = $localImagesFolder . $DIRECTORY;
        $stockManager = new StockManager();
        $validation = null;
        $failedTab = array();
        try {
            if (!is_dir($validDirectory)) {
                echo "Ce n'est pas un répertoire valide.";
                return false;
            }

            $iterator = new DirectoryIterator($validDirectory);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isDot()) {
                    continue;
                }

                $fileName = $fileinfo->getFilename();
                $filePath = $fileinfo->getPathname();
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($fileExtension, $validExtensions)) {
                    echo "Fichier ignoré : $fileName (Extension non valide)<br>";
                    continue;
                }


                $pattern = '/^(.*?)[ _-](FACE|OUVERT-FACE|DOS|PROFIL|FACE-OUVERT|COTE|COTE2|OUVERT)(.*)\.jpg$/';

                if (preg_match($pattern, $fileName, $matches)) {
                    $productName = $matches[1]; // "ORNIPRIM B1 FL 1000 Doses"
                    $productImageType = $matches[2]; // "FACE"
                } else {
                    echo "Aucune correspondance trouvée: $fileName.<br>";
                    $failedTab[] = $fileName;
                }

                if (!$productName || !$productImageType) {
                    echo "Fichier ignoré : $fileName (Nom ou type non valide)<br>";
                    continue;
                }

                $product = $stockManager->getProduct(strtolower($productName));

                if (!$product) {
                    echo "Produit non trouvé : $productName<br>";
                    $failedTab[] = $fileName;
                    continue;
                }

                $destinationFolder = __DIR__ . "/../images/produits/" . $product[0]["lg_proid"] . "/";

                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, true);
                }

                $cleanedFileName = preg_replace('/[^a-zA-Z0-9\-.]/', '-', $fileName);
                if ($productImageType === "FACE") {
                    $prefixe = time();
                    $destinationPath = $destinationFolder . $prefixe . '-' . str_replace(" ", "", $cleanedFileName);
                    $STR_PROPIC_MAIN = $prefixe . '-' . str_replace(" ", "", $cleanedFileName);


                    if (copy($filePath, $destinationPath)) {
                        echo "Fait :" . $product[0]["lg_proid"] . " " . $STR_PROPIC_MAIN;

                        // Mise à jour de l'image principale dans la base de données
                        $params_condition = array("lg_proid" => $product[0]["lg_proid"]);
                        $params_to_update = array(
                            "str_propic" => $STR_PROPIC_MAIN,
                            "dt_proupdated" => get_now(),
                            "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId
                        );

                        if ($this->dbconnnexion != null) {
                            if (Merge($this->Produit, $params_to_update, $params_condition, $this->dbconnnexion)) {
                                Parameters::buildSuccessMessage("Image principale du produit " . $product[0]["lg_proid"] . " uploadée avec succès");
                                $validation = true;
                            } else {
                                Parameters::buildErrorMessage("Échec de l'upload de l'image principale du produit " . $product[0]["lg_proid"]);
                            }
                        }
                    }
                } else {

                    $prefixe = time();
                    $destinationPath = $destinationFolder . $prefixe . '-' . str_replace(" ", "", $cleanedFileName);
                    $STR_PROPIC_THUMBNAIL = $prefixe . '-' . str_replace(" ", "", $cleanedFileName);

                    if (copy($filePath, $destinationPath)) {
                        $LG_DOCID = generateRandomNumber(20);
                        $params = array("lg_docid" => $LG_DOCID, "p_key" => $product[0]["lg_proid"], "str_docpath" => $STR_PROPIC_THUMBNAIL, "dt_doccreated" => get_now(), "str_docstatut" => Parameters::$statut_enable, "lg_lstid" => 5, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
                        if ($this->dbconnnexion != null) {
                            if (Persist($this->Document, $params, $this->dbconnnexion)) {
                                $validation = $params['lg_docid'];
                                Parameters::buildSuccessMessage("Document uploadé avec succès");
                            } else {
                                Parameters::buildErrorMessage("Échec de création du document");
                            }
                        }
                    }
                }

            }
        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
        }

        return $failedTab;
    }

    public function sendEmail($SUBJECT, $BODY, $TO, $FROM) {
        $validation = false;
        Parameters::buildErrorMessage("Erreur lors de l'envoi de l'email");

        $mail = new PHPMailer(true);

        try {
//Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "davysgbala01@gmail.com";
            $mail->Password = "jyuemshgdfpghwjj";
            $mail->SMTPSecure = "PHPMailer::ENCRYPTION_STARTTLS";
            $mail->Port = 587;
            $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'      => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true
            )
        );

//Recipients
            $mail->setFrom($FROM);
            $mail->addAddress($TO);

            $htmlTemplate = file_get_contents(__DIR__ . '/../SN-Proveci-email-template.html');
            $htmlContent = str_replace('{MSG}', $BODY, $htmlTemplate);

//Content
            $mail->isHTML(true);
            $mail->Subject = $SUBJECT;
//            $mail->Body = $BODY;
            $mail->Body = $htmlContent;
            $mail->CharSet = 'UTF-8';

            if ($mail->send()) {
                Parameters::buildSuccessMessage("Email envoyé avec succès");
                $validation = true;
            }
        } catch (Exception $e) {
            var_dump($e);
        }

        return $validation;
    }

    public function showAllOrOneDocument($FILTER_OPTIONS): array
    {
        $arraySql = array();
        $WHERE = [];
        try {
            $query = "SELECT * FROM document WHERE ";
            if (!empty($FILTER_OPTIONS)) {
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "P_KEY") {
                        $WHERE[] = "p_key = ?";
                    }
                    if ($key === "LG_LSTID") {
                        $WHERE[] = "lg_lstid = ?";
                    }
                }
            }

            $WHERE[] = "(str_docstatut = ? " . (array_key_exists("DISABLE", $FILTER_OPTIONS) ? "OR str_docstatut = ? " : "") . ")";
            $query .= implode(" AND ", $WHERE);
            $params = [];
            foreach ($FILTER_OPTIONS as $key => $value) {
                if ($key === "P_KEY") {
                    $params[] = $value;
                }
                if ($key === "LG_LSTID") {
                    $params[] = $value;
                }
            }

            $params[] = "enable";
            if (array_key_exists("DISABLE", $FILTER_OPTIONS)) {
                $params[] = "disable";
            }

            if ($this->dbconnnexion != null) {
                $arraySql = Finds($query, $this->dbconnnexion, $params);
                if ($arraySql) {
                    Parameters::buildSuccessMessage("Documents recupérés avec succès");
                } else {
                    Parameters::buildSuccessMessage("Aucun document trouvé");
                }
            } else {
                Parameters::buildErrorMessage("Erreur lors de la connexion à la base de données");
                return $arraySql;
            }
        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
        }
        return $arraySql ? $arraySql[0] : [];
    }

    public function showAllOrOneListe($search_value, $LG_TYLID): array
    {
        $arraySql = array();
        try {
            $query = "
                    SELECT * 
                    FROM liste 
                    WHERE lg_tylid = :LG_TYLID
                ";

            if (isset($search_value)) {
                $query .= " AND (str_lstdescription LIKE :search_value OR str_lstvalue LIKE :search_value)";
            }

            $query .= " AND str_lststatut = :STR_LSTSTATUT";
            $params = ['LG_TYLID' => $LG_TYLID, 'STR_LSTSTATUT' => Parameters::$statut_enable];

            if (isset($search_value)) {
                $params['search_value'] = "%$search_value%";
            }
            if ($this->dbconnnexion != null) {
                $arraySql = Finds($query, $this->dbconnnexion, $params);
                if ($arraySql) {
                    Parameters::buildSuccessMessage("Eléments recupérées avec succès");
                } else {
                    Parameters::buildSuccessMessage("Aucune élement trouvée");
                }
            } else {
                Parameters::buildErrorMessage("Erreur lors de la connexion à la base de données");
                return $arraySql;
            }
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql ? $arraySql[0] : [];
    }

    public function showAllProductImages($LG_PROID)
    {
        $arraySql = array();
        try {

            $params = ['LG_PROID' => $LG_PROID];
            $this->OListe = Find($this->Produit, $params, $this->dbconnnexion);
            if ($this->OListe == null) {
                Parameters::buildSuccessMessage("Produit inexistant");
                return $arraySql;
            }

            if ($this->OListe[0]['str_propic']) {
                Parameters::buildSuccessMessage("Images du produit recupérées avec succès");
                $arraySql[] = $this->OListe[0];
            }
            $arraySql = array_merge($arraySql, $this->showAllOrOneDocument(['P_KEY' => $LG_PROID, 'LG_LSTID' => 5]));
            Parameters::buildSuccessMessage("Aucune image trouvée");
        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les images du produits");
        }

        return $arraySql;
    }


    public function createProfil($STR_PRONAME, $STR_PRODESCRIPTION, $LG_LSTID, $OUtilisateur)
    {
        $validation = "";
        try {
            $this->OListe = $this->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("Échec de création du profil, type de profil inexistant");
                return $validation;
            }
            $params = array("lg_proid" => generateRandomNumber(), "str_proname" => $STR_PRONAME, "str_prodescription" => $STR_PRODESCRIPTION, "lg_lstid" => $LG_LSTID, "dt_procreated" => get_now(), "str_prostatut" => Parameters::$statut_enable, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
            if ($this->dbconnnexion != null) {
                if (Persist($this->Profile, $params, $this->dbconnnexion)) {
                    $validation = $params['lg_proid'];
                    Parameters::buildSuccessMessage("Profil créé avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de création du profil");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de création du profil. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateProfil($LG_PROID, $STR_PRONAME, $STR_PRODESCRIPTION, $LG_LSTID, $OUtilisateur)
    {
        $validation = "";
        try {
            $this->OProfile = $this->getProfile($LG_PROID);
            if ($this->OProfile == null) {
                Parameters::buildErrorMessage("Échec de la mise à jour du profil, ID inexistant");
                return $validation;
            }
            $params_condition = array("lg_proid" => $this->OProfile[0]['lg_proid']);
            $params_to_update = array("str_proname" => $STR_PRONAME, "str_prodescription" => $STR_PRODESCRIPTION, "lg_lstid" => $LG_LSTID, "dt_proupdated" => get_now(), "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if (Merge($this->Profile, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = $this->OProfile[0]["lg_proid"];
                Parameters::buildSuccessMessage("Profil mis à jour avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de la mise à jour du profil");
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de la mise à jour du profil" . $this->OProfile[0]['str_proname'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteProfil($LG_PROID, $OUtilisateur)
    {
        $validation = "";
        try {
            $this->OProfile = $this->getProfile($LG_PROID);
            if ($this->OProfile == null) {
                Parameters::buildErrorMessage("Échec de la suppression du profil, ID inexistant");
                return $validation;
            }
            $params = array("lg_proid" => $this->OProfile[0]['lg_proid']);
            $params_to_update = ["str_prostatut" => Parameters::$statut_delete];
            if (Merge($this->Profile, $params_to_update, $params, $this->dbconnnexion)) {
                $validation = $this->OProfile[0]["lg_proid"];
                Parameters::buildSuccessMessage("Profil supprimé avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de suppression du profil");
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de la suppression du profil" . $this->OProfile[0]['str_proname'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getPrivilege($LG_PRIID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Privilège inexistant");
        try {
            $params_condition = array("LG_PRIID" => $LG_PRIID, "STR_PRINAME" => $LG_PRIID);
            $validation = $this->OPrivilege = Find($this->Privilege, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OProfile == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Privilège " . $this->OPrivilege[0]['lg_priid'] . " trouvé");
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de récupération du privilège" . $LG_PRIID . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getProfilPrivilege($LG_PROID, $LG_PRIID)
    {
        $validation = null;
        Parameters::buildErrorMessage("ProfilPrivilège inexistant");
        try {
            $params_condition = array("lg_priid" => $LG_PRIID, "lg_proid" => $LG_PROID);
            $validation = $this->OProfilePrivilege = Find($this->ProfilePrivilege, $params_condition, $this->dbconnnexion, "AND");

            if ($this->OProfile == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("ProfilPrivilège trouvé");
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de la récupération, veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOnePrivilege($FILTER_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $select = "*";
        $query = [];
        $WHERE = [];
        try {
            if (!empty($FILTER_OPTIONS)) {
                $query = "SELECT $select FROM privilege p WHERE ";

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        $WHERE[] = "(p.str_priname LIKE ? OR p.str_pridescription LIKE ? OR p.str_priurl LIKE ? OR p.str_pritype LIKE ? OR p.str_prikind LIKE ? OR p.str_priclass LIKE ? OR p.int_pripriority LIKE ? )";
                    } else {
                        $WHERE[] = "p" . $key . "= ? ";
                    }
                }

                $WHERE[] = " p.str_pristatut = ?";
                $query .= implode(" AND ", $WHERE);
                $params = [];
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        for ($i = 0; $i < 7; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    } else {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "SELECT $select FROM privilege WHERE str_pristatut = ? ORDER BY str_priname";
            }

            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = Parameters::$statut_enable;

            $res = $this->dbconnnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            $newSelect = "COUNT(*) total";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
//            var_dump($queryCount);
            $res = $this->dbconnnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

            Parameters::buildSuccessMessage("Privilèges récupérés avec succès");

        } catch (\Exception $exc) {
            var_dump($exc->getMessage());
            Parameters::buildErrorMessage("Échec de récupération des privilèges. Veuillez contacter votre administrateur");
        }

        return ["data" => $arraySql, "total" => $count[0]["total"] == null ? 0 : ($count[0]["total"])];
    }

    public function createPrivilege($STR_PRINAME, $STR_PRIDESCRIPTION, $STR_PROIURL, $STR_PRITYPE, $STR_PRIKIND, $STR_PRICLASS, $INT_PRIPRIORITY, $LG_PRIPARENTID, $LG_PRIGROUPID, $OUtilisateur)
    {
        $validation = "";
        try {
            $params = array("lg_priid" => generateRandomNumber(), "str_priname" => $STR_PRINAME, "str_pridescription" => $STR_PRIDESCRIPTION, "str_priurl" => $STR_PROIURL, "str_pritype" => $STR_PRITYPE, "str_prikind" => $STR_PRIKIND, "str_priclass" => $STR_PRICLASS, "int_pripriority" => $INT_PRIPRIORITY, "lg_priparentid" => $LG_PRIPARENTID, "lg_prigroupid" => $LG_PRIGROUPID, "dt_pricreated" => get_now(), "str_pristatut" => Parameters::$statut_enable, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
            if ($this->dbconnnexion != null) {
                if (Persist($this->Privilege, $params, $this->dbconnnexion)) {
                    $validation = $params['lg_priid'];
                    Parameters::buildSuccessMessage("Privilège créé avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de création du privilège");
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de création du privilège. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updatePrivilege($LG_PRIID, $STR_PRINAME, $STR_PRIDESCRIPTION, $STR_PROIURL, $STR_PRITYPE, $STR_PRIKIND, $STR_PRICLASS, $INT_PRIPRIORITY, $LG_PRIPARENTID, $LG_PRIGROUPID, $OUtilisateur)
    {
        $validation = "";
        try {
            $this->OPrivilege = $this->getPrivilege($LG_PRIID);
            if ($this->OPrivilege == null) {
                Parameters::buildErrorMessage("Échec de la mise à jour du privilège, ID inexistant");
                return $validation;
            }
            $params_condition = array("lg_priid" => $this->OPrivilege[0]['lg_priid']);
            $params_to_update = array("str_priname" => $STR_PRINAME, "str_pridescription" => $STR_PRIDESCRIPTION, "str_priurl" => $STR_PROIURL, "str_pritype" => $STR_PRITYPE, "str_prikind" => $STR_PRIKIND, "str_priclass" => $STR_PRICLASS, "int_pripriority" => $INT_PRIPRIORITY, "lg_priparentid" => $LG_PRIPARENTID, "lg_prigroupid" => $LG_PRIGROUPID, "dt_priupdated" => get_now(), "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if (Merge($this->Privilege, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = $this->OPrivilege[0]["lg_priid"];
                Parameters::buildSuccessMessage("Privilège mis à jour avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de la mise à jour du privilège");
            }
        } catch (\Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Échec de la mise à jour du privilège" . $this->OPrivilege[0]['str_priname'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deletePrivilege($LG_PRIID, $OUtilisateur)
    {
        $validation = "";
        try {
            $this->OPrivilege = $this->getPrivilege($LG_PRIID);
            if ($this->OPrivilege == null) {
                Parameters::buildErrorMessage("Échec de la suppression du privilège, ID inexistant");
                return $validation;
            }
            $params = array("lg_priid" => $this->OPrivilege[0]['lg_priid']);
            $params_to_update = ["str_pristatut" => Parameters::$statut_delete];
            if (Merge($this->Privilege, $params_to_update, $params, $this->dbconnnexion)) {
                $validation = $this->OPrivilege[0]["lg_priid"];
                Parameters::buildSuccessMessage("Privilège supprimé avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de suppression du privilège");
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de la suppression du privilège" . $this->OPrivilege[0]['str_priname'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function assignPrivilegesToProfile($LG_PROID, $LG_PRIIDS)
    {
        $failedTab = [];
        $LG_PRIIDS = is_array($LG_PRIIDS) ? $LG_PRIIDS : [$LG_PRIIDS];
        try {
            if (is_array($LG_PRIIDS) && !empty($LG_PRIIDS)) {
                foreach ($LG_PRIIDS as $LG_PRIID) {
                    $this->OPrivilege = $this->getPrivilege($LG_PRIID);

                    if ($this->OPrivilege === null) {
                        $failedTab[] = $LG_PRIID;
                    } else {
                        $this->OProfilePrivilege = $this->getProfilPrivilege($LG_PROID, $LG_PRIID);
                        if ($this->OProfilePrivilege === null) {
                            $params = array("lg_pprid" => generateRandomNumber(), "lg_proid" => $LG_PROID, "lg_priid" => $LG_PRIID, "dt_pprcreated" => get_now(), "str_pprstatut" => Parameters::$statut_enable);
                            if ($this->dbconnnexion != null) {
                                if (Persist($this->ProfilePrivilege, $params, $this->dbconnnexion)) {
                                    Parameters::buildSuccessMessage("Privilèges assignés au profil avec succès");
                                } else {
                                    Parameters::buildErrorMessage("Échec de l'assignation des privilèges au profil");
                                    $failedTab[] = $LG_PRIID;
                                }
                            }
                        } else {
                            $params_conditions = array("lg_pprid" => $this->OProfilePrivilege[0]["lg_pprid"]);
                            $params_to_update = array("str_pprstatut" => Parameters::$statut_enable);
                            if ($this->dbconnnexion != null) {
                                if (Merge($this->ProfilePrivilege, $params_to_update, $params_conditions, $this->dbconnnexion)) {
                                    Parameters::buildSuccessMessage("Privilèges assignés au profil avec succès");
                                } else {
                                    Parameters::buildErrorMessage("Échec de l'assignation des privilèges au profil");
                                    $failedTab[] = $LG_PRIID;
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de l'assignation des privilèges au profil. Veuillez contacter votre administrateur");
        }

        if (count($failedTab) < count($LG_PRIIDS)) {
            Parameters::buildSuccessMessage("Privilèges assignés au profil avec succès");
        } else {
            Parameters::buildErrorMessage("Échec de l'assignation des privilèges au profil");
        }
        return $failedTab;
    }

    public function showAllProfilPrivileges($LG_PROID)
    {
        $validation = [];
        try {
            $query = "
                SELECT * 
                FROM profile_privilege pp
                INNER JOIN privilege p ON pp.lg_priid = p.lg_priid
                WHERE pp.lg_proid = :LG_PROID AND p.str_pristatut = :STR_PRISTATUT AND pp.str_pprstatut = :STR_PPRSTATUT
                ";
            $stmt = $this->dbconnnexion->prepare($query);
            $stmt->execute(["LG_PROID" => $LG_PROID, "STR_PRISTATUT" => Parameters::$statut_enable, "STR_PPRSTATUT" => Parameters::$statut_enable]);
            $validation = $stmt->fetchAll();

            if ($validation) {
                Parameters::buildSuccessMessage("Privilèges du profil récupérés avec succès");
            } else {
                Parameters::buildSuccessMessage("Aucun privilège trouvé pour le profil");
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
        }

        return $validation;
    }

    public function removePrivilegesFromProfile($LG_PROID, $LG_PRIIDS): bool
    {
        $validation = false;
        try {
            $LG_PRIIDS = is_array($LG_PRIIDS) ? $LG_PRIIDS : [$LG_PRIIDS];

//            $query = "DELETE FROM profile_privilege WHERE lg_proid = ? AND lg_priid IN (" . implode(',', array_fill(0, count($LG_PRIIDS), '?')) . ")";

            $query = "UPDATE profile_privilege SET str_pprstatut = ? WHERE lg_proid = ? AND lg_priid IN (" . implode(',', array_fill(0, count($LG_PRIIDS), '?')) . ")";


            $stmt = $this->dbconnnexion->prepare($query);

            $params = array_merge([Parameters::$statut_delete, $LG_PROID], $LG_PRIIDS);
            $validation = $stmt->execute($params);

            if ($validation) {
                Parameters::buildSuccessMessage("Privilèges retirés du profil avec succès");
            } else {
                Parameters::buildErrorMessage("Échec de la suppression des privilèges du profil");
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de la suppression des privilèges du profil. Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    public function createHistory($params)
    {
        Parameters::buildErrorMessage("Échec de création de l'historique");
        try {
            if ($this->dbconnnexion) {
                if (Persist("history", $params, $this->dbconnnexion)) {
                    Parameters::buildSuccessMessage("Historique créé avec succès");
                }
            }
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Impossible d'effectuer cette opération. Veuillez contacter votre admin.");
        }
    }

    public function loadExternalProductsByInvoice($invoice)
    {
        $CommandeManager = new CommandeManager();
        try {
            $products = $CommandeManager->showAllOrOneInvoiceProducts($invoice["PcvGCliID"], $invoice["PcvID"]);

            $dateTime = new DateTime($invoice["PcvDate"]);
            $month = $dateTime->format('F');
            $year = $dateTime->format('Y');
            foreach ($products->lines as $product) {
                $this->createHistory([strtolower($month) => is_numeric($product->PlvQteUV) ? (int)$product->PlvQteUV : null, "year" => $year, "str_procode" => $product->PlvCode, "lg_socextid" => $invoice["PcvGCliID"]]);
            }

        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Impossible d'effectuer cette opération, veuillez contactez votre administrateur");
        }
    }

    public function showAllOrOneDocumentAndType($P_KEY)
    {
        $validation = [];
        try {
            $query = "SELECT * FROM $this->Document d INNER JOIN $this->Liste l ON d.lg_lstid = l.lg_lstid WHERE p_key = :P_KEY";

            $result = Finds($query, $this->dbconnnexion, ["P_KEY" => $P_KEY]);
            if ($result) {
                $validation = $result;
            }
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Échec de récupération des documents");
        }

        return $validation;
    }

    public function slugifyProductName()
    {
        $StockManager = new StockManager();
        try {
            $products = $StockManager->showAllOrOneProduct([], 999999, 1);
            foreach ($products["products"] as $product) {
                if ($this->dbconnnexion) {
                    $slug = slugify($product["str_prodescription"]);
                    $params_condition = array("lg_proid" => $product["lg_proid"]);
                    $params_to_update = array("str_proslug" => $slug);
                    if (Merge($this->Produit, $params_to_update, $params_condition, $this->dbconnnexion)) {
                        Parameters::buildSuccessMessage("Slug du produit " . $product["str_proname"] . " généré avec succès");
                    } else {
                        Parameters::buildErrorMessage("Impossible de générer le slug du produit " . $product["str_proname"]);
                    }
                }
            }
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Impossible de générer le slug du produit");
        }
    }

    public function changeDocumentStatut($LG_DOCID, $OUtilisateur)
    {
        $validation = false;
        Parameters::buildErrorMessage("Impossible de désactiver le document");
        try {
            $document = Find($this->Document, ["lg_docid" => $LG_DOCID], $this->dbconnnexion);
            if ($document) {
                $params_condition = array("lg_docid" => $LG_DOCID);
                $params_to_update = array("str_docstatut" => $document[0]["str_docstatut"] == Parameters::$statut_enable ? Parameters::$statut_disable : Parameters::$statut_enable, "dt_docupdated" => get_now(), "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);
                if (Merge($this->Document, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Document désactivé avec succès");
                }
            }
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            Parameters::buildErrorMessage("Impossible de désactiver le document");
        }

        return $validation;
    }

    public function resetPasswordUtilisateur($STR_UTIMAIL, $OUtilisateur = null) {
    $validation = false;
    $response = array();
    try {
        // si la connexion à la base de données est valide
        /* if (!$this->dbconnnexion) {
            $response['code_statut'] = "0";
            $response['desc_statut'] = "Erreur de connexion à la base de données";
            echo json_encode($response);
            return $validation;
        }*/
        
        $this->OUtilisateur = $this->getUtilisateur($STR_UTIMAIL);

        if ($this->OUtilisateur == null) {
            $response['code_statut'] = "0";
            $response['desc_statut'] = "Echec de reinitialisation du mot de passe. Adresse incorrecte";
            echo json_encode($response);
            return $validation;
        }

        $STR_UTIPASSWORD = generateRandomString(5);
        $hashedPassword = sha1($STR_UTIPASSWORD);

        $params_condition = array("lg_utiid" => $this->OUtilisateur[0]['lg_utiid']);
        $params_to_update = array("str_utipassword" => $hashedPassword, "lg_utiupdatedid" => $this->OUtilisateur[0]['lg_utiid'], "dt_utiupdated" => get_now());

        if (Merge($this->Utilisateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
            $SUBJECT = "Réinitialisation de votre de passe SN Proveci";
            
            $BODY = "Bonjour cher client " . $this->OUtilisateur[0]['str_utifirstlastname'] . ",<br><br>";
            $BODY .= "Vous trouverez ci-dessous, vos nouveaux identifiants de connexion :<br>";
            $BODY .= "Login : " . $this->OUtilisateur[0]['str_utilogin'] . "<br>";
            $BODY .= "Mot de passe : " . $STR_UTIPASSWORD . "<br><br>";
            
            $htmlTemplate = file_get_contents(__DIR__ . '/../SN-Proveci-email-template.html');
            $htmlContent = str_replace('{MSG}', $BODY, $htmlTemplate);
            
            $FROM = "habibrolandt@gmail.com";
            $TO = $this->OUtilisateur[0]['str_utimail'];
            
            $emailrecu = $this->sendEmail($SUBJECT, $htmlContent, $TO, $FROM);
            if ($emailrecu) {
                $response['code_statut'] = "1";
                $response['desc_statut'] = "Mot de passe réinitialisé avec succès";
                $validation = true;
            } else {
                $response['code_statut'] = "1";
                $response['desc_statut'] = "Mot de passe réinitialisé mais échec de l'envoi de l'email";
                $validation = true;
            }
            
        } else {
            $response['code_statut'] = "0";
            $response['desc_statut'] = "Échec lors de la mise à jour de l'utilisateur.";
        }
        
        echo json_encode($response);
        return $validation;
    } catch (Exception $exc) {
        $response['code_statut'] = "0";
        $response['desc_statut'] = "Échec lors de la mise à jour de l'utilisateur. Veuillez contacter votre administrateur";
        echo json_encode($response);
        error_log($exc->getMessage());
        return $validation;
    }
}


    public function createProduitSubstitution($LG_PROPARENTID, $LG_PROKIDID, $OUtilisateur) {
        
    }

    public function deleteProductImage($LG_PROID, $OUtilisateur) {
        
    }

    public function dowloadDocuments($Documents, $LG_SOCID, $OUtilisateur) {
        
    }

    public function getAllUtilisateurs() {
        
    }

    public function getClientDemandes($statut) {
        
    }

    public function uploadProductPicture($PICTURES, $SUBSTITUTION_PRODUCTS, $LG_PROID, $OUtilisateur) {
        
    }

}

