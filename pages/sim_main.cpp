#include"Vharness.h"
#include<verilated.h>
#include<iostream>
#include<cstdio>

vluint32_t main_time=0;

int main(int argc, char** argv, char** env)
{
	char key;
	Verilated::commandArgs(argc, argv);
	Vharness* top=new Vharness;
	top->CLK=0;
	top->SW=0;
	while(!Verilated::gotFinish())
	{
		top->CLK=!top->CLK;
		if(top->CLK==1)
			cin >> key;
		if(top->CLK==0)
		{
			main_time++;
			cout << main_time<< ": "<<top->LEDR<<"->"<<top->SW<<endl;
		}
		//cout << key <<endl;
		switch(key)
		{
		case '1':
			top->SW=1;
			break;
		case '2':
			top->SW=2;
			break;
		case '3':
			top->SW=4;
			break;
		case '4':
			top->SW=8;
			break;
		case '5':
			top->SW=10;
			break;
		case '6':
			top->SW=12;
			break;
		case '7':
			top->SW=14;
			break;
		case '8':
			top->SW=16;
			break;
		case '9':
			top->SW=18;
			break;
		case '0':
			top->SW=20;
			break;
		default:
			top->SW=0;
			break;
		}
		top->eval();
	}

}
