//arduinoRuntime.h
//Keith Lee
//Jan 20 ,2015

#include<fbDraw.h>
#include<fbBmp2fb.h>
#include"arduinoRuntime.h"
#define AIMAGE "./aimage.bmp"

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
	
	
	reset();
	fb_fillScr(&fb,255,255,255);
	fbBmp_draw(&fb, &bm, 1, OFFSET,OFFSET);
	updateState(&fb);
	
	
	setup();
	
	
	
	
	/*while(1)
	{
		loop();
		updateState(&fb);
	}*/
	
	fb_close(&fb);
	
	return 0;
}

void pinMode(int pin, int mode)
{
	
	if(pin>=13 || mode>OUTPUT)
		return;
	if(mode==OUTPUT)
		d_pin_mode[pin]=(mode<<8)|_n_output++;
	else
		d_pin_mode[pin]=(mode<<8)|_n_input++;
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
		if(d_pin[i])
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


void updateState(dev_fb* fb)
{
	
	drawPinState(fb);
	drawLedState(fb);
	
}

void reset()
{
	int i;
	for (i=0;i<32;i++)
	{
		if(i<4)
			key[i]=255;
		if(i<6)
		{
			a_pin[i]=255;
			seg7[i]=255;
		}
		if(i<10)
		{
			led[i]=255;
			sw[i]=255;
		}
		if(i<14)
		{
			d_pin[i]=255;
			d_pin_mode[i]=255;
		}
		gpio0[i]=255;
	}
	_n_input=0;
	_n_output=0;
}
