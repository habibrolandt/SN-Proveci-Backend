<?php

interface StockInterface
{

    public function createProduct($LG_PROID, $STR_PROCODE, $STR_PRONAME, $STR_PRODESCRIPTION, $INT_PROPRICEVENTE, $INT_PROPRICEACHAT, $STR_PROCATEG, $STR_PROFAMILLE, $STR_PROGAMME, $STR_PROESPECE, $INT_PROSTOCK);

    public function updateProduct($LG_PROID, $STR_PROCODE, $STR_PRONAME, $STR_PRODESCRIPTION, $INT_PROPRICEACHAT, $INT_PROPRICEVENTE, $STR_PROCATEG, $STR_PROFAMILLE, $STR_PROGAMME, $STR_PROESPECE, $INT_PROSTOCK);

    public function getProductRemote($LG_PROID, $token = null);

    public function showAllOrOneProduct_legacy($search_value);

    public function showAllOrOneProduct($FILTERS_OPTIONS, $LIMIT, $PAGE);

    public function showAllOrOneProductRemote($search_value, $start, $limit);

    public function totalProduct($search_value);

    public function loadExternalProduct();

    public function getSubstitutionProduct($LG_PROID);

    public function getProductListGammeCategoryAndSpecies();

    public function filterProductByGammeOrCategory($FILTER_OPTIONS);

    public function listProductPicture($LG_PROID);

    public function getProductStock($LG_PROID, $token);

    public function getProduct($search_value);

    public function listLastestItems($LIMIT, $PAGE);

    public function getProductsByCategory();
    
    public function showAllOrOneEspeceproduct();
    
    public function showAllOrOneCategoryproduct();
    
    public function showAllOrOneGammeproduct();

}

class StockManager implements StockInterface
{

    private $Produit = 'produit';
    private $OProduit = array();

    private $Document = "document";

    private $ProduitSubstitution = "produit_substitution";
    private $dbconnexion;

    //constructeur de la classe
    public function __construct()
    {
        $this->dbconnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    public function createProduct($LG_PROID, $STR_PROCODE, $STR_PRONAME, $STR_PRODESCRIPTION, $INT_PROPRICEACHAT, $INT_PROPRICEVENTE, $STR_PROCATEG, $STR_PROFAMILLE, $STR_PROGAMME, $STR_PROESPECE, $INT_PROSTOCK)
    {
        $validation = false;
        try {
            $params = [
                "lg_proid" => $LG_PROID,
                "str_proname" => $STR_PROCODE,
                "dt_procreated" => get_now(),
                "str_prostatut" => Parameters::$statut_enable,
                "str_prodescription" => $STR_PRONAME,
                "int_propriceachat" => $INT_PROPRICEACHAT == "" ? null : $INT_PROPRICEACHAT,
                "int_propricevente" => $INT_PROPRICEVENTE == "" ? null : $INT_PROPRICEVENTE,
                "str_procateg" => $STR_PROCATEG ?? null,
                "str_profamille" => $STR_PROFAMILLE ?: null,
                "str_progamme" => $STR_PROGAMME ?: null,
                "str_proespece" => $STR_PROESPECE ?? null,
                "str_prodetails" => $STR_PRODESCRIPTION ?: null,
                "int_prostock" => $INT_PROSTOCK,
            ];
            if ($this->dbconnexion != null) {
                if (Persist($this->Produit, $params, $this->dbconnexion)) {
                    $validation = true;
                }
            }
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $validation;
    }

    public function updateProduct($LG_PROID, $STR_PROCODE, $STR_PRONAME, $STR_PRODESCRIPTION, $INT_PROPRICEACHAT, $INT_PROPRICEVENTE, $STR_PROCATEG, $STR_PROFAMILLE, $STR_PROGAMME, $STR_PROESPECE, $INT_PROSTOCK)
    {
        $validation = false;


        try {
            $this->OProduit = $this->getProduct($STR_PROCODE)[0];

            if ($this->OProduit == null) {
                Parameters::buildErrorMessage("Échec de la mise à jour. Produit inexistant");
                return $validation;
            }


            $params_condition = array("lg_proid" => $this->OProduit['lg_proid']);

            $params_to_update = [
                "dt_proupdated" => get_now(),
                "str_prostatut" => Parameters::$statut_enable,
                "str_prodescription" => $STR_PRONAME,
                "int_propriceachat" => (int)$INT_PROPRICEACHAT,
                "int_propricevente" => (int)$INT_PROPRICEVENTE,
                "str_procateg" => $STR_PROCATEG,
                "str_profamille" => $STR_PROFAMILLE ?: null,
                "str_progamme" => $STR_PROGAMME ?: null,
                "str_proespece" => $STR_PROESPECE,
                "str_prodetails" => $STR_PRODESCRIPTION ?: null,
                "int_prostock" => $INT_PROSTOCK,
            ];
            if ($this->dbconnexion != null) {
                if (Merge($this->Produit, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Produit " . $STR_PRODESCRIPTION . " mis à jour avec succès");
                } else {
                    Parameters::buildErrorMessage("Échec de la mise à jour du produit " . $STR_PRODESCRIPTION);
                }
            }
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $validation;
    }

    public function getProductRemote($LG_PROID, $token = null)
    {

        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        try {
            $token = $token != null ? $token : $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/products/" . $LG_PROID . "?ColSuppl=ArtCategEnu,ArtFamilleEnu,ArtGammeEnu,ARTFREE0,ARTFREE1,ARTFREE2,ARTFREE3,ARTFREE4,ARTFREE5";

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
            $productInDataBase = $this->showAllOrOneProduct_legacy($arraySql->products[0]->ArtCode);
            $arraySql->products[0]->str_propic = $productInDataBase[0]["str_propic"];

        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }


    public function getSubstitutionProduct($LG_PROID)
    {
//        $arraySql = array();
        $validation = [];
        Parameters::buildSuccessMessage("Produits de substitution obtenus avec succès");
        try {
            $query = "SELECT *
                    FROM " . $this->ProduitSubstitution . " ps
                    LEFT JOIN " . $this->Produit . " p ON ps.lg_prokidid = p.lg_proid
                    LEFT JOIN document d ON ps.lg_prokidid = d.p_key
                    WHERE ps.lg_proparentid = :LG_PROID AND ps.str_prosubstatut = :STR_STATUT";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('LG_PROID' => $LG_PROID, 'STR_STATUT' => Parameters::$statut_enable));
            $rowObj = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($rowObj) {
                $validation = $rowObj;
            }
            if(count($validation) == 0){
                Parameters::buildSuccessMessage("Aucun produit de substitution trouvé");
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
            Parameters::buildErrorMessage("Impossible d'obtenir les produits de substitution");
        }
        return $validation;
    }

    public function listProductPicture($LG_PROID)
    {
        $validation = [];
        try {
            $query = "
                SELECT *
                FROM $this->Document d
                LEFT JOIN $this->Produit p ON d.p_key = p.lg_proid
                WHERE p.lg_proid = :LG_PROID AND d.str_docstatut = :STR_DOCSTATUT
            ";
            $res = $this->dbconnexion->prepare($query);
            $res->execute(["LG_PROID" => $LG_PROID, "STR_DOCSTATUT" => Parameters::$statut_enable]);
            $rowObj = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($rowObj) {
                $validation = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
        return $validation;
    }

    public function showAllOrOneProduct_legacy($search_value)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM produit t WHERE (t.str_prodescription LIKE :search_value OR t.lg_proid LIKE :search_value OR t.str_proname LIKE :search_value) AND t.str_prostatut = :STR_STATUT ORDER BY t.str_prodescription";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllOrOneProduct($FILTERS_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";
        Parameters::buildSuccessMessage("Liste des produits obtenus avec succès");
        try {
            if (!empty($FILTERS_OPTIONS)) {
                $query = "SELECT $select FROM produit t WHERE ";
                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "str_proespece") {
                        if (is_array($value)) {
                            $conditions = [];
                            foreach ($value as $val) {
                                $conditions[] = "t." . $key . " LIKE ?";
                            }
                            $WHERE[] = "(" . implode(" OR ", $conditions) . ")";
                        } else {
                            // Sinon, gérer comme un seul LIKE
                            $WHERE[] = "t." . $key . " LIKE ?";
                        }
                    } else if ($key == "search") {
                        $WHERE[] = "(t.str_prodescription LIKE ? OR t.str_proname LIKE ? OR t.str_progamme LIKE ? OR t.str_procateg LIKE ? OR t.str_proespece LIKE ? )";

                    } else {
                        $WHERE[] = "t." . $key . " IN (" . implode(',', array_fill(0, count($value), '?')) . ")";
                    }
                }


                $WHERE[] = "t.str_prostatut = ?";
                $query .= implode(" AND ", $WHERE);
                $query .= " ORDER BY t.str_prodescription";
                $params = [];

                foreach ($FILTERS_OPTIONS as $key => $value) {
                    if ($key == "str_proespece") {
                        foreach ($value as $v) {
                            $params[] = "%" . $v . "%";
                        }
                    } else if ($key == "search") {
                        for ($i = 0; $i < 5; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    } else {
                        $params = array_merge($params, $value);
                    }
                }

            } else {
                $query = "SELECT $select FROM produit t WHERE t.str_prostatut = ? ORDER BY t.str_prodescription";
            }


            $query .= " LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = Parameters::$statut_enable;
            $res = $this->dbconnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if(count($arraySql) == 0){
                Parameters::buildSuccessMessage("Aucun produit trouvé");
            }


            $newSelect = "COUNT(t.lg_proid) count";
            $queryCount = str_replace($select, $newSelect, $query);
            $queryCount = str_replace("LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT, "", $queryCount);
            $res = $this->dbconnexion->prepare($queryCount);
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            var_dump($e->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les produits");
        }
        return ["products" => $arraySql, "total" => $count[0]["count"] == null ? 0 : $count[0]["count"]];
    }

    public function getProduct($search_value)
    {
        $arraySql = array();
        Parameters::buildSuccessMessage("Liste des produits");
        try {
            $query = "SELECT * FROM produit t WHERE  (t.str_proname = :search_value OR t.lg_proid = :search_value OR t.str_prodescription = :search_value) AND t.str_prostatut = :STR_STATUT ORDER BY t.str_prodescription LIMIT 1";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch(PDO::FETCH_ASSOC)) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les produits");
        }
        return $arraySql;
    }

    public function totalProduct($search_value)
    {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.lg_proid) NOMBRE FROM produit t WHERE (t.str_prodescription LIKE :search_value OR t.str_proname LIKE :search_value) AND t.str_prostatut = :STR_STATUT";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $result;
    }

    public function showAllOrOneProductRemote($search_value, $start, $limit)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        $token = "";
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/products?nb_by_page=" . $limit . "&ColSuppl=ArtCategEnu,ArtFamilleEnu,ArtGammeEnu,ARTFREE0,ARTFREE1,ARTFREE2,ARTFREE3,ARTFREE4,ARTFREE5,ArtFree2";
// ArtCategEnu,ArtFamilleEnu,ArtGammeEnu,ARTFREE0,ARTFREE1,ARTFREE3,ARTFREE4,ARTFREE5,ArtFree2

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            //echo $data->orderId;
//            var_dump($headers);
//            echo json_encode($dataSend);
            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);
//            var_dump($response);
            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
//            var_dump($arraySql);
            /* foreach ($obj as $value) { //a decommenter en cas de probleme
              $arrayJson_chidren = array();
              $arrayJson_chidren["LG_PROID"] = $value['lg_proid'];
              $arrayJson_chidren["STR_PROPIC"] = $value['str_propic'];
              $arrayJson_chidren["STR_PRONAME"] = $value['str_proname'];
              $arrayJson_chidren["STR_PRODESCRIPTION"] = $value['str_prodescription'];
              //        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
              //        $arrayJson_chidren["TRACREATED"] = DateToString($value['DT_TRACREATED'], 'd/m/Y H:i:s');
              $arrayJson_chidren["STR_PROEAN13"] = $value['str_proean13'];
              //$arrayJson_chidren["STR_PROPIC_DATA_TABLE"] = $value['str_propic'] != null ? "<img class='img-30' src='images/product/".$value['str_propic']."' alt='' style='width:30%;border-radius:5px;'>" : "<img class='img-30' src='images/product/profile.png' alt='' style='width:30%;border-radius:5px;'>";
              $arrayJson_chidren["INT_PROPRICEACHAT"] = $value['int_propriceachat'];
              $arrayJson_chidren["INT_PROPRICEVENTE"] = $value['int_propricevente'];
              $arrayJson_chidren["str_ACTION"] = "<div class='d-flex'><a href='javascript:void(0);' class='btn btn-primary shadow btn-xs sharp mr-1' title='Modification des informations de " . $value['str_proname'] . "'><i class='fa fa-pencil'></i></a><a href='javascript:void(0);' class='btn btn-warning shadow btn-xs sharp' title='Consultation des informations de " . $value['str_proname'] . "'><i class='fa fa-folder-o'></i></a></div>";
              $OJson[] = $arrayJson_chidren;
              } */

            /* $query = "SELECT * FROM ".$this->Produit." t WHERE (t.str_prodescription LIKE :search_value OR t.str_proname LIKE :search_value) AND t.str_prostatut = :STR_STATUT ORDER BY t.str_proname";
              $res = $this->dbconnexion->prepare($query);
              //exécution de la requête
              $res->execute(array('search_value' => "%" . $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
              while ($rowObj = $res->fetch()) {
              $arraySql[] = $rowObj;
              }
              $res->closeCursor(); */
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function loadExternalProduct()
    {
        $arrayJson = array();
        try {
            $arrayJson = $this->showAllOrOneProductRemote("", 1, 800);
            foreach ($arrayJson->products as $value) {
                $this->OProduit = $this->getProduct("$value->ArtCode")[0];


                if ($this->OProduit) {
                    $this->updateProduct($value->ArtID, $value->ArtCode, $value->ArtLib, $value->CmtTxt, $value->ArtLastPA, $value->ArtPrixBase,
                        $value->ArtCategEnu, $value->ArtFamilleEnu, $value->ArtGammeEnu, $value->ArtFree2, $value->ArtStk);
                } else {
                    $this->createProduct($value->ArtID, $value->ArtCode, $value->ArtLib, $value->CmtTxt, $value->ArtLastPA, $value->ArtPrixBase,
                        $value->ArtCategEnu, $value->ArtFamilleEnu, $value->ArtGammeEnu, $value->ArtFree2, $value->ArtStk);
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function getProductListGammeCategoryAndSpecies()
    {
        $result = array('gammes' => array(), 'categories' => array());
        try {
            // Récupérer les gammes des produits
            $queryGamme = "SELECT DISTINCT str_progamme FROM produit WHERE str_prostatut = :STR_STATUT";
            $resGamme = $this->dbconnexion->prepare($queryGamme);
            $resGamme->execute(array('STR_STATUT' => Parameters::$statut_enable));
            while ($rowGamme = $resGamme->fetch(PDO::FETCH_ASSOC)) {
                if ($rowGamme['str_progamme']) {
                    $result['gammes'][] = $rowGamme['str_progamme'];
                }
            }
            $resGamme->closeCursor();

            // Récupérer les catégories des produits
            $queryCategory = "SELECT DISTINCT str_procateg FROM produit WHERE str_prostatut = :STR_STATUT";
            $resCategory = $this->dbconnexion->prepare($queryCategory);
            $resCategory->execute(array('STR_STATUT' => Parameters::$statut_enable));
            while ($rowCategory = $resCategory->fetch(PDO::FETCH_ASSOC)) {
                if ($rowCategory['str_procateg']) {
                    $result['categories'][] = $rowCategory['str_procateg'];
                }
            }

            $querySpecies = "SELECT DISTINCT str_proespece FROM produit WHERE str_prostatut = :STR_STATUT";
            $resSpecies = $this->dbconnexion->prepare($querySpecies);
            $resSpecies->execute(array('STR_STATUT' => Parameters::$statut_enable));
            while ($rowSpecies = $resSpecies->fetch(PDO::FETCH_ASSOC)) {
                if ($rowSpecies['str_proespece']) {
                    $result['especes'][] = $rowSpecies['str_proespece'];
                }
            }
            $resCategory->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $result;
    }

    public function filterProductByGammeOrCategory($FILTER_OPTIONS)
    {

        $result = array();
        try {
            $queryFilter = "SELECT * FROM `produit` WHERE str_prostatut = :STR_STATUT";
            $params = array('STR_STATUT' => Parameters::$statut_enable);

            if (!empty($FILTER_OPTIONS)) {
                $optionsArray = explode(',', $FILTER_OPTIONS);
                $params = array_merge($params, $optionsArray);
                foreach ($optionsArray as $index => $value) {
                    $optionsPlaceholders[] = ":$value";
                    $params["$value"] = $value;
                }
                $queryFilter .= " AND str_progamme IN (" . implode(',', $optionsPlaceholders) . ")";
                $queryFilter .= " AND str_procateg IN (" . implode(',', $optionsPlaceholders) . ")";
            }

            $resFilter = $this->dbconnexion->prepare($queryFilter);
            $resFilter->execute($params);

            while ($row = $resFilter->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
            $resFilter->closeCursor();
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }

        return $result;
    }

    public function getProductStock($LG_PROID, $token)
    {
        $arraySql = 0;
        $ConfigurationManager = new ConfigurationManager();
        try {
            $token = $token ?: $ConfigurationManager->generateToken();

            $sqlQuery = "SELECT SUM(ArdStk) AS ArdStk FROM ARD LEFT JOIN ART ON ARD.ArdGArtID = ART.ArtID LEFT JOIN DEP ON ARD.ArdGDepID = DEP.DepID WHERE DEP.DepCode IN ('ABIDJAN', 'ENTREPOT.AGILITY') AND ArtID = '" . addslashes($LG_PROID) . "'";
            $url = Parameters::$urlRootAPI . "/reqsel?select=" . urlencode($sqlQuery);

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
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
        return $arraySql;
    }

    public function listLastestItems($LIMIT, $PAGE)
    {
        $arraySql = [];
        Parameters::buildSuccessMessage("Produits obtenus avec succès");
        try {
            $query = "
               SELECT *, p.lg_proid
                FROM produit p 
                LEFT JOIN commproduit cp ON p.lg_proid = cp.lg_proid
                WHERE cp.lg_proid IS NULL
                ORDER BY p.str_propic IS NOT NULL DESC, p.str_prodescription;
                LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $res = $this->dbconnexion->prepare($query);
            $res->execute();

            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);
//            var_dump($arraySql);

            if (!$arraySql) {
                Parameters::buildSuccessMessage("Aucun produit recupéré");
                $arraySql = [];
            }
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les produits");
        }

        return $arraySql;
    }

    public function getProductsByCategory()
    {
        $arraySql = [];
        try {
            $queryGammeList = "SELECT DISTINCT(p.str_progamme) FROM produit p GROUP BY p.str_progamme HAVING COUNT(p.lg_proid) > 5 ORDER BY RAND() LIMIT 5";
            $res = $this->dbconnexion->prepare($queryGammeList);
            $res->execute();
            $arrayGamme = $res->fetchAll(PDO::FETCH_ASSOC);
//            var_dump($arrayGamme);

            for ($i = 0; $i < count($arrayGamme); $i++) {
                $query = "SELECT * FROM produit WHERE str_progamme = :STR_PROGAMMME AND str_prostatut = :STR_STATUT ORDER BY RAND() LIMIT 5";
                $res = $this->dbconnexion->prepare($query);
                $res->execute(['STR_PROGAMMME' => $arrayGamme[$i]['str_progamme'], 'STR_STATUT' => Parameters::$statut_enable]);
                $arraySql[$arrayGamme[$i]['str_progamme']] = $res->fetchAll(PDO::FETCH_ASSOC);
            }

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
        return $arraySql;
    }


    public function showAllOrOneCategoryproduct() {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT str_procateg str_filter FROM ".$this->Produit." WHERE str_procateg != '' and str_prostatut = :STR_STATUT ORDER BY str_procateg";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("STR_STATUT" => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function showAllOrOneEspeceproduct() {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT str_proespece str_filter FROM ".$this->Produit." WHERE str_proespece != '' and str_prostatut = :STR_STATUT ORDER BY str_filter";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("STR_STATUT" => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc);//$exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function showAllOrOneGammeproduct() {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT str_progamme str_filter FROM ".$this->Produit." WHERE str_progamme != '' and str_prostatut = :STR_STATUT ORDER BY str_progamme";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("STR_STATUT" => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }
}
