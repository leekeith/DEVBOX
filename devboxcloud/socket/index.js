var	app=require("http").createServer(),
		io=require("socket.io")(app),
		cp=require("child_process"),
		exe=cp.execSync,
		spw;

io.on('connect',function(socket)
	{
		console.log('connected');
		socket.on('C',function(msg)
		{
			var str="";
			var lines;
			var compile=exe.exec('gcc ../main.c -m32 -Wall -std=c99 -static -o../main');
			if(compile.stdout)
			{
				socket.emit('compile',{data: compile.stdout});
			}
			if(compile.stderr)
			{
				socket.emit('compile',{data: compile.stderr}); 
			}
			if(compile.code)
			{
				console.log('Code: '+compile.code)
				socket.emit('end',{data: 'end'});
			}
			else
			{
				spw=cp.spawn('qemu-i386',['../main']);
				var str;
				spw.stdout.on('data',function(data)
				{
					str=data.toString();
					socket.emit('run',{data: str});
				});
				
				spw.stderr.on('data',function(data)
				{
					str=data.toString();
					socket.emit('run',{data: str});
				});
				spw.on('close',function(code)
				{
					socket.emit('end', {data: code});
					console.log('close');
				});
				spw.on('error',function(code)
				{
					console.log('error: '+code);
				});
			}
		});
	});
		


app.listen(4000);
