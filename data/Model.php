<?php
namespace li3_tree\data;
use lithium\data\model\QueryException;
class Model extends \lithium\data\Model {

	// protected $_schema = array(
	// 	'id' => array('type' => 'id'),
	// 	'parent_id' => array('type' => 'integer'),
	// 	'lft' => array('type' => 'integer'),
	// 	'rght' => array('type' => 'integer'),
	// 	'text' => array('type' => 'string', 'null' => false)
	// );

	public static function __init($options = array()){

		parent::__init($options);

		static::applyFilter('find', function($self, $params, $chain) {
			if(isset($params['options']['conditions']['parent_id']) && empty($params['options']['conditions']['parent_id'])){
				$params['options']['conditions']['parent_id'] = NULL;
			}

			return $chain->next($self, $params, $chain);
		});

		static::applyFilter('save', function($self, $params, $chain) {
			$exists = $params['entity']->exists();
			$data = $params['data'];

			if(isset($data['parent_id']) && empty($data['parent_id'])){
				$params['data']['parent_id'] = NULL;
			}

			if($exists && (!empty($data['parent_id']) && !empty($data['id']))){
				throw new QueryException("Modifing `parent_id` is deny !");
				return false; // cant move node and change them id
			}
			return $chain->next($self, $params, $chain);
		});
	}

}