# Homework: Movie Class

Create a Movie class that determines the cost of a ticket to a cinema, based on the moviegoer's age. Assume that the cost of a full-price ticket is $10. Assign the age to a private data member. Use a public member function to determine the ticket price, based on the following schedule:

| Age | Price |
| --- | --- |
| Under 5 | Free |
| 5 to 17 | Half Price |
| 18 to 55 | Full Price |
| Over 55 | $2 off |

Make sure the assignment is done in a dynamic format.  No database is needed, however, make sure that a user can appropriately enter their age into a textbox.  Once the user submits their age, the appropriate prices will be displayed using the above table.

> See [homework](https://git.vdm.dev/champlain/WEBD-325-45/src/branch/master/week-03/homework)...

# Project: Logging In

This weeks assignment involves creating PHP scripts and associated staff/admin site web pages developed to accept username and passwords, verifying the user against information (secure/hashed password) in the database, recording failed login attempts, and either giving users access to the site or allowing them to try again.  After five failed passwords the user session is terminated and access to their username is permanently blocked.

- Create PHP scripts and associated web pages developed to accept username and passwords
- Verify user information against the database
- Either give users access to the staff site or allow them to try again

User session begins on their first attempt to log in and continues if their credentials are accepted. After five failed passwords the user session is terminated and access to their username is permanently blocked.

**Submit the following three items to Canvas:**

- A login username and password (the encrypted version of the password should be stored in the database)
- A zip file of your entire staff website
- A dump of your database.  You could use the [mysqldump](https://dev.mysql.com/doc/refman/8.0/en/mysqldump.html)  (Links to an external site.)program or [phpMyAdmin](https://fragments.turtlemeat.com/mysql-database-backup-restore-phpmyadmin.php)

**Extra Credit:** If you build a "Request Account" feature so new users can email the admin to request a new account, you'll earn 2 extra credit points making this assignment worth a possible 17 points.

> See [project proposal](https://git.vdm.dev/champlain/WEBD-325-45/src/branch/master/week-03/project)...

