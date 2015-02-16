CC= gcc
CFLAGS= -Wall
#SOURCES= ./fbBmp2fb.c ./fbBmp2fb.h ./fbDraw.c ./fbDraw.h
#CFILES= ./fbBmp2fb.c ./fbDraw.c
OBJS= fbDraw.o fbBmp2fb.o
LIBPATH=/lib
INCPATH=/usr/include

all:$(OBJS)
	ar rcs libfbDraw.a $(OBJS)
 
fbDraw.o: fbDraw.c fbDraw.h
 
fbBmp2fb.o: fbBmp2fb.c fbBmp2fb.h fbDraw.o

clean:
	rm $(OBJS) libfbDraw.a
	
install: all
	cp libfbDraw.a $(LIBPATH)
	cp *.h $(INCPATH)
 

