#include "devbox_ioctl_driver.h"

#include <stdio.h>

int main()
{
 int x=open_devbox_io();
 if(x<0)
        printf("FAIL!\n");
 else
{
	
	printf("SUCCESS %x\n",x);
	int q=devbox_get_sw(x);
	printf("Switches= %x\n",q);
	devbox_set_led(x,q);
     close_devbox_io(x);
} 
 return 0;
}