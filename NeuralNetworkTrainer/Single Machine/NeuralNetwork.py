from __future__ import print_function
import keras
from keras.datasets import mnist
from keras.models import Sequential
from keras.layers import Dense, Dropout, Flatten
from keras.layers import Conv2D, MaxPooling2D
from keras.models import model_from_json
from keras import backend as K
import os
import numpy
import sys
import tensorflow as tf
from keras.models import model_from_json
from keras.datasets import cifar100




sys.setrecursionlimit(30000)



def BuildNetwork():

	batch_size = 128
	num_classes = 10
	epochs = 1

	# input image dimensions
	img_rows, img_cols = 28, 28

	# the data, shuffled and split between train and test sets
	(x_train, y_train), (x_test, y_test) = mnist.load_data()


	if K.image_data_format() == 'channels_first':
		x_train = x_train.reshape(x_train.shape[0], 1, img_rows, img_cols)
		x_test = x_test.reshape(x_test.shape[0], 1, img_rows, img_cols)
		input_shape = (1, img_rows, img_cols)
	else:
		x_train = x_train.reshape(x_train.shape[0], img_rows, img_cols, 1)
		x_test = x_test.reshape(x_test.shape[0], img_rows, img_cols, 1)
		input_shape = (img_rows, img_cols, 1)

	x_train = x_train.astype('float32')
	x_test = x_test.astype('float32')
	x_train /= 255
	x_test /= 255
	print('x_train shape:', x_train.shape)
	print(x_train.shape[0], 'train samples')
	print(x_test.shape[0], 'test samples')

	y_train = keras.utils.to_categorical(y_train, num_classes)
	y_test = keras.utils.to_categorical(y_test, num_classes)

	model = Sequential()
	model.add(Conv2D(32, kernel_size=(3, 3),activation='relu',input_shape=input_shape))
	model.add(Conv2D(64, (3, 3), activation='relu'))
	model.add(MaxPooling2D(pool_size=(2, 2)))
	model.add(Dropout(0.25))
	model.add(Flatten())
	model.add(Dense(128, activation='relu'))
	model.add(Dropout(0.5))
	model.add(Dense(num_classes, activation='softmax'))

	model.compile(loss=keras.losses.categorical_crossentropy,
				  optimizer=keras.optimizers.Adadelta(),
				  metrics=['accuracy'])

	return (model,x_test,y_test,x_train,y_train)

def saveNetwork(network):
	network_json = network.to_json()

	with open("network_fashion.json","w") as json_file:
		json_file.write(network_json)
	network.save_weights("network_fashion.h5")
	print("Network saved")

def LoadNetwork():
	json_file = open("Saves/network.json","r")
	load_network_json=json_file.read()
	json_file.close()
	LoadNetwork=model_from_json(load_network_json)

	LoadNetwork.load_weights("Saves/network.h5")
	print("Network laoded")
	return LoadNetwork

def main():

	batch_size = 128
	num_classes = 10
	epochs = 1




	netzwerk,TestX,TestY,TrainX,TrainY = BuildNetwork()
	per=0
	while(per<10):
		netzwerk.fit(TrainX, TrainY,
											batch_size=batch_size,
											epochs=epochs,
											verbose=1,
											validation_data=(TestX, TestY))


		#netzwerk.evaluate(TestX, TestY, verbose=1)

		score = netzwerk.evaluate(x=TestX,y=TestY, verbose=1)
		print("Node %s Network loss score %s"%(str(1),str(score[0])))
		print("Node %s Network accuracy score %s"%(str(1),str(score[1]*100)))
		per+=1

		saveNetwork(netzwerk)


if __name__=="__main__":
	main()
