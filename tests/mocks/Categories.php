<?php
/**
 * @author agborkowski
 */

namespace li3_tree\tests\mocks;
//@fixme \li3_tree\extensions\Model break Categories !
class Categories extends \li3_tree\data\Model {
	protected $_meta = array('connection' => 'test');
	protected $_schema = array(
		'id' => array('type' => 'integer'),
		'text' => array('type' => 'string'),
		'parent_id' => array('type' => 'integer'),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
		'enabled' => array('type' => 'boolean', 'null' => false, 'default' => 1)
	);
}

?>