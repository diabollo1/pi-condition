#!/usr/bin/python

#########################################################################
#                                                                       #
#                       Made by: Tomasz Kulinowski                      #
#                                                                       #
#########################################################################

from subprocess import call
import os

# import sys
# sys.path.insert(0, '..')
# import pass_pi_temp.py

import sys
sys.path.append("/home/pi/admin_skrypt")
import pass_pi_temp

import MySQLdb

# print ""
# print "sys.argv:"
# print sys.argv
# print ""
#--------------------------------------------------------------------------------#

#--------------------------------------------------------------------------------#
# Return CPU temperature as a character string
def getCPUtemperature():
    res = os.popen('vcgencmd measure_temp').readline()
    return(res.replace("temp=","").replace("'C\n",""))

# Return RAM information (unit=kb) in a list
# Index 0: total RAM
# Index 1: used RAM
# Index 2: free RAM
def getRAMinfo():
    p = os.popen('free')
    i = 0
    while 1:
        i = i + 1
        line = p.readline()
        if i==2:
            return(line.split()[1:4])

# Return % of CPU used by user as a character string
def getCPUuse():
    return(str(os.popen("top -n1 | awk '/Cpu\(s\):/ {print $2}'").readline().strip()))

print "aaa"
print getCPUuse()
print "bbb"

# Return information about disk space as a list (unit included)
# Index 0: total disk space
# Index 1: used disk space
# Index 2: remaining disk space
# Index 3: percentage of disk used
def getDiskSpace():
    p = os.popen("df -h /")
    i = 0
    while 1:
        i = i +1
        line = p.readline()
        if i==2:
            return(line.split()[1:5])

tab={}


# CPU informatiom
tab["CPU_temp"] = getCPUtemperature()

tab['CPU_usage'] = getCPUuse().replace(",",".")

# RAM information
# Output is in kb, here I convert it in Mb for readability
RAM_temp = getRAMinfo()
tab["RAM_total"] = round(int(RAM_temp[0]) / 1000,1)
tab["RAM_used"] = round(int(RAM_temp[1]) / 1000,1)
tab["RAM_free"] = round(int(RAM_temp[2]) / 1000,1)

# Disk information
DISK_temp = getDiskSpace()
tab["DISK_total"] = DISK_temp[0][0:-1].replace(",",".")
tab["DISK_free"] = DISK_temp[1][0:-1].replace(",",".")
tab["DISK_perc"] = DISK_temp[3][0:-1]


for key, value in tab.iteritems():
	print key, value

print ""
#--------------------------------------------------------------------------------#

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
print key_temp[0:-1]
print value_temp[0:-1]
print ""

print "INSERT:"
query = 'INSERT INTO temp_cpu_ram_disk ('+key_temp+') VALUES ('+value_temp+')'
print query

cur.execute(query)




db.commit()

db.close()

#--------------------------------------------------------------------------------#

#python /var/www/html/temp/temp_cpu.py
