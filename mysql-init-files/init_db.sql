DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS users;

create table user(
   id INT NOT NULL AUTO_INCREMENT,
   firstname VARCHAR(100) NOT NULL,
   lastname VARCHAR(100) NOT NULL,
   PRIMARY KEY ( id )
);

create table post(
   id INT NOT NULL AUTO_INCREMENT,
   content VARCHAR(100) NOT NULL,
   user_id INT,
   PRIMARY KEY ( id ),
   FOREIGN KEY (user_id)
        REFERENCES user(id)
);
