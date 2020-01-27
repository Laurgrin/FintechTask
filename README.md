To launch the application, simply type "php index.php input.csv" in the command line. If the input file is in a 
different folder, modify the argument accordingly.

Now I used the task description example for testing, and in two cases, the result I've got
was a little different from the expected output outlined there. Double checking with a
calculator, I am pretty sure my app has the correct answer. Those cases are:

| input | output |
|--|--|
| `2016-01-07,1,natural,cash_out,1000.00,EUR` | `0.69` |
|  `2016-02-19,5,natural,cash_out,3000000,JPY` | `8611` |
