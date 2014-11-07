<?php 
class ModelOdtValidatorSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'alias' => array('type' => 'string', 'null' => false),
		'lft' => array('type' => 'integer', 'null' => true),
		'rght' => array('type' => 'integer', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'model' => array('type' => 'string', 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'acos_idx1' => array('unique' => false, 'column' => array('lft', 'rght')),
			'acos_idx2' => array('unique' => false, 'column' => 'alias'),
			'acos_idx3' => array('unique' => false, 'column' => array('model', 'foreign_key')),
			'acos_leftright' => array('unique' => false, 'column' => array('lft', 'rght')),
			'lft' => array('unique' => false, 'column' => 'lft')
		),
		'tableParameters' => array()
	);
	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'foreign_key' => array('type' => 'integer', 'null' => true),
		'alias' => array('type' => 'string', 'null' => false),
		'lft' => array('type' => 'integer', 'null' => true),
		'rght' => array('type' => 'integer', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'model' => array('type' => 'string', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'alias' => array('unique' => false, 'column' => 'alias'),
			'alias_2' => array('unique' => false, 'column' => 'alias'),
			'aros_idx1' => array('unique' => false, 'column' => array('lft', 'rght')),
			'aros_idx2' => array('unique' => false, 'column' => 'alias'),
			'aros_idx3' => array('unique' => false, 'column' => array('model', 'foreign_key')),
			'aros_leftright' => array('unique' => false, 'column' => array('lft', 'rght')),
			'parent_id' => array('unique' => false, 'column' => 'parent_id'),
			'rght' => array('unique' => false, 'column' => 'rght')
		),
		'tableParameters' => array()
	);
	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false),
		'aco_id' => array('type' => 'integer', 'null' => false),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'aco_id' => array('unique' => false, 'column' => 'aco_id')
		),
		'tableParameters' => array()
	);
	public $modelsections = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true),
		'description' => array('type' => 'string', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $modeltemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'length' => 100),
		'filename' => array('type' => 'string', 'null' => true),
		'filesize' => array('type' => 'integer', 'null' => true),
		'content' => array('type' => 'binary', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'modeltype_id' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $modeltypes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true),
		'description' => array('type' => 'string', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $modelvalidations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'modelvariable_id' => array('type' => 'integer', 'null' => true),
		'modelsection_id' => array('type' => 'integer', 'null' => false),
		'modeltype_id' => array('type' => 'integer', 'null' => false),
		'min' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'max' => array('type' => 'integer', 'null' => true),
		'actif' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $modelvariables = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true),
		'description' => array('type' => 'string', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
}
