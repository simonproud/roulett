<?php
/**
* Конфигурация рулетки
* 
* @author SimonProud <simon.proud@ro.ru>
* @version 1.0
*/
return [
    'data' => [
        'money' => [
			'interval' => ['min' => 0.25, 'max' => 25],
			'items' => [],
			'chance' => [
				'byformule' => true,
				'formule' => 'random',
				'static' => 0.33
			]
		],
        'good' => [
			'interval' => ['min' => 1, 'max' => 1],
			'items' => [
				'pen' => 0.5,
				'notebook' => 0.4,
				'phone' => 0.05,
				'laptop' => 0.04,
				'auto' => 0.005
			],
			'chance' => [
				'byformule' => false,
				'static' => 0.33
			]
		],
        'point' => [
			'interval' => ['min' => 1, 'max' => 250],
			'items' => [],
			'chance' => [
				'byformule' => true,
				'formule' => 'random',
				'static' => 0.33
			]
		],
    ],
];
