#!/usr/bin/python

from subprocess import call
import os 

import sys
sys.path.insert(0, '..')
import hasla

import MySQLdb

db = MySQLdb.connect(host=hasla.host,    # your host, usually localhost
                     user=hasla.user,         # your username
                     passwd=hasla.passwd,  # your password
                     db=hasla.db)        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()




def getCPUtemperature():
    res = os.popen('vcgencmd measure_temp').readline()
    return(res.replace("temp=","").replace("'C\n",""))


# CPU informatiom
CPU_temp = getCPUtemperature()
"""
CPU_usage = getCPUuse()

# RAM information
# Output is in kb, here I convert it in Mb for readability
RAM_stats = getRAMinfo()
RAM_total = round(int(RAM_stats[0]) / 1000,1)
RAM_used = round(int(RAM_stats[1]) / 1000,1)
RAM_free = round(int(RAM_stats[2]) / 1000,1)

# Disk information
DISK_stats = getDiskSpace()
DISK_total = DISK_stats[0]
DISK_free = DISK_stats[1]
DISK_perc = DISK_stats[3]
"""
query = 'INSERT INTO temp_cpu (temperatura) VALUES ("'+CPU_temp+'")'

print query





cur.execute(query)

db.commit()

db.close()

#python /var/www/html/temp/temp_cpu.py
