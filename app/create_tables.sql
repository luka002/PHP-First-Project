CREATE TABLE IF NOT EXISTS users (
  id              INT(11) NOT NULL AUTO_INCREMENT,
  name            VARCHAR(100) NOT NULL,
  password        VARCHAR(100) NOT NULL,
  email           VARCHAR(100) NOT NULL,
  premium         TINYINT(1) NOT NULL DEFAULT '0',
  administrator   TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY name (name),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS norms (
  id        INT(11) NOT NULL AUTO_INCREMENT,
  user_id   INT(11) NOT NULL,
  norm      TEXT NOT NULL,
  text      INT(11) NOT NULL,
  phone     INT(11) NOT NULL,
  date      INT(11) NOT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT user_norm FOREIGN KEY (user_id) REFERENCES users (id)
                       ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS update_password (
  email       VARCHAR(100) NOT NULL,
  link_sent   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  token       VARCHAR(80) NOT NULL,
  PRIMARY KEY (email),
  UNIQUE KEY email (email),
  CONSTRAINT email_foreign FOREIGN KEY (email) REFERENCES users (email)
                           ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;