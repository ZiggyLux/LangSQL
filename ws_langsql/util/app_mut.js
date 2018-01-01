/*----------------------------------------------------------------------------*/
/* Application......... LangSql                                               */
/* Version............. 1.0                                                   */
/* Plateforme.......... *                                                     */
/* Source.............. app_mut.js                                            */
/* Dernière MAJ........                                                       */
/* Auteur.............. Marc CESARINI                                         */
/* Remarque............ JavaScript 1.5                                        */
/* Brève description... Module JavaScript gérant un ensemble de variables     */
/*                      mutables                                              */
/*                      utilisées par les page du site                        */
/*                                                                            */
/* Emplacement......... util                                                  */
/*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
/* Gestion d'une variable mutable 											  */
/*		set: affecte la valeur courante de la variable mutable                */
/*		get: récupère la valeur courante d'une variable mutable				  */
/*		isEltDirty: retourne vrai si changement de la variable mutable		  */
/*		isEltClean: retroune vrai si variable mutable inchangée				  */
/*----------------------------------------------------------------------------*/
function O_Mutable_set(aVal) { this.val = aVal; }
function O_Mutable_reset() { this.val = this.valInit; }
function O_Mutable_get() { return this.val; }
function O_Mutable_isEltDirty() { return ((this.val) != (this.valInit)); }
function O_Mutable_isEltClean() { return !(this.isEltDirty()); }

function O_Mutable(
	valInit,	// Valeur initiale
	val			// Valeur actuelle
) {
	this.valInit = valInit;
	this.val = val;
	
	// Rattachement des méthodes
	this.set = O_Mutable_set;
	this.reset = O_Mutable_reset;
	this.get = O_Mutable_get;
	this.isEltDirty = O_Mutable_isEltDirty;
	this.isEltClean = O_Mutable_isEltClean;
}

/*----------------------------------------------------------------------------*/
/* Gestion d'une tableau de variable mutable 								  */
/*----------------------------------------------------------------------------*/
function O_MutableAssocArray_push(aCle, aObj) {
	this.tabCle.push(aCle);
	this.tabObj.push(aObj);
}

function O_MutableAssocArray_isDirty() {
	var fDirty=false;
	for (var i = 0; i < this.tabCle.length; i++) {
		if (this.tabObj[i].isEltDirty()) {
			fDirty=true;
			break;
		}
	}
	return fDirty;
}

function O_MutableAssocArray_isClean() { return !O_MutableAssocArray_isClean(); }

function O_MutableAssocArray_set(aCle, aVal) {
	for (var i = 0; i < this.tabCle.length; i++) {
		if (this.tabCle[i] == aCle) {
			this.tabObj[i].set(aVal);
			break;
		}
	}
}

function O_MutableAssocArray_reset(aCle) {
	for (var i = 0; i < this.tabCle.length; i++) {
		if (this.tabCle[i] == aCle) {
			this.tabObj[i].reset();
			break;
		}
	}
}

function O_MutableAssocArray_get(aCle) {
	for (var i = 0; i < this.tabCle.length; i++) {
		if (this.tabCle[i] == aCle)
			return this.tabObj[i].get();
	}
	return null;
}

function O_MutableAssocArray_isEltDirty(aCle) {
	for (var i = 0; i < this.tabCle.length; i++) {
		if (this.tabCle[i] == aCle)
			return this.tabObj[i].isEltDirty();
	}
	return null;
}

function O_MutableAssocArray_isEltClean(aCle) {
	var fBool = O_MutableAssocArray_isEltDirty(aCle);
	if (fBool == null)
		return null;
	else
		return !fBool;	
}

function O_MutableAssocArray() {
	this.tabCle = new Array();
	this.tabObj = new Array();
	
	// Rattachement des méthodes
	this.push = O_MutableAssocArray_push;				// Ajoute une variable au tableau
	this.set = O_MutableAssocArray_set;					// Affecte une variable avec une nouvelle valeur
	this.reset = O_MutableAssocArray_reset;				// Replace une variable à sa valeur initiale
	this.get = O_MutableAssocArray_get;					// Récupère la valeur courante d'une variable
	this.isEltDirty = O_MutableAssocArray_isEltDirty;	// Vérifie la variable a été modifiée (dirty flag)
	this.isEltClean = O_MutableAssocArray_isEltClean;	// Négation de la fonction précédente
	this.isDirty = O_MutableAssocArray_isDirty;			// Vérifie si une des variables a été modifiée
	this.isClean = O_MutableAssocArray_isClean;			// Négation de la fonction précédente
}
