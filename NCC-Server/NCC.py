#Pythonimports
import socket
import sys
import time
import json
from threading import Thread

#Selfimports
import MySqlHandlerAPI as MySql
import SocketHandlerAPI as SocketHandler
from pydispatch import dispatcher

ThreadSignal= "Resend Clients"
ThreadSender= "Thread"

#********************************************************************************************************
#Here, the socketobjekt gets created
#********************************************************************************************************
def createSocket():
	sock = socket.socket(socket.AF_INET,socket.SOCK_STREAM)
	sock.bind(('0.0.0.0',111))

	sock.listen(5) 	#
	return sock


def getKonfig(Filename="MySql.json"):
		file = open(Filename).read()
		data = json.loads(file)

		return data

#********************************************************************************************************
#This is the Main Programloop
#********************************************************************************************************
def main():
	data=getKonfig()

	sock = createSocket()
	sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1) # damit man den Socketerroe 98 nicht bekommt

	with open('/etc/hostname') as hname:
		hostname = hname.read()

	obj=MySql.MySqlobj(data["Database"]["Host"],data["Database"]["Username"],data["Database"]["Password"],"ncctest",data["HardwareData"]["maxDump"])
	obj.start()

	obj.connect()
	CreateConectionHandler = SocketHandler.WaitOnConnection(sock,hostname,obj)
	CreateConectionHandler.start()

	

if __name__ == "__main__":
	main()
