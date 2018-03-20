#!/usr/bin/python

#########################################################################
#                                                                       #
#                       Made by: Tomasz Kulinowski                      #
#                                                                       #
#########################################################################

from subprocess import call
import os
import time

import sys
sys.path.append("/home/pi/admin_skrypt")
import pass_pi_temp

import MySQLdb

speed = os.popen("speedtest-cli --simple").read()
#testowo z pliku:
#speed = os.popen("cat speed_example.txt").read()

lines = speed.split('\n')

#aktualny czas
ts = time.time()
now = time.strftime('%d-%m-%Y %H:%M:%S')

tab={}

#zabezpieczenie przed zerem
if "Cannot" in speed:
	tab['Ping'] = 0
	tab['Download'] = 0
	tab['Upload'] = 0
else:
    tab['Ping'] = lines[0][6:11]
    tab['Download'] = lines[1][10:16]
    tab['Upload'] = lines[2][8:12]
	
print now, "    ", "Ping:", tab['Ping'], "    ", "Download:", tab['Download'], "    ", "Upload:", tab['Upload']



#--------------------------------------------------------------------------------#

#query = 'INSERT INTO CPU_temp (temperatura) VALUES ("'+tab["CPU_temp"]+'")'

db = MySQLdb.connect(host=pass_pi_temp.host,    # your host, usually localhost
                     user=pass_pi_temp.user,         # your username
                     passwd=pass_pi_temp.passwd,  # your password
                     db=pass_pi_temp.db)        # name of the data base
# you must create a Cursor object. It will let
# you execute all the queries you need
cur = db.cursor()

key_temp = ""
value_temp = ""


for key, value in tab.iteritems():
	temp=str(value)
	if str(value) == "" : temp="0"
	key_temp = key_temp + key + ","
	value_temp = value_temp + temp + ","

key_temp = key_temp[0:-1]
value_temp = value_temp[0:-1]
# print key_temp[0:-1]
# print value_temp[0:-1]
# print ""

# print "INSERT:"
query = 'INSERT INTO speedtest_net ('+key_temp+') VALUES ('+value_temp+')'
print "   "+query

cur.execute(query)




db.commit()

db.close()

#--------------------------------------------------------------------------------#
