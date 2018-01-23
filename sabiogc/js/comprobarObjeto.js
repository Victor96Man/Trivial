$(document).ready(function() {

	cambiarObjeto();

	function cambiarObjeto() {
		if ($("input[name=obj]:checked").val() == "archivo") {
			$("#fichero").css("display", "block");
			$("#ficheroURL").css("display", "none");
		} else {
			$("#fichero").css("display", "none");
			$("#ficheroURL").css("display", "block");
		}
	}

	$("input[name=obj]").on('change', cambiarObjeto);
});