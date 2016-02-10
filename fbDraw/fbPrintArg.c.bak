#include "fbDraw.h"
#include "fbBmp2fb.h"
#define MARGIN 5
int main(int argc, char** argv)
{
	dev_fb fb;
	bmp bm;
	int i;
	
	if(fbBmp_openBmp("/usr/splash_logo.bmp", &bm))
		printf("failed to open /usr/splash_logo.bmp\n");
	
	pixel cursor;
	cursor.x=10;
	cursor.y=10;
	printf("FB status: %d\n",fb_init(&fb));
	fb_fillScr(&fb,255,255,255);
	fbBmp_draw(&fb, &bm, 1, cursor.x, cursor.y);
	
	cursor.x=10;
	cursor.y+=bm.info.height+MARGIN;
	if(argc>=2)
	{
		for(i=1;i<argc;i++)
		{
			fb_printStr(&fb,argv[i],&cursor,12,0,0,0);
			printf("%s\n", argv[i]);
			fb_printStr(&fb," ",&cursor,12,0,0,0);
		}
	}
	else return -1;
	return 0;
}
