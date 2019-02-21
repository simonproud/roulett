<?php
/**
* Конфигурация рулетки
* 
* @author SimonProud <simon.proud@ro.ru>
* @version 1.0
*/
return [
	'multiplier' => 3.25,
    'data' => [
        'Деньги' => [
			'interval' => ['min' => 0.25, 'max' => 25],
			'items' => [
				'Деньги' => [
					'count' => 1000
				]
			],
			'chance' => [
				'byformule' => true,
				'formule' => 'random',
				'static' => 0.2
			]
		],
        'good' => [
			'interval' => ['min' => 1, 'max' => 1],
			'items' => [
				'Ручка' => [
					'count' => 1000,
					'chance' => 0.5
				],
				'Записная книжка' => [
					'count' => 100,
					'chance' => 0.4
				],
				'Телефон' => [
					'count' => 10,
					'chance' => 0.05
				],
				'Ноутбук' => [
					'count' => 5,
					'chance' =>  0.04
				],
				'Автомобиль' => [
					'count' => 1,
					'chance' =>  0.005
				]
			],
			'chance' => [
				'byformule' => false,
				'static' => 0.5
			]
		],
        'Очки лояльности' => [
			'interval' => ['min' => 1, 'max' => 250],
			'items' => [
				'Очки лояльности' =>[
					'count' => 'MAGIC'
				]
			],
			'chance' => [
				'byformule' => true,
				'formule' => 'random',
				'static' => 0.5
			]
		],
    ],
];
