################################################################################
#                     Create Testing Database for PHPUnit                      #
################################################################################
CREATE SCHEMA IF NOT EXISTS `tech-bench-test`;

GRANT ALL PRIVILEGES ON `tech-bench-test`.* TO `tbUser`@'%' WITH GRANT OPTION;

FLUSH PRIVILEGES;
