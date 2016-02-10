/*
		DEVBOX IOCTL
		Keith Lee 2015

*/

#ifndef __DEVBOX_IOCTL_H__
#define __DEVBOX_IOCTL_H__

#include <linux/ioctl.h>

#define MAJOR_NUM 123
#define DEVICE_NAME "devbox_ioctl"
#define DEVICE_FILE_NAME "/dev/devbox_ioctl"

#define IOCTL_SET_LED 		_IOW(MAJOR_NUM, 0, short)
#define IOCTL_GET_LED		_IOR(MAJOR_NUM, 1, short)
#define IOCTL_GET_SW		_IOR(MAJOR_NUM, 2, short)
#define IOCTL_GET_KEY		_IOR(MAJOR_NUM, 3, char)
#define IOCTL_SET_SEG7_0	_IOW(MAJOR_NUM, 4, char)
#define IOCTL_SET_SEG7_1	_IOW(MAJOR_NUM, 5, char)
#define IOCTL_SET_SEG7_2	_IOW(MAJOR_NUM, 6, char)
#define IOCTL_SET_SEG7_3	_IOW(MAJOR_NUM, 7, char)
#define IOCTL_SET_SEG7_4	_IOW(MAJOR_NUM, 8, char)
#define IOCTL_SET_SEG7_5	_IOW(MAJOR_NUM, 9, char)

static int Device_Open=0;
static int Major;


volatile static short* led;
volatile static short* sw;
volatile static char* key;
volatile static char* seg7;

static unsigned long sink;

static int device_open(struct inode *inode, struct file *file);
static int device_release(struct inode *inode, struct file *file);
long device_ioctl(struct file *flip, unsigned int ioctl_num, unsigned long ioctl_param);
int init_module(void);
void cleanup_module(void);

#endif
