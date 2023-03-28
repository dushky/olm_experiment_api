<?php

return [
	"change"  =>  [
		[
			"name" => "Ks",
			"rules" => "required",
			"title" => "Ks",
			"placeholder" => 0.1,
			"type" => "text"
		],
		[
			"name" => "w0",
			"rules" => "required",
			"title" => "Initial step value",
			"placeholder" => 0,
			"type" => "text"
		],
		[
			"name" => "tw",
			"rules" => "required",
			"title" => "Step time",
			"placeholder" => 0,
			"type" => "text"
		],
		[
			"name" => "Tdm",
			"rules" => "required",
			"title" => "Tdm",
			"placeholder" => 0.3,
			"type" => "text"
		],
		[
			"name" => "KP",
			"rules" => "required",
			"title" => "KP",
			"placeholder" => "",
			"type" => "text"
		],
		[
			"name" => "p1",
			"rules" => "required",
			"title" => "p1",
			"placeholder" => "",
			"type" => "text"
		],
		[
			"name" => "p2",
			"rules" => "required",
			"title" => "p2",
			"placeholder" => "",
			"type" => "text"
		]
    ],
    "stop" => [],
	"startLocal" => [
		[
			"name" => "Ks",
			"rules" => "required",
			"title" => "Ks",
			"placeholder" => 0.1,
			"type" => "text"
		],
		[
			"name" => "w0",
			"rules" => "required",
			"title" => "Initial step value",
			"placeholder" => 0,
			"type" => "text"
		],
		[
			"name" => "tw",
			"rules" => "required",
			"title" => "Step time",
			"placeholder" => 0,
			"type" => "text"
		],
		[
			"name" => "Tdm",
			"rules" => "required",
			"title" => "Tdm",
			"placeholder" => 0.3,
			"type" => "text"
		],
		[
			"name" => "KP",
			"rules" => "required",
			"title" => "KP",
			"placeholder" => "",
			"type" => "text"
		],
		[
			"name" => "p1",
			"rules" => "required",
			"title" => "p1",
			"placeholder" => "",
			"type" => "text"
		],
		[
			"name" => "p2",
			"rules" => "required",
			"title" => "p2",
			"placeholder" => "",
			"type" => "text"
		],
		[
			"name" => "reg_output",
			"rules" => "required",
			"title" => "System output",
			"placeholder" => "",
			"type" => "select",
			"options" => [
				[
					"name" => "Teplota",
					"value"=> 1
				],
				[
					"name" => "Svetlo",
					"value"=> 2
				],
				[
					"name" => "Otacky",
					"value"=> 3
				]
			]
		],
		[
			"name" => "reg_regulate",
			"rules" => "required",
			"title" => "Control signal",
			"placeholder" => 3,
			"type" => "select",
			"options" => [
				[
					"name" => "Bulb",
					"value"=> 1
				],
				[
					"name" => "Fan",
					"value"=> 2
				],
				[
					"name" => "LED",
					"value"=> 3
				]
			]
		]
	]
];
