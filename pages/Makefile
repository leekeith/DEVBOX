#! /bin/bash

CC=gcc
CXX=g++
VC=verilator
CFLAGS=-Wall -O2 -I/include -L/lib -lfbDraw -ldevbox_ioctl
VFLAGS=-Wall -O2 --cc
c:
	@$(CC)  main.c $(CFLAGS) -o main 
	
arduino:
	@$(CC) main.c arduinoRuntime/arduinoRuntime.o $(CFLAGS) -o main 
	
verilog:
	@$(VC) $(VFLAGS) main.v --exe sim_main.cpp
	@cd obj_dir
	@make -j -f main.mk main
	
