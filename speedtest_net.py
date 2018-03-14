#!/usr/bin/python

#########################################################################
#                                                                       #
#                       Made by: Tomasz Kulinowski                      #
#                                                                       #
#########################################################################

from subprocess import call
import os
import time

#str(os.popen("cat speed_example.txt | awk '{printf $2}'").readline().strip())


#speed = os.popen("speedtest-cli --simple").read()
speed = os.popen("cat speed_example.txt").read()

lines = speed.split('\n')

ts = time.time()
now = time.strftime('%d-%m-%Y %H:%M:%S')

#if speedtest could not connect set the speeds to 0
if "Cannot" in speed:
	p = 0
	d = 0
	u = 0
#extract the values for ping, down and up values
else:
    p = lines[0][6:11]
    d = lines[1][10:16]
    u = lines[2][8:12]
	
print now, p, d, u

