<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use Throwable;
use yii\data\ActiveDataProvider;

/**
 * Class SysExceptionsSearch
 */
class SysExceptionsSearch extends SysExceptions {

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params):ActiveDataProvider {
		$query = SysExceptions::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['timestamp' => SORT_DESC],
			'attributes' => [
				'id',
				'code',
				'timestamp',
				'user_id',
				'file',
				'message',
				'known'
			]
		]);
		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		$query->andFilterDateBetween(SysExceptions::fieldName('timestamp'), $this->timestamp);
		$query->andFilterWhere(['like', SysExceptions::fieldName('get'), $this->get]);
		$query->andFilterWhere(['like', SysExceptions::fieldName('post'), $this->post]);
		$query->andFilterWhere([SysExceptions::fieldName('user_id') => $this->user_id]);
		$query->andFilterWhere([SysExceptions::fieldName('code') => $this->code]);
		$query->andFilterWhere([SysExceptions::fieldName('statusCode') => $this->statusCode]);
		$query->andFilterWhere(['like', SysExceptions::fieldName('message'), $this->message]);
		$query->andFilterWhere(['like', SysExceptions::fieldName('trace'), $this->trace]);
		$query->andFilterWhere(['like', SysExceptions::fieldName('file'), $this->file]);
		$query->andFilterWhere(['=', SysExceptions::fieldName('known'), $this->known]);

		return $dataProvider;
	}

}