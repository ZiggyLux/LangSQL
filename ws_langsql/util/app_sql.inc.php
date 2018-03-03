<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 5                                     
  Source.............. app_sql.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 5                                        
  Brève description... Fonction MySQL communes                                                      
                       Utilise PDO                                                     
  Emplacement.........                                                       
*****************************************************************************/

/*----------------------------------------------------------------------------
  Connection et Déconnection de la base de données
----------------------------------------------------------------------------*/

function connect_db () {
    /* Connecting, selecting database */
	if (D_APPW_MOD_ENV == "RUN") {
		$databaseName = "langsql_db";
		$userName = "langsql_usr";
		$userPass = "langsql";
	} else if (D_APPW_MOD_ENV == "VAL"){
 		$databaseName = "val_langsql";
		$userName = "val_langsql_usr";
		$userPass = "langsql_val";
	} else {
 		$databaseName = "langsql_dbdev";
		$userName = "langsql_usrdev";
		$userPass = "langsql_dev";
	}
	/* Pour mémoire ancien mode de connexion 
    $link = mysql_connect("", $userName, $userPass)
        or die("Could not connect");
    mysql_select_db($databaseName) or die("Could not select database");
	return $link;
	*/
	$dsn = 'mysql:host=localhost;dbname=' . $databaseName;
	try {
	    $dbh = new PDO($dsn, $userName, $userPass);
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
	    echo "Erreur DB : " . $e->getMessage() . "<br/>";
	    die();
	}
	return $dbh;
}

function disconnect_db ($dbh) {
    /* Pour mémoire ancien mode de déconnexion
    mysql_close($link);
    */
    $dbh = NULL;
}

/*----------------------------------------------------------------------------
  Exécution des queries
----------------------------------------------------------------------------*/

/*
function exec_query ($qry) {
	$result = mysql_query($qry) or die("Query failed");

	return $result;
}
*/

function exec_verbose_query ($dbh, $qry) { // Fonction pour DEBUG
	
	print("<br>\nRequ&ecirc;te SQL: " . $qry . "\n\n");
	
	return $dbh->query($qry);
}

?>
