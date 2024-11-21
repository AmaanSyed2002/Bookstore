This App is similar to the original CRUDL example I provided.

Some key differences are highlighted below.

******************
This App uses a simple ORM/AR framework.

  The master (abstract) class is in the class_data_operations.php file.
  It contains all of the general DB operations in a NON-table specific way.

  There are also two "Table-Specific classes": class_people_table.php and class_states_table.php.
  Their properties match the column names in the corresponding DB tables this example uses.
  Thus, each table-specific class "knows" about its specific table, but also inherits the
  core methods (not table specific) from the parent data operations class.


******************
This App uses common top/bottom HTML files so that all web pages have the same structural format.

  This technique is called SSI (Server Side Includes) since the common top/bottom files
  are applied to each page, being included in all PHP-generated HTML pages.


******************
The files in the SQL folder contain the SQL needed to build the App's database tables.

  IMPORTANT:
    The SQL code uses generic table names.
    If you want to install your own version of the App, change the table names to something suitable.

  The SQL can be executed by copying and pasting it into the SQL tab in the database GUI.