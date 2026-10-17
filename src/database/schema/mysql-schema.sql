/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `app_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_alerts` (
  `alert_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cust_id` bigint unsigned NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`alert_id`),
  KEY `customer_alerts_cust_id_foreign` (`cust_id`),
  CONSTRAINT `customer_alerts_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_contact_phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_contact_phones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cont_id` bigint unsigned NOT NULL,
  `phone_type_id` bigint unsigned NOT NULL,
  `phone_number` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_contact_phones_cont_id_foreign` (`cont_id`),
  KEY `customer_contact_phones_phone_type_id_foreign` (`phone_type_id`),
  CONSTRAINT `customer_contact_phones_cont_id_foreign` FOREIGN KEY (`cont_id`) REFERENCES `customer_contacts` (`cont_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_contact_phones_phone_type_id_foreign` FOREIGN KEY (`phone_type_id`) REFERENCES `phone_number_types` (`phone_type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_contacts` (
  `cont_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cust_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `title` text COLLATE utf8mb4_unicode_ci,
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `local` tinyint(1) NOT NULL DEFAULT '0',
  `decision_maker` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cont_id`),
  KEY `customer_contacts_cust_id_foreign` (`cust_id`),
  CONSTRAINT `customer_contacts_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_equipment` (
  `cust_equip_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cust_id` bigint unsigned NOT NULL,
  `equip_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cust_equip_id`),
  KEY `customer_equipment_cust_id_foreign` (`cust_id`),
  KEY `customer_equipment_equip_id_foreign` (`equip_id`),
  CONSTRAINT `customer_equipment_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_equipment_equip_id_foreign` FOREIGN KEY (`equip_id`) REFERENCES `equipment_types` (`equip_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_equipment_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_equipment_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cust_equip_id` bigint unsigned NOT NULL,
  `field_id` bigint unsigned NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_equipment_data_cust_equip_id_foreign` (`cust_equip_id`),
  KEY `customer_equipment_data_field_id_foreign` (`field_id`),
  CONSTRAINT `customer_equipment_data_cust_equip_id_foreign` FOREIGN KEY (`cust_equip_id`) REFERENCES `customer_equipment` (`cust_equip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_equipment_data_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `data_fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_equipment_workbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_equipment_workbooks` (
  `wb_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wb_hash` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cust_id` bigint unsigned NOT NULL,
  `cust_equip_id` bigint unsigned NOT NULL,
  `wb_skeleton` json NOT NULL,
  `wb_version` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`wb_id`),
  UNIQUE KEY `customer_equipment_workbooks_cust_id_cust_equip_id_unique` (`cust_id`,`cust_equip_id`),
  KEY `customer_equipment_workbooks_cust_equip_id_foreign` (`cust_equip_id`),
  CONSTRAINT `customer_equipment_workbooks_cust_equip_id_foreign` FOREIGN KEY (`cust_equip_id`) REFERENCES `customer_equipment` (`cust_equip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_equipment_workbooks_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_file_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_file_types` (
  `file_type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`file_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_files` (
  `cust_file_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `file_id` bigint unsigned NOT NULL,
  `file_type_id` bigint unsigned NOT NULL,
  `cust_equip_id` bigint unsigned DEFAULT NULL,
  `cust_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cust_file_id`),
  KEY `customer_files_file_id_foreign` (`file_id`),
  KEY `customer_files_file_type_id_foreign` (`file_type_id`),
  KEY `customer_files_cust_id_foreign` (`cust_id`),
  KEY `customer_files_user_id_foreign` (`user_id`),
  KEY `customer_files_cust_equip_id_foreign` (`cust_equip_id`),
  CONSTRAINT `customer_files_cust_equip_id_foreign` FOREIGN KEY (`cust_equip_id`) REFERENCES `customer_equipment` (`cust_equip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_files_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `file_uploads` (`file_id`) ON UPDATE CASCADE,
  CONSTRAINT `customer_files_file_type_id_foreign` FOREIGN KEY (`file_type_id`) REFERENCES `customer_file_types` (`file_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `customer_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_notes` (
  `note_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cust_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `urgent` tinyint(1) NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cust_equip_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`note_id`),
  KEY `customer_notes_cust_id_foreign` (`cust_id`),
  KEY `customer_notes_created_by_foreign` (`created_by`),
  KEY `customer_notes_updated_by_foreign` (`updated_by`),
  KEY `customer_notes_cust_equip_id_foreign` (`cust_equip_id`),
  CONSTRAINT `customer_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `customer_notes_cust_equip_id_foreign` FOREIGN KEY (`cust_equip_id`) REFERENCES `customer_equipment` (`cust_equip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_notes_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_notes_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_site_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_site_contacts` (
  `cont_id` bigint unsigned NOT NULL,
  `cust_site_id` bigint unsigned NOT NULL,
  KEY `customer_site_contacts_cont_id_foreign` (`cont_id`),
  KEY `customer_site_contacts_cust_site_id_foreign` (`cust_site_id`),
  CONSTRAINT `customer_site_contacts_cont_id_foreign` FOREIGN KEY (`cont_id`) REFERENCES `customer_contacts` (`cont_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_site_contacts_cust_site_id_foreign` FOREIGN KEY (`cust_site_id`) REFERENCES `customer_sites` (`cust_site_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_site_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_site_equipment` (
  `cust_equip_id` bigint unsigned NOT NULL,
  `cust_site_id` bigint unsigned NOT NULL,
  KEY `customer_site_equipment_cust_equip_id_foreign` (`cust_equip_id`),
  KEY `customer_site_equipment_cust_site_id_foreign` (`cust_site_id`),
  CONSTRAINT `customer_site_equipment_cust_equip_id_foreign` FOREIGN KEY (`cust_equip_id`) REFERENCES `customer_equipment` (`cust_equip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_site_equipment_cust_site_id_foreign` FOREIGN KEY (`cust_site_id`) REFERENCES `customer_sites` (`cust_site_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_site_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_site_files` (
  `cust_file_id` bigint unsigned NOT NULL,
  `cust_site_id` bigint unsigned NOT NULL,
  KEY `customer_site_files_cust_file_id_foreign` (`cust_file_id`),
  KEY `customer_site_files_cust_site_id_foreign` (`cust_site_id`),
  CONSTRAINT `customer_site_files_cust_file_id_foreign` FOREIGN KEY (`cust_file_id`) REFERENCES `customer_files` (`cust_file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_site_files_cust_site_id_foreign` FOREIGN KEY (`cust_site_id`) REFERENCES `customer_sites` (`cust_site_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_site_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_site_notes` (
  `note_id` bigint unsigned NOT NULL,
  `cust_site_id` bigint unsigned NOT NULL,
  KEY `customer_site_notes_note_id_foreign` (`note_id`),
  KEY `customer_site_notes_cust_site_id_foreign` (`cust_site_id`),
  CONSTRAINT `customer_site_notes_cust_site_id_foreign` FOREIGN KEY (`cust_site_id`) REFERENCES `customer_sites` (`cust_site_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_site_notes_note_id_foreign` FOREIGN KEY (`note_id`) REFERENCES `customer_notes` (`note_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_sites` (
  `cust_site_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cust_id` bigint unsigned NOT NULL,
  `site_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip` int NOT NULL,
  `deleted_reason` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cust_site_id`),
  KEY `customer_sites_cust_id_foreign` (`cust_id`),
  CONSTRAINT `customer_sites_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_vpns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_vpns` (
  `vpn_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vpn_client_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `vpn_portal_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `vpn_username` text COLLATE utf8mb4_unicode_ci,
  `vpn_password` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vpn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `cust_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `primary_site_id` bigint unsigned DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `dba_name` text COLLATE utf8mb4_unicode_ci,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `vpn_id` bigint unsigned DEFAULT NULL,
  `deleted_reason` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cust_id`),
  KEY `customers_primary_site_id_foreign` (`primary_site_id`),
  KEY `customers_vpn_id_foreign` (`vpn_id`),
  CONSTRAINT `customers_primary_site_id_foreign` FOREIGN KEY (`primary_site_id`) REFERENCES `customer_sites` (`cust_site_id`) ON UPDATE CASCADE,
  CONSTRAINT `customers_vpn_id_foreign` FOREIGN KEY (`vpn_id`) REFERENCES `customer_vpns` (`vpn_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `data_field_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_field_types` (
  `type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pattern` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pattern_error` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_hyperlink` tinyint(1) NOT NULL DEFAULT '0',
  `allow_copy` tinyint(1) NOT NULL DEFAULT '0',
  `do_not_log_value` tinyint(1) NOT NULL DEFAULT '0',
  `masked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `data_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_fields` (
  `field_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equip_id` bigint unsigned NOT NULL,
  `type_id` bigint unsigned NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`field_id`),
  KEY `data_fields_equip_id_foreign` (`equip_id`),
  KEY `data_fields_type_id_foreign` (`type_id`),
  CONSTRAINT `data_fields_equip_id_foreign` FOREIGN KEY (`equip_id`) REFERENCES `equipment_types` (`equip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `data_fields_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `data_field_types` (`type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `device_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_tokens` (
  `device_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `os` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registered_ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`device_id`),
  KEY `device_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `device_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `equipment_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_categories` (
  `cat_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `equipment_categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `equipment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_types` (
  `equip_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` bigint unsigned NOT NULL,
  `allow_public_tip` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`equip_id`),
  UNIQUE KEY `equipment_types_name_unique` (`name`),
  KEY `equipment_types_cat_id_foreign` (`cat_id`),
  CONSTRAINT `equipment_types_cat_id_foreign` FOREIGN KEY (`cat_id`) REFERENCES `equipment_categories` (`cat_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `equipment_workbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_workbooks` (
  `equip_id` bigint unsigned NOT NULL,
  `workbook_data` json DEFAULT NULL,
  `version_hash` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`equip_id`),
  CONSTRAINT `equipment_workbooks_equip_id_foreign` FOREIGN KEY (`equip_id`) REFERENCES `equipment_types` (`equip_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_name_scope_unique` (`name`,`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `file_link_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_link_files` (
  `link_file_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `link_id` bigint unsigned NOT NULL,
  `file_id` bigint unsigned NOT NULL,
  `timeline_id` bigint unsigned NOT NULL,
  `upload` tinyint(1) NOT NULL DEFAULT '0',
  `moved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`link_file_id`),
  KEY `file_link_files_link_id_foreign` (`link_id`),
  KEY `file_link_files_file_id_foreign` (`file_id`),
  KEY `file_link_files_timeline_id_foreign` (`timeline_id`),
  CONSTRAINT `file_link_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `file_uploads` (`file_id`) ON UPDATE CASCADE,
  CONSTRAINT `file_link_files_link_id_foreign` FOREIGN KEY (`link_id`) REFERENCES `file_links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `file_link_files_timeline_id_foreign` FOREIGN KEY (`timeline_id`) REFERENCES `file_link_timelines` (`timeline_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `file_link_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_link_notes` (
  `link_note_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `timeline_id` bigint unsigned NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`link_note_id`),
  KEY `file_link_notes_timeline_id_foreign` (`timeline_id`),
  CONSTRAINT `file_link_notes_timeline_id_foreign` FOREIGN KEY (`timeline_id`) REFERENCES `file_link_timelines` (`timeline_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `file_link_timelines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_link_timelines` (
  `timeline_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `link_id` bigint unsigned NOT NULL,
  `added_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`timeline_id`),
  KEY `file_link_timelines_link_id_foreign` (`link_id`),
  CONSTRAINT `file_link_timelines_link_id_foreign` FOREIGN KEY (`link_id`) REFERENCES `file_links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `file_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_links` (
  `link_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `link_hash` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire` date NOT NULL,
  `instructions` longtext COLLATE utf8mb4_unicode_ci,
  `allow_upload` tinyint(1) NOT NULL,
  `cust_id` bigint unsigned DEFAULT NULL,
  `email_on_visit` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`link_id`),
  KEY `file_links_user_id_foreign` (`user_id`),
  KEY `file_links_cust_id_foreign` (`cust_id`),
  CONSTRAINT `file_links_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `file_links_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `file_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_uploads` (
  `file_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `phone_number_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phone_number_types` (
  `phone_type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_class` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`phone_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tip_comment_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tip_comment_flags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tech_tip_comment_flags_user_id_comment_id_unique` (`user_id`,`comment_id`),
  KEY `tech_tip_comment_flags_comment_id_foreign` (`comment_id`),
  CONSTRAINT `tech_tip_comment_flags_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `tech_tip_comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tech_tip_comment_flags_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tip_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tip_comments` (
  `comment_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tip_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `tech_tip_comments_tip_id_foreign` (`tip_id`),
  KEY `tech_tip_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `tech_tip_comments_tip_id_foreign` FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tech_tip_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tip_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tip_equipment` (
  `tip_equip_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tip_id` bigint unsigned NOT NULL,
  `equip_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tip_equip_id`),
  KEY `tech_tip_equipment_tip_id_foreign` (`tip_id`),
  KEY `tech_tip_equipment_equip_id_foreign` (`equip_id`),
  CONSTRAINT `tech_tip_equipment_equip_id_foreign` FOREIGN KEY (`equip_id`) REFERENCES `equipment_types` (`equip_id`) ON UPDATE CASCADE,
  CONSTRAINT `tech_tip_equipment_tip_id_foreign` FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tip_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tip_files` (
  `tip_file_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tip_id` bigint unsigned NOT NULL,
  `file_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tip_file_id`),
  KEY `tech_tip_files_tip_id_foreign` (`tip_id`),
  KEY `tech_tip_files_file_id_foreign` (`file_id`),
  CONSTRAINT `tech_tip_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `file_uploads` (`file_id`) ON UPDATE CASCADE,
  CONSTRAINT `tech_tip_files_tip_id_foreign` FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tip_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tip_types` (
  `tip_type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tip_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tip_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tip_views` (
  `tip_id` bigint unsigned NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `tech_tip_views_tip_id_unique` (`tip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tech_tips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tech_tips` (
  `tip_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `updated_id` bigint unsigned DEFAULT NULL,
  `tip_type_id` bigint unsigned NOT NULL,
  `sticky` tinyint(1) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tip_id`),
  KEY `tech_tips_user_id_foreign` (`user_id`),
  KEY `tech_tips_updated_id_foreign` (`updated_id`),
  KEY `tech_tips_tip_type_id_foreign` (`tip_type_id`),
  CONSTRAINT `tech_tips_tip_type_id_foreign` FOREIGN KEY (`tip_type_id`) REFERENCES `tech_tip_types` (`tip_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `tech_tips_updated_id_foreign` FOREIGN KEY (`updated_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `tech_tips_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_customer_bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_customer_bookmarks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `cust_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_customer_bookmarks_user_id_cust_id_unique` (`user_id`,`cust_id`),
  KEY `user_customer_bookmarks_cust_id_foreign` (`cust_id`),
  CONSTRAINT `user_customer_bookmarks_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_customer_bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_customer_recents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_customer_recents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `cust_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_customer_recents_user_id_foreign` (`user_id`),
  KEY `user_customer_recents_cust_id_foreign` (`cust_id`),
  CONSTRAINT `user_customer_recents_cust_id_foreign` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_customer_recents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_initializes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_initializes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_initializes_username_unique` (`username`),
  UNIQUE KEY `user_initializes_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_logins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ip_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_logins_user_id_foreign` (`user_id`),
  CONSTRAINT `user_logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_role_permission_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role_permission_categories` (
  `role_cat_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_role_permission_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role_permission_types` (
  `perm_type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_cat_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin_link` tinyint(1) NOT NULL DEFAULT '0',
  `feature_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`perm_type_id`),
  KEY `user_role_permission_types_role_cat_id_foreign` (`role_cat_id`),
  CONSTRAINT `user_role_permission_types_role_cat_id_foreign` FOREIGN KEY (`role_cat_id`) REFERENCES `user_role_permission_categories` (`role_cat_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `perm_type_id` bigint unsigned NOT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_role_permissions_role_id_foreign` (`role_id`),
  KEY `user_role_permissions_perm_type_id_foreign` (`perm_type_id`),
  CONSTRAINT `user_role_permissions_perm_type_id_foreign` FOREIGN KEY (`perm_type_id`) REFERENCES `user_role_permission_types` (`perm_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `user_role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `allow_edit` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_setting_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_setting_types` (
  `setting_type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `perm_type_id` bigint unsigned DEFAULT NULL,
  `feature_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`setting_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_settings` (
  `setting_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `setting_type_id` bigint unsigned NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`setting_id`),
  KEY `user_settings_user_id_foreign` (`user_id`),
  KEY `user_settings_setting_type_id_foreign` (`setting_type_id`),
  CONSTRAINT `user_settings_setting_type_id_foreign` FOREIGN KEY (`setting_type_id`) REFERENCES `user_setting_types` (`setting_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_tech_tip_bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_tech_tip_bookmarks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tip_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_tech_tip_bookmarks_user_id_tip_id_unique` (`user_id`,`tip_id`),
  KEY `user_tech_tip_bookmarks_tip_id_foreign` (`tip_id`),
  CONSTRAINT `user_tech_tip_bookmarks_tip_id_foreign` FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_tech_tip_bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_tech_tip_recents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_tech_tip_recents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `tip_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_tech_tip_recents_user_id_foreign` (`user_id`),
  KEY `user_tech_tip_recents_tip_id_foreign` (`tip_id`),
  CONSTRAINT `user_tech_tip_recents_tip_id_foreign` FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_tech_tip_recents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_verification_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_verification_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_verification_codes_user_id_foreign` (`user_id`),
  CONSTRAINT `user_verification_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$2y$12$ZZCaNJ4z95uwvk9ueU4Mge3Zg7XnzQX9BEtGPoPYMNV8Kh9KSUerK',
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `two_factor_via` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_expires` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`role_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `workbook_table_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workbook_table_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wb_id` bigint unsigned NOT NULL,
  `table_index` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_index` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workbook_table_values_wb_id_foreign` (`wb_id`),
  CONSTRAINT `workbook_table_values_wb_id_foreign` FOREIGN KEY (`wb_id`) REFERENCES `customer_equipment_workbooks` (`wb_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `workbook_task_list_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workbook_task_list_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `list_id` bigint unsigned NOT NULL,
  `list_item` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '9999',
  `completed` timestamp NULL DEFAULT NULL,
  `completed_by` text COLLATE utf8mb4_unicode_ci,
  `file_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workbook_task_list_items_list_id_foreign` (`list_id`),
  KEY `workbook_task_list_items_file_id_foreign` (`file_id`),
  CONSTRAINT `workbook_task_list_items_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `file_uploads` (`file_id`) ON UPDATE CASCADE,
  CONSTRAINT `workbook_task_list_items_list_id_foreign` FOREIGN KEY (`list_id`) REFERENCES `workbook_task_lists` (`list_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `workbook_task_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workbook_task_lists` (
  `list_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wb_id` bigint unsigned NOT NULL,
  `list_index` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`list_id`),
  KEY `workbook_task_lists_wb_id_foreign` (`wb_id`),
  CONSTRAINT `workbook_task_lists_wb_id_foreign` FOREIGN KEY (`wb_id`) REFERENCES `customer_equipment_workbooks` (`wb_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `workbook_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workbook_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wb_id` bigint unsigned NOT NULL,
  `index` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workbook_values_wb_id_foreign` (`wb_id`),
  CONSTRAINT `workbook_values_wb_id_foreign` FOREIGN KEY (`wb_id`) REFERENCES `customer_equipment_workbooks` (`wb_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2013_04_02_021438_create_app_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_03_30_042730_create_user_role_permission_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2014_03_30_042747_create_user_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2014_03_30_042825_create_user_role_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2021_03_30_054029_create_user_setting_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2021_03_31_005140_create_user_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2021_04_01_045045_create_user_logins_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2021_04_01_045653_create_user_initializes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2021_04_02_014938_create_file_uploads_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2021_04_04_193650_create_equipment_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2021_04_04_193859_create_equipment_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2021_04_04_195517_create_data_field_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2021_04_04_195531_create_data_fields_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2021_04_06_034501_create_phone_number_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2021_04_07_033544_create_customers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2021_04_14_020014_create_user_customer_bookmarks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2021_04_15_231104_create_customer_equipment_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2021_04_18_012850_create_customer_equipment_data_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2021_04_22_020304_create_customer_contacts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2021_04_22_020351_create_customer_contact_phones_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2021_04_27_022933_create_customer_notes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2021_04_30_014720_create_customer_file_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2021_04_30_014742_create_customer_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2021_06_12_023150_create_tech_tip_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2021_06_13_022945_create_tech_tips_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2021_06_13_045152_create_tech_tip_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2021_06_13_045235_create_user_tech_tip_bookmarks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2021_06_13_045630_create_tech_tip_equipment_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2021_06_13_045646_create_tech_tip_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2021_11_26_223905_create_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2021_11_30_025603_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2022_02_16_015541_create_user_customer_recents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2022_02_16_015612_create_user_tech_tip_recents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2022_05_06_015451_create_user_role_permission_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2022_11_01_000001_create_features_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2024_01_25_031752_create_device_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2024_01_25_043922_create_user_verification_codes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2024_02_10_030242_add_columns_to_data_field_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2024_02_11_190143_creat_customer_sites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2024_02_17_211241_create_customer_alerts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2024_02_22_035417_create_customer_site_equipment_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2024_02_22_035424_create_customer_site_contacts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2024_02_22_035434_create_customer_site_notes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2024_02_22_035439_create_customer_site_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2024_02_23_190220_migrate_customers_to_customer_sites',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2024_02_27_040040_migrate_customer_equipment_to_customer_sites',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2024_03_08_045123_migrate_customer_contacts_to_customer_sites',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2024_03_16_215133_migrate_customer_notes_to_customer_sites',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2024_03_24_173222_migrate_customer_files_to_customer_sites',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2024_03_24_184111_cleanup_customers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2024_04_06_002555_add_file_size_to_file_uploads',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2024_07_21_200736_add_public_column_to_tech_tips',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2024_08_01_020351_create_tech_tip_comment_flags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2024_08_03_192112_add_feature_name_to_user_role_permission_types',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2024_08_03_234226_add_allow_public_tip_to_equipment_types',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2024_08_11_185141_create_file_links_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2024_08_11_185145_create_file_link_timelines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2024_08_11_185150_create_file_link_notes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2024_08_11_185220_create_file_link_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2024_08_24_193516_migrate_app_settings_to_json',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2024_09_01_194732_create_tech_tip_views_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2024_10_28_184300_create_telescope_entries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_05_24_234653_add_cust_id_to_file_links_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2025_05_25_032631_add_moved_column_to_file_link_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2025_06_07_223128_add_two_factor_columns_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2025_06_13_012953_create_customer_vpns_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_05_26_141246_add_hash_name_column_to_file_uploads_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2026_05_30_150224_create_equipment_workbooks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2026_06_05_163727_create_customer_equipment_workbooks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2026_06_05_164021_create_workbook_values_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2026_06_05_164328_create_workbook_table_values_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2026_07_17_012046_create_workbook_task_lists_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2026_07_17_015634_create_workbook_task_list_items_table',1);
