USE udemy;
DROP TABLE recipestbl;
CREATE TABLE recipestbl (
    name varchar(100) NULL,
    description varchar(200) NULL,
    imagePath varchar(500) NULL,
    PRIMARY KEY (name)
);

DROP TABLE ingredientstbl;
CREATE TABLE ingredientstbl (
    recipename varchar(100) NULL,
    name varchar(100) NULL,
    amount INT NULL,
    KEY (recipename)
);

DROP TABLE usertbl;
CREATE TABLE usertbl (
    email varchar(100) NULL,
    password varchar(100) NULL,
    lastupdate datetime,
    KEY (email)
);
