/*******************************************************************************
*
*       Create Initial Database and Tech Bench User
*
********************************************************************************/

CREATE SCHEMA `tech-bench`;
USE `tech-bench`;
CREATE USER `tbUser`@`% IDENTIFIED BY 'techBenchDatabase';
GRANT ALL PRIVILEGES ON `tech-bench` TO 'tbUser' WITH GRANT OPTION;
FLUSH PRIVILEGES; 
