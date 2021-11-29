<?php

return [
	"start"  =>  [
		[
			"name"	=>	"reg_request",
			"rules"	=>	"required",
			"title"	=>	"Žiadaná hodnota (C/lx/RPM)",
			"placeholder"	=>	30,
			"type"	=>	"text"
		],
		[
			"name"	=>	"input_fan",
			"rules"	=>	"required",
			"title"	=>	"Napätie ventilátora (0-100)",
			"placeholder" =>	0,
			"type"	=>	"text"
		],
		[
			"name"	=>	"input_lamp",
			"rules"	=>	"required",
			"title"	=>	"Napätie lampy (0-100)",
			"placeholder"	=>	0,
			"type"	=>	"text"
		],
		[
			"name"	=>	"input_led",
			"rules"	=>	"required",
			"title"	=>	"Napätie LED",
			"placeholder" => 0,
			"type"	=>	"text"
		],/*
		[
			"name"	=>	"reg_regulate",
			"rules"	=>	"required",
			"title"	=>	"Regulačná veličina",
			"placeholder"	=>	1,
			"type"	=>	"select",
			"values" => [
				[
					"name" => "Ziarovka",
					"value" => 1
				],
				[
					"name" => "Ventilator",
					"value" => 2
				],
				[
					"name" => "LED",
					"value" => 3
				]
			]
		],
		[
			"name"	=>	"reg_output",
			"rules"	=>	"required",
			"title"	=>	"Regulovaná veličina",
			"placeholder"	=>	1,
			"type"	=>	"select",
			"values" => [
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
		],*/
		[
			"name"	=>	"t_sim",
			"rules"	=>	"required",
			"title"	=>	"Čas simulácie",
			"placeholder"	=>	10,
			"type"	=>	"text",
			"meaning"	=>	"experiment_duration"
		],
		[
			"name"	=>	"s_rate",
			"rules"	=>	"required",
			"title"	=>	"Vzorkovacia frekvencia",
			"placeholder"	=>	200,
			"type"	=>	"text",
			"meaning"	=>	"sampling_rate"
		],
                [
                        "name"  =>      "uploaded_file",
                        "rules" =>      "required",
                        "title" =>      "Simulation scheme",
                        "type"  =>      "file",
                        "meaning" => "parent_schema"
                ],
                [
                        "name"  =>      "user_function",
                        "rules" =>      "",
                        "title" =>      "Own fun",
                        "type"  =>      "file",
                        "meaning" => "child_schema"
                ]

	]/*,
	"change"  =>  [
		[
			"name"	=>	"reg_request",
			"rules"	=>	"required",
			"title"	=>	"Žiadaná hodnota (C/lx/RPM)",
			"placeholder"	=>	30,
			"type"	=>	"text"
		],
		[
			"name"	=>	"input_fan",
			"rules"	=>	"required",
			"title"	=>	"Napätie ventilátora (0-100)",
			"placeholder" =>	0,
			"type"	=>	"text"
		],
		[
			"name"	=>	"input_lamp",
			"rules"	=>	"required",
			"title"	=>	"Napätie lampy (0-100)",
			"placeholder"	=>	0,
			"type"	=>	"text"
		],
		[
			"name"	=>	"input_led",
			"rules"	=>	"required",
			"title"	=>	"Napätie LED",
			"placeholder" => 0,
			"type"	=>	"text"
		]
	]*/
];
