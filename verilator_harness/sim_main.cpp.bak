#include"Vharness.h"

#if defined (__cplusplus)
extern "C"
{
#endif

#include<devbox_ioctl_driver.h>

#if defined (__cplusplus)
}
#endif

#include<verilated.h>
#include<iostream>
#include<cstdio>


int main(int argc, char** argv, char** env)
{
	int db,cycles;
	char key_old;
	Verilated::commandArgs(argc, argv);
	Vharness* top=new Vharness;
	cycles=0;
	top->CLK=0;
	key_old=top->KEY;
	while(!Verilated::gotFinish())
	{
		db=open_devbox_io();
		top->SW=devbox_get_sw(db);
		top->KEY=devbox_get_key(db);
		
		devbox_set_led(db, top->LEDR);
		if(top->CLK_COUNT==0)
		{
			if(top->CLK==0)
				cycles++;
			top->CLK=!top->CLK;
		}
		else if(key_old!=top->KEY)
		{
			if(top->CLK==0)
				cycles++;
			top->CLK=!top->CLK;
			if(cycles>top->CLK_COUNT)
				exit(0);
		}
		close_devbox_io(db);
		if(top->CLK==0)
		{
			
			cout << cycles<< ": "<<"\n\t"<<top->LEDR<<"\n\t"<<top->SW<<endl;
		}
		
		top->eval();
	}

}
