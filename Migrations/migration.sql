USE PSI;
CREATE OR REPLACE TABLE user (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) UNIQUE NOT NULL,
    `password` varchar(255) NOT NULL    /*aumentado pra 255 pra não dar problema com o hash*/

) ENGINE=InnoDB;


CREATE OR REPLACE TABLE authors (
    `aid` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(100) NOT NULL,
    `surname` varchar(100) NOT NULL

) ENGINE=InnoDB;

CREATE OR REPLACE TABLE book (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ISBN` char(13) UNIQUE NOT NULL,
    `aid` INT UNSIGNED UNIQUE, 
    CONSTRAINT `aid` FOREIGN KEY (aid) REFERENCES authors (aid) ON DELETE CASCADE
) ENGINE=InnoDB;