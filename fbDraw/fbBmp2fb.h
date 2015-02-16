// fbBmp2fb.h
// Keith Lee

typedef struct fileHeader{
	char head[2];
	unsigned int size;
	unsigned int offset;
}fileHeader;

typedef struct DIBHeader{
	unsigned int DIBsize;
	int width;
	int height;
	short planes;
	short bpp;
	int compression;
	int raw_sz;
	int res_x;
	int res_y;
	unsigned int nColors;
	unsigned int nImportantColors;
}DIBHeader;

typedef struct bmp{
	fileHeader header;
	DIBHeader info;
	char* image;
}bmp;

int fbBmp_openBmp(char* path, bmp* the_bmp);
int fbBmp_draw(dev_fb* fb, bmp* the_bmp, int scale, int x, int y);
