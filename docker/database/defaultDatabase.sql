################################################################################
#                Create Initial Database and Tech Bench User                   #
################################################################################

CREATE SCHEMA IF NOT EXISTS `tech-bench`;
CREATE USER `tbUser`@`%` IDENTIFIED BY 'techBenchDatabase';
GRANT ALL PRIVILEGES ON `tech-bench`.* TO 'tbUser'@'%';
FLUSH PRIVILEGES; 
