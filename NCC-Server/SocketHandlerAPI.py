#Pythonimports
import socket
import threading
from pydispatch import dispatcher
import datetime
import time

import MySqlHandlerAPI as msqlAPI


#---------------------------------------------------
#Class HandleSocketConnections
#Handels the clientconnections and recvives the data
#---------------------------------------------------
class HandleSocketConnections(threading.Thread):
	def __init__(self,clientsocket,adresse,hostname,mysql):
		threading.Thread.__init__(self)
		self.clientsocket=clientsocket
		self.adresse=adresse
		self.hostname=hostname
		self.mysql=mysql

	def run(self):
		#The Handler gets called with the clientsocket and the clientadresse
		HandleSocketConnections.Handler(self.clientsocket,self.adresse,self.hostname,self.mysql)
	
	@staticmethod
	def Handler(clientsocket,adresse,Hostname,mysql):
		Name=clientsocket.recv(1024)
		Pprint("Client %s connected to %s"%(str(Name),str(Hostname)))

		mysql.changeClientData(Name,1,adresse)

		while True:
				#The recived data gets stored in the varable data, the maximum lenth is 1024 characters
				daten = clientsocket.recv(1024)
				
				#If the Lenth is 1 or under, the client has disconnected
				#if (len(daten)>1 & self.CurrentStatus):
				if (len(daten)>1):
					data = daten.split(":")
					data.append(Name)
					
					mysql.add(data)
					'''
					if(data[0]=="T"):
						print("Temperatur")
						mysql.addHardwareData(Name,data[0],data[1])
					if(data[0]=="P"):
						print("CPU Auslastung")
						mysql.addHardwareData(Name,data[0],data[1])
					'''
					PrintHandler = Printhandler(("%s| %s"%(Name,daten)))
					PrintHandler.start()
				else:
					#If the < than 1, than the client has disconnected and we can close the thread
					#because ist is not longer needed
					#print("Client %s disconnected"%(str(adresse)))
					Pprint("Client %s disconnected"%(Name))
					mysql.changeClientData(Name,0,adresse)
					break


#------------------------------------------------------------------------------------
#Waits for incomming conenctions and than starts a new HandleSocketConnection-Thread
#------------------------------------------------------------------------------------
class WaitOnConnection(threading.Thread):
	def __init__(self,sock,hostname,mysql):
		threading.Thread.__init__(self)
		self.sock = sock
		self.hostname = hostname
		self.mysql=mysql

	def run(self):
		self.mysql.setClOffline()
		Pprint("Alle Clients offline gesetzt")
		WaitOnConnection.Handler(self.sock,self.hostname,self.mysql)

	@staticmethod
	def Handler(sock,hostname,mysql):
		while True:
				try:
					clientsocket,adresse = sock.accept()
				except KeyboardInterrupt:
					pass
				#If a client connects, an new thread gets created and the data is 
				#being processed in this new thread
				Clienthandler = HandleSocketConnections(clientsocket,adresse,str(hostname),mysql)
				Clienthandler.start()

class Printhandler(threading.Thread):
	def __init__(self,text):
		threading.Thread.__init__(self)
		self.text=text

	def run(self):
		file = open("ncc.log","a+") 
		ts = time.time()
		Date = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
		file.write("[%s] %s\n"%(Date,self.text))
		print("[%s] %s"%(Date,self.text))

def Pprint(text):
	file = open("ncc.log","a+") 
	ts = time.time()
	Date = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
	file.write("[%s] %s\n"%(Date,text))
	print("[%s] %s"%(Date,text))

