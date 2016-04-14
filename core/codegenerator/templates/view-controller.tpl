	function view($id) {ldelim}
		//TODO: Comprobaciones
		
		$entity = Doctrine::getTable("{$classname}")->find($id);
		
		if(!$entity) {ldelim}
			$this->redirect("application/404");
			exit;
		{rdelim}
		
		$this->{$objectname} = $entity;
	{rdelim}