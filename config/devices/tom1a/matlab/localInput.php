<?php

return [
    "change"  =>  [
        [
            "name" => "vi",
            "rules" => "required",
            "title" => "Input disturbance (20s)",
            "placeholder" => 0,
            "type" => "text"
        ],
        [
            "name" => "Kc",
            "rules" => "required",
            "title" => "Kc",
            "placeholder" => 0.007,
            "type" => "text"
        ],
        [
            "name" => "Ti",
            "rules" => "required",
            "title" => "Ti",
            "placeholder" => 0.7,
            "type" => "text"
        ],
    ],
    "stop" => [],
    "startLocal" => [
        [
            "name" => "vi",
            "rules" => "required",
            "title" => "Input disturbance (20s)",
            "placeholder" => 0,
            "type" => "text"
        ],
        [
            "name" => "Kc",
            "rules" => "required",
            "title" => "Kc",
            "placeholder" => 0.007,
            "type" => "text"
        ],
        [
            "name" => "Ti",
            "rules" => "required",
            "title" => "Ti",
            "placeholder" => 0.7,
            "type" => "text"
        ],
        [
            "name" => "reg_output",
            "rules" => "required",
            "title" => "System output",
            "placeholder" => 3,
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
            "placeholder" => 2,
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
