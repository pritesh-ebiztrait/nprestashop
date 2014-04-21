function editTemplate(template_id, token_cartabandonment){
	$.ajax({
		type: "POST",
		url: "../modules/cartabandonment/ajax_editTemplate.php",
		data: { template_id: template_id, token_cartabandonment: token_cartabandonment }
	})
	.done(function( msg ) {
		if(!msg)
			alert( "Erreur lors de la récupération du template.");
		else{
			$("#newTemplate").hide();
			$("#edit_template_content").html(msg);
			$("#edit_template").show();
		}
	});
}

function previewTemplate(template_id, token_cartabandonment){
	$.ajax({
		type: "POST",
		url: "../modules/cartabandonment/ajax_editTemplate.php",
		data: { template_id: template_id, token_cartabandonment: token_cartabandonment }
	})
	.done(function( msg ) {
		if(!msg)
			alert( "Erreur lors de la récupération du template.");
		else{
			$("#newTemplate").hide();
			$("#edit_template_content").html(msg);
			$("#edit_template").show();
		}
	});
}

function deleteTemplate(template_id, token_cartabandonment){
	$.ajax({
		type: "POST",
		url: "../modules/cartabandonment/ajax_deleteTemplate.php",
		data: { template_id: template_id, token_cartabandonment: token_cartabandonment }
	})
	.done(function( msg ) {
		if(!msg)
			alert( "Erreur lors de la supression du template.");
		$("#edit_template").hide();
		$("#newTemplate").hide();
	});
}