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


key_temp = ""
value_temp = ""


for key, value in tab.iteritems():
	temp=str(value)
	if str(value) == "" : temp="0"
	key_temp = key_temp + key + ","
	value_temp = value_temp + temp + ","

key_temp = key_temp[0:-1]
value_temp = value_temp[0:-1]


query = 'INSERT INTO '+pass_pi_temp.table_speed+' ('+key_temp+') VALUES ('+value_temp+')'
print "   "+query
query2 = 'INSERT INTO '+pass_pi_temp.table_speed_linux+' ('+key_temp+') VALUES ('+value_temp+')'
print "   "+query


db = MySQLdb.connect(host=pass_pi_temp.host,    # your host, usually localhost
                     port=pass_pi_temp.port,
                     user=pass_pi_temp.user,         # your username
					 passwd=pass_pi_temp.passwd,  # your password
                     db=pass_pi_temp.db)        # name of the data base
db2 = MySQLdb.connect(host=pass_pi_temp.host_linux,    # your host, usually localhost
                     port=pass_pi_temp.port_linux,
                     user=pass_pi_temp.user_linux,         # your username
					 passwd=pass_pi_temp.passwd_linux,  # your password
                     db=pass_pi_temp.db_linux)        # name of the data base
# you must create a Cursor object. It will let
# you execute all the queries you need
cur = db.cursor()
cur2 = db2.cursor()

cur.execute(query)
cur2.execute(query2)

db.commit()
db2.commit()

db.close()
db2.close()

#--------------------------------------------------------------------------------#
