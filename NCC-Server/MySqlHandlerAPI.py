#MPythonimports
import mysql.connector as mysqlconnector
import json
import sys
import threading
import time


class MySqlobj(threading.Thread):
	def __init__(self,Host,Username,Password,Database,maxDump):
		threading.Thread.__init__(self)
		self.Password=Password
		self.Username=Username
		self.Host=Host
		self.Database=Database
		self.mysqlobj=None
		self.clientdata=None
		self.maxDump=int(maxDump)

		self.Queue=[]
		self.running=None

	def connect(self):
		try:
			self.mysqlobj = mysqlconnector.connect(user=self.Username,password=self.Password,host=self.Host,database=self.Database)
		except:
			print("Mysql Objekt nicht erstellt ...")

	def addHardwareData(self,node,identifier,data):
		self.checkSpaceHD(node,identifier)
		querry="insert into HardwareData (Node,time_stamp,identifier,data) values(\"%s\",\"%s\",\"%s\",\"%s\")"%(str(node),str(time.time()),str(identifier),str(data))
		cur=self.mysqlobj.cursor(buffered=True)
		cur.execute(querry)
		self.mysqlobj.commit()

	def checkSpaceHD(self,nodename,identifier):
		querry="select * from HardwareData where Node=\"%s\" and identifier=\"%s\""%(nodename,identifier)
		cur = self.mysqlobj.cursor(buffered=True)
		cur.execute(querry)
		data=cur.fetchall()
		print(len(data))
		if(len(data)>self.maxDump):
			print(data[0])
			querry = "delete from HardwareData where id=%s"%data[0][0]
			cur.execute(querry)
			self.mysqlobj.commit()

	def changeClientData(self,NodeName,state,IPAdresse):
		querry="Select * from ClientData where node = \"%s\""%(NodeName)
		cur=self.mysqlobj.cursor(buffered=True)
		cur.execute(querry)
		data=cur.fetchall()
		if(len(data)>0):
			#print(data[0][0])
			print("Node in DB")
			querry="update ClientData set time_stamp=\"%s\"where id=%s"%(time.time(),data[0][0])
			cur.execute(querry)
			querry="update ClientData set status=\"%s\"where id=%s"%(str(state),data[0][0])
			cur.execute(querry)
			querry="update ClientData set IPaddress=\"%s\"where id=%s"%(str(IPAdresse),data[0][0])
			cur.execute(querry)
			self.mysqlobj.commit()
		else:
			querry="select * from ClientData"
			cur.execute(querry)
			data=cur.fetchall()
			print(data[len(data)-1][0])
			print("Node not in DB")
			querry="insert into ClientData (id,node,time_stamp,status,IPaddress) values (\"%s\",\"%s\",\"%s\",\"%s\",\"%s\")"%((data[len(data)-1][0]+1),NodeName,time.time(),state,IPAdresse)
			cur.execute(querry)
			self.mysqlobj.commit()

	def printHardwareData(self):
		querry="select * from HardwareData"
		cur = self.mysqlobj.cursor(buffered=True)
		cur.execute(querry)
		data=cur.fetchall()
		print(data)

	def setClOffline(self):
		querry = "select * from ClientData"
		cur = self.mysqlobj.cursor(buffered=True)
		cur.execute(querry)
		data=cur.fetchall()
		self.clientdata=data
		print data
		for x in data:
			querry="update ClientData set time_stamp=\"%s\"where id=%s"%(time.time(),x[0])
			cur.execute(querry)
			querry="update ClientData set status=\"%s\"where id=%s"%("0",x[0])
			cur.execute(querry)
			querry="update ClientData set IPaddress=\"%s\"where id=%s"%("---",x[0])
			cur.execute(querry)
		self.mysqlobj.commit()

	def add(self,data):
		self.Queue.append(data)
	
	def printQueue(self):
		print("Queue: %s"%str(self.Queue))

	def stop(self):
		self.running=False

	def run(self):
		self.running=True
		self.work()

	def work(self):
		while(self.running):
			if(len(self.Queue)>0):
				temp = self.Queue[0]
				print temp
				self.addHardwareData(str(temp[2]),str(temp[0]),str(temp[1]))
				self.Queue.remove(self.Queue[0])
				


def getKonfig(Filename="MySql.json"):
		file = open(Filename).read()
		data = json.loads(file)

		return data

if __name__ == "__main__":
	data=getKonfig()
	obj=MySqlobj(data["Database"]["Host"],data["Database"]["Username"],data["Database"]["Password"],"ncctest",data["HardwareData"]["maxDump"])
	
	obj.add(["T","12","NC1"])
	obj.add(["T","13","NC1"])
	obj.add(["T","14","NC1"])
	obj.add(["T","15","NC1"])
	obj.add(["T","16","NC1"])

	#obj.start()

	
	obj.connect()
	obj.start()
	#obj.changeClientData("ClusterMaster",1,"192.167.1.2")