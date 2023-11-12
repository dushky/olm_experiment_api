#!/usr/bin/python3

import serial
import time

try:
    ser = serial.Serial('/dev/tty.usbmodem12301', 9600)
    time.sleep(2)  

    ser.write(b'S')
    # Always close the connection when done
    ser.close()

except serial.SerialException as e:
    print(f"SerialException: {e}")
except Exception as e:
    print(f"Unexpected error: {e}")