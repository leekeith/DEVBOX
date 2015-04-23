#! /bin/bash
CFLAGS=-Wall -I/include -L/lib -lfbDraw -ldevbox_ioctl
all: iotest

iotest: arduinoRuntime.o
	gcc arduinoIotest.c arduinoRuntime.o $(CFLAGS) -o iotest
	
arduinoRuntime.o:
	gcc -c arduinoRuntime.c $(CFLAGS)