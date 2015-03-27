#! /bin/bash

obj-m +=devbox_ioctl.o

EXEC_DIR= /lib/modules/3.12.0-00307-g507abb4-dirty/build
ARM_CC= /usr/bin/arm-linux-gnueabihf-gcc
INCLUDE_DIRS= -I./ -I$(EXEC_DIR)/include
PREFIX?= ./install

package: all
	mkdir -p $(PREFIX)/usr/include
	mkdir -p $(PREFIX)/lib/modules/3.12.0
	cp *.h $(PREFIX)/usr/include
	cp libdevbox_ioctl.so $(PREFIX)/lib
	tar zcvf devbox_ioctl.tar.gz $(PREFIX)/*
	

all: module libdevbox_ioctl.so

libdevbox_ioctl.so: devbox_ioctl_driver.o
	$(ARM_CC) -shared -Wall -o libdevbox_ioctl.so devbox_ioctl_driver.o

devbox_ioctl_driver.o:
	$(ARM_CC) -c -fPIC devbox_ioctl_driver.c -Wall $(INCLUDE_DIRS)

module:
	make -C $(EXEC_DIR) M=$(PWD) modules

clean:
	make -C $(EXEC_DIR) M=$(PWD) clean
	rm libdevbox_ioctl.so
	rm devbox_ioctl.tar.gz
	rm -r $(PREFIX)
