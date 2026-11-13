################################################################################
#                Create Initial Database and Tech Bench User                   #
################################################################################

# Create Tech Bench Database
CREATE SCHEMA IF NOT EXISTS `tech-bench`;
CREATE SCHEMA IF NOT EXISTS `tech-bench-test`;

# Create the TB User
CREATE USER IF NOT EXISTS 'tbUser'@'%' IDENTIFIED WITH caching_sha2_password BY 'techBenchDatabase';
GRANT ALL PRIVILEGES ON `tech-bench`.* TO 'tbUser'@'%';
GRANT ALL PRIVILEGES ON `tech-bench-test`.* TO `tbUser`@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
