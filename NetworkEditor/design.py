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
        MainWindow.resize(288, 212)
        MainWindow.setAutoFillBackground(False)
        self.centralwidget = QtGui.QWidget(MainWindow)
        self.centralwidget.setObjectName(_fromUtf8("centralwidget"))
        self._btnCreate = QtGui.QPushButton(self.centralwidget)
        self._btnCreate.setGeometry(QtCore.QRect(20, 150, 231, 27))
        self._btnCreate.setObjectName(_fromUtf8("_btnCreate"))
        self._gbZeichen = QtGui.QGroupBox(self.centralwidget)
        self._gbZeichen.setGeometry(QtCore.QRect(10, 10, 261, 131))
        font = QtGui.QFont()
        font.setStrikeOut(False)
        self._gbZeichen.setFont(font)
        self._gbZeichen.setAutoFillBackground(True)
        self._gbZeichen.setObjectName(_fromUtf8("_gbZeichen"))
        self._lblFarbraum = QtGui.QLabel(self._gbZeichen)
        self._lblFarbraum.setGeometry(QtCore.QRect(10, 30, 71, 17))
        self._lblFarbraum.setObjectName(_fromUtf8("_lblFarbraum"))
        self._cbFarbraum = QtGui.QComboBox(self._gbZeichen)
        self._cbFarbraum.setGeometry(QtCore.QRect(80, 30, 141, 27))
        self._cbFarbraum.setObjectName(_fromUtf8("_cbFarbraum"))
        self._lblDiemnsionen = QtGui.QLabel(self._gbZeichen)
        self._lblDiemnsionen.setGeometry(QtCore.QRect(10, 70, 91, 17))
        self._lblDiemnsionen.setObjectName(_fromUtf8("_lblDiemnsionen"))
        self._spDimensionen = QtGui.QSpinBox(self._gbZeichen)
        self._spDimensionen.setGeometry(QtCore.QRect(110, 70, 61, 21))
        self._spDimensionen.setObjectName(_fromUtf8("_spDimensionen"))
        self.label = QtGui.QLabel(self._gbZeichen)
        self.label.setGeometry(QtCore.QRect(10, 110, 68, 17))
        self.label.setObjectName(_fromUtf8("label"))
        self._cbSettings = QtGui.QComboBox(self._gbZeichen)
        self._cbSettings.setGeometry(QtCore.QRect(70, 100, 101, 27))
        self._cbSettings.setObjectName(_fromUtf8("_cbSettings"))
        self._pbLoad = QtGui.QPushButton(self._gbZeichen)
        self._pbLoad.setGeometry(QtCore.QRect(170, 100, 81, 27))
        self._pbLoad.setObjectName(_fromUtf8("_pbLoad"))
        self._btnSave = QtGui.QPushButton(self.centralwidget)
        self._btnSave.setGeometry(QtCore.QRect(20, 180, 231, 27))
        self._btnSave.setObjectName(_fromUtf8("_btnSave"))
        MainWindow.setCentralWidget(self.centralwidget)

        self.retranslateUi(MainWindow)
        QtCore.QMetaObject.connectSlotsByName(MainWindow)

    def retranslateUi(self, MainWindow):
        MainWindow.setWindowTitle(_translate("MainWindow", "Designer", None))
        self._btnCreate.setText(_translate("MainWindow", "Create", None))
        self._gbZeichen.setTitle(_translate("MainWindow", "Zeichenparameter", None))
        self._lblFarbraum.setText(_translate("MainWindow", "Farbraum", None))
        self._lblDiemnsionen.setText(_translate("MainWindow", "Dimensionen", None))
        self.label.setText(_translate("MainWindow", "Settings", None))
        self._pbLoad.setText(_translate("MainWindow", "Load", None))
        self._btnSave.setText(_translate("MainWindow", "Save", None))

