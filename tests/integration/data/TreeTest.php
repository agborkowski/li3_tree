<?php
/**
 * @author
 */

namespace li3_tree\tests\integration\data;

use li3_tree\tests\mocks\data\Categories;
use lithium\data\Connections;
use li3_fixtures\test\Fixture;

class TreeTest extends \lithium\test\Integration {

	protected $_connection = null;

	protected $_key = null;

	public function setUp() {
		Categories::config();
		$this->_key = Categories::key();
		$this->_connection = Connections::get('test');
	}

	/**
	 * Skip the test if no test database connection available.
	 *
	 * @return void
	 */
	public function skip() {
		$isAvailable = (
			Connections::get('test', array('config' => true)) &&
			Connections::get('test')->isConnected(array('autoConnect' => true))
		);
		$this->skipIf(!$isAvailable, "No test connection available.");
	}
	/**
	 * Tests that a single record with a manually specified primary key can be created, persisted
	 * to an arbitrary data store, re-read and updated.
	 *
	 * @return void
	 */
	public function testCreateRootNode() {
		Categories::all()->delete();
		$this->assertIdentical(0, Categories::count());

		$new = Categories::create(array('name' => 'My Categories'));
		$expected = array('name' => 'My Categories', 'lft' => 1, 'rght' => 2, 'praent_id' => NULL);
		$result = $new->data();
		$this->assertEqual($expected, $result);

		$this->assertEqual(
			array(false, true, true),
			array($new->exists(), $new->save(), $new->exists())
		);
		$this->assertIdentical(1, Categories::count());
	}
}