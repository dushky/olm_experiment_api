#!/usr/bin/python3
import matlab.engine
import os
import argparse
import time
import getpass

def getArguments():
   parser = argparse.ArgumentParser()
   parser.add_argument("--port")
#    parser.add_argument("--output")
   parser.add_argument("--input")
   args = parser.parse_args()
   port = args.port
   args = args.input
   args = args.split(",")
   args_map = {}
   for arg in args:
      argument = arg.split(":")
      args_map[argument[0]] = argument[1]
   args_map["port"] = port
   return args_map

def app(args):

	currentDir = os.path.dirname(os.path.abspath(__file__))

	matlabInstance = matlab.engine.connect_matlab(matlab.engine.find_matlab()[0])

	inputFan = args["input_fan"]
	inputLamp = args["input_lamp"]
	inputLed = args["input_led"]
	regRequest = args["reg_request"]

	print("LAMP: ", inputLamp)
	print("FAN: ", inputFan)
	print("LED: ", inputLed)
	print("regRequest: ", regRequest)

	print("<=================================>")

	print("MATLAB LAMP: ", matlabInstance.workspace['input_lamp'])
	print("MATLAB FAN: ", matlabInstance.workspace['input_fan'])
	print("MATLAB LED: ", matlabInstance.workspace['input_led'])
	print("MATLAB regRequest: ", matlabInstance.workspace['reg_request'])

	matlabInstance.workspace['input_fan'] = float(inputFan)
	matlabInstance.workspace['input_lamp'] = float(inputLamp)
	matlabInstance.workspace['input_led'] = float(inputLed)
	matlabInstance.workspace['reg_request'] = float(regRequest)

	print("<=================================>")

	print("MATLAB LAMP: ", matlabInstance.workspace['input_lamp'])
	print("MATLAB FAN: ", matlabInstance.workspace['input_fan'])
	print("MATLAB LED: ", matlabInstance.workspace['input_led'])
	print("MATLAB regRequest: ", matlabInstance.workspace['reg_request'])

	print("<=================================>")

	try:
		matlabInstance.eval("assignin(" + args["file_name"] + ",'SimulationCommand','update')", nargout=0)
	except Exception as ex:
		print("EXCEPTION: ", ex)

	matlabInstance.set_param(args["file_name"],'SimulationCommand','update',nargout=0)

	print("MATLAB LAMP: ", matlabInstance.workspace['input_lamp'])
	print("MATLAB FAN: ", matlabInstance.workspace['input_fan'])
	print("MATLAB LED: ", matlabInstance.workspace['input_led'])
	print("MATLAB regRequest: ", matlabInstance.workspace['reg_request'])

	matlabInstance.quit()


if __name__ == '__main__':
   args = getArguments()
   app(args)


