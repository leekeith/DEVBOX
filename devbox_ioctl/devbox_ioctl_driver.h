#ifndef __DEVBOX_IOCTL_DRIVER_H__
#define __DEVBOX_IOCTL_DRIVER_H__
#include"devbox_ioctl.h"

int devbox_set_led(int file_desc, short led_state);
short devbox_get_led(int file_desc);
short devbox_get_sw(int file_desc);
char devbox_get_key(int file_desc);
int open_devbox_io(void);
void close_devbox_io(int file_desc);
int devbox_set_7seg(int file_desc, short seg7_sel, char state);

#endif
