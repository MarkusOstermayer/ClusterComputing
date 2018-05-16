from PyQt4 import QtGui # Import the PyQt4 module we'll need
from PyQt4.QtCore import *
from PyQt4.QtGui import *
import sys # We need sys so that we can pass argv to QApplication

import keras
from keras.datasets import mnist
from keras.datasets import fashion_mnist
from keras.models import model_from_json

import pygame
from pygame.locals import *
from OpenGL.GL import *
from OpenGL.GLU import *
import time
import numpy
from pygame import USEREVENT
from PIL import Image
from PIL import ImageOps
import design # This file holds our MainWindow and all design related things
			  # it also keeps events etc that we defined in Qt Designer

ScreenWith=400
ScreenHight=400

class ExampleApp(QtGui.QMainWindow, design.Ui_MainWindow):
	def __init__(self):
		super(self.__class__, self).__init__()

		self.array=None
		self.setupUi(self)
		self._cbFarbraum.addItem("Schwarz-Weis")
		self._cbFarbraum.addItem("RGB")
		self._spDimensionen.setValue(28)
		self._btnCreate.clicked.connect(self.onCreateKlicked)

		self.isClicked=False

		self.oldMouse=None

	def onCreateKlicked(self):
		#print self._cbFarbraum.currentIndex()
		#print self._spDimensionen.value()

		pygame.init()
		display = (ScreenWith, ScreenHight)
		self.screen=pygame.display.set_mode(display)
		pygame.display.set_caption("NeuralNetwork Editor")
		
		running=False
		self.array=numpy.array([[0 for x in range(self._spDimensionen.value())] for y in range(self._spDimensionen.value())])
		for i in range(self._spDimensionen.value()):
			for j in range(self._spDimensionen.value()):
				self.array[i][j]=0
		self.oldMouse=pygame.mouse.get_pos()

		seperatorH=int(ScreenHight/self._spDimensionen.value())
		seperatorW=int(ScreenWith/self._spDimensionen.value())

		while not running:
			for event in pygame.event.get():
				if event.type == pygame.QUIT:
					running=True

				if event.type == MOUSEMOTION:
					if(self.isClicked):

						try:
							if((pygame.mouse.get_pos()[0]>self.oldMouse[0]+seperatorW or pygame.mouse.get_pos()[0]<self.oldMouse[0]-seperatorW) or
								(pygame.mouse.get_pos()[1]>self.oldMouse[1]+seperatorH or pygame.mouse.get_pos()[1]<self.oldMouse[1]-seperatorH)):

								if(self.array[pygame.mouse.get_pos()[0]/seperatorH][pygame.mouse.get_pos()[1]/seperatorW]==0):
									self.array[pygame.mouse.get_pos()[0]/seperatorH][pygame.mouse.get_pos()[1]/seperatorW]=255

								self.oldMouse=pygame.mouse.get_pos()
						except:
							pass
				if event.type==MOUSEBUTTONDOWN:
					self.isClicked= not self.isClicked
					if(self.isClicked==False):
						arr=numpy.rot90(self.array, 3)
						arr=numpy.fliplr(arr)

						self.Network(arr)
					#print(self.isClicked)



			self.Grid(self._spDimensionen.value())
			pygame.display.flip()
			#pygame.time.wait(1)

		#print("Ende")


		pygame.quit()

		#quit()

		'''
		msgBox = QtGui.QMessageBox( self )
		msgBox.setIcon( QtGui.QMessageBox.Question )
		msgBox.setText("Save Settings?")
		msgBox.addButton( QtGui.QMessageBox.Yes)
		msgBox.addButton( QtGui.QMessageBox.No)
		ret = msgBox.exec_()

		if ret == QtGui.QMessageBox.Yes:
			print( "Yes" )
		else:
			print( "No" )
		'''
	def Network(self,daten):
		img_width, img_height = 28, 28

		model = self.LoadNetwork("Netzwerke/MNIST/")
		aw=["0","1","2","3","4","5","6","7","8","9",]

		#arr = numpy.array(x_test[num]).reshape((img_width,img_height,1))
		ar = numpy.array(daten.reshape((img_width,img_height,1)))


		arr = numpy.expand_dims(ar, axis=0)
		prediction = model.predict(arr)

		answer="Na"

		for x in range(10):
			if(prediction[0][x]==1):
				answer=prediction[0][x]*x
				#self.lbl_answer.setText("%s|%s"%(str(aw[int(answer)]),str(aw[y_test[num]])))
				print("Ergebniss: %s"%(str(aw[int(answer)])))

				msg = QMessageBox()
				msg.setIcon(QMessageBox.Information)

				msg.setText("Ergebnis")
				msg.setInformativeText("%s"%(str(aw[int(answer)])))
				msg.setWindowTitle("NeuralNetwork")

   				msg.exec_()
		if(answer=="Na"):
			msg = QMessageBox()
			msg.setIcon(QMessageBox.Information)

			msg.setText("Ergebnis")
			msg.setInformativeText("Konnte nicht bestimmt werden")
			msg.setWindowTitle("NeuralNetwork")
			msg.exec_()


		self.array=numpy.array([[0 for x in range(self._spDimensionen.value())] for y in range(self._spDimensionen.value())])

		QtGui.QApplication.processEvents()


	def Grid(self,number):
		seperatorH=int(ScreenHight/number)
		seperatorW=int(ScreenWith/number)

		for x in range(1,number):
			pygame.draw.line(self.screen,(255,255,255),(seperatorH*x,0),(seperatorH*x,ScreenHight-1))
			pygame.draw.line(self.screen,(255,255,255),(0,seperatorW*x),(ScreenWith-1,seperatorW*x))

		for i in range(number):
			for j in range(number):
				if(self.array[i][j]!=0):
					pygame.draw.rect(self.screen,(255,255,255),pygame.Rect(i*seperatorW+1,j*seperatorH+1,seperatorW-1,seperatorH-1))
				else:
					pygame.draw.rect(self.screen,(0,0,0),pygame.Rect(i*seperatorW+1,j*seperatorH+1,seperatorW-1,seperatorH-1))


	def LoadNetwork(self,path):
		json_file = open(path+"network.json","r")
		load_network_json=json_file.read()
		json_file.close()
		LoadNetwork=model_from_json(load_network_json)

		LoadNetwork.load_weights(path+"network.h5","r")
		#print("Network laoded")
		return LoadNetwork

def main():
	app = QtGui.QApplication(sys.argv)  # A new instance of QApplication
	form = ExampleApp()                 # We set the form to be our ExampleApp (design)
	form.setFixedSize(form.size())  #Verhindert das resizen
	form.show()                         # Show the form
	app.exec_()                         # and execute the app






if __name__ == '__main__':              # if we're running file directly and not importing it
	main()                              # run the main function
