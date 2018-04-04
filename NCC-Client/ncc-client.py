#!/usr/bin/env python
# coding: utf8

#*********************************************
#Imports von bereits vorhandenen Bibliotheken
#*********************************************
import socket
import threading
import os
import json
import time
import sys

#*********************************************
#Imports von nachinstallierten Bibliotheken
#********************************************
import psutil


#************************************************************************************************
#				Hierbei handelt es sich um die Basisklasse, von der andere FUnktionen erben
# 				Sie ist hier, damit man einfacher Threads erstellen kann
#				Variablenmanagement wird auch hier gemanaged
#************************************************************************************************
class Timer(threading.Thread):
	def __init__(self,Name,Time,socketObjekt):
		threading.Thread.__init__(self)
		self._stop_event = threading.Event()
		self.Name = Name
		self.Time = Time
		self.running=True
		self.Socket=socketObjekt

	def run(self):
		Timer.onTimer(self)

	def stop(self):
		self.running=False

	def onTimer(self):
		while(True):
			try:
				print(self.Name)
				time.sleep(self.Time)
			except KeyboardInterrupt:
				return



#************************************************************************************************
#				Erbt von der Timerklasse und wird dazu benutzt, um nach der in der Konfig
#				speizifizierten Zeit die aktuelle Coretemperatur zu senden
#************************************************************************************************
class Temperatur(Timer):
	def __init__(self,Name,Time,socketObjekt):
		Timer.__init__(self,Name,Time,socketObjekt)

	def run(self):
		print("Temperatur Started")
		Temperatur.onTemperatur(self)


	def onTemperatur(self):
		countup=self.Time
		while(self.running):
			if(countup==self.Time):
				with open('/sys/class/thermal/thermal_zone0/temp') as temp:
					curCtemp = float(temp.read()) / 1000
					u = str(curCtemp)
				self.Socket.onWrite("T:%s"%(str(curCtemp)))
				countup=0
			countup+=1
			time.sleep(1)
		print("Temperatur stopped")

#************************************************************************************************
#				Erbt von der Timerklasse und wird dazu benutzt, um nach der in der Konfig
#				speizifizierten Zeit die aktuelle CPU-AUslastung zu senden
#************************************************************************************************
class Cpu(Timer):
	def __init__(self,Name,Time,socketObjekt):
		Timer.__init__(self,Name,Time,socketObjekt)

	def run(self):
		print("CPU Started")
		Cpu.onCPU(self)

	def onCPU(self):
		countup=self.Time
		while(self.running):
			if(countup==self.Time):
				self.Socket.onWrite("P:%s"%(str(psutil.cpu_percent(interval=1))))
				countup=0
			countup+=1
			time.sleep(1)
		print("CPU stopped")

#************************************************************************************************
#				Die Socketklasse erstellt den Socket und stellt eine Schreibschnittstelle
#				zur Verfügung.
#				Über diese Schnittstelle wird eine Verbindung zum Server aufgebaut, sollte 
#				dieser nicht verfügabr sein, dann wird nach einer in der Konfig 
#				spezifizierten Zeit erneut eversucht eine Verbindung aufzubauen.
#************************************************************************************************
class Socket():
	def __init__(self,adresse,port,timeout):
		self.adresse=adresse
		self.timeout=timeout
		self.port=port
		self.lock=threading.Lock()
	
	def onConnect(self):
		sock=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
		connecting = True

		#Funktioniert aus irgend einem Grund noch nicht, muss noch behoben werden
		server_adress=(str(self.adresse),int(self.port))

		#server_adress=(('127.0.0.1',111))
		#server_adress=(('195.191.252.239',111))

		while(connecting):
			try:
				print("Connecting to %s"%(str(server_adress)))
				sock.connect(server_adress)
				connecting=False
			except KeyboardInterrupt:
				return
			except:
				print("Connection failed, reconnecting in %s sekunden"%(str(self.timeout)))
				time.sleep(float(self.timeout))

		self.socket = sock
		return  self.socket

	def onWrite(self,message):
		self.lock.acquire()
		sys.stdout.writelines("%s\n"%message)
		self.socket.send(message)
		self.lock.release()

#************************************************************************************************
#				Konfigklasse, liest die Konfig ein
#************************************************************************************************
class Konfig():

	@staticmethod
	def readKonfig(filename="NCC.json"):
		KonfigFile = open(filename,'r')
		JsonString = KonfigFile.read()

		inhalt = json.loads(JsonString)
		return inhalt


if __name__ == "__main__":

	konf = Konfig.readKonfig()
	
	socket1 = Socket(str(konf['NCC']['Adresse']),int(konf['NCC']['Port']),int(konf['NCC']['Timeout']))
	socket1.onConnect()

	socket1.onWrite(konf['Client']['Hostname'])	
#	time.sleep(1)
#	socket1.onWrite(os.uname()[4])
#	time.sleep(1)
#	socket1.onWrite("%s Cors"%(psutil.cpu_count(logical=False)))
	time.sleep(7)

	T1=Temperatur("Timer1",float(konf['Temperatur']['TimeDelta']),socket1)
	T1.start()

	C1 = Cpu("CPU1",float(konf['CPU']['TimeDelta']),socket1)
	C1.start()

	running=True
	while(running):
		try:
			pass
		except KeyboardInterrupt:
			running=False
			T1.stop()
			C1.stop()
				
	
