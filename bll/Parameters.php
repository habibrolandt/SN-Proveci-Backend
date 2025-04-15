<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parameters
 *
 * @author NGBEADEGO Martial
 */
class Parameters
{

    //  public static $host = "132.148.178.160"; //online db parameters
     public static $host = "localhost"; //online db parameters
    public static $user = "root";
    //    public static $user = "";
    public static $port = "3306"; //PROD
    // public static $user = "clinic_db";//PROD
    // public static $pass = "M@rtial_13061988";//PROD
    //public static $pass = "nkmclinic";
    public static $pass = "";
    public static $onesignal_appid = "0d39b660-bd77-4762-8a4c-4fa258cb827c";
    public static $onesignal_rest_apikey = "OGJhZDU1ZDgtYTk5Mi00MmI5LWE3MjItY2Q1MmMzNGYxOGQ5";

    /* public static $host = "marco.local"; //local db parameters
      public static $user = "root";
      public static $pass = "admin"; */
    //    public static $db = "extranetproveci_db_good";
    public static $db = "extranetproveci_db";
    //public static $db = "extranetproveci_db_last";

    public static $statut_process = "process";
    public static $statut_enable = "enable";
    public static $statut_closed = "closed";
    public static $statut_delete = "delete";

    public static $statut_disable = "disable";

    public static $statut_waiting = "waiting";

    public static $Message = "0";
    public static $Detailmessage = "Aucune opération en cours";
    public static $type_activite = "activite";
    public static $type_publication = "publication";
    public static $default_picture = "default.png";

    public static $SMTP_USERNAME = "saoured11@gmail.com";

    public static $SMTP_PASSWORD = "ffzedlnehbivkwkh";

    public static $piste_audit_consultation = "0000000000000000000000000000000000000790";
    public static $file_log = "../reports/import/images/publications/mylog.log";
    //    public static $rootFolderPictureUser = "http://vps694144.ovh.net/amermoz/reports/import/images/users/";
    //    public static $rootFolderRelative = "extranetbackend/backoffice/images/products/
    public static $rootFolderRelative = "extranetbackend/backoffice/images/";

    //    public static $rootFolderAbsolute = "C:/xampp/htdocs/extranetbackend/backoffice/images/";
    public static $path_import = "/home/i56lxrcy870n/public_html/extranetbackend/backoffice/images/produits/"; //chemin prod godaddy

    //    public static $rootFolderAbsolute = "/Applications/MAMP/htdocs/extranetbackend/backoffice/images/products/";
    public static $rootFolderPictureUser = "http://localhost/cashtracking/images/avatar/";
    public static $rootFolderPicturePublication = "http://vps694144.ovh.net/amermoz/reports/import/images/publications/";
    public static $urlOnesignal = "https://onesignal.com/api/v1/notifications";
    public static $P_ALERT_SOUSCRIPTION = "P_ALERT_SOUSCRIPTION";
    public static $PROCESS_FAILED = "0";
    public static $PROCESS_SUCCESS = "1";
    public static $type_system = "system";
    public static $type_customer = "customer";
    public static $defaultAdminId = "2";

    public static $defaultSystemProfilID = "0000000000000000000000000000000000000793";




    public static $urlRootAPI = "http://160.120.155.165/v1";
    public static $apikey = "ZghY887665YhGH";
    public static $apiusername = "CLIENTREST";
    public static $apipassword = "123";

    public static $gerantProfileID = "3";

    public static $secretKey = "ma_cle_secrete";

    public static $SN_PROVECI_ID = "1";

    public static $admin_profileID = '3';

    public static $client_profil_ID = '3003408924';
    public static $lst_viewed_product = "0000000000000000000000000000000000000790";
    public static $rootFolderAbsolute = __DIR__ . "/../images/";

    public static $LAST_BACKUP_DATE = "LAST_BACKUP_DATE";
    public static $typelisteValue = array("7", "4");
    
    public static $listeValue = array("MSG_RESETPASS", "4");
    public static $listParameters = array("[P1]", "[P2]", "[P3]", "[P4]", "[P5]", "{MSG}");

    public static function buildErrorMessage($description)
    {
        Parameters::$Message = "0";
        Parameters::$Detailmessage = $description;
    }

    public static function buildSuccessMessage($description)
    {
        Parameters::$Message = "1";
        Parameters::$Detailmessage = $description;
    }

    //transforme une chaine en date
    public static function StringToDate($format, $datestring)
    {
        return date($format, strtotime($datestring));
    }

    public static function StringToDateNew($format, $datestring)
    {
        $date = DateTime::createFromFormat('d/m/Y', $datestring);
        return $date->format("Y-m-d");
    }

    public static function RevertStringToDateNew($datestring)
    {
        $date = DateTime::createFromFormat('Y-m-d', $datestring);
        return $date->format("d/m/Y");
    }

    public static function uploadFile($rootPath, $filename = null)
    {
        $pic = "";
        try {
            // a decommenter en cas de probleme
            if ($_FILES["file"]["size"] > 1000000) { //gère uniquement les fichiers de 1Mo
                Parameters::buildErrorMessage("Echec de chargement de la photo. Vérifiez la taille du fichier");
                return $pic;
            }
            $filename = ($filename != null ? $filename : "") . time() . '-' . $_FILES['file']['name'];
            //            $targetFile = $rootPath . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) . "/" . $filename;
            $targetFile = $rootPath . "/" . $filename;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $pic = $filename;
            }

            /* $img = $_POST['file'];
              $img = str_replace('data:image/png;base64,', '', $img);
              $img = str_replace(' ', '+', $img);
              $data = base64_decode($img);
              $file = $rootPath . "/" . uniqid() . '.png';
              $pic = file_put_contents($file, $data);
              $path_parts = pathinfo($file);
              $pic = $path_parts['basename']; */
        } catch (Exception $ex) {
            $ex->getMessage();
        }
        return $pic;
    }

    public static function writeLog($filename, $data, $mode)
    {
        file_put_contents($filename, $data, $mode);
    }
}
