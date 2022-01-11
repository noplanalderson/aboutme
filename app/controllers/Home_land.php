<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_land extends PROFILE_Core {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->_partial = array(
			'head',
			'header',
			'home_land',
			'body',
			'footer',
			'script'
		);

		$this->_module 	= 'home/main';

		$this->js 		= 'main';
		
		$this->_data 	= array(
			'title' 	=> "Muhammad Ridwan Na'im",
		);

		$this->load_view();
	}

}

/* End of file Home_land.php */
/* Location: .//C/xampp/htdocs/cv/app/controllers/Home_land.php */