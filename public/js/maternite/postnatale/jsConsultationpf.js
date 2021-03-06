$(function(){
	$("#accordions").accordion();
	//$( "button" ).button(); // APPLICATION DU STYLE POUR LES BOUTONS
});   
var base_url = window.location.toString();
	var tabUrl = base_url.split("public");
	
//BOITE DE DIALOG POUR LA CONFIRMATION DE SUPPRESSION
function confirmation(id){
  $( "#confirmation" ).dialog({
    resizable: false,
    height:375,
    width:485,
    autoOpen: false,
    modal: true,
   
    buttons: {
        "Admettre": function() {
        	declarer(id);
        	$( this ).dialog( "close" );      	             	     
            return false;
        }
 
   }
 
  });
}

function visualiser(id){
	 confirmation(id);
	 var cle = id;
    var chemin = tabUrl[0]+'public/postnatale/declarer-deces';
    $.ajax({
        type: 'POST',
        url: chemin ,
        data:'id='+cle,
        success: function(data) {    
        	    var result = jQuery.parseJSON(data);   
        	     $("#info").html(result);
        	     
        	     $("#confirmation").dialog('open'); //Appel du POPUP
        	       
        },
        error:function(e){console.log(e);alert("Une erreur interne est survenue!");},
        dataType: "html"
    });
}

$(function(){
setTimeout(function() {
	infoBulle();
}, 1000);
});
function infoBulle(){
	/***
	 * INFO BULLE FE LA LISTE
	 */
	 var tooltips = $( 'table tbody tr td infoBulleVue' ).tooltip({show: {effect: 'slideDown', delay: 250}});
	     tooltips.tooltip( 'close' );
	  $('table tbody tr td infoBulleVue').mouseenter(function(){
	    var tooltips = $( 'table tbody tr td infoBulleVue' ).tooltip({show: {effect: 'slideDown', delay: 250}});
	    tooltips.tooltip( 'open' );
	  });
}
var  oTable
function initialisation(){
	
    
	var asInitVals = new Array();
        oTable = $('#patient').dataTable
	( {
		"sPaginationType": "full_numbers",
		"aLengthMenu": [5,7,10,15],
		"aaSorting": [], //On ne trie pas la liste automatiquement
		"oLanguage": {
			"sInfo": "_START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
			"sInfoEmpty": "0 &eacute;l&eacute;ment &agrave; afficher",
			"sInfoFiltered": "",
			"sUrl": "",
			"oPaginate": {
				"sFirst":    "|<",
				"sPrevious": "<",
				"sNext":     ">",
				"sLast":     ">|"
				}
		   },

		"sAjaxSource": ""+tabUrl[0]+"public/postnatale/liste-admission-ajax", 

		"fnDrawCallback": function() 
		{
			//markLine();
			clickRowHandler();
		}
						
	} );

	$("thead input").keyup( function () {
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, $("thead input").index(this) );
	} );

	/*
	* Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
	* the footer
	*/
	$("thead input").each( function (i) {
		asInitVals[i] = this.value;
	} );

	$("thead input").focus( function () {
		if ( this.className == "search_init" )
		{
			this.className = "";
			this.value = "";
		}
	} );

	$("thead input").blur( function (i) {
		if ( this.value == "" )
		{
			this.className = "search_init";
			this.value = asInitVals[$("thead input").index(this)];
		}
	} );

}


function clickRowHandler() 
{
	var id;
	$('#patient tbody tr').contextmenu({
		target: '#context-menu',
		onItem: function (context, e) {
			
			if($(e.target).text() == 'terminer_ad' || $(e.target).is('#terminer_ad')){
				declarer(id);
			} else 
				if($(e.target).text() == 'terminer_ad' || $(e.target).is('#terminer_ad')){
					//alert("vnn");
					declarer(id);
				}
			
		}
	
	}).bind('mousedown', function (e) {
			var aData = oTable.fnGetData( this );
		    id = aData[0];
	});
	
	
	
	$("#patient tbody tr").bind('dblclick', function (event) {
		var aData = oTable.fnGetData( this );
		var id = aData[0];
		visualiser(id);
	});
	
}


function animation(){
//ANIMATION
//ANIMATION
//ANIMATION

	
	

$('#declarer_deces').toggle(false);

$('#precedent').click(function(){
	$("#titre2").replaceWith("<div id='titre' style='font-family: police2; color: green; font-size: 18px; font-weight: bold; padding-left: 35px;'><iS style='font-size: 25px;'>&curren;</iS> RECHERCHER LE PATIENT </div>");	
    
	$('#contenu').animate({
        height : 'toggle'
     },1000);
     $('#declarer_deces').animate({
        height : 'toggle'
     },1000);
	 
     //IL FAUT LE RECREER POUR L'ENLEVER DU DOM A CHAQUE FOIS QU'ON CLIQUE SUR PRECEDENT
     $("#termineradmission").replaceWith("<button id='termineradmission' style='height:35px;'>Terminer</button>");
     
     return false;
});

}




function declarer(id){
	$("#termineradmission").replaceWith("<button id='termineradmission' style='height:35px;'>Terminer</button>");
    $("#titre").replaceWith("<div id='titre2' style='font-family: police2; color: green; font-size: 18px; font-weight: bold; padding-left: 35px;'><iS style='font-size: 25px;'>&curren;</iS> ADMISSION </div>");	
    //$("#termineradmission").replaceWith("<button id='termineradmission' style='height:35px;'>Terminer</button>");
    //R�cup�ration des donn�es du patient
    var cle = id;
    var chemin = tabUrl[0]+'public/postnatale/admission';
    $.ajax({
        type: 'POST',
        url: chemin ,
        data: $(this).serialize(),  
        data:'id='+cle,
        success: function(data) {    
        	    var result = jQuery.parseJSON(data);  
        	     $("#info_patient").html(result);
        	     //PASSER A SUIVANT
        	     $('#declarer_deces').animate({
        	         height : 'toggle'
        	      },1000);
        	     $('#contenu').animate({
        	         height : 'toggle'
        	     },1000);
        },
        error:function(e){console.log(e);alert("Une erreur interne est survenue!");},
        dataType: "html"
    });
    //Fin R�cup�ration des donn�es de la maman
    
    //Annuler l'enregistrement d'une naissance
    $("#annuler").click(function(){
    	$("#annuler").css({"border-color":"#ccc", "background":"-webkit-linear-gradient( #555, #CCC)", "box-shadow":"1px 1px 5px black inset,0 1px 0 rgba( 255, 255, 255, 0.4)"});
    	
	    vart = tabUrl[0]+'public/postnatale/admission';
	    $(location).attr("href",vart);
        return false;
    });
    
    $("#termineradmission").click(function(){
    	this.attr('disabled',true);
    	$("#envoyerDonneesForm").trigger("click");
        return false;
    });
//    $("#terminer_ad").click(function(){
//    	this.attr('disabled',true);
//    	$("#envoyerDonneesForm").trigger("click");
//        return false;
//    });
    $("#id_patient").val(id); 
}


//motif d'admission





















