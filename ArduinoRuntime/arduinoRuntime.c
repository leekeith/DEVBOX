//arduinoRuntime.h
//Keith Lee
//Jan 20 ,2015

#include"arduinoRuntime.h"
#define AIMAGE "./aimage.bmp"


static int fd;


//  Arduino Runtime main loop

int main()
{
	dev_fb fb;
	bmp bm;
	
	
	
	if(fbBmp_openBmp(AIMAGE, &bm))
	{
		printf("\nArduinoRuntime error: Failed to initialize Arduino Image\n");
		return -1;
	}
	
	fb_init(&fb);
	
	
	//Begin mapping to hardware
		
	reset();
	fb_fillScr(&fb,255,255,255);
	fbBmp_draw(&fb, &bm, 1, OFFSET,OFFSET);
	fd=open_devbox_io()	;
 printf("%d\n",fd);
	updateState(&fb, fd);
	
	setup();
	updateState(&fb, fd);
	close_devbox_io(fd);
	
	//Call user's loop function
	while(1)
	{
		loop();
		if((fd=open_devbox_io())==-1)
		{
			printf("ERROR: Could not open io\n");
			return -1;
		}
		updateState(&fb, fd);
		close_devbox_io(fd);
	}
 
 //Clean up
	
	fb_close(&fb);

	
	return 0;
}

void pinMode(int pin, int mode)
{
	
	if(pin>13)
		return;
	if(mode==OUTPUT)
	{
		d_pin_mode[pin]=(mode<<8)|_n_output;
		if(_n_output<10)
			led[_n_output]=d_pin[pin];
		_n_output++;
	}
	else
	{
		d_pin_mode[pin]=(mode<<8)|_n_input;
		if(_n_input<4)
			d_pin[pin]=key[_n_input];
		else
			d_pin[pin]=sw[_n_input-4];
		_n_input++;
	}
	//printf("o: %d\ni: %d\n\n",_n_output, _n_input);
}

void drawPinState(dev_fb* fb)
{
	pixel px={PIN13_8_STARTX+OFFSET,PIN_STARTY+OFFSET};
	unsigned char color[3];
	int i;
	//Set D_PINS
	color[1]=color[2]=0;
	for(i=13;i>=0;i--)
	{
		//printf("%d: %d\n", i, d_pin[i]);
		if(d_pin[i]!=0)
			color[0]=255;
		else
			color[0]=0;
		
		fb_fillBox(fb, px, PIN_DIMX, PIN_DIMY, color[0], color[1], color[2]);
		
		if(i == 8)
		{
			px.x=PIN7_0_STARTX+OFFSET;
			px.y=PIN_STARTY+OFFSET;
		}
		else px.x+=PIN_GAP+PIN_DIMX;
	}
	
	//Set A_PINS
	px.x=A_PINS_STARTX+OFFSET;
	px.y=A_PINS_STARTY+OFFSET;
	color[1]=color[2]=0;
	for(i=5;i>=0;i--)
	{
		color[0]=a_pin[i];
		fb_fillBox(fb, px, PIN_DIMX, PIN_DIMY, color[0], color[1], color[2]);
		px.x+=PIN_GAP+PIN_DIMX;
	}
}

void drawLedState(dev_fb* fb)
{
	int i;
	pixel px={LEDR_STARTX+OFFSET, LEDR_STARTY+OFFSET};
	unsigned char color[3];
	color[1]=color[2]=0;
	for(i=9;i>=0;i--)
	{
		if(led[i])
			color[0]=255;	
		else
			color[0]=0;
			
		fb_fillBox(fb, px, LEDR_DIMX, LEDR_DIMY, color[0], color[1], color[2]);
		px.x+=LEDR_GAP;
	}
}

void drawKeyState(dev_fb* fb)
{
	int i;
	pixel px={KEY_STARTX+OFFSET, KEY_STARTY+OFFSET};
	unsigned char color[3];
	color[0]=color[1]=color[2]=0;
	for(i=3;i>=0;i--)
	{
		if(key[i])
			color[0]=255;
		else
			color[0]=0;
		
		fb_fillBox(fb, px, KEY_DIMX, KEY_DIMY, color[0], color[1], color[2]);
		px.x+=KEY_GAP;
	}
	
}

void drawSwState(dev_fb* fb)
{
	int i;
	pixel px={SW_STARTX+OFFSET, SW_STARTY+OFFSET};
	unsigned char top_color[3], bottom_color[3];
	top_color[1]=top_color[2]=bottom_color[1]=bottom_color[2]=0;
	for(i=9;i>=0;i--)
	{
		if(sw[i])
		{
			top_color[0]=255;
			bottom_color[0]=0;	
		}
		else
		{
			top_color[0]=0;
			bottom_color[0]=255;
		}	
		fb_fillBox(fb, px, SW_DIMX, SW_DIMY/2, top_color[0], top_color[1], top_color[2]);
		px.y+=(SW_DIMY/2)+1;
		fb_fillBox(fb, px, SW_DIMX, SW_DIMY/2, bottom_color[0], bottom_color[1], bottom_color[2]);
		px.y=SW_STARTY+OFFSET;
		px.x+=SW_GAP;
	}	
}

void getIo(int fd)
{
	int i;
  unsigned short input_val=devbox_get_sw(fd) & 0x3ff;
  
	for(i=0;i<10;i++)
	{
		sw[i]=input_val&1;
		input_val=input_val>>1;
	}
	input_val=~(devbox_get_key(fd) & 0xf);
	for(i=0;i<4;i++)
	{
		key[i]=input_val&1;
		input_val=input_val>>1;
	}
	
}

void setIo(int fd)
{
	int i;
	unsigned int output_val=0;
	unsigned char bit_val;
	for(i=0;i<10;i++)
	{
		bit_val=(led[i]>0)?1:0;
		output_val=output_val|(bit_val<<i);
	}
	devbox_set_led(fd,output_val);

}

void setPins()
{
	int i;
	int n_input;
	for(i=0;i<14;i++)
	{
		if(d_pin_mode[i]>>8==INPUT)
		{
			n_input=d_pin_mode[i]&0xff;
			if(n_input<4)
				d_pin[i]=key[n_input];
			else
				d_pin[i]=sw[n_input-4];
		}
	}
}

void getPins()
{
	int i;
	int n_output;
	for(i=0;i<14;i++)
	{
		if(d_pin_mode[i]>>8==OUTPUT)
		{
			n_output=d_pin_mode[i]&0xff;
			if(n_output<10)
				led[n_output]=d_pin[i];
		}
		
	}
}


void updateState(dev_fb* fb, int fd)
{
	getIo(fd);
	setPins();
	setIo(fd);
	getPins();
	drawPinState(fb);
	drawLedState(fb);
	drawKeyState(fb);
	drawSwState(fb);
	
}

void reset()
{
	int i;
	for (i=0;i<32;i++)
	{
		if(i<4)
			key[i]=0;
		if(i<6)
		{
			a_pin[i]=0;
			seg7[i]=0;
		}
		if(i<10)
		{
			led[i]=0;
			sw[i]=0;
		}
		if(i<14)
		{
			d_pin[i]=0;
			d_pin_mode[i]=0;
		}
		gpio0[i]=0;
	}
	_n_input=0;
	_n_output=0;
}

















void digitalWrite(int pin, int value)
{
	if(pin<14)
		d_pin[pin]=value;
}

int digitalRead(int pin)
{
	return d_pin[pin];
}

void randomSeed(long seed)
{
	srand(seed);
}

int a_random(int max)
{
	return (rand()%max);
}

char lowByte(short x)
{
	return x&0xff;
}

char highByte(short x)
{
	return (x&0xff00)>>8;
}

char bitRead(int x, char n)
{
	int bitmask=1<<n;
	if(x&bitmask)
		return 1;
	else return 0;
}



void delay(int ms)
{
	usleep(ms<<3);
}

void delayMicroseconds(int us)
{
	usleep(us);
}
