#!/usr/bin/python3
import matlab.engine
import os
import argparse
import time
import getpass

def getArguments():
   parser = argparse.ArgumentParser()
   parser.add_argument("--port")
   parser.add_argument("--output")
   parser.add_argument("--input")
   args = parser.parse_args()
   port = args.port
   outputPath = args.output
   args = args.input
   args = args.split(",")
   args = [pair.replace(" ","") for pair in args]
   args_map = {}
   for arg in args:
      argument = arg.split(":")
      args_map[argument[0]] = argument[1]
   args_map["port"] = port
   args_map["output_path"] = outputPath
   return args_map

def app(args):

	currentDir = os.path.dirname(os.path.abspath(__file__))

	matlabInstance = matlab.engine.connect_matlab(matlab.engine.find_matlab()[0])

	inputFan = args["input_fan"]
	inputLamp = args["input_lamp"]
	inputLed = args["input_led"]
	regRequest = args["reg_request"]
	regType = args["reg_type"]
	P = args["P"]
	I = args["I"]
	D = args["D"]
	K0 = args["K0"]
	T0 = args["T0"]
	Tf = args["Tf"]

	matlabInstance.workspace['P'] = float(P)
	matlabInstance.workspace['I'] = float(I)
	matlabInstance.workspace['D'] = float(D)
	matlabInstance.workspace['K0'] = float(K0)
	matlabInstance.workspace['T0'] = float(T0)
	matlabInstance.workspace['Tf'] = float(Tf)
	matlabInstance.workspace['input_fan'] = float(inputFan)
	matlabInstance.workspace['input_lamp'] = float(inputLamp)
	matlabInstance.workspace['input_led'] = float(inputLed)
	matlabInstance.workspace['reg_request'] = float(regRequest)

	if regType=="Ziadna":
		matlabInstance.workspace['reg_type'] = float(1) #typ regulacie ziadna
	elif regType=="PID":
	      	matlabInstance.workspace['reg_type'] = float(2) #typ regulacie PID
	elif regType=="IMC":
	      	matlabInstance.workspace['reg_type'] = float(3) #typ regulacie IMC

	matlabInstance.set_param('thermo_simple_with_imc','SimulationCommand','update',nargout=0)
	matlabInstance.quit()


if __name__ == '__main__':
   args = getArguments()
   app(args)


