<?php

return [
	"start" => [
		[
			"name" => "setup",
			"rules" => "",
			"title" => "setup",
			"placeholder" => 
"
while (true) {
    for(int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, strip.Color(128, 0, 0));
    }
    strip.show();
    delay(500);

    for(int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, strip.Color(128, 128, 0));
    }
    strip.show();
    delay(500);

    for(int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, strip.Color(128, 0, 128));
    }
    strip.show();
    delay(500);

    for(int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, strip.Color(0, 128, 128));
    }
    strip.show();
    delay(500);

    for(int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, strip.Color(0, 128, 0));
    }
    strip.show();
    delay(500);

    for(int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, strip.Color(0, 0, 128));
    }
    strip.show();
    delay(500);
}
",
			"type" => "text",
			"row" => 1,
			"order" => 1,
			"multiline" => true
		],
		[
			"name" => "c_code",
			"rules" => "",
			"title" => "C code",
			"placeholder" => " ",
			"type" => "text",
			"row" => 2,
			"order" => 3,
			"multiline" => true
		],
		[
			"name"	=>	"uploaded_file",
			"rules"	=>	"",
			"title"	=>	"File with your code",
			"type"	=>	"file",
			"meaning" => "parent_schema"
		],
	],
	"stop" => [],
	"change" => []
];
