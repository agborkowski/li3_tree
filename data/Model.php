<?php
namespace li3_tree\data;

class Model extends \lithium\data\Model {

	public static function __init($options = array()){

		parent::__init($options);

		static::applyFilter('find', function($self, $params, $chain) {
			$defaults = array(
				'conditions' => array(
					'enabled' => true
				)
			);
			if(isset($params['options']['conditions']['parent_id'])){
				if(empty($params['options']['conditions']['parent_id'])){
					$params['options']['conditions']['parent_id'] = NULL;
				}
			}
			$params['options'] += $defaults + (array) $params['options'];
			return $chain->next($self, $params, $chain);
		});

		static::applyFilter('save', function($self, $params, $chain) {
			$defaults = array(
				'conditions' => array(
					'enabled' => true
				)
			);

			$params['options'] += $defaults + (array) $params['options'];


			$record = $params['entity'];
			$modified = $record->modified();
			$exists = $record->exists();

			if($exists && (isset($modified['parent_id']) || isset($modified['id']))){
				return false; // cant move node and change them id
			}
			return $chain->next($self, $params, $chain);
		});
		//save new, update
		// static::finder('countChildren', function($self, $params, $chain){
		// 	$defaults = array(
		// 		'enabled' => true
		// 	);

		// 	$params['options']['conditions'] = $defaults + (array) $params['options']['conditions'];

		// 	$data = $chain->next($self, $params, $chain);

		// 	return $data;
		// });
	}

}