<?php

namespace Game\Module\Sample\Action;

use GameManager\Core\Component\Action;
use GameManager\Core\Component\Loader;

class Sample extends Action {
	
	public function index() {
		
		$data = array('parameter'=>'freaking parameter');
		
		return $this->load(Loader::T_VIEW, 'sample',$data);		
	}	
}