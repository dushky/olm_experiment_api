#!/usr/bin/python3

import argparse
import subprocess
import os

def generate_arduino_code(c_code_snippet):
    arduino_code_template = """
#include <Adafruit_NeoPixel.h>

#define NUM_LEDS 512
#define DATA_PIN 2
#define CUBE_SIZE 8

Adafruit_NeoPixel strip = Adafruit_NeoPixel(NUM_LEDS, DATA_PIN, NEO_GRB + NEO_KHZ800);

void setup() {{
  strip.begin();
  strip.setBrightness(20);
  strip.show();
}}

void loop() {{
  {}
  delay(500);
}}

int xyzToIndex(int x, int y, int z) {{
  return x * CUBE_SIZE * CUBE_SIZE + z * CUBE_SIZE + y;
}}

void clearCube() {{
  for(int i = 0; i < NUM_LEDS; i++) {{
    strip.setPixelColor(i, strip.Color(0, 0, 0));  // Turn off the current pixel
  }}
  strip.show();  // Update the strip to turn off the LEDs
}}

void lightUpLED(int x, int y, int z) {{
  clearCube();
  int idx = xyzToIndex(x, y, z);
  strip.setPixelColor(idx, strip.Color(255, 255, 255));
  strip.show();
}}

void fillCubeBlue() {{
  for(int i = 0; i < NUM_LEDS; i++) {{
    strip.setPixelColor(i, strip.Color(0, 0, 255));
  }}
  strip.show();  // Update the strip to show the new color
}}

void fillCubeRed() {{
  for(int i = 0; i < NUM_LEDS; i++) {{
    strip.setPixelColor(i, strip.Color(255, 0, 0));
  }}
  strip.show();
}}

void fillCubeGreen() {{
  for(int i = 0; i < NUM_LEDS; i++) {{
    strip.setPixelColor(i, strip.Color(0, 255, 0));
  }}
  strip.show();
}}

void indexer() {{
  for (int x = 0; x < CUBE_SIZE; x++) {{
    for (int y = 0; y < CUBE_SIZE; y++) {{
      for (int z = 0; z < CUBE_SIZE; z++) {{
        lightUpLED(x, y, z);
        delay(250);
     }}
    }}
  }}
}}

"""
    
    # Insert the C code snippet into the template
    return arduino_code_template.format(c_code_snippet)

def compile_and_upload(code, port, board_type="arduino:avr:mega"):
    # Create a temporary directory to hold the sketch
    temp_dir = "temp_sketch"
    os.makedirs(temp_dir, exist_ok=True)

    # Write the Arduino code to a .ino file in the temporary directory
    sketch_path = os.path.join(temp_dir, "temp_sketch.ino")
    with open(sketch_path, "w") as file:
        file.write(code)

    # Compile and upload using Arduino CLI
    compile_cmd = f"arduino-cli compile --fqbn {board_type} {temp_dir}"
    upload_cmd = f"arduino-cli upload -p {port} --fqbn {board_type} {temp_dir}"

    try:
        subprocess.run(compile_cmd, check=True, shell=True)
        subprocess.run(upload_cmd, check=True, shell=True)
        print("Upload successful")
    except subprocess.CalledProcessError as e:
        print(f"An error occurred: {e}")

    # Clean up: remove the temporary directory
    subprocess.run(f"rm -rf {temp_dir}", shell=True)


def getArguments():
    parser = argparse.ArgumentParser()
    parser.add_argument("--port")
    parser.add_argument("--input")
    parser.add_argument("--output")
    args = parser.parse_args()
    output = args.output
    port = args.port
    args = args.input

    input_str = args
    keys = ['c_code', 'uploaded_code_file', 'uploaded_file', 'file_name']
    result = {}

    last_index = 0
    for key in keys:
        start_index = input_str.find(key + ":", last_index) + len(key) + 1
        if start_index != -1:
            last_index = input_str.find(",", start_index)
            last_index = last_index if last_index != -1 else None
            value = input_str[start_index:last_index]
            result[key] = value.strip() if value else None
            last_index = last_index if last_index else len(input_str)

    #"c_code": c_code_value.replace("\n", " "),
 
    result["port"] = port
    result["output"] = output

    return(result)

def main():
    args=getArguments()

    if(args["uploaded_code_file"] and args["uploaded_code_file"] != ""):
      full_arduino_code = generate_arduino_code(args["uploaded_code_file"])
      compile_and_upload(full_arduino_code, args["port"])
    else:
      full_arduino_code = generate_arduino_code(args["c_code"])
      compile_and_upload(full_arduino_code, args["port"])

if __name__ == '__main__':
    main()