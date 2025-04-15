<?php

interface StatistiqueInterface
{
    public function topOperateur($LG_SOCID, $DT_BEGIN, $DT_END, $limit);

    public function topTransaction($LG_SOCID, $DT_BEGIN, $DT_END, $limit);

    public function listProductsStatViewed($FILTER_OPTIONS, $LIMIT, $PAGE);

    public function ordersStatByYear($YEAR, $PCVGCLIID = null);

    public function listBestPurchaser($FILTER_OPTIONS, $LIMIT, $PAGE, $YEAR);
}

class StatistiqueManager implements StatistiqueInterface
{

    private $Transaction = 'TRANSACTION';
    private $Operateur = 'OPERATEUR';
    private $SocieteUtilisateur = 'SOCIETE_UTILISATEUR';
    private $Typetransaction = 'TYPETRANSACTION';
    private $dbconnnexion;

    private $StatFacture = "stat_facture";
    private $Produit = "produit";
    private $PisteAudit = "piste_audit";

    private $OProduit = array();
    private $OPisteAudit = array();


    //constructeur de la classe 
    public function __construct()
    {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    //liste des tops X du classement des opérateurs
    public function topOperateur($LG_SOCID, $DT_BEGIN, $DT_END, $limit)
    {
        $arraySql = array();
        try {
            $query = "SELECT COUNT(t.LG_TRAID) NOMBRE, sum(t.DBL_TRAAMOUNT) MONTANT, o.LG_OPEID, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID FROM " . $this->Transaction . " t, " . $this->Operateur . " o, " . $this->SocieteUtilisateur . " su
            WHERE t.LG_OPEID = o.LG_OPEID AND t.LG_SUTID = su.LG_SUTID and t.STR_TRASTATUT = :STR_STATUT AND su.LG_SOCID LIKE :LG_SOCID AND date(t.DT_TRACREATED) BETWEEN :DT_BEGIN AND :DT_END GROUP BY o.LG_OPEID, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID ORDER BY o.STR_OPEDESCRIPTION LIMIT " . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_SOCID" => $LG_SOCID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //liste des tops X du classement des transactions
    public function topTransaction($LG_SOCID, $DT_BEGIN, $DT_END, $limit)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.LG_TRAID, t.STR_TRAREFERENCE, t.STR_TRAPHONE, t.STR_OPEPHONE, t.DBL_TRAAMOUNT, t.STR_TRAOTHERVALUE, t.DT_TRACREATED, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID, tt.STR_TTRDESCRIPTION FROM " . $this->Transaction . " t, " . $this->Operateur . " o, " . $this->SocieteUtilisateur . " su, " . $this->Typetransaction . " tt 
                WHERE t.LG_OPEID = o.LG_OPEID AND t.LG_SUTID = su.LG_SUTID AND t.LG_TTRID = tt.LG_TTRID AND su.LG_SOCID LIKE :LG_SOCID AND date(t.DT_TRACREATED) BETWEEN :DT_BEGIN AND :DT_END and t.STR_TRASTATUT = :STR_STATUT ORDER BY t.DBL_TRAAMOUNT DESC LIMIT " . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_SOCID" => $LG_SOCID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function listProductsStatViewed($FILTER_OPTIONS, $LIMIT, $PAGE)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";

        Parameters::buildSuccessMessage("Statistique des produits consultés avec succès");
        try {
            if (!empty(($FILTER_OPTIONS))) {
                $query = "
                 SELECT p.*, 
                   COUNT(p_a.p_key) as 'nombre_de_vues', 
                   COUNT(DISTINCT p.lg_proid) as 'c'
                FROM piste_audit p_a
                LEFT JOIN produit p ON p_a.p_key = p.lg_proid
                WHERE ";

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        $WHERE[] = "(p.str_prodescription LIKE ?)";
                    }
                    if ($key == "month") {
                        $WHERE[] = " MONTH(`dt_pistcreated`)= ? ";
                    }
                }

                $WHERE[] = "p_a.lg_lstid = ? ";
                $query .= implode(" AND ", $WHERE);

                $params = [];
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        for ($i = 0; $i < 1; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    }
                    if ($key == "month") {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "
                      SELECT p.*, 
                       COUNT(p_a.p_key) as 'nombre_de_vues', 
                       COUNT(DISTINCT p.lg_proid) as 'c'
                        FROM piste_audit p_a
                    LEFT JOIN produit p ON p_a.p_key = p.lg_proid
                    WHERE  lg_lstid = ?
                    ";
            }

            $query .= " GROUP BY p_key ORDER BY COUNT( p_key) DESC LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = Parameters::$piste_audit_consultation;
            $res = $this->dbconnnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if (!$arraySql) {
                Parameters::buildSuccessMessage("Aucun produit trouvé");
                $arraySql = [];
            }


            $queryCount = "SELECT COUNT(*) as total_rows
               FROM (
                   SELECT p.lg_proid
                   FROM piste_audit p_a
                   LEFT JOIN produit p ON p_a.p_key = p.lg_proid
                   WHERE p_a.lg_lstid = ?"
                . (!empty($FILTER_OPTIONS) ? " AND (p.str_prodescription LIKE ?) AND MONTH(`dt_pistcreated`)= ? " : "") .
                "GROUP BY p.lg_proid
               ) as subquery";
            $res = $this->dbconnnexion->prepare($queryCount);
            if (!empty($FILTER_OPTIONS)) {
               $params = [
        Parameters::$piste_audit_consultation,
        "%" . ($FILTER_OPTIONS["search"] ?? "") . "%",
        $FILTER_OPTIONS["month"] ?? null
    ];

            }
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return ["data" => $arraySql, "total" => $count[0]["total_rows"] == null ? 0 : $count[0]["total_rows"]];

    }

    public function ordersStatByYear($YEAR, $PCVGCLIID = null)
    {
        $validation = [];
        try {
            $query = "SELECT MONTH(PcvDate) as month, COUNT(*) as totalOrders, SUM(PcvMtTotal) as totalAmount FROM $this->StatFacture WHERE " . ($PCVGCLIID ? "PCVGCLIID = $PCVGCLIID AND " : "") . " YEAR(PcvDate)= ? AND PcvEtatFNuf != 'NufPCVEtatFNo' GROUP BY MONTH(PcvDate) ORDER BY MONTH(PcvDate)";
            $res = $this->dbconnnexion->prepare($query);
            if (!$res->execute([$YEAR])) {
                Parameters::buildErrorMessage("Erreur lors de la récupération des statistiques des commandes");
                return [];
            }
            $validation = $res->fetchAll(PDO::FETCH_ASSOC);
            Parameters::buildSuccessMessage("Statistiques récupérées avec succès");
        } catch (Exception $exc) {
            error_log($exc->getMessage());
            Parameters::buildErrorMessage("Impossible d'obtenir les statistiques. Veuillez contactez votre administrateur");
        }
        return $validation;
    }

    public function listBestPurchaser($FILTER_OPTIONS, $LIMIT, $PAGE, $YEAR)
    {
        $arraySql = array();
        $WHERE = [];
        $select = "*";

        Parameters::buildSuccessMessage("Statistique des utilisateurs recuperée avec succès");
        try {
            if (!empty(($FILTER_OPTIONS))) {
                $query = "
                 SELECT *, COUNT(stat.PcvID) as 'total_cmd', SUM(stat.PcvMtTotal) as 'total_amout'
                FROM `stat_facture` stat 
                JOIN societe s ON stat.PcvGCliID = s.lg_socextid 
                JOIN agence age ON s.lg_socid = age.lg_socid 
                JOIN utilisateur uti ON age.lg_ageid = uti.lg_ageid 
                WHERE YEAR(stat.`PcvDate`) = ? AND ";

                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        $WHERE[] = "(uti.str_utifirstlastname LIKE ?)";
                    }
                }

                $query .= implode(" AND ", $WHERE);

                $params = [];
                foreach ($FILTER_OPTIONS as $key => $value) {
                    if ($key === "search") {
                        for ($i = 0; $i < 1; $i++) {
                            $params[] = "%" . $value . "%";
                        }
                    }
                    if ($key == "year") {
                        $params[] = $value;
                    }
                }
            } else {
                $query = "
                    SELECT  *, COUNT(stat.PcvGCliID) as 'total_cmd', SUM(stat.PcvMtTotal) as 'total_amout'
                    FROM `stat_facture` stat 
                    JOIN societe s ON stat.PcvGCliID = s.lg_socextid 
                    JOIN agence age ON s.lg_socid = age.lg_socid 
                    JOIN utilisateur uti ON age.lg_ageid = uti.lg_ageid 
                    WHERE YEAR(stat.`PcvDate`) = ?
                    ";
            }

            $query .= " GROUP BY uti.lg_utiid ORDER BY COUNT(stat.PcvID) DESC LIMIT $LIMIT OFFSET " . ($PAGE - 1) * $LIMIT;
            $params[] = $YEAR;
            $res = $this->dbconnnexion->prepare($query);
            $res->execute($params);
            $arraySql = $res->fetchAll(PDO::FETCH_ASSOC);

            if (!$arraySql) {
                Parameters::buildSuccessMessage("Aucun utilisateur trouvé");
                $arraySql = [];
            }


            $queryCount = "SELECT COUNT(*) as total_rows
                            FROM (
                                SELECT uti.lg_utiid
                                FROM `stat_facture` stat
                                JOIN societe s ON stat.PcvGCliID = s.lg_socextid
                                JOIN agence age ON s.lg_socid = age.lg_socid
                                JOIN utilisateur uti ON age.lg_ageid = uti.lg_ageid
                                WHERE YEAR(stat.`PcvDate`) = ?
                                GROUP BY uti.lg_utiid
                            ) as subquery;";
            $res = $this->dbconnnexion->prepare($queryCount);
            if (!empty($FILTER_OPTIONS)) {
                $params = ["%" . $FILTER_OPTIONS["search"] . "%"];
            }
            $res->execute($params);
            $count = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return ["data" => $arraySql, "total" => $count[0]["total_rows"] == null ? 0 : $count[0]["total_rows"]];
    }

    public function getProductStatViewed() {
        
    }

}
