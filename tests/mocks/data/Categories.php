<?php
/**
 * @author agborkowski
 */

namespace li3_tree\tests\mocks\data;
//@fixme \li3_tree\extensions\Model break Categories !
class Categories extends \li3_tree\extensions\Model {
	protected $_meta = array('connection' => 'test');
	protected $_schema = array(
		'id' => array('type' => 'integer'),
		'text' => array('type' => 'string'),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
		'enabled' => array('type' => 'boolean')
	);
}

?>