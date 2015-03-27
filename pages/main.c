//arduinoBase.c
//Keith Lee 2015
#include"arduinoRuntime/arduinoRuntime.h"
void setup(void)
{
 int i;
 for(i=0;i<10;i++)
 {
 	pinMode(i, INPUT);
 	//printf("%d: %x\n", i, d_pin_mode[i]);
 	//printf("%d\n",d_pin[i]);
 }
 for(i=10;i<14;i++)
   pinMode(i, OUTPUT);
}

void loop(void)
{
 int x=digitalRead(0);
 x|=digitalRead(1);
 x|=digitalRead(2);
 
 digitalWrite(10, x);
     
 x=digitalRead(3);
 x|=digitalRead(4);
 x|=digitalRead(5);
 
 digitalWrite(11, x);
 
 x=digitalRead(6);
 x|=digitalRead(7);
 x|=digitalRead(8);
 
 digitalWrite(12, x);
 
 digitalWrite(13, digitalRead(9));
    
}
