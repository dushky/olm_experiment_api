#!/usr/bin/python3 -u
import time
import sys


for i in range(1,10):
    # sys.stderr.write(str(i))
    time.sleep(1)
    sys.stdout.write(str(i))
    print(i)
    # print("TEST\n")
    # time.sleep(1)
    # print >> sys.stdout, str(i)
    # print(str(i)+'', file = sys.stdout)
    # time.sleep(1)