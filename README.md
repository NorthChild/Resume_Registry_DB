# Resume_Registry_DB
resume database that supports CRUD, made in PHP and JS

part of my uni course

Simple resume database that support Create, Read, Update, and Delete operations (CRUD). 
Its also possible to move user information into its own table and link entries between two tables using foreign keys. there is also some in-browser JavaScript data validation.


# Resume_Registry_DB UPDATE

In this next assignment that extended from the original resume database to support CRUD into a Position table that has a many-to-one relationship to our Profile table.
we'll use JQuery to dynamically add and delete positions in the add and edit user interface.

changes with the update

- created a utility file so not avoid repeating code throughout the application 
- added year and description of position to the resume 
- new position table with many to one relationship to profile 
- changed add.php to add new table data (year and description)
- changed view.php to also show new table data
- changed edit.php  to edit new table data


# Here are some general specifications for this application:

   - PHP PDO database connection.
   - All data that comes from the users is properly escaped using the htmlentities() function in PHP.
   - POST-Redirect-GET pattern for all POST requests.
   - Use of the "header('Location: ...');" function and "return;" to send the location header and redirect
   - All error messages are "flash-style" messages where the message is passed from a POST to a GET using the SESSION.
