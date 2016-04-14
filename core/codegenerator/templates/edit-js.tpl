$(function() {ldelim}
	$("#{$objectname}_form").submit(function() {ldelim}
		validate{$classname}(this);
	{rdelim});
{rdelim});

function validate{$classname}(form) {ldelim}
	var ok = validateForm(form);
	// Validaciones adicionales
	
	return ok;
{rdelim}