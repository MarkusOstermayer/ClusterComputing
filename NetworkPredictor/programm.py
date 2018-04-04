from PyQt4 import QtGui # Import the PyQt4 module we'll need
from PyQt4.QtCore import *
from PyQt4.QtGui import *
import sys # We need sys so that we can pass argv to QApplication
from PIL import Image
from PIL import ImageOps
import time
import matplotlib.pyplot as plt
import matplotlib.image as mpimg
import keras
from keras.datasets import mnist
from keras.datasets import fashion_mnist
import random
import numpy
from PIL import Image
from keras.models import model_from_json
import design # This file holds our MainWindow and all design related things
			  # it also keeps events etc that we defined in Qt Designer

class ExampleApp(QtGui.QMainWindow, design.Ui_MainWindow):
	def __init__(self):
		super(self.__class__, self).__init__()
		self.setupUi(self)  
		self.btn_predict.clicked.connect(self.onBtnPredictClick)
		#self.fig=None

		model = QStandardItemModel()
		model.appendRow(QStandardItem("MNIST"))
		model.appendRow(QStandardItem("MNIST_fashion"))
		self.listView.setModel(model)

	def onbtn(self):
		print(self.listView.selectedIndexes()[0].row())

	def onBtnPredictClick(self):
		img_width, img_height = 28, 28

		num=random.randint(1, 10000)
		try:
			if(self.listView.selectedIndexes()[0].row()==0):

				model = LoadNetwork("Netzwerke/MNIST/")
				aw=["0","1","2","3","4","5","6","7","8","9",]
				(x_train, y_train), (x_test, y_test) = mnist.load_data()

			elif(self.listView.selectedIndexes()[0].row()==1):
				model = LoadNetwork("Netzwerke/MNIST_fashion/")
				aw=["T-shirt/top","Trouser","Pullover","Dress","Coat","Sandal","Shirt","Sneaker","Bag","Ankle boot"]
				(x_train, y_train), (x_test, y_test) = fashion_mnist.load_data()

			#else:
			#	self.lbl_answer.setText("Please select an Item")
			#	return
		except:
			self.lbl_answer.setText("Please select an Item")
			return

		arr = numpy.array(x_test[num]).reshape((img_width,img_height,1))
		arr = numpy.expand_dims(arr, axis=0)
		prediction = model.predict(arr)

		answer="Na"
		
		for x in range(10):
			if(prediction[0][x]==1):
				answer=prediction[0][x]*x
				self.lbl_answer.setText("%s|%s"%(str(aw[int(answer)]),str(aw[y_test[num]])))
		if(answer=="Na"):
			self.lbl_answer.setText(str(answer))

		QtGui.QApplication.processEvents()
		self.showIMG(x_test[num])

	def showIMG(self,x_test):
		plt.close('all')
		imgplot = plt.imshow(x_test)
		plt.show(block=False)
 
def LoadNetwork(path):
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
