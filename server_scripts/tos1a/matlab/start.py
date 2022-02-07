#!/usr/bin/python3
import logging, logging.handlers
# Log everything, and send it to stderr.
import pwd

import matlab.engine
import os
import argparse
import subprocess
import time
import getpass
import subprocess


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
   args_map = {}
   for arg in args:
      argument = arg.split(":")
      args_map[argument[0]] = argument[1]
   args_map["port"] = port
   args_map["output_path"] = outputPath
   return args_map

def app(args):
	logfun = logging.getLogger("logfun")
	logfun.debug("Inside f!")

	matlabInstance = None

	i = 0
	logfun.debug("hladam MATLAB")

	try:
		logfun.debug(matlab.engine.find_matlab())
		while (len(matlab.engine.find_matlab()) == 0):
			time.sleep(5)
			i += 1
			if ((i*5) > 15):
				logfun.debug("spustam MATLAB")
				matlabInstance = matlab.engine.start_matlab()
				matlabInstance.desktop(nargout=0)
				break
	except Exception as ex:
		logfun.exception("Something awful happened!")

	if ( matlabInstance is None ):
		matlabInstance = matlab.engine.connect_matlab(matlab.engine.find_matlab()[0])

	logfun.debug("nacitavam argumenty")
	logfun.debug(args)

	port = args["port"] + "," +  args["output_path"]

	logfun.debug("MATLAB clear")
	matlabInstance.eval('clear', nargout=0)
	matlabInstance.workspace['baud'] = 115200
	matlabInstance.workspace['fTt'] = 0.05
	matlabInstance.workspace['fTf'] = 0.05
	matlabInstance.workspace['fTl'] = 0.05
	matlabInstance.workspace['Ts'] = float(args["s_rate"])/1000
	matlabInstance.workspace['com'] = port
	matlabInstance.workspace['t'] = 0
	matlabInstance.workspace['tempdps'] = 0
	matlabInstance.workspace['ftemp'] = 0
	matlabInstance.workspace['dtemp'] = 0
	matlabInstance.workspace['flight'] = 0
	matlabInstance.workspace['dlight'] = 0
	matlabInstance.workspace['frpm'] = 0
	matlabInstance.workspace['drpm'] = 0
	matlabInstance.workspace['uc'] = 0
	matlabInstance.workspace['tw'] = 10
	matlabInstance.workspace['Umax'] = float(8000)
	matlabInstance.workspace['Umin'] = float(0)



	for key, value in args.items():
		isFloat = False;
		logfun.debug(key)
		logfun.debug(value)
		try:
			#logfun.debug('assignin("base", "' + key + '", eval("' + value + '"))')
			matlabInstance.workspace[key] = float(value)
			isFloat = True;
		except Exception as ex:
			logfun.exception(ex)

		if not isFloat and '[' in value:
			try:
				logfun.debug('assignin("base", "' + key + '", eval("' + value + '"))')
				matlabInstance.eval('assignin("base", "' + key + '", eval("' + value + '"))', nargout=0)
			except Exception as ex:
				logfun.exception(ex)



	#matlabInstance.eval('assignin("base", "vystupy", eval("'+ vystupy + '"))', nargout=0)

	os.chdir("/var/www/olm_experiment_api/server_scripts/tos1a/matlab/")
	matlabInstance.addpath("/var/www/olm_experiment_api/server_scripts/tos1a/matlab/")
	matlabInstance.load_system("thermo_openloop_v2.slx",nargout=0)
	try:
		matlabInstance.set_param("thermo_openloop_v2",'SimulationCommand','start',nargout=0)

	except Exception as ex:
		logfun.exception("Something awful happened!")

	while matlabInstance.get_param("thermo_openloop_v2",'SimulationStatus') != 'stopped':
	    pass
	matlabInstance.quit()

if __name__ == '__main__':
   args = getArguments()
   app(args)


