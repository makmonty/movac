{"<"}?php

class {$controllername} extends ApplicationController {ldelim}
	
	function __construct($view) {ldelim}
		parent::__construct($view);
		$this->navigation_menu->setSelected("menu_{$objectname}");
	{rdelim}
	
{rdelim}