#!/usr/bin/python
f = open('access_log-20160803')
fileout = open("log.txt","w")
count = 0

for line in f.readlines():
	if line.find ("GET /catalog/") != -1:
		count += 1
		#print line
		fileout.write (line)		
print "Total requests: ", count
print "Open file log.txt" 