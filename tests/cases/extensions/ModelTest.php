<?php
/**
 * Test case for li3_tree model extend
 *
 * @author agborkowski
 */

namespace li3_tree\tests\cases\extensions;

use li3_tree\tests\mocks\data\MockCategory;
use li3_fixtures\test\Fixture;


class ModelTest extends \lithium\tests\cases\data\ModelTest {
	protected static $config = array(
		'fixtures' => 'li3_tree/tests/fixtures' # i changed some load path in li3_fixtures
	);

	public function setUp() {
		parent::setUp();
		MockCategory::config(array('connection' => 'mock-source'));
	}

	public function testAddRootNode(){
		MockCategory::remove();
		$this->assertEqual(MockCategory::find('count'), 0);
		$data = Fixture::load('categories', array(
			'path' => static::$config['fixtures']
		));
		$dataExcepted = Fixture::load('excepteds', array(
			'path' => static::$config['fixtures']
		));
		$node = MockCategory::create($data->first());
		$save = $node->save();
		$this->assertEqual(MockCategory::find('count'), 1); #fail
		$this->assertEqual($dataExcepted->first(), $save);
	}
}
?>
