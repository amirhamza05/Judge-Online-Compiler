with open("expected_output.txt") as f:
    output1 = f.read()
with open("output.txt") as f:
    output2 = f.read()

if output1[-1] == '\n' or output1[-1] == ' ':
	output1 = output1[:-1]

if output2[-1] == '\n' or output2[-1] == ' ':
	output2 = output2[:-1]


if output1 == output2:
	print("OK")
else:
	print("NOT OK")