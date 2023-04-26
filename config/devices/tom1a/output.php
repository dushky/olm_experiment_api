<?php

return [
	[
		"name" => "time",
		"title" => "Timestamp"
	],
	[
		"name"  => "temp_chip",
		"title" => "Chip temp"
	],
	[
		"name"  => "f_temp_int",
		"title" => "Filtered internal temp",
        "defaultVisibilityFor" => ["openloop"]
	],
	[
		"name"  => "d_temp_int",
		"title" => "Derived internal temp"
	],
	[
		"name"  => "f_temp_ext",
		"title" => "Filtered external temp"
	],
	[
		"name"  => "d_temp_ext",
		"title" => "Derived external temp"
	],
	[
		"name"  => "f_light_int_lin",
		"title" => "Light filtered lin intensity",
        "defaultVisibilityFor" => ["openloop"]
	],
	[
		"name"  => "d_light_int_lin",
		"title" => "Light derived lin intensity"
	],
	[
		"name"  => "f_light_int_log",
		"title" => "Light filtered log intensity"
	],
	[
		"name"  => "d_light_int_log",
		"title" => "Light derived log intensity"
	],
	[
		"name"  => "I_bulb",
		"title" => "Bulb current"
	],
	[
		"name"  => "V_bulb",
		"title" => "Bulb voltage"
	],
	[
		"name"  => "I_fan",
		"title" => "Fan current"
	],
	[
		"name"  => "V_fan",
		"title" => "Fan voltage"
	],
	[
		"name"  => "f_rpm",
		"title" => "Fan filtered rpm",
        "defaultVisibilityFor" => ["openloop"]
	],
	[
		"name"  => "d_rmp",
		"title" => "Fan derived rpm"
	],
	[
		"name"  => "f_rfs",
		"title" => "Fan RPM from start"
	],
	[
		"name"  => "p_cmf",
		"title" => "Period of cycle"
	],
	[
		"name"  => "u_lamp",
		"title" => "Lamp control signal",
        "defaultVisibilityFor" => ["matlab"]
	],
	[
		"name"  => "u_led",
		"title" => "LED control signal",
        "defaultVisibilityFor" => ["matlab"]
	],
	[
		"name"  => "u_fan",
		"title" => "Fan control signal",
        "defaultVisibilityFor" => ["matlab"]
	],
    [
        "name"  => "set_point",
        "title" => "Set point value",
        "defaultVisibilityFor" => ["matlab", "openloop"]
    ]
];
