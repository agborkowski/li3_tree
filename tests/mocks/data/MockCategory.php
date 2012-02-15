<?php
/**
 * @author agborkowski
 */

namespace li3_tree\tests\mocks\data;

class MockCategory extends \lithium\data\Model {
	protected $_meta = array('connection' => 'test');
	protected $_schema = array(
		'id' => array('type' => 'integer'),
		'name' => array('type' => 'string'),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
	);
}

?>