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

if len(sys.argv) == 1:
    print("give me a path to com")
    sys.exit()

port = sys.argv[1]

ports = glob.glob('/dev/tty[A-Za-z]*');
if port not in ports:
    sys.exit(1);

ser = serial.Serial(port, 115200)
ser.write(makeCommand('SEE'))
