# Paysera Commission task skeleton

Following steps:
- don't forget to change `Paysera` namespace and package name in `composer.json`
 to your own, as `Paysera` keyword should not be used anywhere in your task;
- `\Paysera\CommissionTask\Service\Math` is an example class provided for the skeleton and could or could not be used by your preference;
- needed scripts could be found inside `composer.json`;
- before submitting the task make sure that all the scripts pass (`composer run test` in particular);
- this file should be updated before submitting the task with the documentation on how to run your program.

Good luck! :) 

# Update
To launch the application, simply type "php index.php input.csv" in the command line. If the input file is in a 
different folder, modify the argument accordingly.

Now I used the task description example for testing, and in two cases, the result I've got
was a little different from the expected output outlined there. Double checking with a
calculator, I am pretty sure my app has the correct answer. Those cases are:

| input | output |
|--|--|
| `2016-01-07,1,natural,cash_out,1000.00,EUR` | `0.69` |
|  `2016-02-19,5,natural,cash_out,3000000,JPY` | `8611` |
