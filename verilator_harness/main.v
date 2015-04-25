module main(SW, LEDR,CLK);
	input wire CLK;
	input wire [9:0] SW;
	output wire [9:0] LEDR;
	reg [9:0] r_LEDR;
	initial begin
	r_LEDR= 10'b0;
	end
	always @ (posedge CLK)
	begin
		$display("V CLK HIGH");
		r_LEDR<=SW;
	end
	assign LEDR=r_LEDR;

endmodule

