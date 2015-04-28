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
	
verilog_run: 
	@$(VC) -cc harness.v --exe sim_main.cpp -CFLAGS "$(CFLAGS)" -LDFLAGS "-I/include -L/lib -ldevbox_ioctl"
	@cd obj_dir && \
	make -s -j -f Vharness.mk;
	
