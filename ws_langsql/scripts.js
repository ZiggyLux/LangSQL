/*----------------------------------------------------------------------------*/
/* Application......... LangSql                                               */
/* Version............. 1.0                                                   */
/* Plateforme.......... *                                                     */
/* Source.............. scripts.js                                            */
/* Dernière MAJ........                                                       */
/* Auteur.............. Marc CESARINI                                         */
/* Remarque............ JavaScript 1.5                                        */
/* Brève description... Module JavaScript reprenant les fonctions les plus    */
/*                      utilisées par les page du site                        */
/*                                                                            */
/* Emplacement......... doc                                                   */
/*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
/* Suppression des espaces de tête d'une chaîne de caractères                 */
/*     retour: une copie de la chaîne passée en paramètre sans les espaces    */
/*             de tête.                                                       */
/*----------------------------------------------------------------------------*/
function str_supesptete(pStr) {
    var str = pStr;
    var l = str.length;
    var i = 0;
    while (i != l && str.substr(i, 1) == " ") { i++; }
    return (i == l)? "": str.substr(i, l-i);
}

/*----------------------------------------------------------------------------*/
/* Création dynamique du contrôle de la page de retour                        */
/*                                                                            */
/* <input type="hidden" name="retpag" id="retpag" value="<retpag>" />         */
/*----------------------------------------------------------------------------*/
function addReturnPageToForm(strFormNam, strRetPag) {
	var eltReturnPage = document.createElement('input');
	eltReturnPage.setAttribute('type',	'hidden');
	eltReturnPage.setAttribute('id',	'retpag');
	eltReturnPage.setAttribute('name',	'retpag');
	eltReturnPage.setAttribute('value',	strRetPag);
	
	document.getElementById(strFormNam).appendChild(eltReturnPage);
}

/*----------------------------------------------------------------------------*/
/* Création dynamique du contrôle comportant le nom de la variable de retour  */
/*                                                                            */
/* <input type="hidden" name="selvar" id="selvar" value="<NomVarRetour>" />   */
/*----------------------------------------------------------------------------*/
function addNomVarRetToForm(strFormNam, strNomVarRetour) {
	var eltNomVarRet = document.createElement('input');
	eltNomVarRet.setAttribute('type',	'hidden');
	eltNomVarRet.setAttribute('id',		'selvar');
	eltNomVarRet.setAttribute('name',	'selvar');
	eltNomVarRet.setAttribute('value',	strNomVarRetour);
	
	document.getElementById(strFormNam).appendChild(eltNomVarRet);
}

/*----------------------------------------------------------------------------*/
/* Création dynamique du contrôle comportant la variable de retour et sa      */
/*                                                                     valeur */
/*                                                                            */
/* <input type="hidden" name="selvar" id="selvar" value="<NomVarRetour>" />   */
/*----------------------------------------------------------------------------*/
function addNomValVariableToForm(strFormNam, strNomVarRetour, strValeurRetour) {
	var eltVarValRet = document.createElement('input');
	eltVarValRet.setAttribute('type',	'hidden');
	eltVarValRet.setAttribute('id',		strNomVarRetour);
	eltVarValRet.setAttribute('name',	strNomVarRetour);
	eltVarValRet.setAttribute('value',	strValeurRetour);
	
	document.getElementById(strFormNam).appendChild(eltVarValRet);
}
