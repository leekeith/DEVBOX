/*
		DevBox ioctl Driver
		Keith Lee 2015
*/

#include <devbox_ioctl.h>
#include <devbox_ioctl_driver.h>


#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <unistd.h>
#include <sys/ioctl.h>

int devbox_set_led(int file_desc, short led_state)
{
	int ret_val;
	ret_val = ioctl(file_desc,IOCTL_SET_LED,led_state);
	if(ret_val<0)
	{
		printf("DEVBOX_IOCTL: set_led failed: %d\n",ret_val);
		return -1;
	}
	return 0;
}

short devbox_get_led(int file_desc)
{
	short ret_val;
	ret_val = (short)ioctl(file_desc,IOCTL_GET_LED,0);
	if(ret_val<0)
	{
		printf("DEVBOX_IOCTL: get_led failed: %d\n",ret_val);
		return -1;
	}
	else return ret_val;
}

short devbox_get_sw(int file_desc)
{
	short ret_val;
	ret_val = (short)ioctl(file_desc,IOCTL_GET_SW,0);
	if(ret_val<0)
	{
		printf("DEVBOX_IOCTL: get_sw failed: %d\n",ret_val);
		return -1;
	}
	else return ret_val;
}

char devbox_get_key(int file_desc)
{
	char ret_val;
	ret_val=(char)ioctl(file_desc,IOCTL_GET_KEY,0);
	if(ret_val<0)
	{
		printf("DEVBOX_IOCTL: get_key failed: %d\n",ret_val);
		return -1;
	}
	else return ret_val;
}

int open_devbox_io(void)
{
	int file_desc = open(DEVICE_FILE_NAME, 0);
	if(file_desc<0)
	{
		printf("DEVBOX_IOCTL: Failed to open IO file: %d\n", file_desc);
		return -1;
	}
	return file_desc;
}

void close_devbox_io(int file_desc)
{
	close(file_desc);
}

int devbox_set_7seg(int file_desc, short seg7_sel, char state)
{
	int ret_val;
	switch(seg7_sel)
	{
	case 0:
		ret_val=ioctl(file_desc, IOCTL_SET_SEG7_0, state);
		break;
	case 1:
		ret_val=ioctl(file_desc, IOCTL_SET_SEG7_1, state);
		break;
	case 2:
		ret_val=ioctl(file_desc, IOCTL_SET_SEG7_2, state);
		break;
	case 3:
		ret_val=ioctl(file_desc, IOCTL_SET_SEG7_3, state);
		break;
	case 4:
		ret_val=ioctl(file_desc, IOCTL_SET_SEG7_4, state);
		break;
	case 5:
		ret_val=ioctl(file_desc, IOCTL_SET_SEG7_5, state);
		break;
	default:
		return -1;
	}
	if(ret_val<0)
	{
		printf("DEVBOX IOCTL: set_7seg %d failed: %d", seg7_sel, ret_val);
		return -1;
	}
	return ret_val;
}




