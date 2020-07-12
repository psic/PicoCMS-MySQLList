# [PicoCMS](https://github.com/picocms/Pico) plugin : MySQLList

Fetch data from mysqlDB.
Put fields values into markdown.

# Motivations

Adding database connection for the great flat file CMS Pico sound weird! Yes it is.
The goal is to use pico as a base to monitor a MySQL database and show some key values.
It will be completed with a MySQLGraph plugin and a GETFromAPI plugin.

# Install

Copy the `MySQLListPlugin.php` and the `MySQLConfig.php` files into the `plugins` folder.

# Config

First, config your database access in the `MySQLConfig.php` :

```
return array(
    'db1'=>array ( // database settings name for the plugin 
        'host' => 'localhost', //database host
        'username' => 'admin', //database username
        'password' => 'passwd1', //database password
        'db_name' => 'db1_name') //database name
);


```
You can add several database in this file.
Then, you should write queries, and give them names in the Pico's config :

```
mysql_source:
 db1:                             # First database config name
  #query_name: "SQL Query, SELECT only"
  select_users: "select * from user limit 2"
  select_android_user: "select * from user  where is_android = false limit 3"

```
For queries delimitation, only use `"`, not `` ` ``,  since it can be use in the SQL query.
Finally, use those queries in your markdown file :

+ `query` : the name of the query used as it is in the Pico's conf file.
+ `row` : the markdown you want to insert in your file for each row of the query result. Use `{` and `}` to put your query column name.

For a list: 

```
[db_source query="select_users" row=" + {id} *email* : {email}" ]
```

Or a table: 

```
| id | email |
|----|:------|
[db_source query="select_users" row=" | {id} | {email} |" ]
```


