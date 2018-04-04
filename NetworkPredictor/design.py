# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'design.ui'
#
# Created by: PyQt4 UI code generator 4.11.4
#
# WARNING! All changes made in this file will be lost!

from PyQt4 import QtCore, QtGui

try:
    _fromUtf8 = QtCore.QString.fromUtf8
except AttributeError:
    def _fromUtf8(s):
        return s

try:
    _encoding = QtGui.QApplication.UnicodeUTF8
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig, _encoding)
except AttributeError:
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig)

class Ui_MainWindow(object):
    def setupUi(self, MainWindow):
        MainWindow.setObjectName(_fromUtf8("MainWindow"))
        MainWindow.resize(182, 169)
        self.centralwidget = QtGui.QWidget(MainWindow)
        self.centralwidget.setObjectName(_fromUtf8("centralwidget"))
        self.btn_predict = QtGui.QPushButton(self.centralwidget)
        self.btn_predict.setGeometry(QtCore.QRect(20, 100, 131, 27))
        self.btn_predict.setObjectName(_fromUtf8("btn_predict"))
        self.lbl_answer = QtGui.QLabel(self.centralwidget)
        self.lbl_answer.setGeometry(QtCore.QRect(10, 130, 161, 20))
        self.lbl_answer.setText(_fromUtf8(""))
        self.lbl_answer.setObjectName(_fromUtf8("lbl_answer"))
        self.listView = QtGui.QListView(self.centralwidget)
        self.listView.setGeometry(QtCore.QRect(10, 10, 151, 81))
        self.listView.setObjectName(_fromUtf8("listView"))
        MainWindow.setCentralWidget(self.centralwidget)

        self.retranslateUi(MainWindow)
        QtCore.QMetaObject.connectSlotsByName(MainWindow)

    def retranslateUi(self, MainWindow):
        MainWindow.setWindowTitle(_translate("MainWindow", "NetworkPredictor", None))
        self.btn_predict.setText(_translate("MainWindow", "new Prediction", None))

