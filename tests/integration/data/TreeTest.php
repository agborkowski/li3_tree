<?php
/**
 * @author
 */

namespace li3_tree\tests\integration\data;

use lithium\data\Connections;
use li3_fixtures\test\Fixture;
use li3_tree\tests\mocks\Categories;

class TreeTest extends \lithium\test\Integration {

	protected $_connection = null;

	protected $_key = null;

	protected static $config = array(
		'fixtures' => 'li3_tree/tests/fixtures' # i changed some load path in li3_fixtures
	);

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

	public function testInitTree() {
		Categories::remove();
		$this->assertIdentical(0, Categories::count());
		$fixtures = Fixture::load('Categories',array(
			'path' => static::$config['fixtures']
		));
		foreach ($fixtures as $row){
			Categories::create($row)->save();
		}

		$this->assertIdentical(16, Categories::count());
	}

	public function testGetRoots(){
		$options = array(
			array('conditions' => array(
				'parent_id' => 0
			)),
			array('conditions' => array(
				'parent_id' => NULL
			)),
			array('conditions' => array(
				'parent_id' => '0'
			))
		);
		foreach ($options as $row){
			$data = Categories::find('all', $row);
			$this->assertEqual(2, count($data), 'option:'.json_encode($row));
		}

		$options = array(
			array(),
			array('conditions' => array()),
		);
		foreach ($options as $row){
			$data = Categories::find('all', $row);
			$this->assertEqual(16, count($data), 'option:'.json_encode($row));
		}

		$data = Categories::find('first', array(
			'conditions' => array(
				'name' => 'Your Categories'
			)
		));
		$exceptedData = array(
			'id' => '16',
			'name' => 'Your Categories',
			'parent_id' => NULL,
			'lft' => NULL,
			'rght' => NULL
		);
		$this->assertEqual($exceptedData, $data->to('array'));
	}

	public function testGetChildren(){
		$data = Categories::find('count', array(
			'conditions' => array(
				'parent_id' => 6
			)
		));
		$this->assertEqual(3, $data);

		// $data = Categories::find('count', array(
		// 	'conditions' => array(
		// 		'parent_id' => 1
		// 	),
		// 	'li3_tree' => array(
		// 		'resursive' => true
		// 	)
		// ));
		// $this->assertEqual(14, $data);
	}

	public function testAddRoot(){
		$data = Categories::create(array(
			'id' => 17,
			'name' => 'testAddRoot'
		))->save();
		$data = Categories::find('count');
		$this->assertEqual(17, $data);

		$data = Categories::find('count', array('conditions' => array(
			'parent_id' => NULL
		)));
		$this->assertEqual(3, $data);

		$excepted = array(
			'id' => 17,
			'name' => 'testAddRoot',
			'parent_id' => NULL,
			'lft' => NULL,
			'rght' => NULL
		);
		$data = Categories::find('first', array(
			'conditions' => array(
				'parent_id' => NULL
			),
			'order' => 'id DESC'
		));
		$this->assertEqual($excepted, $data->to('array'));

	}

	public function testAddChildrenToRoot(){
		$root = Categories::first(16);
		$this->assertEqual('Your Categories', $root->name);

		$children = array(
			'id' => 18,
			'name' => 'Your categories children',
			'parent_id' => $root->id
		);
		$data = Categories::create($children)->save();

		$data = Categories::first(18);
		$children += array(
			'lft' => NULL,
			'rght' => NULL
		);
		$this->assertEqual($children, $data->to('array'));
	}

	public function testEditNodeNameAndParent(){
		$root = Categories::first(16);
		$update = $root->save(array(
			'name' => 'Your Categories moved',
			'parent_id' => 1
		));
		$this->assertFalse($update);
		//$root = Categories::first(16);
		//$this->assertEqual(NULL, $root->parent_id);
	}

	// /**
	//  * Tests that a single record with a manually specified primary key can be created, persisted
	//  * to an arbitrary data store, re-read and updated.
	//  *
	//  * @return void
	//  */
	// public function testCreateRootNode() {
	// 	Categories::all()->delete();
	// 	$this->assertIdentical(0, Categories::count());

	// 	$new = Categories::create(array('name' => 'My Categories'));
	// 	$expected = array('name' => 'My Categories', 'lft' => 1, 'rght' => 2, 'praent_id' => NULL);
	// 	$result = $new->data();
	// 	$this->assertEqual($expected, $result);

	// 	$this->assertEqual(
	// 		array(false, true, true),
	// 		array($new->exists(), $new->save(), $new->exists())
	// 	);
	// 	$this->assertIdentical(1, Categories::count());
	// }
}