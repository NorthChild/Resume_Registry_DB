# Resume_Registry_DB
resume database that supports CRUD, made in PHP and JS

part of my uni course

Simple resume database that support Create, Read, Update, and Delete operations (CRUD). 
Its also possible to move user information into its own table and link entries between two tables using foreign keys. there is also some in-browser JavaScript data validation.



Here are some general specifications for this application:

   - You must use the PHP PDO database layer in order for it to work.
   - All data that comes from the users is properly escaped using the htmlentities() function in PHP.
   - POST-Redirect-GET pattern for all POST requests.
   - Use of the "header('Location: ...');" function and either "return;" or "exit();" to send the location header and redirect
   - All error messages are "flash-style" messages where the message is passed from a POST to a GET using the SESSION.
