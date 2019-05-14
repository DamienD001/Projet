	
	var compteur=0;
	var caloriesEnCours=0;
	var numTourEnCours=1;
	var numExoEnCours=0;
	var monTimer;


//---------------classe Seance 
	function Seance(jsonSeance){
		this.dateSeance=jsonSeance[0];
		this.echauffement=jsonSeance[1];
		this.calme=jsonSeance[2];
		this.nbTours=jsonSeance[3];
		this.exercices=jsonSeance[4];
		this.nbExercices=jsonSeance[4].length;
	}

	Seance.prototype.exo=function(i){
		return this.exercices[i];
	}

	Seance.prototype.exoIntitule=function(i){
		return this.exercices[i][0];
	}

	Seance.prototype.exoEffort=function(i){
		return this.exercices[i][1];
	}	

	Seance.prototype.exoRepos=function(i){
		return this.exercices[i][2];
	}	

	Seance.prototype.exoCalories=function(i){
		return this.exercices[i][4];
	}		
//---------------fin de classe Seance 


//---------------classe Seance étendue
function Exercice(intitule,rangDansTour,numTour,duree,calories){
	this.intitule=intitule;
	this.rangDansTour=rangDansTour;
	this.numTour=numTour;
	this.duree=duree;
	this.calories=calories;
}

function SeanceEtendue(jsonSeance){
	this.dateSeanceEtendue=jsonSeance[0];
	this.nbExoEtendus=2*jsonSeance[4].length*jsonSeance[3]+1;
	this.nbExo=jsonSeance[4].length;
	this.nbTours=jsonSeance[3]
	this.exoEtendus=[];
}

SeanceEtendue.prototype.ajouteExos=function(jsonSeance){
	//alert('taille des exo : '+jsonSeance[4].length);
	var exo=new Exercice('Echauffement',0,1,jsonSeance[1],0);
	this.exoEtendus.push(exo);
	
	var i=0;
	var j=0;

	for(i=0;i<jsonSeance[3];i++){
	  	for(j=0;j<jsonSeance[4].length;j++){
			exo=new Exercice(jsonSeance[4][j][0],j+1,i+1,jsonSeance[4][j][1],jsonSeance[4][j][4]);
			this.exoEtendus.push(exo);
			if((j!=jsonSeance[4].length-1) || (i!=jsonSeance[3]-1)){
				exo=new Exercice('Repos',j+1,i+1,jsonSeance[4][j][2],0);
				this.exoEtendus.push(exo);
			}
		}
	}

	exo=new Exercice('Calme',0,jsonSeance[4].length,jsonSeance[2],0);
	this.exoEtendus.push(exo);
}
//---------------fin de classe Seance étendue



function listeExercices_V2(){
	request=new asyncRequest();
	request.open("POST","ajaxListeExercices.php",false);
	request.send(null);
	
	var jsonExercices=JSON.parse(request.responseText);
	
	//document.getElementById("resultatAjax").innerHTML="prenom de Damien : "+jsonExercices[1];
	return jsonExercices;
}

function listeExercices_V3(){
	request=new asyncRequest();
	request.open("POST","ajaxListeExercicesAvecCalories.php",false);
	request.send(null);
	
	var jsonExercices=JSON.parse(request.responseText);
	
	//document.getElementById("resultatAjax").innerHTML="prenom de Damien : "+jsonExercices[1];
	return jsonExercices;
}



function detailSeance(){
	request=new asyncRequest();
	request.open("POST","ajaxSeance.php",false);
	request.send(null);
	
	//var contenuSeance=JSON.parse(request.responseText);
	var contenuSeance=request.responseText;
	alert(contenuSeance);
	var jsonContenu=JSON.parse(contenuSeance);
	// alert(jsonContenu[0]);
	// alert(jsonContenu[1]);
	// alert(jsonContenu[2]);
	// alert(jsonContenu[3]);
	// alert(jsonContenu[4][0][0]);
	// alert(jsonContenu[4][1][0]);
	return jsonContenu;
}

function asyncRequest(){
	try{
		var request=new XMLHttpRequest();
		alert("etape 1 OK");
	}catch(e1){
		try{
			request=new ActiveXObject("Msxml2.XMLHTTP");
			alert("etape 2 OK");
		}catch(e2){
			try{
				request=new ActiveXObject("Microsoft.XMLHTTP");
				alert("etape 3 OK");
			}catch(e3){
				request=false;
				alert("probleme");
			}
		}
	}
	return request;
}


function ajouteForm(){
	//alert("toto"+indice);
	indice++;
	ajouteInputWithLabel("exercice"+indice,indice);
	
	res=document.getElementById('nbExercices');
	res.setAttribute('value',indice);
	verif=document.getElementById('verifHidden');
	verif.innerHTML=res.value;
}

function ajouteInputWithLabel(nom,rang){
	
	var res=document.getElementById('resultat');
	
	var newField=document.createElement('fieldset');

	//---------------creation hidden
	var newHidden=document.createElement('input');
	newHidden.setAttribute('type', 'hidden');
	newHidden.setAttribute('name', 'hidden_'+nom);
	newHidden.setAttribute('id', 'hidden_'+nom);
	newHidden.setAttribute('value', rang);

	//---------------creation label exercice
	var newTextLabelExercice=document.createTextNode('Exercice '+rang+' : ');
	var newLabelExercice=document.createElement('label');
	newLabelExercice.setAttribute('for', 'exercice_'+nom);
	newLabelExercice.setAttribute('class', 'form-lbl');
	newLabelExercice.appendChild(newTextLabelExercice);

	//---------------creation Select des exercices
	var newSelectExercice=document.createElement('select');
	newSelectExercice.setAttribute('id', 'selectExercice_'+nom);
	newSelectExercice.setAttribute('name', 'selectExercice_'+nom);
	for(i=0;i<listeExercices.length;i++){
		var newTextOptionExercice=document.createTextNode(listeExercices[i][0]);
		var newOptionExercice=document.createElement('option');
		newOptionExercice.appendChild(newTextOptionExercice);
		newSelectExercice.appendChild(newOptionExercice);
	}
	newSelectExercice.setAttribute('onchange', 'caloriesExercice(this)');

	//---------------creation label durée
	var newTextLabelDuree=document.createTextNode('durée d\'effort');
	var newLabelDuree=document.createElement('label');
	newLabelDuree.setAttribute('for', 'duree_'+nom);
	newLabelDuree.setAttribute('class', 'form-lbl');
	newLabelDuree.appendChild(newTextLabelDuree);

	//---------------creation input durée minutes
	var newInputMin = document.createElement('input');
	newInputMin.setAttribute('type', 'numeric');
	newInputMin.setAttribute('name', 'minutes_'+nom);
	newInputMin.setAttribute('id', 'minutes_'+nom);
	newInputMin.setAttribute('class', 'form-input-time');
	newInputMin.setAttribute('value', '00');
	newInputMin.setAttribute('onchange', 'fctTotal()');

	//---------------creation input durée secondes
	var newInputSec = document.createElement('input');
	newInputSec.setAttribute('type', 'numeric');
	newInputSec.setAttribute('name', 'secondes_'+nom);
	newInputSec.setAttribute('id', 'secondes_'+nom);
	newInputSec.setAttribute('class', 'form-input-time');
	newInputSec.setAttribute('value', '00');
	newInputSec.setAttribute('onchange', 'fctTotal()');

	//---------------creation label durée récupération
	var newTextLabelDureeRecup=document.createTextNode('durée de récupération');
	var newLabelDureeRecup=document.createElement('label');
	newLabelDureeRecup.setAttribute('for', 'dureeRecup_'+nom);
	newLabelDureeRecup.setAttribute('class', 'form-lbl');
	newLabelDureeRecup.appendChild(newTextLabelDureeRecup);

	//---------------creation input durée minutes récupération
	var newInputRecupMin = document.createElement('input');
	newInputRecupMin.setAttribute('type', 'numeric');
	newInputRecupMin.setAttribute('name', 'recupMinutes_'+nom);
	newInputRecupMin.setAttribute('id', 'recupMinutes_'+nom);
	newInputRecupMin.setAttribute('class', 'form-input-time');
	newInputRecupMin.setAttribute('value', '00');
	newInputRecupMin.setAttribute('onchange', 'fctTotal()');

	//---------------creation input durée secondes récupération
	var newInputRecupSec = document.createElement('input');
	newInputRecupSec.setAttribute('type', 'numeric');
	newInputRecupSec.setAttribute('name', 'recupSecondes_'+nom);
	newInputRecupSec.setAttribute('id', 'recupSecondes_'+nom);
	newInputRecupSec.setAttribute('class', 'form-input-time');
	newInputRecupSec.setAttribute('value', '00');
	newInputRecupSec.setAttribute('onchange', 'fctTotal()');

	//---------------------------------------------------------
	//---------------creation input calories pour test---------
	//---------------------------------------------------------
	var newHiddenCal=document.createElement('input');
	newHiddenCal.setAttribute('type', 'input');
	newHiddenCal.setAttribute('name', 'calories_'+nom);
	newHiddenCal.setAttribute('id', 'calories_'+nom);
	newHiddenCal.setAttribute('value', 0);


	//---------------------------------------------------------
	//---------------fin du input calories pour test-----------
	//---------------------------------------------------------


	//---------------ajout des champs de formulaires
	newField.appendChild(newHidden);

	newField.appendChild(newLabelExercice);
	newField.appendChild(newSelectExercice);
	newField.appendChild(document.createElement('br'));

	newField.appendChild(newLabelDuree);
	newField.appendChild(newInputMin);
	newField.appendChild(document.createTextNode(':'));
	newField.appendChild(newInputSec);
	newField.appendChild(document.createElement('br'));

	newField.appendChild(newLabelDureeRecup);
	newField.appendChild(newInputRecupMin);
	newField.appendChild(document.createTextNode(':'));
	newField.appendChild(newInputRecupSec);

	newField.appendChild(newHiddenCal);

	res.appendChild(newField);
}

function fctTotal(){
	var nbRounds=Number(document.getElementById('nbRounds').value);
	var echauf=Number(document.getElementById('dureeEchaufMin').value)*60+Number(document.getElementById('dureeEchaufSec').value);
	var calme=Number(document.getElementById('dureeCalmeMin').value)*60+Number(document.getElementById('dureeCalmeSec').value);
	
	var formField=document.getElementById('fieldExercices');
	var somDuree=0;
	var tablDuree=formField.getElementsByTagName('input');
	//-------------------pour tous les input du fieldset, on récupère la valeur
	//-------------------s'il s'agit du champ des minutes, on multiplie par 60
	for(i=0;i<tablDuree.length;i++){
		if((tablDuree[i].type=='numeric')||(tablDuree[i].type=='text')){
			if(tablDuree[i].name.search('minutes')!=-1){
				somDuree+=Number(tablDuree[i].value)*60;
			}else{
				somDuree+=Number(tablDuree[i].value);
			}
		}
	}

	//------------------- calcul de la durée (y compris durées d'échauffement et de retour au calme)
	var totalSecondesSeance=somDuree*nbRounds+echauf+calme;

	document.getElementById('dureeTotaleSeance').innerHTML=totalSecondesSeance+"s";
	document.getElementById('minutesTotalSeance').innerHTML=SecondesToMinutesSecondes(totalSecondesSeance)[0];
	document.getElementById('secondesTotalSeance').innerHTML=SecondesToMinutesSecondes(totalSecondesSeance)[1];
}

function SecondesToMinutesSecondes(totalSecondes){
	tablDuree=[parseInt(totalSecondes/60),totalSecondes%60];
	return tablDuree;
}

function caloriesExercice(exercice){
	var cal=0;
	for(i=0;i<listeExercices.length;i++){
		if(listeExercices[i][0]===exercice.value){
			cal=listeExercices[i][1];
		}
	}
	var nomSelect=exercice.name;
	var nomCalories=nomSelect.replace('selectExercice','calories');
	var res=document.getElementById(nomCalories);
	res.value=cal;

}



function listeExercices(){
	request=new asyncRequest();
	request.open("POST","scriptAjax.php",false);
	request.send(null);
	var tableauExercices=request.responseText.split(';');
	
	var chaineExercices="";
	for(var i=0;i<tableauExercices.length;i++) {
	 	chaineExercices+=" - " + tableauExercices[i];
	}
	document.getElementById("resultatAjax").innerHTML=chaineExercices;
}



function toto(){
	request=new asyncRequest();
	request.overrideMimeType("application/json");
	request.open("POST","scriptAjax.php",false);
	request.send(null);
	var jsonExercices=JSON.parse(request.responseText);
	alert(typeof jsonExercices);
	var chaineExercices='';
	for(var i=0;i<jsonExercices.length;i++) {
	 	chaineExercices+=jsonExercices[i].intitule;
	}
	document.getElementById("resultatAjax").innerHTML=jsonExercices.length+chaineExercices;
	//ajouté le 15/04/19
	//request.responseType = 'json';
	//var jsonExercices=request.response;
	// var chaineExercices='';
	// for(var i=0;i<jsonExercices.length;i++) {
	// 	chaineExercices+=jsonExercices[i].intitule;
	// }
	// document.getElementById("resultatAjax").innerHTML=jsonExercices.length+chaineExercices;
}




//------------------------function timer

// var compteur=0;
// var elt=document.getElementById('chrono');
// var eltCtrl=document.getElementById('controle');

// var eltContenuSeance=document.getElementById('contenuSeance');

// var tpsEffort=0;

// var numTourEnCours=1;
// var numExoEnCours=1;
//var nbExo=2;
//var timeStart;

//var tableauTpsEfforts=new Array();


function start(){

	monTimer=setInterval("afficheDecompte()", 1000);
	//var elt=document.getElementById('chrono');
	//var eltCtrl=document.getElementById('controle');

	//var eltContenuSeance=document.getElementById('contenuSeance');

	//var tpsEffort=0;

	
	//var nbExo=2;
	//var timeStart;

//var tableauTpsEfforts=new Array();




	// eltEffort1=document.getElementById('dureeEffort1').value;
	// tpsEffort1=Number(eltEffort1);
	// eltEffort2=document.getElementById('dureeEffort2').value;
	// tpsEffort2=Number(document.getElementById('dureeEffort2').value);
	// tableauTpsEfforts=[tpsEffort1,tpsEffort2];
	// var dt=new Date();
	// var sec=dt.getSeconds();

	
}

function stop(){
	clearInterval(monTimer);
}


function afficheDecompte(){
	
	caloriesEnCours+=seanceJS.exoEtendus[numExoEnCours].calories/3600;
	valeur=seanceJS.exoEtendus[numExoEnCours].duree-compteur;
	intitule=seanceJS.exoEtendus[numExoEnCours].intitule;

	affichageIndicateur('listeExo','exo '+numExoEnCours+ ' / ' +seanceJS.nbExoEtendus+' : '+intitule);
	affichageIndicateur('decompte','decompte : '+valeur);
	affichageIndicateur('compteTours','tour '+numTourEnCours+" / "+seanceJS.nbTours);
	affichageIndicateur('calories',Math.round(caloriesEnCours)+' calories');
	
	if(valeur==0){
		if(numExoEnCours==seanceJS.nbExoEtendus-1){
			stop();
			alert("terminé")
		}else if((numExoEnCours>0) && (numExoEnCours%(2*seanceJS.nbExo)==0)){
			numTourEnCours++;
		}
		if(numExoEnCours<seanceJS.nbExoEtendus-1){
			numExoEnCours++;
		}
	 	compteur=0;
	}else{
		compteur++;
	}


	// if((valeur==0) && (numExoEnCours<seanceJS.nbExoEtendus)){
	// 	numExoEnCours++;
	// 	compteur=0;
	// }else if((valeur==0) && (numExoEnCours%seanceJS.nbExo==0)){
	// 	numTourEnCours++;
	// 	numExoEnCours++;
	// 	compteur=0;
	// }else{
	// 	compteur++;
	// }

}

function affichageIndicateur(identifiant,valeur){
	var elt=document.getElementById(identifiant);
	elt.innerHTML=valeur;

}
