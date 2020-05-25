# [PicoCMS](https://github.com/picocms/Pico) plugin : MySQLList

Fetch data from mysqlDB.
Put fields values into markdown.

# Motivations

Adding database connection for the great flat file CMS Pico sound weird! Yes it is.
The goal is to use pico as a base to monitor a MySQL database and show some key values.
It will be completed with a MySQLGraph plugin and a GETFromAPI plugin.

# Install

Copy the `MySQLListPlugin.php` into the `plugins` folder.


# Example

The config :

```
mysql_source:
 db1:                             # First database config name
  db_name: first_db                 # database name
  db_user: admin                    # database user
  db_pwd: myComplexPwd              # database password
  db_host: localhost                # database host
```

Your markdown file : 

```
[db_source query="select id, email from user" db="db1" row=" + {id} *email* : {email}" ]
```
