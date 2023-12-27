#!/usr/bin/python3

import argparse
import subprocess
import os

arduino_code_template = """
#include <Adafruit_NeoPixel.h>

#define NUM_LEDS 512
#define DATA_PIN 2
#define CUBE_SIZE 8

Adafruit_NeoPixel strip = Adafruit_NeoPixel(NUM_LEDS, DATA_PIN, NEO_GRB + NEO_KHZ800);

void setup() {
  strip.begin();
  strip.setBrightness(20);
  strip.show();
}

void loop() {
}

"""
    

def compile_and_upload(code, port, board_type="arduino:avr:mega"):
    # Create a temporary directory to hold the sketch
    temp_dir = "temp_sketch" #TODO CHANGE TO STORAGE DIR, NOW IN ROOT
    os.makedirs(temp_dir, exist_ok=True)

    sketch_path = os.path.join(temp_dir, "temp_sketch.ino")
    with open(sketch_path, "w") as file:
        file.write(code)

    compile_cmd = f"arduino-cli compile --fqbn {board_type} {temp_dir}"
    upload_cmd = f"arduino-cli upload -p {port} --fqbn {board_type} {temp_dir}"

    try:
        subprocess.run(compile_cmd, check=True, shell=True)
        subprocess.run(upload_cmd, check=True, shell=True)
        print("Upload successful")
    except subprocess.CalledProcessError as e:
        print(f"An error occurred: {e}")

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

    associative_array = {}

    associative_array["port"] = port
    associative_array["output"] = output

    return(associative_array)


def main():
    args=getArguments()    
    compile_and_upload(arduino_code_template, args["port"])


if __name__ == '__main__':
    main()