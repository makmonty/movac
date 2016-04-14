	function ajaxGetAll() {ldelim}
		if(!IS_AJAX)
			$this->redirect("application/401");
		$data = array();
		foreach($entities as $entity) {ldelim}
			$data[] = array(
{foreach $columns as $key => $def}
				$entity['{$key}'],
{/foreach}
				"<a class='action-view action' title='"._("Ver")."' href='".ROOT."{$objectname}/view/". $entity['id'] ."'><span>"._('Ver')."</span></a> ".
				"<a class='action-edit action' title='"._("Editar")."' href='".ROOT."{$objectname}/edit/". $entity['id'] ."'><span>"._('Editar')."</span></a> ".
				"<a class='action-delete action' entity_id='".$entity['id']."' title='"._("Eliminar")."' href='#'><span>"._('Eliminar')."</span></a>"
			);
		{rdelim}
	
		echo json_encode(array("aaData" => $data));
	{rdelim}

	function ajaxDelete() {ldelim}
		if(!IS_AJAX)
			$this->redirect("application/401");
			
		$data['error'] = '';
		try {ldelim}
			$id = $_POST['id'];
			
			//TODO: Comprobamos que podemos eliminar la entidad.
			//if($this->usuario->checkDelete($id)) {ldelim}
			
			Doctrine::getTable("{$classname}")->delete($id);
			
			//{rdelim} else {ldelim}
			//	$data['error'] = _ERR_PERMISSION;
			//{rdelim}
			
		{rdelim} catch (Exception $e) {ldelim}
			$data['error'] = _ERR_UNEXPECTED;
			//TODO: Loguear error
		{rdelim}
		echo json_encode($data);
	{rdelim}