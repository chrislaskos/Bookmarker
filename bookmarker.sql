CREATE DATABASE bookmarker;
USE bookmarker;

CREATE TABLE users (
  username VARCHAR(16) PRIMARY KEY,
  password CHAR(40) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created DATETIME,
  modified DATETIME
);

CREATE TABLE bookmarks (
  username VARCHAR(16) NOT NULL,
  url VARCHAR(255) NOT NULL,
  created DATETIME,
  INDEX (username),
  INDEX (url)
);

INSERT INTO users (username, password, email, created, modified)
VALUES
('user', SHA1('secret'), 'demo@example.com', NOW(), NOW());

INSERT INTO bookmarks (username, url, created)
VALUES
('user', 'http://www.php.net', NOW());

GRANT SELECT, INSERT, UPDATE, DELETE
ON bookmarker.*
TO bookmark_user@localhost IDENTIFIED BY 'password';
