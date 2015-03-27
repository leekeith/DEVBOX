#! /bin/bash
direction=$1;
if [ "$direction" == "to" ] 

then
	rcp -r --exclude 'dbdbsync.sh' ./* /var/www;

elif [ "$direction" == "from" ] 
then
		cp -r /var/www/* .;

else echo "Unknown argument."
fi
	
	
