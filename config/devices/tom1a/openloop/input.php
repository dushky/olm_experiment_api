<?php

return [
	"start"  =>  [
		[
			"name"	=>	"c_fan",
			"rules"	=>	"required",
			"title"	=>	"Fan voltage",
			"placeholder"	=>	20,
			"type"	=>	"text",
			"row" => 1,
			"order" => 1
		],
		[
			"name"	=>	"c_lamp",
			"rules"	=>	"required",
			"title"	=>	"Lamp voltage",
			"placeholder"	=>	60,
			"type"	=>	"text",
			"row" => 1,
			"order" => 2
		],
		[
			"name"	=>	"c_led",
			"rules"	=>	"required",
			"title"	=>	"LED voltage",
			"placeholder"	=>	0,
			"type"	=>	"text",
			"row" => 1,
			"order" => 3
		],
		[
			"name"	=>	"t_sim",
			"rules"	=>	"required",
			"title"	=>	"Simulation time",
			"placeholder"	=>	10,
			"type"	=>	"text",
			"meaning"	=>	"experiment_duration",
			"row" => 2,
			"order" => 1
		],
		[
			"name"	=>	"s_rate",
			"rules"	=>	"required",
			"title"	=>	"Sampling rate",
			"placeholder"	=>	200,
			"type"	=>	"text",
			"meaning"	=>	"sampling_rate",
			"row" => 2,
			"order" => 2
		]
	],
	"stop" => [],
	"change" => [
		[
			"name"	=>	"c_fan",
			"rules"	=>	"required",
			"title"	=>	"Fan Voltage",
			"placeholder"	=>	20,
			"type"	=>	"text",
			"row" => 1,
			"order" => 1
		],
		[
			"name"	=>	"c_lamp",
			"rules"	=>	"required",
			"title"	=>	"Lamp voltage",
			"placeholder"	=>	60,
			"type"	=>	"text",
			"row" => 1,
			"order" => 2
		],
		[
			"name"	=>	"c_led",
			"rules"	=>	"required",
			"title"	=>	"LED voltage",
			"placeholder"	=>	0,
			"type"	=>	"text",
			"row" => 1,
			"order" => 3
		]
	]
];