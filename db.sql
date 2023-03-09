CREATE DATABASE test_php_dbbookstore;

CREATE TABLE `books` (

  `id` int(11) NOT NULL,

  `title` varchar(500) NOT NULL,

  `author` varchar(500) NOT NULL,

  `price` varchar(500) NOT NULL,

  `ISBN` varchar(50) NOT NULL,

  `category` varchar(100) NOT NULL

) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
