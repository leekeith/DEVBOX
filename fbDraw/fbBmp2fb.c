// fbBmp2fb.c
// Keith Lee
// Jan 19, 2015

#include "fbDraw.h"
#include "fbBmp2fb.h"

#ifdef LITTLEENDIAN
#undef LITTLEENDIAN
#endif

int getBytes( int nBytes, FILE* pf)
{
	int c,i;
	int bytes[4];
	if(nBytes>4)
		return 0;
	for(i=0;i<nBytes;i++)
	{
		
		bytes[i]=fgetc(pf);
	}
	c=0;
	
	for(i=0;i<nBytes;i++)
	{
		//printf("%x\n",bytes[i]);
		c=c|(bytes[i]<<(i*8));
		
	}
	//printf("\n%x\n",c);
	return c;
	
}


void getHeader(FILE* pf, fileHeader* h);
void getBITMAPINFOHEADER(FILE* pf, DIBHeader* h);
void getInfo(FILE* pf, DIBHeader* h);





int fbBmp_openBmp(char* path,  bmp* the_bmp)
{
	FILE* pf;
	pf=fopen(path,"r");
	
	if(!pf)
	{
		printf("Error: Unable to open file\n");
		return -1;
	}
	
	getHeader(pf, &the_bmp->header);
	
	
	if(the_bmp->header.head[0]!='B' || the_bmp->header.head[1]!='M')
	{
		printf("Error:  Invalid file format: %c%c\n",the_bmp->header.head[0],the_bmp->header.head[1]);
		fclose(pf);
		return -1;
	}
	
	
	getInfo(pf, &the_bmp->info);
	
	
	if(the_bmp->info.DIBsize==0)
	{
		printf("Error: Info Header invalid\n");
		fclose(pf);
		return -1;
	}

	fseek(pf,the_bmp->header.offset,SEEK_SET);
	the_bmp->image=(void*)malloc(the_bmp->header.size);
	int i=0;
	for(i=0;i<the_bmp->info.raw_sz;i++)
	{
		((char*)(the_bmp->image))[i]=fgetc(pf);
		if(feof(pf))
			break;
	}
	return 0;
}


int fbBmp_draw(dev_fb* fb, bmp* the_bmp, int scale, int x, int y)
{
	int i,j,offset;
	char r, g, b;
	int x_max=the_bmp->info.width;
	if(x_max%2)x_max++;
	int y_max=the_bmp->info.height;
	offset=0;
	scale=1; //for debugging
	if(scale==0 || scale==1)
	{
		for(i=0;i<y_max;i++)
		{
			offset=i*(x_max*3);
			for(j=0;j<x_max;j++)
			{
				r=the_bmp->image[offset+2];
				g=the_bmp->image[offset+1];
				b=the_bmp->image[offset];
				fb_drawPixel(fb,x+(j),y+(y_max-i),r,g,b);
				offset+=3;
			}
		}
	}
	return 0;
}


void getBITMAPINFOHEADER(FILE* pf, DIBHeader* h)
{
	h->width=getBytes(4,pf);
	h->height=getBytes(4,pf);
	h->planes=getBytes(2,pf);
	if(h->planes!=1)
	{
			h->DIBsize=0;
			return;
	}
	h->bpp=getBytes(2,pf);
	h->compression=getBytes(4,pf);
	h->raw_sz=getBytes(4,pf);
	h->res_x=getBytes(4,pf);
	h->res_y=getBytes(4,pf);
	h->nColors=getBytes(4,pf);
	h->nImportantColors=getBytes(4,pf);
}

void getHeader(FILE* pf, fileHeader* h)
{
	fseek(pf,0,SEEK_SET);
	fscanf(pf,"%c%c",&h->head[0],&h->head[1]);
	fseek(pf,2,SEEK_SET);
	h->size=getBytes(4,pf);
	fseek(pf,10,SEEK_SET);
	h->offset=getBytes(4,pf);
}

void getInfo(FILE* pf, DIBHeader* h)
{
	fseek(pf,14,SEEK_SET);
	h->DIBsize=getBytes(4,pf);
	switch(h->DIBsize)
	{
	case 40:
		getBITMAPINFOHEADER(pf, h);
		break;
	default:
		h->DIBsize=0;
		break;
	}
}

