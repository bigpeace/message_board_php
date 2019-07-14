## PHP にて メッセージボード(Web) 作成
#### PHP + MySQL

* MySQL setting
* Database Name : mydb

1.table name : members
  - id INT
  - name VARCHAR(255)
  - email VARCHAR(255)
  - password VARCHAR(100)
  - picture VARCHAR(255)
  - created DATETIME
  - modified TIMESTAMP
 
 2.table name : posts
  - id INT
  - message TEXT
  - member_id INT
  - reply_post_id INT
  - created DATETIME
  - modified TIMESTAMP
