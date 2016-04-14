	function edit($id = null) {ldelim}
		try {ldelim}
			// Iniciar variables
			
			$selects = array();
			// Obtención de relaciones
{foreach $columns as $field => $def}
	{$relation = (substr($field,0,3) == "id_")? true : false}
	{if $relation}
		{if isset($mapHasOne[$field])}
		
			$selects['{$field}'] = Doctrine::getTable("{$mapHasOne[$field]}")->getAllAsSelect();
		{else}
		
			// TODO: Obtener relaciones Has Many
		{/if}
	{/if}
{/foreach}

			//TODO: Comprobaciones
			
			if(!is_null($id) || isset($_POST['id']) && $_POST['id']) {ldelim}
				$id = (isset($_POST['id']) && $_POST['id'])? $_POST['id'] : $id;
				${$objectname} = Doctrine::getTable("{$classname}")->find($id);
			{rdelim} else {ldelim}
				${$objectname} = new {$classname}();
			{rdelim}
			
			// Parámetros para la vista
			$this->selects = $selects;
			$this->{$objectname} = ${$objectname};
			
			if(isset($_POST['submit'])) {ldelim}
				// Asignar valores enviados
{foreach $columns as $field => $def}
	{$datetime = ($def.type == "datetime" || $def.type == "timestamp")}
	{if $datetime}

				${$objectname}->{$field} = $this->formatDate($_POST['{$field}_date']) ." ". $_POST['{$field}_time'];
	{else if $field != "id"}

				${$objectname}->{$field} = $_POST['{$field}'];
	{/if}
{/foreach}

				$fields = array(
{$i = 1}
{foreach $columns as $field => $def}
	{$required = (isset($def.notnull) && $def.notnull)? "_REQUIRED," : ""}
	{$email = (substr($field,0,5) == "email")? "_EMAIL," : ""}
	{$date = ($def.type == "date")? "_DATE," : ""}
	{$datetime = ($def.type == "datetime" || $def.type == "timestamp")}
	{$numeric = ($def.type == "integer" || $def.type == "float" || $def.type == "decimal" || $def.type == "real")? "_NUMERIC," : ""}
	{$restrictions = ""}
	{$restrictions = $restrictions|cat:$required|cat:$email|cat:$date|cat:$numeric}
	{if $restrictions != ""}
		{$restrictionsl = strlen($restrictions)}
		{$restrictions = substr($restrictions,0,$restrictionsl-1)}
	{/if}
	{if $datetime}
	
					"{$field}_date" => array({$restrictions}{if $i < $restrictions},{/if}_DATE),
					"{$field}_time" => array({$restrictions}){if $i < count($columns)},{/if}
	{else if $field != "id"}
	
					"{$field}" => array({$restrictions}){if $i < count($columns)},{/if}
	{/if}
	{$i = $i+1}
{/foreach}

				);
				
				$errors = $this->validateFields($fields);
				if(!$errors) {ldelim}
					${$objectname}->save();
				{rdelim}
			{rdelim}
			
		{rdelim} catch (Doctrine_Validator_Exception $e) {ldelim}
			$this->doctrineValidatorException($e);
			
		{rdelim} catch (Exception $e) {ldelim}
			$this->addError(_ERR_UNEXPECTED);
			
		{rdelim}
	{rdelim}