# Homework: Using Twig

Create a web application that calculates the correct amount of change to return when performing a cash transaction. Allow the user (a cashier) to enter the cost of a transaction and the exact amount of money that the customer hands over to pay for the transaction.

Once the user clicks the submit button after entering in the information into the form fields, determine the largest amount of each denomination to return to the customer. Assume that the largest denomination a customer will use is a $100 bill. Therefore, you need to calculate the correct amount of change to return, the number of $50, $20, $10, $5, and $1 bills to return, and the number of quarters, dimes, nickels, and pennies to return. For example, if the price of a transaction is $5.65 and the customer hands the cashier $10, the cashier should return $4.35 to the customer as four $1 bills, a quarter, and a dime.

Use a Twig template design that effectively separates the business logic required to perform calculations from web presentations used to accept user inputs and display results.

> See [homework](https://git.vdm.dev/champlain/WEBD-325-45/src/branch/master/week-05/homework)...

# Project: Content Management of Pages

Complete the scripts to manage content for multiple pages on the public site for your CMS projects.  Your functionality should include all necessary CRUD (Create, Read, Update, Delete) tasks required to maintain page content on the public site.  Remember that a logged in administrator (that was created in week 3) should have the ability to create, update, and delete the pages appropriately.  When an admin is updating a page, the form field should be autofilled with the information that is currently displaying for the subject.  The admin and all users should have the ability to read the pages.  After you complete this part of your project, zip up your entire project and submit in this assignment.

There will be 2 extra credit points awarded if any part of the Twig Templating Framework is used in the project for this week.  Make sure to state in a submission comment on your use of the Twig Templating Framework and what exactly you did with Twig so the extra credit can be awarded appropriately.

Also, make sure to include a dump of your test database using the mysqldump program or phpMyAdmin and upload the exported file with your submission.  Here are some sites with tutorials that show you how to use mysqldump and phpMyAdmin:

[How to export a database with mysqldump](http://dev.mysql.com/doc/refman/5.1/en/mysqldump.html)
[How to export a database using phpMyAdmin](http://fragments.turtlemeat.com/mysql-database-backup-restore-phpmyadmin.php)

> See [project](https://git.vdm.dev/champlain/WEBD-325-45/src/branch/master/week-05/project)...

