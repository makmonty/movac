<?php

namespace menu;

class Menu {
	private $menu;
	private $selected;
	
	public function __construct($menu = array()) {
		$this->menu = $menu;
	}
	
	public function addItem($id, $mi) {
		$this->menu[$id] = $mi;
	}

	public function get($ul_class = '', $li_class = '') {
		$s = "<ul class='$ul_class'>";
		$reserved_li_attrs = array("name", "url", "submenu", "class", "id");
		
		foreach($this->menu as $mi_id => $mi) {
			$class = $li_class;
			
			if($mi_id == $this->selected)
				$class .= " active";
			if(isset($mi['submenu']))
				$class .= " dropdown";
			if(isset($mi['class']))
				$class .= " ". $mi['class'];
			
			$s .= "<li class='".$class."' id='".$mi_id."'";
			
			foreach($mi as $attr => $attrvalue) {
				if(!in_array($attr, $reserved_li_attrs)) {
					$s .= " ". $attr ."='". $attrvalue ."'";
				}
			}
			
			$s .= ">";
			
			if(isset($mi['url']) || isset($mi['submenu'])) {
				$s .= "<a href='";
				if(isset($mi['url']))
					$s .= $mi['url'];
				else
					$s .= "#";
				$s .= "'";
				if(isset($mi['submenu']))
					$s .= " class='dropdown-toggle' data-toggle='dropdown'";
				$s .=">";
			}
			
			if(isset($mi['name']) && $mi['name'] != "")
				$s .= "<span>".$mi['name']."</span>";
			
			if(isset($mi['submenu'])) $s .= " <b class='caret'></b>";
			if(isset($mi['url'])) $s .= "</a>";
			
			if(isset($mi['submenu'])) {
				$s .= $mi['submenu']->get('dropdown-menu');
			}
			$s .= "</li>";
		}
		$s .= "</ul>";
		
		return $s;
	}
	
	
	public function draw($ul_class = '', $li_class = '') {
		echo $this->get($ul_class, $li_class);
	}
	
	public function getMenu() {
		return $this->menu;
	}
	public function setMenu($menu) {
		$this->menu = $menu;
	}
	public function getSelected() {
		return $this->selected;
	}
	public function setSelected($selected) {
		$this->selected = $selected;
	}
}