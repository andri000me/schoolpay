<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Core_Model extends CI_Model{
	protected $table_name;
	public function __construct() {
		parent::__construct();
	}
}
