---
Title: Sub Page
---

[db_source query="select * from user  where android = false limit 3" db="db2" row=" + {id_user} : *email* : {email} / {is_android}" ]


[db_source query="select id, email from user" db="db1" row=" + {id} *email* : {email}" ]

