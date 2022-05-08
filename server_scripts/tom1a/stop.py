#!/usr/bin/python3
def calcCrc( msg ):
        "Vypocet checksumu"
        crc = 0;
        for letter in msg:     # First Example
           crc = crc ^ ord(letter)
        crc = format(crc, 'X')
        return crc;

def makeCommand( msg ):
        "Vytvorenie vety"
        veta = msg+'*'+calcCrc(msg)+'\n'
        final = b'$'+str.encode(veta)
        return final;

import serial
import sys
import glob
import argparse
import matlab.engine



if len(sys.argv) == 1:
    print("give me a path to com")
    sys.exit()

parser = argparse.ArgumentParser()
parser.add_argument("--port")
parser.add_argument("--software")
parser.add_argument("--fileName")
args = parser.parse_args()
port = args.port
software = args.software
fileName = args.fileName
ports = glob.glob('/dev/tty[A-Za-z]*')

if port not in ports:
    sys.exit(1)

if (software == "matlab"):
    matlabInstance = matlab.engine.connect_matlab(matlab.engine.find_matlab()[0])
    matlabInstance.set_param(fileName,'SimulationCommand','stop',nargout=0)
    matlabInstance.quit()
ser = serial.Serial(port, 115200)
ser.write(makeCommand('SEE'))
