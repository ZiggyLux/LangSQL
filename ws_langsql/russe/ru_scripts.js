/*----------------------------------------------------------------------------*/
/* Application......... LangSql                                               */
/* Version............. 1.0                                                   */
/* Plateforme.......... *                                                     */
/* Source.............. ru_scripts.js                                         */
/* Dernière MAJ........                                                       */
/* Auteur.............. Marc CESARINI                                         */
/* Remarque............ JavaScript 1.5                                        */
/* Brève description... Module JavaScript reprenant les fonctions les plus    */
/*                      utilisées pour l'apprentissage du russe               */
/*                                                                            */
/* Emplacement......... doc                                                   */
/*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/

/*---------------------------- Variables de validation -----------------------*/
var bullet_ok= "../greenbullet.gif";
var bullet_nok= "../redbullet.gif";

/*----------------------------------------------------------------------------*/
/* Validation du champ "test" en fonction de la réponse en BD                 */
/* Indice est le numéro d'élément du tirage                                   */
/*----------------------------------------------------------------------------*/
function onblur_test(indice) {
	var str_id_test = new String().concat("id_test", String(indice));
	var str_id_rep = new String().concat("id_rep", String(indice));
	var str_id_bullet = new String().concat("id_bullet", String(indice));
	
    document.getElementById(str_id_test).value
    	= str_supesptete(document.getElementById(str_id_test).value);

	if (document.getElementById(str_id_test).value 
		== document.getElementById(str_id_rep).firstChild.nodeValue) {
		document.getElementById(str_id_bullet).src=bullet_ok;
	} else {
		document.getElementById(str_id_bullet).src=bullet_nok;
	}
}

function onblur_test2(indice) {
	var str_id_test = new String().concat("id_test2", String(indice));
	var str_id_rep = new String().concat("id_rep2", String(indice));
	var str_id_bullet = new String().concat("id_bullet2", String(indice));
	
    document.getElementById(str_id_test).value
    	= str_supesptete(document.getElementById(str_id_test).value);

	if (document.getElementById(str_id_test).value 
		== document.getElementById(str_id_rep).firstChild.nodeValue) {
		document.getElementById(str_id_bullet).src=bullet_ok;
	} else {
		document.getElementById(str_id_bullet).src=bullet_nok;
	}
}

/*----------------------------------------------------------------------------*/
/* Affichage/Effacement de la réponse par click sur bullette                  */
/*----------------------------------------------------------------------------*/
function onclick_bullet(indice) {
	var str_id_rep = new String().concat("id_rep", String(indice));
	
	if (document.getElementById(str_id_rep).style.visibility == "visible") { 
		document.getElementById(str_id_rep).style.visibility = "hidden";
	} else {
		document.getElementById(str_id_rep).style.visibility = "visible";
	}
}

function onclick_bullet12(indice) {
	var str_id_rep = new String().concat("id_rep_", String(indice));
	var str_id_rep2 = new String().concat("id_rep2_", String(indice));
	
	if (document.getElementById(str_id_rep).style.visibility == "visible") { 
		document.getElementById(str_id_rep).style.visibility = "hidden";
		document.getElementById(str_id_rep2).style.visibility = "hidden";
	} else {
		document.getElementById(str_id_rep).style.visibility = "visible";
		document.getElementById(str_id_rep2).style.visibility = "visible";
	}
}

function onclick_bullet2(indice) {

    //      onclick_bullet(indice);

	var str_id_rep2 = new String().concat("id_rep2", String(indice));
	
	if (document.getElementById(str_id_rep2).style.visibility == "visible") { 
		document.getElementById(str_id_rep2).style.visibility = "hidden";
	} else {
		document.getElementById(str_id_rep2).style.visibility = "visible";
	}
}

