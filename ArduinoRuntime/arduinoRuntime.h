//arduinoRuntime.h
//Keith Lee

#ifndef __ARDUINORUNTIME_H__
#define __ARDUINORUNTIME_H__

#include<fbDraw.h>
#include<fbBmp2fb.h>
#include<devbox_ioctl_driver.h>
#include<time.h>
#include<stdlib.h>

#define INPUT 0
#define INPUT_PULLUP 1
#define OUTPUT 2

#define HIGH 0xFF
#define LOW 0

#define LED_BUILTIN 13



//Arduino Shield image map constants
#define OFFSET					20

#define PIN_DIMX 				7
#define PIN_DIMY 				7
#define PIN_GAP 				6
#define PIN_STARTY 			10
#define PIN_COUNT				14

#define PIN13_8_STARTX 	186

#define PIN7_0_STARTX		274

#define A_PINS_STARTX		300
#define A_PINS_STARTY		261
#define APIN_COUNT			6

#define LEDR_STARTX			18
#define LEDR_STARTY			306
#define LEDR_DIMX				14
#define LEDR_DIMY				10
#define LEDR_GAP				53
#define LEDR_COUNT			10

#define KEY_STARTX			560
#define KEY_STARTY			322
#define KEY_DIMX				32
#define KEY_DIMY				32
#define KEY_GAP				93

#define SW_STARTX				19
#define SW_STARTY				363
#define SW_DIMX				13
#define SW_DIMY				34
#define SW_GAP					53

//Runtime Globals
static int _n_input;
static int _n_output;

//IO Globals
static unsigned char d_pin[14];
static unsigned short d_pin_mode[14];
static unsigned char a_pin[6];
static unsigned char ledr[10];
static unsigned char switches[10];
static unsigned char keys[4];
static unsigned short seg7[6];
unsigned int gpio0[32];

//Arduino Functions
void setup();
void loop();
void reset();

void pinMode(int pin, int mode);


void digitalWrite(int pin, int value);
int digitalRead(int pin);

void randomSeed(long seed);
#define random(max) a_random(max)
int a_random(int max);

char lowByte(short x);
char highByte(short x);
char bitRead(int x, char n);
void bitWrite(int x, char n, char b);
void bitSet(int x, char n);
void bitClear(int x, char n);
int bit(char n);
int constrain(int x, int a, int b);
int map(int x, int fromLow, int fromHigh, int toLow, int toHigh);
void delay(int ms);
void delayMicroseconds(int us);


//Runtime Functions
void updateState(dev_fb* fb, int fd);




#endif
