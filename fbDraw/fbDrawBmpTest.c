//fbDrawBmpTest.c
//Keith Lee

#include"fbDraw.h"
#include"fbBmp2fb.h"

int main(int argc, char* argv[])
{
	dev_fb fb;
	if(argc<2)
	{
		printf("No file specified.");
		return 0;
	}
	bmp the_bmp;
	if(fbBmp_openBmp(argv[1],&the_bmp))
		printf("failed to open %s\n",argv[1]);
	else
		printf("Opened %s",argv[1]);
	
	printf("Head1 = %c  head2= %c\n",the_bmp.header.head[0],the_bmp.header.head[1]);
	printf("size = %u\noffset = %u\n", the_bmp.header.size,the_bmp.header.offset);
	printf("DIBsize = %u\nwidth = %u\nheight = %u\n",the_bmp.info.DIBsize, the_bmp.info.width, the_bmp.info.height);
	fb_init(&fb);
	fbBmp_draw(&fb, &the_bmp, 1, 100, 100);
	fb_close(&fb);
	return 0;
}
