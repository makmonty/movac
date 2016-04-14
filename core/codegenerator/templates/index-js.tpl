$(function() {ldelim}

	$(document).on("click", ".action-delete", function() {ldelim}
		var id = $(this).attr("entity_id");
		ajaxDelete(id);
	{rdelim});

	$('.resultados table').dataTable( {ldelim}
		"bProcessing": true,
		"bAutoWidth": false,
		"iDisplayLength": 25,
		"aaSorting": [[1,'asc']],
		"bJQueryUI": true,
		"oLanguage": {ldelim}
			"sUrl": root+"js/dataTables.spanish.txt"
		{rdelim},
		//"aoColumns": [
		//             {ldelim}"bSortable": true{rdelim}, // Ordenar por esta columna
		//             {ldelim}"sType": "date-euro"{rdelim} // Ordenar por fecha bien
        //            ],
		"sPaginationType": "full_numbers",
		"sAjaxSource": root+'{$objectname}/ajaxGetAll'
	{rdelim});
{rdelim});



function ajaxDelete(id) {ldelim}
	var popupContent = "<p>¿Está seguro de que desea elmininar este elemento?</p>";
	elimina_elemento(({ldelim}
		id: id,
		message: popupContent,
		elemento: "{$classname}",
		dir: "{$objectname}/ajaxDelete",
	{rdelim}));
{rdelim}