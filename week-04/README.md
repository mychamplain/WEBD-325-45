# Homework: PDO and CRUD

Create a database named "sportstars". Inside that database, create a table also named "sportstars". Inside that table, the following column names would be required to complete the assignment:

| id | int(11) | primary key, not null, auto increment |
| --- | --- | --- |
| name | varchar(100) | not null |
| age | int(3) | not null |
| sport | varchar(100) | not null |

Create a PHP Web Application using the lesson plans taught during this week regarding PHP Data Objects (PDO), as well as the skills being used in the CMS project regarding CRUD (Create, Read, Update, and Delete) to create a fully-functional, dynamic, database-driven web application that will allow the user to enter the name, age, and sport of the sport star. The following criteria would be required when completing this:

- The Homepage (first page to access) MUST include a blank table. This table will display column headings "Name", "Age", "Sport", and "Update".  There should be a link to allow the user to add a new sport star above or below the table.  The last column in the table should be two separate links, one of which states "Edit" and another that states "Delete".
- When a user adds a new sport star, it'll take them to a form where the user will fill out the proper fields. Proper error handling must be displayed for a form with invalid characters, as well as empty form fields.  When the user submits the newly created sport star, they should be told that the star has been added successfully with a link to go back to the Homepage to view the newly created star.
- When a user edits an existing sport star, it'll take them to an auto-filled form that has the information currently existing for the sport star in the form field.  The user can change the information. Upon updating the existing sport star, the user should be redirected to the Homepage and be able to view their changes.  The previously existing information should now display the updated information.
- When a user deletes an existing sport star, it should first give them a warning (either a new page or an alert box) asking the user to confirm the deletion or cancel it.  Once confirmed, the sport star should be removed from both the database and the table on the Homepage.

Keep in mind that this is being done in PDO.  The use of mysqli in PHP is FORBIDDEN and will result in a loss of points.  Make sure upon completion to zip up your project files (with all pages created for this assignment) and then submit in this assignment.

Include a dump of your test database using the mysqldump program or phpmyAdmin and upload the exported file with your submission. Here are some sites with tutorials that show you how to use mysqldump and phpMyAdmin:

[How to export a database with mysqldump](http://dev.mysql.com/doc/refman/5.1/en/mysqldump.html)
[How to export a database using phpMyAdmin](http://fragments.turtlemeat.com/mysql-database-backup-restore-phpmyadmin.php)

> See [homework](https://git.vdm.dev/champlain/WEBD-325-45/src/branch/master/week-04/homework)...

# Project: Content Management of Subjects

Complete the scripts to manage content for multiple subjects on the public site for your CMS projects.  Your functionality should include all necessary CRUD (Create, Read, Update, Delete) tasks required to maintain subject categories on the public site.  Remember that a logged in administrator (that was created in week 3) should have the ability to create, update, and delete the subjects appropriately.  When an admin is updating a subject, the form field should be auto-filled with the information that is currently displaying for the subject.  The admin and all users should have the ability to read the subjects.  After you complete this part of your project, zip up your entire project and submit in this assignment.

There will be 2 points of extra credit awarded if PDO is used to complete the database programming with MySQL and PHP.  There will be an additional 3 points extra credit awarded if you incorporate the Composer Library into your project.  Make sure to send me a submission comment about your use of PDO and/or the Composer Library and what exactly you did with Composer so the extra credit can be awarded appropriately.

Also, make sure to include a dump of your test database using the mysqldump program or phpMyAdmin and upload the exported file with your submission.  Here are some sites with tutorials that show how to use mysqldump and phpMyAdmin:

[How to export a database with mysqldump](http://dev.mysql.com/doc/refman/5.1/en/mysqldump.html)
[How to export a database using phpMyAdmin](http://fragments.turtlemeat.com/mysql-database-backup-restore-phpmyadmin.php)

> See [project proposal](https://git.vdm.dev/champlain/WEBD-325-45/src/branch/master/week-04/project)...

