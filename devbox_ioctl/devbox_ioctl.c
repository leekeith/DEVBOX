/*
		DEVBOX IOCTL
		Keith Lee 2015
*/
#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/init.h>
#include <linux/ioctl.h>
#include <linux/fs.h>
#include "hps_0.h"
#include "/usr/include/socal/hps.h"
#include <asm/io.h>
#include "devbox_ioctl.h"



#define OK 0

#define HW_REGS_BASE ( ALT_STM_OFST )
#define HW_REGS_SPAN ( 0x04000000 )
#define HW_REGS_MASK ( HW_REGS_SPAN - 1 )


MODULE_LICENSE("GPL");
MODULE_AUTHOR("Keith Lee");
MODULE_DESCRIPTION("User-level access to soft IOs");
unsigned long virtual_base;

struct file_operations fops = {
	.unlocked_ioctl = device_ioctl,
	.open = device_open,
	.release = device_release
};

static int device_open(struct inode *inode, struct file *flip)
{

	if (Device_Open)
		return -EBUSY;
	Device_Open++;
	printk(KERN_INFO "Device %s opened\n",DEVICE_NAME);
	virtual_base=(unsigned long)ioremap(ALT_LWFPGASLVS_OFST, 0x1f000);
	led = virtual_base+LEDR_BASE;
	sw = virtual_base+SW_BASE;
	key = virtual_base+KEY_BASE;

	try_module_get(THIS_MODULE);
	
	return OK;
}

static int device_release(struct inode *inode, struct file *flip)
{
	Device_Open--;
	led = (short*)&sink;
	sw = (short*)&sink;
	key = (char*)&sink;
	iounmap(virtual_base);
	module_put(THIS_MODULE);
	printk(KERN_INFO "Device %s closed\n", DEVICE_NAME);
	return OK;
}

long device_ioctl(struct file *flip, unsigned int ioctl_num, unsigned long ioctl_param)
{
	printk(KERN_INFO "Device %s ioctl\n",DEVICE_NAME);
	int i;
	printk(KERN_INFO "On to sw. SW=0x%X\n",sw);
	switch(ioctl_num)
	{
	case IOCTL_SET_LED:
		iowrite16((short)ioctl_param%0x3ff,led);
		return 0;
	case IOCTL_GET_LED:
		return ioread16(led);
	case IOCTL_GET_SW:
		return ioread16(sw);
	case IOCTL_GET_KEY:
		return ioread8(key);
	case IOCTL_SET_SEG7_0:
		iowrite8((char)ioctl_param, seg7);
		return 0;
	case IOCTL_SET_SEG7_1:
		iowrite8((char)ioctl_param, int(((char*)seg7)+1));
		return 0;
	case IOCTL_SET_SEG7_2:
		iowrite8((char)ioctl_param, int(((char*)seg7)+2));
		return 0;
	case IOCTL_SET_SEG7_3:
		iowrite8((char)ioctl_param, int(((char*)seg7)+3));
		return 0;
	case IOCTL_SET_SEG7_4:
		iowrite8((char)ioctl_param, int(((char*)seg7)+4));
		return 0;
	case IOCTL_SET_SEG7_5:		
		iowrite8((char)ioctl_param, int(((char*)seg7)+5));
		return 0;
	default:
		return -1;
	}
}

int init_module(void)
{
	Major= register_chrdev(MAJOR_NUM, DEVICE_NAME, &fops);
	if(Major!=0)
	{
		printk(KERN_ALERT "Registering %s chrdev failed with %d\n",DEVICE_NAME, Major);
		return Major;
	}
	printk(KERN_ALERT "Registering %s chrdev succeeded with Major=%d\n",DEVICE_NAME, MAJOR_NUM);
	return OK;
}

void cleanup_module()
{
	unregister_chrdev(MAJOR_NUM, DEVICE_NAME);
	
}
