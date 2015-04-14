//fbTest.c
//keith lee
//dev box

#include "fbDraw.h"

int main()
{	
	dev_fb fb;
	int x,y;
	printf("%d\n",fb_init(&fb));
	printf("ABS test:\nabs(-1)=%d\nabs(-24)=%d\nabs(5)=%d\n",abs(-1),abs(-24),abs(5));
	fb_fillScr(&fb,0,0,0);
	for(x=10;x<100;x++)
	{
		for(y=10;y<70;y++)
		{
			fb_drawPixel(&fb,x,y,255,0,0);
		}
	}
	pixel cursor=fb_toPixel(10,300);
	fb_drawBox(&fb,fb_toPixel(200,400),100,100,137,44,88);
	fb_fillBox(&fb,fb_toPixel(400,400),100,100,144,56,255);
	fb_drawLine(&fb,fb_toPixel(0,0),fb_toPixel(300,300),255,255,255);
	fb_printStr(&fb,"1234567890,.!? /\t\\\nabcdefghijklmnopqrstuvwxyz",&cursor,15,255,255,255);
	fb_close(&fb);
	while(1);
	return 0;
}
