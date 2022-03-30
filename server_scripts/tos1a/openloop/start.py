#!/usr/bin/python3
import serial
import time
import argparse
import sys
import os
import calendar

current_milli_time = lambda: int(round(time.time() * 1000))

def calcCrc( msg ):
        "Vypocet checksumu"
        crc = 0;
        for letter in msg:     # First Example
           crc = crc ^ ord(letter)
        crc = format(crc, 'X')
        return crc;

def makeCommand( msg ):
        "Vytvorenie vety"
        #final = b'$'+msg+'*'+calcCrc(msg)+'\n'
        veta = msg+'*'+calcCrc(msg)+'\n'
        final = b'$'+str.encode(veta)
        return final;

def getArguments():
    parser = argparse.ArgumentParser()
    parser.add_argument("--port")
    parser.add_argument("--input")
    parser.add_argument("--output")
    args = parser.parse_args()
    output = args.output
    port = args.port
    args = args.input
    args = args.split(",")
    # args = [pair.replace(" ","") for pair in args]
    args_map = {}
    for arg in args:
       argument = arg.split(":")
       args_map[argument[0]] = argument[1]
    args_map["port"] = port
    args_map["output"] = output
    return args_map


def startReading(args):
    port = args["port"]
    ser = serial.Serial(port, 115200)
    readCmd = makeCommand('SGV')
    filePath = args["output"]
#    now = calendar.timegm(time.gmtime())
#    now = current_milli_time()
    start = current_milli_time()
#    end = now + int(float(args["t_sim"])) * 1000
    elapsedTime = 0
    count = 0
    duration = int(float(args["t_sim"])) * 1000
    rate = float(args["s_rate"])

    try:
        while (elapsedTime < duration):
            file = open(filePath, "a+")
            ser.write(makeCommand("SGV"))
            output = ser.readline()
            try :
                output = output.decode("utf-8")
                beginPos = output.find("$") + 1
                endPos = output.find("*")
                elapsedTime = current_milli_time() - start
                output = str(elapsedTime) + "," + output[beginPos:endPos] + "," + args["c_lamp"] + "," + args["c_led"] + "," + args["c_fan"] + "\n"
#                print(output)
            except ValueError:
                # How to handle such thing ?
                print("ops")

            file.write(output)
            file.close()
            count = count + 1
            time.sleep((-current_milli_time() + start + count*rate)/1000.0)
#            now = current_milli_time()
        file.close()
        ser.close()
    except Exception as e:
        exc_type, exc_obj, exc_tb = sys.exc_info()
        fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
        print(exc_type, fname, exc_tb.tb_lineno)
        print("Could not create file")
        ser.close()
        sys.exit(0)

def stopDevice(args):
    ser = serial.Serial(args["port"], 115200)
    ser.write(makeCommand('SEE'))
    ser.close()

def app(args):
    ser = serial.Serial(args["port"], 115200)
    ser.write(makeCommand('SSE'))
    command = "SGV," + args["c_lamp"] + "," + args["c_fan"] + "," + args["c_led"]
    ser.write(makeCommand(command))
    ser.close()
    startReading(args)
    stopDevice(args)

if __name__ == '__main__':
    args = getArguments()
    app(args)
