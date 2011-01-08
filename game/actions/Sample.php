<?php

class Sample extends Action {
	
	function index() {		
		return $this->view('exemple_view');
	}
	
	function test() {
		return "test";
	}
}