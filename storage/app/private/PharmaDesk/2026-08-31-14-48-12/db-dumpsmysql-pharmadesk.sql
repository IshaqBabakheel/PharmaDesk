
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('pharmadesk-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:285:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"dashboard.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:16:\"dashboard.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:14:\"dashboard.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"dashboard.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:17:\"dashboard.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:22:\"dashboard.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:16:\"dashboard.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:16:\"dashboard.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"settings.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:15:\"settings.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:13:\"settings.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:15:\"settings.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:16:\"settings.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:21:\"settings.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"settings.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:15:\"settings.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:10:\"roles.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"roles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:10:\"roles.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"roles.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:13:\"roles.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:18:\"roles.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:12:\"roles.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"roles.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:16:\"permissions.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:18:\"permissions.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"permissions.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"permissions.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:19:\"permissions.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:24:\"permissions.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:18:\"permissions.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"permissions.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:10:\"users.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:12:\"users.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:10:\"users.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:12:\"users.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:13:\"users.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"users.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:12:\"users.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:12:\"users.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:24:\"medicine-categories.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:6;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:26:\"medicine-categories.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:24:\"medicine-categories.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:26:\"medicine-categories.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:27:\"medicine-categories.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:32:\"medicine-categories.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:26:\"medicine-categories.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:26:\"medicine-categories.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:19:\"medicine-types.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:6;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:21:\"medicine-types.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:19:\"medicine-types.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:21:\"medicine-types.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:22:\"medicine-types.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:27:\"medicine-types.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:21:\"medicine-types.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:21:\"medicine-types.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:10:\"units.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:6;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:12:\"units.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:10:\"units.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:12:\"units.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:13:\"units.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:18:\"units.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:12:\"units.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:12:\"units.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:18:\"manufacturers.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:6;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:20:\"manufacturers.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:18:\"manufacturers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:20:\"manufacturers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:21:\"manufacturers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:26:\"manufacturers.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:20:\"manufacturers.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:20:\"manufacturers.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:14:\"medicines.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:16:\"medicines.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:14:\"medicines.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:16:\"medicines.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:17:\"medicines.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:22:\"medicines.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:16:\"medicines.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:16:\"medicines.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:14:\"suppliers.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:16:\"suppliers.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:14:\"suppliers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:16:\"suppliers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:17:\"suppliers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:22:\"suppliers.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:16:\"suppliers.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:16:\"suppliers.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:14:\"customers.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:16:\"customers.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:14:\"customers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:16:\"customers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:17:\"customers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:22:\"customers.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:16:\"customers.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:16:\"customers.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:14:\"purchases.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:16:\"purchases.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:14:\"purchases.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:16:\"purchases.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:17:\"purchases.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:22:\"purchases.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:16:\"purchases.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:16:\"purchases.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:21:\"purchase-returns.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:23:\"purchase-returns.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:21:\"purchase-returns.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:23:\"purchase-returns.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:24:\"purchase-returns.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:29:\"purchase-returns.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:23:\"purchase-returns.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:23:\"purchase-returns.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:10:\"sales.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:113;a:4:{s:1:\"a\";i:114;s:1:\"b\";s:12:\"sales.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:114;a:4:{s:1:\"a\";i:115;s:1:\"b\";s:10:\"sales.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:115;a:4:{s:1:\"a\";i:116;s:1:\"b\";s:12:\"sales.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:116;a:4:{s:1:\"a\";i:117;s:1:\"b\";s:13:\"sales.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:117;a:4:{s:1:\"a\";i:118;s:1:\"b\";s:18:\"sales.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:118;a:4:{s:1:\"a\";i:119;s:1:\"b\";s:12:\"sales.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:119;a:4:{s:1:\"a\";i:120;s:1:\"b\";s:12:\"sales.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:120;a:4:{s:1:\"a\";i:121;s:1:\"b\";s:17:\"sale-returns.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:121;a:4:{s:1:\"a\";i:122;s:1:\"b\";s:19:\"sale-returns.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:122;a:4:{s:1:\"a\";i:123;s:1:\"b\";s:17:\"sale-returns.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:123;a:4:{s:1:\"a\";i:124;s:1:\"b\";s:19:\"sale-returns.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:124;a:4:{s:1:\"a\";i:125;s:1:\"b\";s:20:\"sale-returns.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:125;a:4:{s:1:\"a\";i:126;s:1:\"b\";s:25:\"sale-returns.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:126;a:4:{s:1:\"a\";i:127;s:1:\"b\";s:19:\"sale-returns.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:127;a:4:{s:1:\"a\";i:128;s:1:\"b\";s:19:\"sale-returns.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:128;a:4:{s:1:\"a\";i:129;s:1:\"b\";s:11:\"stocks.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:129;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:13:\"stocks.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:130;a:4:{s:1:\"a\";i:131;s:1:\"b\";s:11:\"stocks.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:131;a:4:{s:1:\"a\";i:132;s:1:\"b\";s:13:\"stocks.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:132;a:4:{s:1:\"a\";i:133;s:1:\"b\";s:14:\"stocks.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:133;a:4:{s:1:\"a\";i:134;s:1:\"b\";s:19:\"stocks.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:134;a:4:{s:1:\"a\";i:135;s:1:\"b\";s:13:\"stocks.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:135;a:4:{s:1:\"a\";i:136;s:1:\"b\";s:13:\"stocks.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:136;a:4:{s:1:\"a\";i:137;s:1:\"b\";s:22:\"stock-adjustments.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:137;a:4:{s:1:\"a\";i:138;s:1:\"b\";s:24:\"stock-adjustments.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:138;a:4:{s:1:\"a\";i:139;s:1:\"b\";s:22:\"stock-adjustments.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:139;a:4:{s:1:\"a\";i:140;s:1:\"b\";s:24:\"stock-adjustments.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:140;a:4:{s:1:\"a\";i:141;s:1:\"b\";s:25:\"stock-adjustments.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:141;a:4:{s:1:\"a\";i:142;s:1:\"b\";s:30:\"stock-adjustments.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:142;a:4:{s:1:\"a\";i:143;s:1:\"b\";s:24:\"stock-adjustments.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:143;a:4:{s:1:\"a\";i:144;s:1:\"b\";s:24:\"stock-adjustments.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:144;a:4:{s:1:\"a\";i:145;s:1:\"b\";s:13:\"expenses.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:145;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:15:\"expenses.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:146;a:4:{s:1:\"a\";i:147;s:1:\"b\";s:13:\"expenses.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:147;a:4:{s:1:\"a\";i:148;s:1:\"b\";s:15:\"expenses.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:148;a:4:{s:1:\"a\";i:149;s:1:\"b\";s:16:\"expenses.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:149;a:4:{s:1:\"a\";i:150;s:1:\"b\";s:21:\"expenses.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:150;a:4:{s:1:\"a\";i:151;s:1:\"b\";s:15:\"expenses.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:151;a:4:{s:1:\"a\";i:152;s:1:\"b\";s:15:\"expenses.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:152;a:4:{s:1:\"a\";i:153;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:153;a:4:{s:1:\"a\";i:154;s:1:\"b\";s:14:\"reports.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:154;a:4:{s:1:\"a\";i:155;s:1:\"b\";s:12:\"reports.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:155;a:4:{s:1:\"a\";i:156;s:1:\"b\";s:14:\"reports.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:156;a:4:{s:1:\"a\";i:157;s:1:\"b\";s:15:\"reports.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:157;a:4:{s:1:\"a\";i:158;s:1:\"b\";s:20:\"reports.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:158;a:4:{s:1:\"a\";i:159;s:1:\"b\";s:14:\"reports.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:159;a:4:{s:1:\"a\";i:160;s:1:\"b\";s:14:\"reports.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:160;a:4:{s:1:\"a\";i:161;s:1:\"b\";s:14:\"companies.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:161;a:4:{s:1:\"a\";i:162;s:1:\"b\";s:16:\"companies.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:162;a:4:{s:1:\"a\";i:163;s:1:\"b\";s:14:\"companies.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:163;a:4:{s:1:\"a\";i:164;s:1:\"b\";s:16:\"companies.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:164;a:4:{s:1:\"a\";i:165;s:1:\"b\";s:17:\"companies.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:165;a:4:{s:1:\"a\";i:166;s:1:\"b\";s:22:\"companies.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:166;a:4:{s:1:\"a\";i:167;s:1:\"b\";s:16:\"companies.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:167;a:4:{s:1:\"a\";i:168;s:1:\"b\";s:16:\"companies.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:168;a:4:{s:1:\"a\";i:169;s:1:\"b\";s:12:\"backups.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:169;a:4:{s:1:\"a\";i:170;s:1:\"b\";s:14:\"backups.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:170;a:4:{s:1:\"a\";i:171;s:1:\"b\";s:12:\"backups.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:171;a:4:{s:1:\"a\";i:172;s:1:\"b\";s:14:\"backups.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:172;a:4:{s:1:\"a\";i:173;s:1:\"b\";s:15:\"backups.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:173;a:4:{s:1:\"a\";i:174;s:1:\"b\";s:20:\"backups.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:174;a:4:{s:1:\"a\";i:175;s:1:\"b\";s:14:\"backups.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:175;a:4:{s:1:\"a\";i:176;s:1:\"b\";s:14:\"backups.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:176;a:4:{s:1:\"a\";i:177;s:1:\"b\";s:23:\"medicines.print-barcode\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:177;a:4:{s:1:\"a\";i:178;s:1:\"b\";s:23:\"medicines.stock-history\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:6;}}i:178;a:4:{s:1:\"a\";i:179;s:1:\"b\";s:26:\"medicines.purchase-history\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:179;a:4:{s:1:\"a\";i:180;s:1:\"b\";s:23:\"medicines.sales-history\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:180;a:4:{s:1:\"a\";i:181;s:1:\"b\";s:22:\"medicines.adjust-stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:181;a:4:{s:1:\"a\";i:182;s:1:\"b\";s:25:\"medicines.view-cost-price\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:182;a:4:{s:1:\"a\";i:183;s:1:\"b\";s:19:\"medicines.duplicate\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:183;a:4:{s:1:\"a\";i:184;s:1:\"b\";s:12:\"sales.cancel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:184;a:4:{s:1:\"a\";i:185;s:1:\"b\";s:14:\"sales.complete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:185;a:4:{s:1:\"a\";i:186;s:1:\"b\";s:20:\"sales.update-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:186;a:4:{s:1:\"a\";i:187;s:1:\"b\";s:19:\"sales.print-invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:187;a:4:{s:1:\"a\";i:188;s:1:\"b\";s:18:\"sales.download-pdf\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:188;a:4:{s:1:\"a\";i:189;s:1:\"b\";s:16:\"sales.send-email\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:189;a:4:{s:1:\"a\";i:190;s:1:\"b\";s:18:\"sales.view-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:190;a:4:{s:1:\"a\";i:191;s:1:\"b\";s:15:\"sales.duplicate\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:191;a:4:{s:1:\"a\";i:192;s:1:\"b\";s:22:\"sales.convert-to-quote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:192;a:4:{s:1:\"a\";i:193;s:1:\"b\";s:17:\"sales.view-profit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:193;a:4:{s:1:\"a\";i:194;s:1:\"b\";s:12:\"sales.return\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:194;a:4:{s:1:\"a\";i:195;s:1:\"b\";s:17:\"sales.bulk-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:195;a:4:{s:1:\"a\";i:196;s:1:\"b\";s:17:\"sales.bulk-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:196;a:4:{s:1:\"a\";i:197;s:1:\"b\";s:21:\"sale-returns.complete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:197;a:4:{s:1:\"a\";i:198;s:1:\"b\";s:19:\"sale-returns.cancel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:198;a:4:{s:1:\"a\";i:199;s:1:\"b\";s:17:\"users.assign-role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:199;a:4:{s:1:\"a\";i:200;s:1:\"b\";s:17:\"users.remove-role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:200;a:4:{s:1:\"a\";i:201;s:1:\"b\";s:23:\"users.assign-permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:201;a:4:{s:1:\"a\";i:202;s:1:\"b\";s:17:\"purchases.receive\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:202;a:4:{s:1:\"a\";i:203;s:1:\"b\";s:16:\"purchases.return\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:203;a:4:{s:1:\"a\";i:204;s:1:\"b\";s:21:\"purchases.print-order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:204;a:4:{s:1:\"a\";i:205;s:1:\"b\";s:22:\"purchases.download-pdf\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:205;a:4:{s:1:\"a\";i:206;s:1:\"b\";s:21:\"purchases.view-profit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:206;a:4:{s:1:\"a\";i:207;s:1:\"b\";s:19:\"purchases.duplicate\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:207;a:4:{s:1:\"a\";i:208;s:1:\"b\";s:21:\"purchases.bulk-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:208;a:4:{s:1:\"a\";i:209;s:1:\"b\";s:21:\"purchases.bulk-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:209;a:4:{s:1:\"a\";i:210;s:1:\"b\";s:20:\"customers.view-sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:210;a:4:{s:1:\"a\";i:211;s:1:\"b\";s:23:\"customers.view-payments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:211;a:4:{s:1:\"a\";i:212;s:1:\"b\";s:22:\"customers.view-returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:212;a:4:{s:1:\"a\";i:213;s:1:\"b\";s:19:\"customers.duplicate\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:213;a:4:{s:1:\"a\";i:214;s:1:\"b\";s:21:\"customers.bulk-import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:214;a:4:{s:1:\"a\";i:215;s:1:\"b\";s:21:\"customers.bulk-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:215;a:4:{s:1:\"a\";i:216;s:1:\"b\";s:20:\"customers.send-email\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:216;a:4:{s:1:\"a\";i:217;s:1:\"b\";s:18:\"customers.send-sms\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:217;a:4:{s:1:\"a\";i:218;s:1:\"b\";s:24:\"suppliers.view-purchases\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:218;a:4:{s:1:\"a\";i:219;s:1:\"b\";s:23:\"suppliers.view-payments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:219;a:4:{s:1:\"a\";i:220;s:1:\"b\";s:19:\"suppliers.duplicate\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:220;a:4:{s:1:\"a\";i:221;s:1:\"b\";s:21:\"suppliers.bulk-import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:221;a:4:{s:1:\"a\";i:222;s:1:\"b\";s:21:\"suppliers.bulk-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:222;a:4:{s:1:\"a\";i:223;s:1:\"b\";s:20:\"suppliers.send-email\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:223;a:4:{s:1:\"a\";i:224;s:1:\"b\";s:18:\"suppliers.send-sms\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:224;a:4:{s:1:\"a\";i:225;s:1:\"b\";s:20:\"medicines.view-stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:225;a:4:{s:1:\"a\";i:226;s:1:\"b\";s:21:\"medicines.view-expiry\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:226;a:4:{s:1:\"a\";i:227;s:1:\"b\";s:21:\"medicines.bulk-import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:227;a:4:{s:1:\"a\";i:228;s:1:\"b\";s:21:\"medicines.bulk-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:228;a:4:{s:1:\"a\";i:229;s:1:\"b\";s:22:\"medicines.update-price\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:229;a:4:{s:1:\"a\";i:230;s:1:\"b\";s:21:\"medicines.update-cost\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:230;a:4:{s:1:\"a\";i:231;s:1:\"b\";s:17:\"medicines.reorder\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:231;a:4:{s:1:\"a\";i:232;s:1:\"b\";s:22:\"medicines.view-reorder\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:232;a:4:{s:1:\"a\";i:233;s:1:\"b\";s:19:\"reports.sales-daily\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:233;a:4:{s:1:\"a\";i:234;s:1:\"b\";s:21:\"reports.sales-monthly\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:234;a:4:{s:1:\"a\";i:235;s:1:\"b\";s:20:\"reports.sales-yearly\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:235;a:4:{s:1:\"a\";i:236;s:1:\"b\";s:23:\"reports.purchases-daily\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:236;a:4:{s:1:\"a\";i:237;s:1:\"b\";s:25:\"reports.purchases-monthly\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:237;a:4:{s:1:\"a\";i:238;s:1:\"b\";s:20:\"reports.stock-report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:238;a:4:{s:1:\"a\";i:239;s:1:\"b\";s:19:\"reports.profit-loss\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:239;a:4:{s:1:\"a\";i:240;s:1:\"b\";s:18:\"reports.tax-report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:240;a:4:{s:1:\"a\";i:241;s:1:\"b\";s:23:\"reports.customer-report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:241;a:4:{s:1:\"a\";i:242;s:1:\"b\";s:23:\"reports.supplier-report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:242;a:4:{s:1:\"a\";i:243;s:1:\"b\";s:20:\"reports.export-excel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:243;a:4:{s:1:\"a\";i:244;s:1:\"b\";s:18:\"reports.export-pdf\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:244;a:4:{s:1:\"a\";i:245;s:1:\"b\";s:18:\"reports.export-csv\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:245;a:4:{s:1:\"a\";i:246;s:1:\"b\";s:13:\"stocks.adjust\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:246;a:4:{s:1:\"a\";i:247;s:1:\"b\";s:15:\"stocks.transfer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:247;a:4:{s:1:\"a\";i:248;s:1:\"b\";s:12:\"stocks.audit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:248;a:4:{s:1:\"a\";i:249;s:1:\"b\";s:12:\"stocks.count\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:249;a:4:{s:1:\"a\";i:250;s:1:\"b\";s:14:\"stocks.history\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:250;a:4:{s:1:\"a\";i:251;s:1:\"b\";s:13:\"stocks.report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:251;a:4:{s:1:\"a\";i:252;s:1:\"b\";s:16:\"expenses.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:252;a:4:{s:1:\"a\";i:253;s:1:\"b\";s:15:\"expenses.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:253;a:4:{s:1:\"a\";i:254;s:1:\"b\";s:20:\"expenses.bulk-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:254;a:4:{s:1:\"a\";i:255;s:1:\"b\";s:20:\"expenses.bulk-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:255;a:4:{s:1:\"a\";i:256;s:1:\"b\";s:20:\"expenses.view-report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:256;a:4:{s:1:\"a\";i:257;s:1:\"b\";s:18:\"purchases.complete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:257;a:4:{s:1:\"a\";i:258;s:1:\"b\";s:24:\"purchases.update-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:258;a:4:{s:1:\"a\";i:259;s:1:\"b\";s:16:\"purchases.cancel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:259;a:4:{s:1:\"a\";i:260;s:1:\"b\";s:26:\"stock-adjustments.complete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:260;a:4:{s:1:\"a\";i:261;s:1:\"b\";s:11:\"expiry.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:261;a:4:{s:1:\"a\";i:262;s:1:\"b\";s:8:\"pos.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:262;a:4:{s:1:\"a\";i:263;s:1:\"b\";s:10:\"pos.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:263;a:4:{s:1:\"a\";i:264;s:1:\"b\";s:12:\"pos.checkout\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:264;a:4:{s:1:\"a\";i:265;s:1:\"b\";s:10:\"pos.cancel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:265;a:4:{s:1:\"a\";i:266;s:1:\"b\";s:13:\"payments.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:266;a:4:{s:1:\"a\";i:267;s:1:\"b\";s:15:\"payments.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:267;a:4:{s:1:\"a\";i:268;s:1:\"b\";s:13:\"payments.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:268;a:4:{s:1:\"a\";i:269;s:1:\"b\";s:15:\"payments.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:269;a:4:{s:1:\"a\";i:270;s:1:\"b\";s:16:\"payments.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:270;a:4:{s:1:\"a\";i:271;s:1:\"b\";s:21:\"payments.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:271;a:4:{s:1:\"a\";i:272;s:1:\"b\";s:15:\"payments.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:272;a:4:{s:1:\"a\";i:273;s:1:\"b\";s:17:\"stock-ledger.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:273;a:4:{s:1:\"a\";i:274;s:1:\"b\";s:22:\"inventory-reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:274;a:4:{s:1:\"a\";i:275;s:1:\"b\";s:18:\"sales-reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:275;a:4:{s:1:\"a\";i:276;s:1:\"b\";s:21:\"purchase-reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:276;a:4:{s:1:\"a\";i:277;s:1:\"b\";s:22:\"financial-reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:277;a:4:{s:1:\"a\";i:278;s:1:\"b\";s:23:\"expense-categories.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:278;a:4:{s:1:\"a\";i:279;s:1:\"b\";s:25:\"expense-categories.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:279;a:4:{s:1:\"a\";i:280;s:1:\"b\";s:23:\"expense-categories.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:280;a:4:{s:1:\"a\";i:281;s:1:\"b\";s:25:\"expense-categories.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:281;a:4:{s:1:\"a\";i:282;s:1:\"b\";s:26:\"expense-categories.restore\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:282;a:4:{s:1:\"a\";i:283;s:1:\"b\";s:31:\"expense-categories.force-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:283;a:4:{s:1:\"a\";i:284;s:1:\"b\";s:16:\"profit-loss.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:284;a:3:{s:1:\"a\";i:285;s:1:\"b\";s:16:\"backups.download\";s:1:\"c\";s:3:\"web\";}}s:5:\"roles\";a:6:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:7:\"Manager\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"Pharmacist\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:7:\"Cashier\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"Store Keeper\";s:1:\"c\";s:3:\"web\";}}}',1788274047);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `free_quantity` int NOT NULL DEFAULT '0',
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_id_medicine_id_unique` (`cart_id`,`medicine_id`),
  KEY `cart_items_cart_id_index` (`cart_id`),
  KEY `cart_items_medicine_id_index` (`medicine_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (1,1,16,3,0,454.43,697.75,0.00,0.00,2093.25,'2026-08-20 22:53:39','2026-08-20 22:54:03'),(2,1,36,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-20 22:53:42','2026-08-20 22:53:42'),(3,1,18,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-20 22:53:44','2026-08-20 22:53:44'),(4,1,25,4,0,314.75,338.54,0.00,0.00,1354.16,'2026-08-20 22:53:48','2026-08-20 22:53:53'),(8,2,20,1,0,808.79,906.72,0.00,0.00,906.72,'2026-08-20 23:39:58','2026-08-20 23:39:58'),(9,2,19,3,0,819.40,960.40,0.00,0.00,2881.20,'2026-08-20 23:39:59','2026-08-20 23:40:11'),(10,3,16,18,0,454.43,697.75,0.00,0.00,12559.50,'2026-08-20 23:42:15','2026-08-20 23:42:27'),(12,4,18,9,0,280.24,447.81,0.00,0.00,4030.29,'2026-08-21 00:49:01','2026-08-21 00:49:09'),(13,5,27,1,0,908.72,1068.80,0.00,0.00,1068.80,'2026-08-21 00:49:44','2026-08-21 00:49:44'),(14,6,42,2,0,620.68,636.33,0.00,0.00,1272.66,'2026-08-21 01:27:56','2026-08-21 01:39:52'),(15,6,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 01:39:51','2026-08-21 01:39:51'),(16,6,5,1,0,723.83,853.97,0.00,0.00,853.97,'2026-08-21 01:39:53','2026-08-21 01:39:53'),(17,6,12,1,0,779.97,1064.84,0.00,0.00,1064.84,'2026-08-21 01:39:54','2026-08-21 01:39:54'),(18,6,47,1,0,100.96,264.43,0.00,0.00,264.43,'2026-08-21 01:39:55','2026-08-21 01:39:55'),(19,6,28,1,0,963.13,1213.78,0.00,0.00,1213.78,'2026-08-21 01:39:55','2026-08-21 01:39:55'),(20,7,23,1,0,754.12,972.29,0.00,0.00,972.29,'2026-08-21 02:19:14','2026-08-21 02:19:14'),(22,8,41,4,0,803.70,1096.52,0.00,0.00,4386.08,'2026-08-21 02:33:07','2026-08-21 02:34:14'),(23,8,22,2,0,588.36,671.97,0.00,0.00,1343.94,'2026-08-21 02:33:56','2026-08-21 02:34:15'),(24,8,37,2,0,219.41,352.26,0.00,0.00,704.52,'2026-08-21 02:34:01','2026-08-21 02:34:15'),(25,8,5,2,0,723.83,853.97,0.00,0.00,1707.94,'2026-08-21 02:34:07','2026-08-21 02:34:17'),(26,8,16,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-21 02:34:09','2026-08-21 02:34:09'),(27,8,25,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-21 02:34:12','2026-08-21 02:34:12'),(28,8,42,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-21 02:34:16','2026-08-21 02:34:16'),(29,9,25,4,0,314.75,338.54,0.00,0.00,1354.16,'2026-08-21 02:35:49','2026-08-21 02:57:10'),(30,9,41,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-21 02:36:12','2026-08-21 02:36:12'),(31,9,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 02:36:17','2026-08-21 02:36:17'),(33,9,22,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-21 02:50:57','2026-08-21 02:50:57'),(38,10,22,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-21 05:22:34','2026-08-21 05:22:34'),(39,11,18,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-21 09:56:49','2026-08-21 09:56:49'),(40,11,36,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-21 09:56:51','2026-08-21 09:56:51'),(41,11,16,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-21 09:56:53','2026-08-21 09:56:53'),(42,11,25,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-21 09:56:54','2026-08-21 09:56:54'),(43,11,41,2,0,803.70,1096.52,0.00,0.00,2193.04,'2026-08-21 09:56:55','2026-08-21 09:56:56'),(44,12,23,1,0,754.12,972.29,0.00,0.00,972.29,'2026-08-21 10:07:59','2026-08-21 10:07:59'),(45,12,11,5,0,279.76,391.85,0.00,0.00,1959.25,'2026-08-21 10:08:00','2026-08-21 10:08:09'),(47,13,36,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-21 10:13:11','2026-08-21 10:13:11'),(49,13,25,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-21 10:13:22','2026-08-21 10:13:22'),(50,14,18,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-21 12:58:02','2026-08-21 12:58:02'),(51,14,36,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-21 12:58:03','2026-08-21 12:58:03'),(52,14,16,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-21 12:58:04','2026-08-21 12:58:04'),(53,15,25,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-21 12:58:52','2026-08-21 12:58:52'),(54,15,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 12:58:54','2026-08-21 12:58:54'),(55,15,22,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-21 12:58:55','2026-08-21 12:58:55'),(56,16,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 13:12:07','2026-08-21 13:12:07'),(57,16,42,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-21 13:12:08','2026-08-21 13:12:08'),(58,16,5,1,0,723.83,853.97,0.00,0.00,853.97,'2026-08-21 13:12:09','2026-08-21 13:12:09'),(59,16,12,1,0,779.97,1064.84,0.00,0.00,1064.84,'2026-08-21 13:12:10','2026-08-21 13:12:10'),(60,17,16,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-21 13:41:51','2026-08-21 13:41:51'),(61,17,42,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-21 13:41:52','2026-08-21 13:41:52'),(62,17,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 13:41:53','2026-08-21 13:41:53'),(63,18,18,2,0,280.24,447.81,0.00,0.00,895.62,'2026-08-22 00:00:38','2026-08-22 00:00:44'),(64,18,36,2,0,549.22,763.72,0.00,0.00,1527.44,'2026-08-22 00:00:39','2026-08-22 00:00:43'),(65,18,16,2,0,454.43,697.75,0.00,0.00,1395.50,'2026-08-22 00:00:40','2026-08-22 00:00:42'),(66,18,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-22 00:00:41','2026-08-22 00:00:41'),(67,18,25,7,0,314.75,338.54,0.00,0.00,2369.78,'2026-08-22 00:00:42','2026-08-22 00:01:12'),(68,19,41,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 00:16:52','2026-08-22 00:16:52'),(69,20,41,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 00:48:56','2026-08-22 00:48:56'),(70,20,25,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-22 00:48:57','2026-08-22 00:48:57'),(71,21,41,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 00:49:29','2026-08-22 00:49:29'),(72,22,18,3,0,280.24,447.81,0.00,0.00,1343.43,'2026-08-22 01:26:07','2026-08-22 01:26:12'),(73,22,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-22 01:26:15','2026-08-22 01:26:15'),(74,22,17,1,0,33.91,103.38,0.00,0.00,103.38,'2026-08-22 01:26:16','2026-08-22 01:26:16'),(75,22,28,1,0,963.13,1213.78,0.00,0.00,1213.78,'2026-08-22 01:26:16','2026-08-22 01:26:16'),(76,22,47,1,0,100.96,264.43,0.00,0.00,264.43,'2026-08-22 01:26:17','2026-08-22 01:26:17'),(77,22,12,1,0,779.97,1064.84,0.00,0.00,1064.84,'2026-08-22 01:26:27','2026-08-22 01:26:27'),(78,22,41,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 01:26:31','2026-08-22 01:26:31'),(79,22,30,1,0,514.78,619.17,0.00,0.00,619.17,'2026-08-22 01:26:35','2026-08-22 01:26:35'),(81,23,18,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-22 14:17:16','2026-08-22 14:17:16'),(82,23,36,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-22 14:17:18','2026-08-22 14:17:18'),(83,23,5,5,0,723.83,853.97,0.00,0.00,4269.85,'2026-08-22 14:17:18','2026-08-22 14:17:30'),(84,23,42,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-22 14:17:19','2026-08-22 14:17:19'),(85,23,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-22 14:17:20','2026-08-22 14:17:20'),(86,23,22,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-22 14:17:21','2026-08-22 14:17:21'),(87,24,18,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-25 12:44:48','2026-08-25 12:44:48'),(88,24,36,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-25 12:44:53','2026-08-25 12:45:12'),(90,24,25,2,0,314.75,338.54,0.00,0.00,677.08,'2026-08-25 12:44:55','2026-08-25 12:45:10'),(91,24,37,6,0,219.41,352.26,0.00,0.00,2113.56,'2026-08-25 12:44:56','2026-08-25 12:45:07'),(92,25,22,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-25 12:46:01','2026-08-25 12:46:01'),(93,25,37,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-25 12:46:02','2026-08-25 12:46:02'),(94,26,18,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-28 12:40:01','2026-08-28 12:40:01'),(95,26,25,2,0,314.75,338.54,0.00,0.00,677.08,'2026-08-28 12:47:08','2026-08-28 12:47:47'),(96,27,16,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-28 13:01:22','2026-08-28 13:01:22'),(97,28,16,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-29 09:39:30','2026-08-29 09:39:30'),(98,28,25,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-29 09:39:32','2026-08-29 09:39:32');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `status` enum('active','held','checked_out','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(15,2) NOT NULL DEFAULT '0.00',
  `other_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_index` (`user_id`),
  KEY `carts_customer_id_index` (`customer_id`),
  KEY `carts_status_index` (`status`),
  CONSTRAINT `carts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,1,21,'checked_out',4658.94,400.00,0.00,0.00,0.00,4258.94,4258.94,0.00,NULL,'2026-08-20 21:52:54','2026-08-20 22:58:04'),(2,1,NULL,'cancelled',3787.92,0.00,0.00,0.00,0.00,3787.92,0.00,3787.92,NULL,'2026-08-20 22:59:05','2026-08-20 23:42:04'),(3,1,NULL,'cancelled',12559.50,0.00,0.00,0.00,0.00,12559.50,0.00,12559.50,NULL,'2026-08-20 23:42:05','2026-08-20 23:42:33'),(4,1,NULL,'cancelled',4030.29,0.00,0.00,0.00,0.00,4030.29,0.00,4030.29,NULL,'2026-08-20 23:42:34','2026-08-21 00:49:16'),(5,1,NULL,'checked_out',1068.80,56.00,0.00,0.00,0.00,1012.80,1012.80,0.00,NULL,'2026-08-21 00:49:16','2026-08-21 00:51:05'),(6,1,NULL,'cancelled',5021.94,0.00,0.00,0.00,0.00,5021.94,0.00,5021.94,NULL,'2026-08-21 00:54:39','2026-08-21 02:11:19'),(7,1,NULL,'checked_out',972.29,0.00,0.00,0.00,0.00,972.29,972.29,0.00,NULL,'2026-08-21 02:11:20','2026-08-21 02:30:29'),(8,1,NULL,'cancelled',9815.10,0.00,0.00,0.00,0.00,9815.10,0.00,9815.10,NULL,'2026-08-21 02:30:30','2026-08-21 02:34:25'),(9,1,NULL,'cancelled',3474.91,0.00,0.00,0.00,0.00,3474.91,0.00,3474.91,NULL,'2026-08-21 02:34:25','2026-08-21 02:58:30'),(10,1,NULL,'cancelled',671.97,76.00,0.00,0.00,0.00,595.97,0.00,595.97,NULL,'2026-08-21 02:58:30','2026-08-21 05:22:53'),(11,1,NULL,'cancelled',4440.86,0.00,0.00,0.00,0.00,4440.86,0.00,4440.86,NULL,'2026-08-21 05:22:53','2026-08-21 09:57:50'),(12,1,NULL,'cancelled',2931.54,0.00,0.00,0.00,0.00,2931.54,0.00,2931.54,NULL,'2026-08-21 09:57:50','2026-08-21 10:09:49'),(13,1,NULL,'cancelled',1102.26,0.00,0.00,0.00,0.00,1102.26,0.00,1102.26,NULL,'2026-08-21 10:09:50','2026-08-21 10:13:47'),(14,1,NULL,'checked_out',1909.28,0.00,0.00,0.00,0.00,1909.28,1909.28,0.00,NULL,'2026-08-21 10:13:47','2026-08-21 12:58:09'),(15,1,NULL,'checked_out',1362.77,0.00,0.00,0.00,0.00,1362.77,0.00,1362.77,NULL,'2026-08-21 12:58:10','2026-08-21 12:59:12'),(16,1,62,'checked_out',2907.40,0.00,0.00,0.00,0.00,2907.40,1500.00,1407.40,NULL,'2026-08-21 12:59:13','2026-08-21 13:12:40'),(17,1,49,'checked_out',1686.34,0.00,0.00,0.00,0.00,1686.34,0.00,1686.34,NULL,'2026-08-21 13:12:41','2026-08-21 13:42:09'),(18,1,NULL,'checked_out',6540.60,0.00,0.00,0.00,0.00,6540.60,6540.60,0.00,NULL,'2026-08-21 13:42:10','2026-08-22 00:01:36'),(19,1,NULL,'checked_out',1096.52,0.00,0.00,0.00,0.00,1096.52,1096.52,0.00,NULL,'2026-08-22 00:01:37','2026-08-22 00:44:11'),(20,1,NULL,'checked_out',1435.06,0.00,0.00,0.00,0.00,1435.06,143.00,1292.06,NULL,'2026-08-22 00:44:13','2026-08-22 00:49:09'),(21,1,NULL,'checked_out',1096.52,0.00,0.00,0.00,0.00,1096.52,0.00,1096.52,NULL,'2026-08-22 00:49:10','2026-08-22 00:49:36'),(22,1,16,'checked_out',6057.81,300.00,0.00,0.00,0.00,5757.81,5757.81,0.00,NULL,'2026-08-22 00:49:37','2026-08-22 01:28:03'),(23,1,NULL,'checked_out',7141.94,0.00,0.00,0.00,0.00,7141.94,7141.94,0.00,NULL,'2026-08-22 01:28:04','2026-08-22 14:17:48'),(24,1,NULL,'checked_out',4002.17,300.00,0.00,0.00,0.00,3702.17,3702.17,0.00,NULL,'2026-08-22 14:17:49','2026-08-25 12:45:51'),(25,1,NULL,'cancelled',1024.23,0.00,0.00,0.00,0.00,1024.23,0.00,1024.23,NULL,'2026-08-25 12:45:53','2026-08-25 12:46:05'),(26,1,NULL,'checked_out',1124.89,0.00,0.00,0.00,0.00,1124.89,1124.89,0.00,NULL,'2026-08-25 12:46:06','2026-08-28 12:54:26'),(27,1,NULL,'checked_out',697.75,0.00,0.00,0.00,0.00,697.75,697.75,0.00,NULL,'2026-08-28 12:55:12','2026-08-28 13:01:28'),(28,1,NULL,'checked_out',1036.29,0.00,0.00,0.00,0.00,1036.29,1036.29,0.00,NULL,'2026-08-28 13:01:29','2026-08-29 09:39:37'),(29,1,NULL,'active',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,NULL,'2026-08-29 09:39:38','2026-08-29 09:39:38');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pakistan',
  `customer_type` enum('credit','regular','walk_in','corporate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `credit_limit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_type` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'debit',
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allergies` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`),
  KEY `customers_created_by_foreign` (`created_by`),
  KEY `customers_updated_by_foreign` (`updated_by`),
  KEY `customers_deleted_by_foreign` (`deleted_by`),
  KEY `customers_phone_index` (`phone`),
  CONSTRAINT `customers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customers_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Kassandra Schmeler','03655500874','doyle01@example.com','male','2000-01-01','4364 Jacky Isle','Islamabad','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','O-',NULL,'Rem soluta qui et.',2,NULL,NULL,'2026-08-16 09:06:21','2026-08-16 09:06:21',NULL),(2,'Ms. Viviane Kautzer II','03973356881','geovany.turner@example.net','male','2000-01-01','9511 Jast Roads Suite 277','Islamabad','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','AB+','Ea perspiciatis placeat dignissimos totam voluptate alias facere.',NULL,5,NULL,NULL,'2026-08-16 09:06:21','2026-08-16 09:06:21',NULL),(3,'Kyler Fay','03491452288','homenick.dejah@example.net','male','2000-01-01','846 Bode Crossing Suite 190','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','O-','Reprehenderit dolores facilis totam libero.',NULL,4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(4,'Myah Stokes','03505505333','bednar.vena@example.net','male','2000-01-01','4771 Aurore Track','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','A+','Veritatis at magni laboriosam quisquam cum incidunt.',NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(5,'Dr. Mekhi Hoeger','03010798840','lenore.veum@example.org','female','2000-01-01','4059 Wuckert Lodge Suite 924','Lahore','Pakistan','Pakistan','regular',60024.87,13632.63,'credit','O-',NULL,NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(6,'Timothy Ortiz','03385907040','jamar.runolfsdottir@example.com','female','2000-01-01','3435 Lang Tunnel','Peshawar','Pakistan','Pakistan','credit',0.00,0.00,'debit','A+',NULL,NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(7,'Cynthia Runolfsdottir','03895902632','vhegmann@example.net','male','2000-01-01','1676 Sanford Lights Apt. 322','Swat','Pakistan','Pakistan','corporate',0.00,0.00,'debit','A+','Officia libero assumenda ratione velit est aut error.','Voluptatem mollitia et quibusdam a.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(8,'Dr. Chaim Hegmann PhD','03512770435','klocko.dante@example.com','male','2000-01-01','1197 Vivien Trafficway','Karachi','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','B+','Quia modi veniam est quaerat consequuntur dignissimos quisquam.','Ipsam ipsum qui necessitatibus architecto praesentium magni.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(9,'Guillermo Ebert','03917310621','spencer.bettye@example.com','male','2000-01-01','192 Bridie Ways Apt. 205','Lahore','Pakistan','Pakistan','corporate',0.00,0.00,'debit','AB+','Asperiores ut dolores laborum.','Tempora optio quis placeat amet ut qui nemo.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(10,'Sharon Kiehn DDS','03022868130','sstanton@example.net','female','2000-01-01','1864 Sienna Rest Suite 467','Swat','Pakistan','Pakistan','regular',0.00,0.00,'debit','O+',NULL,NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(11,'Josefina Graham','03487252822','ottis17@example.net','male','2000-01-01','16087 Rosalinda Fields Suite 878','Swat','Pakistan','Pakistan','regular',21261.12,16265.23,'credit','AB+','Amet dolore laudantium quos minima quo.','Maiores dolor non officiis.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(12,'Kailee Steuber PhD','03897943783','justina.hegmann@example.com','male','2000-01-01','8678 Mariam Point Apt. 665','Islamabad','Pakistan','Pakistan','walk_in',9498.65,1764.94,'credit','O-',NULL,NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(13,'Ethel Bruen','03931029091','casimer83@example.org','female','2000-01-01','668 Luisa Meadows Suite 381','Swat','Pakistan','Pakistan','credit',0.00,0.00,'debit','AB+','Molestias totam eveniet aut mollitia odio eos.',NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(14,'Dante Rodriguez','03477979539','zulauf.berenice@example.com','female','2000-01-01','44949 O\'Connell Corner Apt. 051','Swat','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','A+',NULL,'Tenetur dolor animi tenetur voluptatibus beatae ut minima.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(15,'Fermin Leffler','03783764026','barney.bernhard@example.org','male','2000-01-01','9445 Reichel Divide','Karachi','Pakistan','Pakistan','corporate',44412.12,7400.08,'credit','O+',NULL,'Delectus facilis sed deserunt sed.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(16,'Brook Kozey II','03231007492','isabelle04@example.com','female','2000-01-01','96050 O\'Hara Meadow','Swat','Pakistan','Pakistan','credit',0.00,0.00,'debit','AB+','Maxime eaque ducimus sunt libero et.',NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(17,'Nelle Fisher','03824787275','joaquin90@example.net','male','2000-01-01','928 King Pines Suite 612','Islamabad','Pakistan','Pakistan','corporate',0.00,0.00,'debit','B+','Praesentium laborum qui eos nihil nam laudantium vitae.',NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(18,'Marcelino Bartell','03622916368','joan.turcotte@example.org','male','2000-01-01','697 Kerluke Trail Apt. 372','Peshawar','Pakistan','Pakistan','credit',0.00,0.00,'debit','O-','Accusantium illum sit consectetur nulla deleniti ut.',NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(19,'Alessandro Gibson','03915303985','penelope.veum@example.org','male','2000-01-01','7566 Gerda Island Apt. 682','Swat','Pakistan','Pakistan','credit',0.00,0.00,'debit','O+',NULL,'Veritatis qui quo dicta inventore nihil perspiciatis est eos.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(20,'Crawford Wyman','03223509260','fabian16@example.com','male','2000-01-01','8047 Eileen Parkway','Karachi','Pakistan','Pakistan','credit',51049.86,2789.02,'credit','B+',NULL,NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(21,'Brandt Schamberger','03683338168','wilfrid78@example.org','male','2000-01-01','81853 Rodrigo Loaf Apt. 001','Karachi','Pakistan','Pakistan','regular',0.00,0.00,'debit','AB+',NULL,'Voluptatem aut quidem animi dolorum est nam odio.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(22,'Deon Krajcik','03479071812','kutch.leta@example.com','female','2000-01-01','9667 Howe Mill Suite 523','Peshawar','Pakistan','Pakistan','regular',0.00,0.00,'debit','O+','Et aut ut perspiciatis dignissimos.','Quisquam aut facilis exercitationem.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(23,'William Dibbert','03301244988','muriel23@example.net','male','2000-01-01','494 Mable Ford','Swat','Pakistan','Pakistan','walk_in',72690.14,12342.11,'credit','B+','Consectetur ex autem quam amet quis.','Quam magni dolores aliquam unde accusantium minima.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(24,'Maurice Kling','03755991838','zabernathy@example.com','female','2000-01-01','4600 Labadie Springs Apt. 046','Peshawar','Pakistan','Pakistan','regular',0.00,0.00,'debit','A+',NULL,NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(25,'Mathias Adams','03572655080','iliana05@example.org','male','2000-01-01','766 Preston Fort','Swat','Pakistan','Pakistan','corporate',0.00,0.00,'debit','B+','Sunt reprehenderit sit modi accusamus ut at perspiciatis.',NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(26,'Bryce Walter','03796820294','oreilly.sydnie@example.com','female','2000-01-01','73937 Labadie Square Apt. 269','Swat','Pakistan','Pakistan','credit',9326.60,16410.14,'credit','O+','Eos ut aliquid omnis ut odit aliquam adipisci ex.',NULL,1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(27,'Jaleel Macejkovic','03645143454','pharris@example.com','male','2000-01-01','39867 Gleichner Points Apt. 404','Karachi','Pakistan','Pakistan','regular',26276.57,15839.53,'credit','O-','Odit similique quia autem aut ratione.',NULL,1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(28,'Mr. Jamal Nicolas','03669190193','royal.franecki@example.org','male','2000-01-01','365 Anastacio Orchard','Swat','Pakistan','Pakistan','regular',0.00,0.00,'debit','AB+','Qui ut est ab temporibus repellat explicabo.','Numquam magnam quia fugiat culpa.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(29,'Dr. Heather Haag Jr.','03136988376','pasquale.rutherford@example.net','female','2000-01-01','6636 Veda Village Apt. 862','Swat','Pakistan','Pakistan','walk_in',44235.99,14768.35,'credit','B+',NULL,'Sunt cumque eos sint quos aliquid eius doloribus.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(30,'Baron Labadie','03100599906','lang.earline@example.net','male','2000-01-01','798 Terrence Light','Swat','Pakistan','Pakistan','corporate',0.00,0.00,'debit','O-',NULL,'Suscipit sequi quos sit ut.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(31,'Lia Heidenreich','03459969271','marina53@example.org','female','2000-01-01','884 Marcelo Burgs','Lahore','Pakistan','Pakistan','regular',64380.70,17017.76,'credit','AB+','Dignissimos velit quibusdam id cum et.','Quae tempora adipisci sapiente quod ea.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(32,'Issac Welch','03794839062','aufderhar.christy@example.net','male','2000-01-01','4396 Lorenza Tunnel Apt. 829','Swat','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','B+',NULL,NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(33,'Mr. Kameron Abshire','03380729907','doug.jones@example.net','female','2000-01-01','7743 Jacobson Court Suite 167','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','AB+',NULL,'Maxime rerum vitae molestias voluptatem voluptas consectetur velit.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(34,'Ethyl Wuckert','03687624975','desiree42@example.com','female','2000-01-01','21929 Mills Keys','Islamabad','Pakistan','Pakistan','credit',0.00,0.00,'debit','A+','Corrupti dolor veniam reiciendis.',NULL,4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(35,'Elsa Macejkovic','03104786840','zschumm@example.org','female','2000-01-01','250 Aufderhar Streets','Karachi','Pakistan','Pakistan','corporate',0.00,0.00,'debit','O+',NULL,NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(36,'Braulio Gottlieb','03217145911','klowe@example.org','female','2000-01-01','9434 Bashirian Stravenue Suite 971','Lahore','Pakistan','Pakistan','corporate',51801.21,16266.31,'credit','O-',NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(37,'Lambert Pollich MD','03905947933','cwisozk@example.net','male','2000-01-01','26242 Nedra Way Suite 190','Swat','Pakistan','Pakistan','regular',0.00,0.00,'debit','O+','Praesentium ut a provident vel est aliquid quisquam.',NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(38,'Josephine Farrell','03409304023','ignatius36@example.org','female','2000-01-01','39940 Dibbert Causeway','Karachi','Pakistan','Pakistan','credit',40719.39,6031.08,'credit','B+','Sit earum vero dolorum nobis.','Impedit eveniet numquam minus fuga nihil.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(39,'Prof. Matilda King','03210507310','audrey.corwin@example.com','male','2000-01-01','5536 Lori Haven','Swat','Pakistan','Pakistan','corporate',0.00,0.00,'debit','AB+',NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(40,'Lavada Farrell','03268005737','witting.elisha@example.org','male','2000-01-01','323 Isabella Mountain','Lahore','Pakistan','Pakistan','regular',0.00,0.00,'debit','B+','Molestiae non doloribus dolor in.','Sed magni temporibus nihil ducimus blanditiis aut.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(41,'Mrs. Celia Goyette V','03564313062','chanelle14@example.net','male','2000-01-01','622 Steuber Crest','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','B+','Id animi ea facere laborum eveniet.',NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(42,'Mavis Watsica','03029010200','tkertzmann@example.com','female','2000-01-01','270 Ahmed Valleys Suite 081','Peshawar','Pakistan','Pakistan','regular',14234.31,15180.80,'credit','A+',NULL,'Dignissimos vel perferendis numquam deserunt sed.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(43,'Domingo Pouros Jr.','03311236301','ruthe73@example.com','female','2000-01-01','611 Heidi Points','Lahore','Pakistan','Pakistan','walk_in',22928.49,18578.28,'credit','O+',NULL,NULL,4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(44,'Emmet Fadel','03342169666','ona.stehr@example.com','female','2000-01-01','8092 Alfredo Neck Suite 913','Islamabad','Pakistan','Pakistan','regular',50147.07,6455.02,'credit','O+','Perspiciatis odio commodi reprehenderit debitis cumque autem.','Sint officia ut quam.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(45,'Kennith Corwin','03890296177','chester05@example.net','male','2000-01-01','117 Lexi Fork Apt. 528','Karachi','Pakistan','Pakistan','regular',45868.49,10304.32,'credit','A+','Sunt id dolorem culpa quia quibusdam dolores.',NULL,3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(46,'Lilian Nitzsche','03302399310','lucie.mccullough@example.org','female','2000-01-01','5065 Gutmann Club','Swat','Pakistan','Pakistan','credit',0.00,0.00,'debit','A+','Exercitationem qui vel voluptatum.','Officia distinctio hic dolor.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(47,'Ms. Lysanne Doyle PhD','03776059292','krajcik.dalton@example.net','male','2000-01-01','169 Buddy Well Apt. 152','Swat','Pakistan','Pakistan','corporate',0.00,0.00,'debit','O+',NULL,'Autem velit aliquid dolor aliquid commodi.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(48,'Jettie Stark DVM','03893154345','okeefe.jody@example.com','female','2000-01-01','762 Jorge Fall','Swat','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','O+',NULL,'Dignissimos rerum mollitia eligendi magni necessitatibus quo eos.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(49,'Carmel Kub','03870043911','waylon29@example.org','male','2000-01-01','255 Brown Shoals','Karachi','Pakistan','Pakistan','corporate',0.00,0.00,'debit','B+','Eius nulla qui autem qui eum maxime.','Cum odit molestias velit ut maiores praesentium.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(50,'Kaya Stehr','03283879908','judy.pollich@example.com','female','2000-01-01','342 Cummerata Brook','Lahore','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','A+','Hic adipisci dolorem necessitatibus quae.',NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(51,'Mallory Lueilwitz','03084735872','trenton.waelchi@example.com','male','2000-01-01','545 Grady Wall','Peshawar','Pakistan','Pakistan','corporate',0.00,0.00,'debit','B+','Aliquam est aut eligendi fugiat recusandae itaque.','Sit facere nihil totam quibusdam officiis ratione eligendi.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(52,'Raymundo Hegmann','03327160895','mathilde61@example.com','male','2000-01-01','752 Rodriguez Coves','Peshawar','Pakistan','Pakistan','corporate',0.00,0.00,'debit','AB+',NULL,'Asperiores architecto debitis facere.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(53,'Prof. Christine Rau III','03657134285','cbeatty@example.org','female','2000-01-01','627 Kiehn Crest Suite 942','Swat','Pakistan','Pakistan','regular',0.00,0.00,'debit','A+','Placeat et ut temporibus magnam nostrum voluptas non.',NULL,4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(54,'Otho Schuster III','03068910968','dhauck@example.org','female','2000-01-01','3451 Abshire Centers Suite 000','Peshawar','Pakistan','Pakistan','credit',6704.36,8735.24,'credit','A+',NULL,NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(55,'Jeramy Thiel','03898622240','gwaelchi@example.com','male','2000-01-01','16457 Pouros Pike Suite 366','Karachi','Pakistan','Pakistan','credit',0.00,0.00,'debit','B+',NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(56,'Danyka Rowe','03317689283','terence98@example.org','male','2000-01-01','872 Nathanael Corner Suite 843','Swat','Pakistan','Pakistan','credit',0.00,0.00,'debit','O+',NULL,NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(57,'Damian Renner','03919642934','waelchi.devonte@example.net','male','2000-01-01','90619 Sienna Expressway','Peshawar','Pakistan','Pakistan','credit',0.00,0.00,'debit','O+',NULL,'Doloremque impedit sit molestias dignissimos.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(58,'Cleo Bartell','03612478105','vandervort.sven@example.net','male','2000-01-01','546 Benedict Avenue Apt. 336','Lahore','Pakistan','Pakistan','regular',52909.58,18687.93,'credit','O+','Maiores eum ut cumque eum voluptatibus.',NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(59,'Adalberto Lockman','03570766970','calista.brekke@example.org','male','2000-01-01','50965 Nikolaus Crest Apt. 001','Lahore','Pakistan','Pakistan','corporate',0.00,0.00,'debit','B+',NULL,'Reprehenderit distinctio est id molestiae.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(60,'Miss Layla Baumbach','03825658559','lwhite@example.com','male','2000-01-01','719 Gulgowski Walks','Karachi','Pakistan','Pakistan','corporate',49097.78,7571.79,'credit','AB+',NULL,'Incidunt pariatur similique explicabo architecto eos dolor qui ad.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(61,'Dr. Trenton Dickens','03487342786','ashton.maggio@example.org','female','2000-01-01','65464 Tillman Trace','Swat','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','O+',NULL,'Quia in omnis accusamus nobis.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(62,'Candice Dooley','03984753116','obuckridge@example.com','female','2000-01-01','484 Lebsack Haven Apt. 095','Peshawar','Pakistan','Pakistan','regular',11532.24,11931.79,'credit','AB+',NULL,'Beatae ducimus placeat natus non.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(63,'Mrs. Kasey Ankunding','03053494563','hartmann.janessa@example.net','female','2000-01-01','732 Dach Club','Islamabad','Pakistan','Pakistan','credit',0.00,0.00,'debit','O+','Laboriosam reprehenderit molestiae quaerat est nihil molestiae.','Iusto ipsum unde debitis reiciendis asperiores sapiente exercitationem.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(64,'Mrs. Rose O\'Conner DVM','03290385175','klein.giovanna@example.com','male','2000-01-01','258 Alanna Run','Peshawar','Pakistan','Pakistan','walk_in',60038.86,15210.35,'credit','O+','Illo dolores quis architecto consequuntur.',NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(65,'Liza Sawayn','03520942556','desiree.steuber@example.com','female','2000-01-01','3176 Genoveva Branch Suite 513','Lahore','Pakistan','Pakistan','corporate',0.00,0.00,'debit','AB+','Quia dicta qui doloremque quia.','Vero voluptatem saepe quam architecto.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(66,'Mr. Angel Crist II','03496953770','xbeier@example.com','male','2000-01-01','6447 Satterfield Canyon Suite 953','Peshawar','Pakistan','Pakistan','credit',91104.58,11609.74,'credit','AB+',NULL,'Sequi dicta vitae odit natus dicta et.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(67,'Dr. Carlee Wiza III','03981571098','barton35@example.com','male','2000-01-01','56517 Abernathy Extensions Suite 697','Swat','Pakistan','Pakistan','credit',97639.54,11105.99,'credit','B+',NULL,NULL,4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(68,'Prof. Wilton Ondricka MD','03200020352','amos68@example.net','male','2000-01-01','109 Jacobi Ridges','Karachi','Pakistan','Pakistan','corporate',90387.70,14513.26,'credit','O-',NULL,'Aut quis adipisci qui perferendis atque dolores.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(69,'Serenity Hand','03462530536','garnet12@example.org','female','2000-01-01','81769 Toy Fords','Peshawar','Pakistan','Pakistan','regular',0.00,0.00,'debit','O+',NULL,'Ex fuga qui eum velit.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(70,'Colby Beer DDS','03648569067','birdie04@example.net','male','2000-01-01','26400 Camylle Hill Suite 539','Islamabad','Pakistan','Pakistan','regular',19027.69,5550.35,'credit','B+',NULL,NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(71,'Jovan Yundt','03573130490','adolfo.schumm@example.com','male','2000-01-01','479 Stiedemann Walks','Lahore','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','A+','Rerum nihil ex ad qui laudantium.','Vel facere nobis sint autem.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(72,'Nathanael Ernser IV','03256313412','lschimmel@example.com','male','2000-01-01','179 Lelah Drives Apt. 856','Peshawar','Pakistan','Pakistan','regular',0.00,0.00,'debit','A+','Ut quasi quibusdam amet iste amet maxime tempore.',NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(73,'Sydnie McLaughlin','03793313408','abigail82@example.net','female','2000-01-01','2061 Hassie Pass Apt. 945','Islamabad','Pakistan','Pakistan','walk_in',74071.72,18098.55,'credit','O-',NULL,'Non maxime facilis eos dolorem voluptas voluptates at facere.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(74,'Miss Margret Johnston','03430671414','zkuhlman@example.org','female','2000-01-01','87050 Wyman Tunnel Suite 488','Lahore','Pakistan','Pakistan','credit',0.00,0.00,'debit','A+',NULL,NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(75,'Germaine Fay','03245062599','emerald67@example.org','male','2000-01-01','42813 Ericka Circle','Peshawar','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','AB+','Voluptatem minima recusandae voluptas praesentium alias dicta necessitatibus.','Doloribus at quidem architecto perferendis voluptatem voluptatem.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(76,'Florence Volkman','03994949932','daniel.allen@example.org','female','2000-01-01','50600 Grace Camp Apt. 755','Peshawar','Pakistan','Pakistan','regular',0.00,0.00,'debit','O+',NULL,'Id rem impedit voluptate id ratione molestias ut.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(77,'Dr. Misty Cassin I','03763977092','runte.malika@example.org','male','2000-01-01','82530 Stiedemann Walks Suite 595','Peshawar','Pakistan','Pakistan','corporate',65994.90,5545.99,'credit','AB+','Labore impedit id perspiciatis repellendus.','Delectus sunt laborum ipsam velit eos.',1,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(78,'Prof. Diamond Hammes V','03392943226','linnie05@example.org','male','2000-01-01','7818 Santino Crescent','Peshawar','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','O+','Hic numquam omnis optio et eveniet ea.',NULL,5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(79,'Mrs. Molly Feil Sr.','03721162371','kerluke.kassandra@example.com','male','2000-01-01','87809 Murphy Manor Suite 970','Karachi','Pakistan','Pakistan','credit',22935.34,15223.77,'credit','O-','Aut alias dolores ab perferendis temporibus.','Veniam eos laudantium earum dignissimos.',4,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(80,'Syble Crist V','03458542456','shalvorson@example.org','female','2000-01-01','694 Green Fords','Karachi','Pakistan','Pakistan','regular',0.00,0.00,'debit','O-',NULL,'Fuga aut veritatis assumenda voluptatem necessitatibus ut.',2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(81,'Icie Kohler','03683915600','rosenbaum.brannon@example.net','male','2000-01-01','31807 Kris Garden','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','B+',NULL,NULL,2,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(82,'Emmett Jaskolski DVM','03939840029','jaeden.mosciski@example.com','male','2000-01-01','5400 Fleta Inlet Suite 919','Swat','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','B+','Hic id voluptatum molestiae est dolores dolor sed sed.','Aut sed velit quia enim deleniti a vero.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(83,'Mrs. Katrina Shanahan','03335608193','ohoppe@example.org','male','2000-01-01','274 Eldora Keys Apt. 467','Karachi','Pakistan','Pakistan','walk_in',92085.98,1436.38,'credit','AB+',NULL,'Commodi perferendis autem eligendi minima sit nostrum aut.',3,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(84,'Emmitt Pfeffer Jr.','03201483285','laurianne78@example.org','male','2000-01-01','1329 Dach Cove Apt. 536','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','B+','Facilis laborum alias enim enim soluta voluptas.','Possimus et error illo qui pariatur.',5,NULL,NULL,'2026-08-16 09:06:22','2026-08-16 09:06:22',NULL),(85,'Darby Green','03540688205','nikko89@example.com','female','2000-01-01','671 Gleichner Junctions','Swat','Pakistan','Pakistan','credit',52196.83,1376.00,'credit','A+','Officia eum impedit labore pariatur aliquid velit at.',NULL,4,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(86,'Kallie Glover','03162017748','dulce.kuhic@example.net','male','2000-01-01','6917 Kling Circles','Peshawar','Pakistan','Pakistan','credit',19794.63,14411.81,'credit','A+','Consequatur dolor dicta est incidunt aliquam et est.',NULL,3,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(87,'Ms. Alysha Monahan Sr.','03834195700','agoldner@example.org','male','2000-01-01','96481 Jane Ports','Islamabad','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','A+',NULL,NULL,3,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(88,'Reid Greenfelder I','03606933602','von.urban@example.net','female','2000-01-01','29466 Beaulah Fields','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','B+',NULL,'Fugit consectetur commodi ex eum nostrum esse et possimus.',1,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(89,'Rose Schultz','03537251390','justice61@example.net','male','2000-01-01','624 Lehner Station Suite 388','Karachi','Pakistan','Pakistan','regular',7887.95,9949.99,'credit','AB+',NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(90,'Holly Quitzon DVM','03922833230','terry65@example.org','male','2000-01-01','31090 Shields Prairie','Islamabad','Pakistan','Pakistan','credit',0.00,0.00,'debit','A+',NULL,'Ipsam corrupti qui qui.',3,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(91,'Carlie Ruecker','03510354319','arden85@example.com','female','2000-01-01','3493 Agustin Trail Apt. 819','Peshawar','Pakistan','Pakistan','credit',0.00,0.00,'debit','A+','Consequatur pariatur quia qui nostrum eaque praesentium at.',NULL,4,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(92,'Murray Cummerata','03979576892','itrantow@example.net','male','2000-01-01','74007 Marco Tunnel','Swat','Pakistan','Pakistan','walk_in',57708.96,17717.80,'credit','O+',NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(93,'Mrs. Linnie Stokes','03927104843','myra15@example.net','female','2000-01-01','18267 Vern Prairie Suite 513','Karachi','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','AB+',NULL,'Consectetur id et aut non nemo at tenetur.',2,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(94,'Bernice Cormier III','03359044902','emie44@example.org','female','2000-01-01','9317 Harris Squares','Karachi','Pakistan','Pakistan','regular',0.00,0.00,'debit','AB+',NULL,NULL,2,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(95,'Lavada Boyer','03686840538','gleffler@example.org','female','2000-01-01','6842 Kris Prairie Apt. 362','Swat','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','O-','Quia cupiditate natus deserunt perferendis accusantium nihil.','Doloribus ullam possimus consequatur et alias atque.',3,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(96,'Chelsey Carroll PhD','03271062775','lydia19@example.org','female','2000-01-01','253 Eleanora Fords','Peshawar','Pakistan','Pakistan','corporate',0.00,0.00,'debit','O+','Ut voluptas dolores eius.','Ipsum saepe impedit cumque quod quisquam porro qui.',2,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(97,'Russ Rippin','03305020142','cassin.mitchel@example.com','male','2000-01-01','4109 Mariano Well Suite 250','Peshawar','Pakistan','Pakistan','corporate',0.00,0.00,'debit','AB+',NULL,NULL,4,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(98,'Clark Mayer','03361120736','hmccullough@example.net','female','2000-01-01','94466 Allen Ports Suite 544','Islamabad','Pakistan','Pakistan','regular',0.00,0.00,'debit','O+','Sint dolores rem asperiores ipsam dicta voluptatem.','Quia ut voluptatibus dignissimos ut ad.',1,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(99,'Zena O\'Reilly','03635539183','jan.mayer@example.com','female','2000-01-01','2022 Harvey Viaduct Apt. 902','Karachi','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','O+','Dolores quos suscipit est.',NULL,2,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(100,'Jennings Okuneva Jr.','03944751987','oral45@example.org','female','2000-01-01','64458 Giovani Lock Suite 294','Karachi','Pakistan','Pakistan','walk_in',0.00,0.00,'debit','AB+','Provident explicabo modi consequatur autem.',NULL,3,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(101,'Walk-in Customer','00000000000',NULL,NULL,NULL,NULL,NULL,NULL,'Pakistan','walk_in',0.00,0.00,'debit',NULL,NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL),(102,'Prime Care Hospital','03001234567','accounts@primecare.com',NULL,NULL,NULL,NULL,NULL,'Pakistan','corporate',500000.00,0.00,'credit',NULL,NULL,NULL,1,NULL,NULL,'2026-08-16 09:06:23','2026-08-16 09:06:23',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_name_unique` (`name`),
  KEY `expense_categories_created_by_foreign` (`created_by`),
  KEY `expense_categories_updated_by_foreign` (`updated_by`),
  KEY `expense_categories_deleted_by_foreign` (`deleted_by`),
  KEY `expense_categories_status_index` (`status`),
  CONSTRAINT `expense_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expense_categories_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expense_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
INSERT INTO `expense_categories` VALUES (1,'Rent',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(2,'Salaries',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(3,'Electricity',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(4,'Internet',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(5,'Telephone',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(6,'Transportation',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(7,'Office Supplies',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(8,'Maintenance',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(9,'Marketing',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(10,'Software & Subscriptions',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(11,'Bank Charges',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(12,'Taxes & Government Fees',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(13,'Cleaning',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(14,'Security',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL),(15,'Miscellaneous',NULL,1,NULL,NULL,NULL,'2026-08-25 12:25:45','2026-08-25 12:25:45',NULL);
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_category_id` bigint unsigned NOT NULL,
  `expense_date` date NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('Unpaid','Partially Paid','Paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `status` enum('Completed','Draft','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Completed',
  `payment_method` enum('cash','card','bank_transfer','jazzcash','easypaisa','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expenses_expense_number_unique` (`expense_number`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_updated_by_foreign` (`updated_by`),
  KEY `expenses_deleted_by_foreign` (`deleted_by`),
  KEY `expenses_expense_number_index` (`expense_number`),
  KEY `expenses_expense_category_id_index` (`expense_category_id`),
  KEY `expenses_expense_date_index` (`expense_date`),
  KEY `expenses_payment_status_index` (`payment_status`),
  KEY `expenses_status_index` (`status`),
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `expenses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'EXP-000001',3,'2026-08-25','Bill','asdfasd',2000.00,1000.00,1000.00,'Partially Paid','Completed','cash',NULL,'asdfasd',1,NULL,NULL,'2026-08-25 14:45:03','2026-08-25 14:45:03',NULL),(2,'EXP-000002',1,'2026-08-26','Rent','asdfasdf',2000.00,1000.00,1000.00,'Partially Paid','Completed','bank_transfer',NULL,'asdfasdf',1,1,NULL,'2026-08-26 09:44:35','2026-08-26 09:44:35',NULL),(3,'EXP-000003',11,'2026-08-26','Bill','uyuytuytuytu',60060.00,5000.00,55060.00,'Partially Paid','Completed','cash','EXREF-000003','iuyyiuyiuyiu',1,1,NULL,'2026-08-26 11:54:17','2026-08-26 11:54:17',NULL);
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `manufacturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manufacturers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manufacturers_created_by_foreign` (`created_by`),
  KEY `manufacturers_updated_by_foreign` (`updated_by`),
  KEY `manufacturers_deleted_by_foreign` (`deleted_by`),
  KEY `manufacturers_status_index` (`status`),
  KEY `manufacturers_sort_order_index` (`sort_order`),
  CONSTRAINT `manufacturers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `manufacturers_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `manufacturers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `manufacturers` WRITE;
/*!40000 ALTER TABLE `manufacturers` DISABLE KEYS */;
INSERT INTO `manufacturers` VALUES (1,'Getz Pharma','Muhammad Ali','+92 21 111 111 111','info@getzpharma.com','https://www.getzpharma.com',NULL,'Karachi','Pakistan',NULL,1,1,1,NULL,NULL,NULL,'2026-08-16 09:01:24','2026-08-16 09:01:24'),(2,'GSK Pakistan','Ahmed Khan','+92 21 222 222 222','info@gsk.com.pk','https://pk.gsk.com',NULL,'Karachi','Pakistan',NULL,1,2,1,NULL,NULL,NULL,'2026-08-16 09:01:24','2026-08-16 09:01:24'),(3,'Abbott Laboratories','Usman Tariq','+92 42 333 333 333','info@abbott.com','https://www.abbott.com',NULL,'Lahore','Pakistan',NULL,1,3,1,NULL,NULL,NULL,'2026-08-16 09:01:24','2026-08-16 09:01:24');
/*!40000 ALTER TABLE `manufacturers` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `medicine_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicine_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medicine_categories_name_unique` (`name`),
  UNIQUE KEY `medicine_categories_slug_unique` (`slug`),
  KEY `medicine_categories_created_by_foreign` (`created_by`),
  KEY `medicine_categories_updated_by_foreign` (`updated_by`),
  KEY `medicine_categories_deleted_by_foreign` (`deleted_by`),
  KEY `medicine_categories_status_index` (`status`),
  KEY `medicine_categories_sort_order_index` (`sort_order`),
  CONSTRAINT `medicine_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medicine_categories_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medicine_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `medicine_categories` WRITE;
/*!40000 ALTER TABLE `medicine_categories` DISABLE KEYS */;
INSERT INTO `medicine_categories` VALUES (1,'Tablet','tablet',NULL,1,1,NULL,NULL,NULL,NULL,'2026-08-16 09:00:09','2026-08-16 09:00:09'),(2,'Capsule','capsule',NULL,1,2,NULL,NULL,NULL,NULL,'2026-08-16 09:00:09','2026-08-16 09:00:09'),(3,'Syrup','syrup',NULL,1,3,NULL,NULL,NULL,NULL,'2026-08-16 09:00:09','2026-08-16 09:00:09'),(4,'Injection','injection',NULL,1,4,NULL,NULL,NULL,NULL,'2026-08-16 09:00:09','2026-08-16 09:00:09'),(5,'Cream','cream',NULL,1,5,NULL,NULL,NULL,NULL,'2026-08-16 09:00:09','2026-08-16 09:00:09'),(6,'Ointment','ointment',NULL,1,6,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(7,'Gel','gel',NULL,1,7,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(8,'Drops','drops',NULL,1,8,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(9,'Powder','powder',NULL,1,9,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(10,'Inhaler','inhaler',NULL,1,10,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(11,'Suppository','suppository',NULL,1,11,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(12,'Patch','patch',NULL,1,12,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(13,'Lotion','lotion',NULL,1,13,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10'),(14,'Spray','spray',NULL,1,14,NULL,NULL,NULL,NULL,'2026-08-16 09:00:10','2026-08-16 09:00:10');
/*!40000 ALTER TABLE `medicine_categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `medicine_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicine_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medicine_types_name_unique` (`name`),
  UNIQUE KEY `medicine_types_slug_unique` (`slug`),
  KEY `medicine_types_created_by_foreign` (`created_by`),
  KEY `medicine_types_updated_by_foreign` (`updated_by`),
  KEY `medicine_types_deleted_by_foreign` (`deleted_by`),
  KEY `medicine_types_status_index` (`status`),
  KEY `medicine_types_sort_order_index` (`sort_order`),
  CONSTRAINT `medicine_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medicine_types_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medicine_types_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `medicine_types` WRITE;
/*!40000 ALTER TABLE `medicine_types` DISABLE KEYS */;
INSERT INTO `medicine_types` VALUES (1,'Prescription','prescription','Medicines that require a doctor prescription.',1,1,NULL,NULL,NULL,NULL,'2026-08-16 09:00:36','2026-08-16 09:00:36'),(2,'OTC','otc','Over-the-counter medicines available without prescription.',1,2,NULL,NULL,NULL,NULL,'2026-08-16 09:00:36','2026-08-16 09:00:36'),(3,'Controlled','controlled','Medicines controlled by government regulations.',1,3,NULL,NULL,NULL,NULL,'2026-08-16 09:00:36','2026-08-16 09:00:36'),(4,'Herbal','herbal','Medicines made from herbal ingredients.',1,4,NULL,NULL,NULL,NULL,'2026-08-16 09:00:36','2026-08-16 09:00:36'),(5,'Supplement','supplement','Vitamins, minerals and dietary supplements.',1,5,NULL,NULL,NULL,NULL,'2026-08-16 09:00:36','2026-08-16 09:00:36');
/*!40000 ALTER TABLE `medicine_types` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `medicines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medicine_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medicine_category_id` bigint unsigned NOT NULL,
  `medicine_type_id` bigint unsigned NOT NULL,
  `manufacturer_id` bigint unsigned NOT NULL,
  `unit_id` bigint unsigned NOT NULL,
  `purchase_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `wholesale_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `opening_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `maximum_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reorder_level` decimal(12,2) NOT NULL DEFAULT '0.00',
  `has_expiry` tinyint(1) NOT NULL DEFAULT '1',
  `shelf_life_months` smallint unsigned DEFAULT NULL,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medicines_sku_unique` (`sku`),
  UNIQUE KEY `medicines_barcode_unique` (`barcode`),
  UNIQUE KEY `medicines_medicine_code_unique` (`medicine_code`),
  KEY `medicines_medicine_category_id_foreign` (`medicine_category_id`),
  KEY `medicines_medicine_type_id_foreign` (`medicine_type_id`),
  KEY `medicines_manufacturer_id_foreign` (`manufacturer_id`),
  KEY `medicines_unit_id_foreign` (`unit_id`),
  KEY `medicines_created_by_foreign` (`created_by`),
  KEY `medicines_updated_by_foreign` (`updated_by`),
  KEY `medicines_deleted_by_foreign` (`deleted_by`),
  KEY `medicines_name_index` (`name`),
  KEY `medicines_generic_name_index` (`generic_name`),
  KEY `medicines_status_index` (`status`),
  KEY `medicines_current_stock_index` (`current_stock`),
  KEY `medicines_barcode_index` (`barcode`),
  CONSTRAINT `medicines_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medicines_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medicines_manufacturer_id_foreign` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `medicines_medicine_category_id_foreign` FOREIGN KEY (`medicine_category_id`) REFERENCES `medicine_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `medicines_medicine_type_id_foreign` FOREIGN KEY (`medicine_type_id`) REFERENCES `medicine_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `medicines_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `medicines_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `medicines` WRITE;
/*!40000 ALTER TABLE `medicines` DISABLE KEYS */;
INSERT INTO `medicines` VALUES (1,'in modi',NULL,'MED-005871','0602234971837','MD167269',10,2,2,6,982.46,1116.72,998.77,79.00,79.00,10.00,508.00,38.00,1,18,10.00,NULL,'Et excepturi ut ipsam iusto amet est ea earum.',1,5,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(2,'et at','et','MED-964431','8456453719945','MD707218',11,2,1,5,552.80,806.81,687.53,58.00,67.00,17.00,787.00,40.00,1,24,10.00,NULL,'Explicabo excepturi non aut non sit tempore ad.',1,72,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-19 15:28:46',NULL),(3,'quaerat repellendus','suscipit','MED-884269','3138707736827','MD801586',12,4,1,5,832.40,894.78,876.48,85.00,85.00,17.00,746.00,34.00,1,18,0.00,NULL,'Vel ad voluptate rerum explicabo laudantium cumque facere.',1,81,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(4,'voluptas ullam',NULL,'MED-641906','1334508432155','MD090280',12,3,2,2,253.05,365.13,316.77,115.00,115.00,19.00,682.00,36.00,1,36,5.00,NULL,'Omnis magnam veniam vero ratione quia eligendi amet.',1,4,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(5,'deleniti quo',NULL,'MED-595084','1778086120088','MD488499',7,3,2,4,723.83,853.97,801.69,498.00,492.00,18.00,993.00,21.00,1,36,15.00,NULL,'Corrupti fuga quam itaque ut voluptatibus rerum et excepturi.',1,7,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-22 14:17:48',NULL),(6,'quas quod',NULL,'MED-481152','3030474946167','MD980033',9,3,3,14,473.87,528.86,506.17,384.00,384.00,15.00,714.00,15.00,1,12,0.00,NULL,'Atque dolore recusandae ex.',1,77,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(7,'in et','beatae','MED-785889','0767823853410','MD395481',7,5,3,2,574.67,629.06,617.86,265.00,260.00,9.00,649.00,33.00,1,36,0.00,NULL,'Dolore ab dolorum expedita voluptas perferendis.',1,82,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-18 02:47:40',NULL),(8,'non harum',NULL,'MED-318727','7715735765768','MD374482',8,1,3,11,588.90,741.06,607.32,287.00,287.00,6.00,420.00,27.00,1,12,0.00,NULL,'Expedita et ad officiis eligendi culpa deserunt adipisci alias.',1,67,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(9,'voluptas placeat',NULL,'MED-565471','4296972088252','MD095895',10,1,2,2,828.52,1027.76,856.75,472.00,472.00,19.00,859.00,28.00,1,18,18.00,NULL,'Similique nemo asperiores quia sed corporis.',1,85,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(10,'occaecati iure',NULL,'MED-733131','0403796797411','MD253846',11,3,3,14,731.13,991.50,861.70,159.00,159.00,15.00,873.00,33.00,1,18,0.00,NULL,'Omnis et magni qui sed libero eum amet.',1,58,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(11,'placeat animi','consequatur','MED-383978','6094372006993','MD671982',11,3,3,12,279.76,391.85,371.14,491.00,491.00,19.00,405.00,31.00,1,12,10.00,NULL,'Sint quo excepturi est et.',1,40,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(12,'cum sit',NULL,'MED-395658','5952517780546','MD390062',2,1,2,10,779.97,1064.84,812.36,499.00,497.00,18.00,718.00,21.00,1,18,10.00,NULL,'Repellat et dolor voluptatem est excepturi.',1,68,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-22 01:28:03',NULL),(13,'veritatis eveniet',NULL,'MED-677719','6188450755832','MD131167',11,2,1,7,838.00,851.33,949.16,44.00,44.00,12.00,296.00,48.00,1,18,18.00,NULL,'Quo perspiciatis enim eum est aspernatur.',1,12,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(14,'voluptatibus blanditiis','velit','MED-204894','9317662958763','MD521552',14,5,1,9,999.21,1059.89,1140.40,156.00,156.00,19.00,946.00,32.00,1,12,5.00,NULL,'Atque et occaecati deleniti neque ad.',1,8,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(15,'repellat cum',NULL,'MED-142529','8297473273006','MD371387',8,4,2,10,725.08,887.74,749.34,452.00,452.00,7.00,467.00,20.00,0,36,18.00,NULL,'Dolor velit fugiat magni quia voluptate architecto.',1,75,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(16,'atque repellat','in','MED-334827','1286948078229','MD837660',13,2,1,14,454.43,697.75,596.64,359.00,350.00,7.00,373.00,49.00,1,24,5.00,NULL,'Consequuntur nesciunt voluptas velit a quaerat deserunt.',1,30,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-29 09:39:37',NULL),(17,'esse sunt','ut','MED-107548','6996503567536','MD087679',1,1,1,13,33.91,103.38,84.18,390.00,519.00,11.00,540.00,38.00,1,18,15.00,NULL,'Consequuntur necessitatibus doloribus cupiditate quas.',1,24,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-22 01:28:03',NULL),(18,'animi harum','et','MED-807126','1267924125700','MD340686',7,2,2,7,280.24,447.81,318.70,106.00,105.00,17.00,681.00,36.00,1,36,0.00,NULL,'Quod et minima rerum dolorem.',1,75,1,NULL,1,'2026-08-16 09:03:53','2026-08-28 12:54:25',NULL),(19,'soluta eum',NULL,'MED-489510','6974860885210','MD828436',2,1,2,3,819.40,960.40,931.37,24.00,24.00,6.00,282.00,16.00,1,24,0.00,NULL,'Sint consequatur ullam in eligendi.',1,25,1,NULL,NULL,'2026-08-16 09:03:53','2026-08-16 09:03:53',NULL),(20,'sit neque','dolores','MED-665663','3713893567785','MD266471',2,2,1,12,808.79,906.72,886.12,275.00,275.00,8.00,329.00,16.00,1,24,15.00,NULL,'Necessitatibus veniam corrupti tempore consectetur quasi.',1,34,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(21,'eum earum','quis','MED-713233','2316830479878','MD478605',14,1,1,5,932.80,1229.26,1004.39,371.00,367.00,17.00,808.00,41.00,1,12,5.00,NULL,'Et cum dolorem adipisci eius et reprehenderit.',1,71,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-19 06:30:07',NULL),(22,'ducimus necessitatibus','perspiciatis','MED-680740','6726986929534','MD657664',11,5,2,6,588.36,671.97,731.66,263.00,261.00,15.00,419.00,34.00,1,24,10.00,NULL,'In accusantium quasi doloribus vel praesentium est qui.',1,57,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-22 14:17:48',NULL),(23,'placeat dignissimos',NULL,'MED-878185','4809417147645','MD338662',9,4,3,14,754.12,972.29,870.82,436.00,435.00,16.00,514.00,41.00,1,36,18.00,NULL,'Est qui voluptas occaecati aperiam sunt qui odit a.',1,8,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-21 02:30:29',NULL),(24,'nemo tempora','qui','MED-249281','7153629634033','MD832552',12,1,3,11,364.42,578.82,483.85,73.00,73.00,19.00,401.00,37.00,1,12,10.00,NULL,'Aut in fugiat est rerum tenetur.',1,88,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(25,'aut exercitationem',NULL,'MED-385201','9435126704715','MD772503',10,5,2,2,314.75,338.54,372.99,468.00,450.00,15.00,802.00,29.00,1,24,15.00,NULL,'Ut ex placeat sit ipsum voluptatem et.',1,21,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-29 09:39:37',NULL),(26,'quam commodi','vitae','MED-680558','3294464173067','MD511614',13,2,2,10,934.38,1068.25,950.21,452.00,452.00,20.00,607.00,47.00,1,24,10.00,NULL,'Quod nihil sequi eveniet laboriosam architecto qui voluptates.',1,58,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(27,'provident id','reiciendis','MED-456524','9174245404655','MD092066',6,4,1,1,908.72,1068.80,990.57,125.00,124.00,17.00,448.00,10.00,1,36,15.00,NULL,'Quo ex alias et illo sit minima.',1,24,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-21 00:51:05',NULL),(28,'error tempore','dolor','MED-184964','6681511818342','MD589681',1,1,2,11,963.13,1213.78,1037.35,299.00,310.00,16.00,350.00,21.00,1,36,15.00,NULL,'Voluptate qui dolor dolores voluptatem qui vel saepe perspiciatis.',1,61,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-22 01:28:03',NULL),(29,'exercitationem consequatur','quos','MED-538077','6099345103976','MD282032',6,5,1,8,656.88,904.74,676.23,295.00,295.00,9.00,839.00,34.00,1,12,0.00,NULL,'Corrupti labore sunt assumenda tempora odit officia.',1,42,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-17 07:04:24',NULL),(30,'rerum error','nobis','MED-920454','1816491050528','MD176802',4,5,2,14,514.78,619.17,616.57,135.00,134.00,6.00,832.00,26.00,1,36,5.00,NULL,'Tempora quaerat nisi error.',1,13,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-22 01:28:03',NULL),(31,'quo soluta','voluptas','MED-056831','2690690280813','MD333603',13,5,3,4,27.83,253.60,98.42,69.00,69.00,13.00,934.00,26.00,1,12,5.00,NULL,'Rerum minima magni et at ab accusantium est.',1,43,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(32,'labore suscipit',NULL,'MED-998857','4940286871351','MD953679',7,5,2,6,142.58,411.65,229.28,432.00,432.00,5.00,530.00,45.00,1,24,0.00,NULL,'Cumque rerum molestias quibusdam quis molestias consequuntur nam molestiae.',1,12,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(33,'voluptatem sit',NULL,'MED-720080','4971618044280','MD888066',3,3,3,14,519.40,694.70,646.44,243.00,243.00,5.00,494.00,12.00,1,24,15.00,NULL,'Rerum itaque ad vitae optio officia autem.',1,12,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(34,'libero quis','qui','MED-055689','6740962786881','MD452924',13,3,1,10,238.84,263.70,325.91,124.00,124.00,9.00,672.00,46.00,1,24,15.00,NULL,'Quia provident laboriosam perspiciatis consectetur eligendi voluptas.',1,54,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(35,'placeat tempora',NULL,'MED-502880','5138188399250','MD690916',13,3,2,6,993.14,1140.95,1035.85,283.00,283.00,9.00,911.00,17.00,1,12,15.00,NULL,'Officiis veritatis neque iste doloribus non amet.',1,62,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(36,'architecto dolorem','consequatur','MED-158883','0967172606440','MD756140',6,1,3,12,549.22,763.72,678.95,218.00,212.00,20.00,353.00,50.00,0,36,15.00,NULL,'Ut qui illo ut dolorem quo quisquam.',1,34,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-25 12:45:51',NULL),(37,'doloribus et','qui','MED-147685','4507984337420','MD187916',3,1,2,6,219.41,352.26,286.94,274.00,262.00,11.00,937.00,49.00,1,24,18.00,NULL,'Cum aspernatur velit id hic animi omnis.',1,43,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-25 12:45:51',NULL),(38,'molestiae dignissimos',NULL,'MED-146854','7212411958402','MD887971',8,1,1,4,50.54,315.84,60.03,50.00,50.00,15.00,990.00,21.00,1,12,0.00,NULL,'Ullam exercitationem voluptates omnis velit sapiente voluptates et.',1,88,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(39,'illum fuga','enim','MED-801173','3612026759649','MD237864',10,3,2,5,497.85,647.98,602.50,65.00,65.00,9.00,558.00,31.00,1,36,5.00,NULL,'Minus qui consequatur repellendus quas iste.',1,19,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(40,'est enim','fugiat','MED-075302','0202805638611','MD944435',5,4,3,12,863.61,978.71,991.78,477.00,477.00,10.00,338.00,24.00,1,12,18.00,NULL,'Tempora et adipisci vitae doloremque non magnam fugiat.',1,94,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(41,'aut voluptatem',NULL,'MED-919343','4266591775543','MD376389',8,3,1,14,803.70,1096.52,912.92,275.00,283.00,19.00,542.00,20.00,1,18,10.00,NULL,'Incidunt veniam ea dolorum.',1,64,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-22 01:28:03',NULL),(42,'doloremque aut',NULL,'MED-542276','9942870522804','MD122203',5,4,3,1,620.68,636.33,648.17,375.00,372.00,10.00,445.00,32.00,1,18,0.00,NULL,'Aliquam repellendus exercitationem quasi eos porro recusandae ut molestiae.',1,71,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-22 14:17:48',NULL),(43,'rerum cupiditate',NULL,'MED-169799','5662497068205','MD868436',1,4,3,9,281.29,373.91,394.06,236.00,236.00,18.00,721.00,42.00,1,18,0.00,NULL,'Expedita hic nulla voluptate rerum qui pariatur.',1,72,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(44,'ratione rem',NULL,'MED-040373','2237354053972','MD969213',4,4,3,14,846.80,1002.43,890.98,450.00,450.00,12.00,410.00,35.00,1,24,0.00,NULL,'Velit nulla magnam omnis eos dolorum deserunt.',1,16,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(45,'laboriosam sed',NULL,'MED-889699','8433915359307','MD931621',1,2,2,2,520.09,599.53,558.98,19.00,19.00,7.00,736.00,14.00,1,12,5.00,NULL,'Consequuntur debitis laborum quisquam magnam debitis ipsum cumque.',1,83,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(46,'voluptatem architecto',NULL,'MED-830990','1254868223494','MD977724',10,1,3,11,621.68,848.18,698.70,443.00,443.00,7.00,626.00,42.00,1,24,5.00,NULL,'At aliquid quis ea eveniet repellendus cupiditate dolorum.',1,85,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(47,'enim nesciunt','debitis','MED-057698','6576723006260','MD676535',14,3,3,5,100.96,264.43,238.79,411.00,254.00,14.00,752.00,36.00,1,36,15.00,NULL,'Quia recusandae libero qui facilis autem.',1,85,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-22 01:28:03',NULL),(48,'in omnis',NULL,'MED-008169','9903921994407','MD557972',3,2,2,8,498.93,607.09,575.09,142.00,142.00,12.00,596.00,30.00,1,12,0.00,NULL,'Et totam repellendus rerum tenetur voluptatum.',1,18,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(49,'voluptates aliquam','reiciendis','MED-291903','4076493284223','MD611847',7,3,2,7,807.94,1003.37,955.42,407.00,407.00,8.00,365.00,30.00,1,18,18.00,NULL,'Aut qui nemo et.',1,99,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL),(50,'voluptate quia','et','MED-520639','4708799807119','MD825444',8,2,2,10,939.42,1162.29,976.81,229.00,229.00,5.00,502.00,25.00,1,36,18.00,NULL,'Vero est minus distinctio aperiam doloribus.',1,70,1,NULL,NULL,'2026-08-16 09:03:54','2026-08-16 09:03:54',NULL);
/*!40000 ALTER TABLE `medicines` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_23_114856_create_permission_tables',1),(5,'2026_07_23_115459_create_activity_log_table',1),(6,'2026_07_23_115500_add_event_column_to_activity_log_table',1),(7,'2026_07_23_115501_add_batch_uuid_column_to_activity_log_table',1),(8,'2026_07_23_120620_create_settings_table',1),(9,'2026_07_23_141319_create_medicine_categories_table',1),(10,'2026_07_25_102652_create_medicine_types_table',1),(11,'2026_07_25_194258_create_units_table',1),(12,'2026_07_26_070320_create_manufacturers_table',1),(13,'2026_07_26_104541_create_medicines_table',1),(14,'2026_07_27_195051_create_suppliers_table',1),(15,'2026_08_04_105012_create_purchases_table',1),(16,'2026_08_04_105148_create_purchase_items_table',1),(17,'2026_08_05_180649_create_purchase_returns_table',1),(18,'2026_08_05_181000_create_purchase_return_items_table',1),(19,'2026_08_06_045609_create_customers_table',1),(20,'2026_08_06_051414_create_sales_table',1),(21,'2026_08_06_051501_create_sale_items_table',1),(22,'2026_08_13_111821_create_sale_returns_table',1),(23,'2026_08_13_111910_create_sale_return_items_table',1),(24,'2026_08_16_210644_add_soft_deletes_to_permission_tables',2),(25,'2026_08_17_094845_add_stock_applied_to_purchases_table',3),(26,'2026_08_17_143500_add_stock_applied_to_purchase_returns_table',4),(27,'2026_08_19_034129_create_stock_adjustments_table',5),(28,'2026_08_19_034340_create_stock_adjustment_items_table',5),(29,'2026_08_20_171945_create_carts_table',6),(30,'2026_08_20_172239_create_cart_items_table',6),(31,'2026_08_21_120856_create_payments_table',7),(32,'2026_08_24_153613_ceate_expense_categories_table',8),(33,'2026_08_24_153714_ceate_expenses_table',8),(34,'2026_08_24_154124_add_expense_id_to_payments_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(4,'App\\Models\\User',4),(5,'App\\Models\\User',5);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('receipt','payment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` bigint unsigned DEFAULT NULL,
  `purchase_id` bigint unsigned DEFAULT NULL,
  `expense_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` enum('cash','card','bank_transfer','jazzcash','easypaisa','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_date` date NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  KEY `payments_created_by_foreign` (`created_by`),
  KEY `payments_updated_by_foreign` (`updated_by`),
  KEY `payments_deleted_by_foreign` (`deleted_by`),
  KEY `payments_payment_number_index` (`payment_number`),
  KEY `payments_type_index` (`type`),
  KEY `payments_sale_id_index` (`sale_id`),
  KEY `payments_purchase_id_index` (`purchase_id`),
  KEY `payments_customer_id_index` (`customer_id`),
  KEY `payments_supplier_id_index` (`supplier_id`),
  KEY `payments_payment_date_index` (`payment_date`),
  KEY `payments_method_index` (`method`),
  KEY `payments_expense_id_index` (`expense_id`),
  CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (3,'PAY-000003','receipt',2,NULL,NULL,91,NULL,1851.01,'cash','2026-08-21',NULL,NULL,1,NULL,NULL,'2026-08-21 12:53:09','2026-08-21 12:53:09',NULL),(4,'PAY-000004','receipt',3,NULL,NULL,59,NULL,264.43,'cash','2026-08-21',NULL,NULL,1,NULL,NULL,'2026-08-21 12:56:06','2026-08-21 12:56:06',NULL),(6,'PAY-000005','receipt',9,NULL,NULL,NULL,NULL,1362.77,'cash','2026-08-21',NULL,NULL,1,NULL,NULL,'2026-08-21 13:10:25','2026-08-21 13:10:25',NULL),(7,'PAY-000007','receipt',10,NULL,NULL,62,NULL,2907.40,'cash','2026-08-21',NULL,NULL,1,NULL,NULL,'2026-08-21 13:13:03','2026-08-21 13:13:03',NULL),(8,'PAY-000008','receipt',11,NULL,NULL,49,NULL,1686.34,'cash','2026-08-22',NULL,NULL,1,NULL,NULL,'2026-08-21 23:55:43','2026-08-21 23:55:43',NULL),(9,'PAY-000009','payment',NULL,12,NULL,NULL,12,4137.02,'cash','2026-08-22',NULL,NULL,1,1,NULL,'2026-08-21 23:57:06','2026-08-21 23:59:50',NULL),(10,'PAY-000010','payment',NULL,13,NULL,NULL,8,148540.57,'cash','2026-08-22',NULL,NULL,1,NULL,NULL,'2026-08-21 23:59:16','2026-08-21 23:59:16',NULL),(11,'PAY-000011','receipt',13,NULL,NULL,NULL,NULL,1096.52,'cash','2026-08-22',NULL,NULL,1,NULL,NULL,'2026-08-22 00:44:11','2026-08-22 00:44:11',NULL),(12,'PAY-000012','receipt',14,NULL,NULL,NULL,NULL,143.00,'cash','2026-08-22',NULL,NULL,1,NULL,NULL,'2026-08-22 00:49:09','2026-08-22 00:49:09',NULL),(13,'PAY-000013','receipt',16,NULL,NULL,16,NULL,5757.81,'cash','2026-08-22',NULL,NULL,1,NULL,NULL,'2026-08-22 01:28:03','2026-08-22 01:28:03',NULL),(14,'PAY-000014','receipt',17,NULL,NULL,NULL,NULL,7141.94,'cash','2026-08-22',NULL,NULL,1,NULL,NULL,'2026-08-22 14:17:48','2026-08-22 14:17:48',NULL),(15,'PAY-000015','receipt',18,NULL,NULL,NULL,NULL,3702.17,'cash','2026-08-25',NULL,NULL,1,NULL,NULL,'2026-08-25 12:45:51','2026-08-25 12:45:51',NULL),(16,'PAY-000016','payment',NULL,NULL,2,NULL,NULL,1000.00,'bank_transfer','2026-08-26',NULL,'asdfasdf',1,NULL,NULL,'2026-08-26 09:44:35','2026-08-26 09:44:35',NULL),(17,'PAY-000017','payment',NULL,NULL,3,NULL,NULL,5000.00,'cash','2026-08-26','EXREF-000003','iuyyiuyiuyiu',1,NULL,NULL,'2026-08-26 11:54:17','2026-08-26 11:54:17',NULL),(18,'PAY-000018','receipt',19,NULL,NULL,NULL,NULL,1124.89,'cash','2026-08-28',NULL,NULL,1,NULL,NULL,'2026-08-28 12:54:26','2026-08-28 12:54:26',NULL),(19,'PAY-000019','receipt',20,NULL,NULL,NULL,NULL,697.75,'cash','2026-08-28',NULL,NULL,1,NULL,NULL,'2026-08-28 13:01:28','2026-08-28 13:01:28',NULL),(20,'PAY-000020','receipt',21,NULL,NULL,NULL,NULL,1036.29,'cash','2026-08-29',NULL,NULL,1,NULL,NULL,'2026-08-29 09:39:37','2026-08-29 09:39:37',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'dashboard.view','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(2,'dashboard.create','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(3,'dashboard.edit','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(4,'dashboard.delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(5,'dashboard.restore','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(6,'dashboard.force-delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(7,'dashboard.import','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(8,'dashboard.export','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(9,'settings.view','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(10,'settings.create','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(11,'settings.edit','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(12,'settings.delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(13,'settings.restore','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(14,'settings.force-delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(15,'settings.import','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(16,'settings.export','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(17,'roles.view','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(18,'roles.create','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(19,'roles.edit','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(20,'roles.delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(21,'roles.restore','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(22,'roles.force-delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(23,'roles.import','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(24,'roles.export','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(25,'permissions.view','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(26,'permissions.create','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(27,'permissions.edit','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(28,'permissions.delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(29,'permissions.restore','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(30,'permissions.force-delete','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(31,'permissions.import','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(32,'permissions.export','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(33,'users.view','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(34,'users.create','web','2026-08-16 08:56:22','2026-08-16 08:56:22',NULL),(35,'users.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(36,'users.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(37,'users.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(38,'users.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(39,'users.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(40,'users.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(41,'medicine-categories.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(42,'medicine-categories.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(43,'medicine-categories.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(44,'medicine-categories.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(45,'medicine-categories.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(46,'medicine-categories.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(47,'medicine-categories.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(48,'medicine-categories.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(49,'medicine-types.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(50,'medicine-types.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(51,'medicine-types.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(52,'medicine-types.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(53,'medicine-types.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(54,'medicine-types.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(55,'medicine-types.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(56,'medicine-types.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(57,'units.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(58,'units.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(59,'units.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(60,'units.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(61,'units.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(62,'units.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(63,'units.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(64,'units.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(65,'manufacturers.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(66,'manufacturers.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(67,'manufacturers.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(68,'manufacturers.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(69,'manufacturers.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(70,'manufacturers.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(71,'manufacturers.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(72,'manufacturers.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(73,'medicines.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(74,'medicines.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(75,'medicines.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(76,'medicines.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(77,'medicines.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(78,'medicines.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(79,'medicines.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(80,'medicines.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(81,'suppliers.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(82,'suppliers.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(83,'suppliers.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(84,'suppliers.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(85,'suppliers.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(86,'suppliers.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(87,'suppliers.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(88,'suppliers.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(89,'customers.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(90,'customers.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(91,'customers.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(92,'customers.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(93,'customers.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(94,'customers.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(95,'customers.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(96,'customers.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(97,'purchases.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(98,'purchases.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(99,'purchases.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(100,'purchases.delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(101,'purchases.restore','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(102,'purchases.force-delete','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(103,'purchases.import','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(104,'purchases.export','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(105,'purchase-returns.view','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(106,'purchase-returns.create','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(107,'purchase-returns.edit','web','2026-08-16 08:56:23','2026-08-16 08:56:23',NULL),(108,'purchase-returns.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(109,'purchase-returns.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(110,'purchase-returns.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(111,'purchase-returns.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(112,'purchase-returns.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(113,'sales.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(114,'sales.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(115,'sales.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(116,'sales.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(117,'sales.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(118,'sales.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(119,'sales.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(120,'sales.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(121,'sale-returns.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(122,'sale-returns.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(123,'sale-returns.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(124,'sale-returns.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(125,'sale-returns.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(126,'sale-returns.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(127,'sale-returns.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(128,'sale-returns.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(129,'stocks.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(130,'stocks.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(131,'stocks.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(132,'stocks.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(133,'stocks.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(134,'stocks.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(135,'stocks.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(136,'stocks.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(137,'stock-adjustments.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(138,'stock-adjustments.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(139,'stock-adjustments.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(140,'stock-adjustments.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(141,'stock-adjustments.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(142,'stock-adjustments.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(143,'stock-adjustments.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(144,'stock-adjustments.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(145,'expenses.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(146,'expenses.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(147,'expenses.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(148,'expenses.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(149,'expenses.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(150,'expenses.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(151,'expenses.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(152,'expenses.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(153,'reports.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(154,'reports.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(155,'reports.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(156,'reports.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(157,'reports.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(158,'reports.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(159,'reports.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(160,'reports.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(161,'companies.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(162,'companies.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(163,'companies.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(164,'companies.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(165,'companies.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(166,'companies.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(167,'companies.import','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(168,'companies.export','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(169,'backups.view','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(170,'backups.create','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(171,'backups.edit','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(172,'backups.delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(173,'backups.restore','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(174,'backups.force-delete','web','2026-08-16 08:56:24','2026-08-16 08:56:24',NULL),(175,'backups.import','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(176,'backups.export','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(177,'medicines.print-barcode','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(178,'medicines.stock-history','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(179,'medicines.purchase-history','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(180,'medicines.sales-history','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(181,'medicines.adjust-stock','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(182,'medicines.view-cost-price','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(183,'medicines.duplicate','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(184,'sales.cancel','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(185,'sales.complete','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(186,'sales.update-payment','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(187,'sales.print-invoice','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(188,'sales.download-pdf','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(189,'sales.send-email','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(190,'sales.view-payment','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(191,'sales.duplicate','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(192,'sales.convert-to-quote','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(193,'sales.view-profit','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(194,'sales.return','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(195,'sales.bulk-delete','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(196,'sales.bulk-export','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(197,'sale-returns.complete','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(198,'sale-returns.cancel','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(199,'users.assign-role','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(200,'users.remove-role','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(201,'users.assign-permission','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(202,'purchases.receive','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(203,'purchases.return','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(204,'purchases.print-order','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(205,'purchases.download-pdf','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(206,'purchases.view-profit','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(207,'purchases.duplicate','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(208,'purchases.bulk-delete','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(209,'purchases.bulk-export','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(210,'customers.view-sales','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(211,'customers.view-payments','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(212,'customers.view-returns','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(213,'customers.duplicate','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(214,'customers.bulk-import','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(215,'customers.bulk-export','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(216,'customers.send-email','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(217,'customers.send-sms','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(218,'suppliers.view-purchases','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(219,'suppliers.view-payments','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(220,'suppliers.duplicate','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(221,'suppliers.bulk-import','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(222,'suppliers.bulk-export','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(223,'suppliers.send-email','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(224,'suppliers.send-sms','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(225,'medicines.view-stock','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(226,'medicines.view-expiry','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(227,'medicines.bulk-import','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(228,'medicines.bulk-export','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(229,'medicines.update-price','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(230,'medicines.update-cost','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(231,'medicines.reorder','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(232,'medicines.view-reorder','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(233,'reports.sales-daily','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(234,'reports.sales-monthly','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(235,'reports.sales-yearly','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(236,'reports.purchases-daily','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(237,'reports.purchases-monthly','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(238,'reports.stock-report','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(239,'reports.profit-loss','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(240,'reports.tax-report','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(241,'reports.customer-report','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(242,'reports.supplier-report','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(243,'reports.export-excel','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(244,'reports.export-pdf','web','2026-08-16 08:56:25','2026-08-16 08:56:25',NULL),(245,'reports.export-csv','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(246,'stocks.adjust','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(247,'stocks.transfer','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(248,'stocks.audit','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(249,'stocks.count','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(250,'stocks.history','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(251,'stocks.report','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(252,'expenses.approve','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(253,'expenses.reject','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(254,'expenses.bulk-delete','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(255,'expenses.bulk-export','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(256,'expenses.view-report','web','2026-08-16 08:56:26','2026-08-16 08:56:26',NULL),(257,'purchases.complete','web','2026-08-16 16:54:55','2026-08-16 16:54:55',NULL),(258,'purchases.update-payment','web','2026-08-16 17:23:42','2026-08-16 17:23:42',NULL),(259,'purchases.cancel','web','2026-08-16 17:23:54','2026-08-16 17:23:54',NULL),(260,'stock-adjustments.complete','web','2026-08-19 06:03:41','2026-08-19 06:03:41',NULL),(261,'expiry.view','web','2026-08-19 12:18:34','2026-08-19 12:18:34',NULL),(262,'pos.view','web','2026-08-20 05:24:01','2026-08-20 05:24:01',NULL),(263,'pos.create','web','2026-08-20 05:28:10','2026-08-20 05:28:10',NULL),(264,'pos.checkout','web','2026-08-20 22:57:22','2026-08-20 22:57:22',NULL),(265,'pos.cancel','web','2026-08-20 23:41:30','2026-08-20 23:41:30',NULL),(266,'payments.view','web','2026-08-21 12:35:32','2026-08-21 12:35:32',NULL),(267,'payments.create','web','2026-08-21 12:37:01','2026-08-21 12:37:01',NULL),(268,'payments.edit','web','2026-08-21 12:39:45','2026-08-21 12:39:45',NULL),(269,'payments.delete','web','2026-08-21 12:40:00','2026-08-21 12:40:00',NULL),(270,'payments.restore','web','2026-08-21 12:40:15','2026-08-21 12:40:15',NULL),(271,'payments.force-delete','web','2026-08-21 12:40:29','2026-08-21 12:40:29',NULL),(272,'payments.export','web','2026-08-21 12:40:44','2026-08-21 12:40:44',NULL),(273,'stock-ledger.view','web','2026-08-22 02:07:14','2026-08-22 02:07:14',NULL),(274,'inventory-reports.view','web','2026-08-22 06:14:32','2026-08-22 06:14:32',NULL),(275,'sales-reports.view','web','2026-08-22 13:24:09','2026-08-22 13:24:09',NULL),(276,'purchase-reports.view','web','2026-08-23 04:16:29','2026-08-23 04:16:29',NULL),(277,'financial-reports.view','web','2026-08-24 09:41:41','2026-08-24 09:41:41',NULL),(278,'expense-categories.view','web','2026-08-25 12:26:19','2026-08-25 12:26:19',NULL),(279,'expense-categories.create','web','2026-08-25 12:42:43','2026-08-25 12:42:43',NULL),(280,'expense-categories.edit','web','2026-08-25 12:42:58','2026-08-25 12:42:58',NULL),(281,'expense-categories.delete','web','2026-08-25 14:38:41','2026-08-25 14:38:41',NULL),(282,'expense-categories.restore','web','2026-08-25 14:38:55','2026-08-25 14:38:55',NULL),(283,'expense-categories.force-delete','web','2026-08-25 14:39:09','2026-08-25 14:39:09',NULL),(284,'profit-loss.view','web','2026-08-28 08:25:14','2026-08-28 08:25:14',NULL),(285,'backups.download','web','2026-08-30 01:06:13','2026-08-30 01:06:13',NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned DEFAULT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purchase',
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` int NOT NULL,
  `free_quantity` int NOT NULL DEFAULT '0',
  `purchase_price` decimal(15,2) NOT NULL,
  `selling_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_index` (`purchase_id`),
  KEY `purchase_items_medicine_id_index` (`medicine_id`),
  KEY `purchase_items_batch_number_index` (`batch_number`),
  KEY `purchase_items_expiry_date_index` (`expiry_date`),
  KEY `purchase_items_source_index` (`source`),
  CONSTRAINT `purchase_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
INSERT INTO `purchase_items` VALUES (2,2,18,'purchase','BT489713XECD',NULL,5,0,280.24,447.81,0.00,0.00,1401.20,'2026-08-17 02:36:51','2026-08-17 02:36:51'),(3,3,2,'purchase','BT726384UJUN',NULL,5,2,552.80,806.81,0.00,0.00,2764.00,'2026-08-17 02:39:49','2026-08-17 02:39:49'),(4,4,28,'purchase','BT504684IOPW','2026-08-31',4,2,963.13,1213.78,0.00,0.00,3852.52,'2026-08-17 02:44:01','2026-08-17 02:44:01'),(5,6,7,'purchase','BT745248IALQ',NULL,4,1,574.67,629.06,0.00,0.00,2298.68,'2026-08-17 06:36:01','2026-08-17 06:36:01'),(6,7,7,'purchase','BT689150MXDB',NULL,1,0,574.67,629.06,0.00,0.00,574.67,'2026-08-17 06:45:02','2026-08-17 06:45:02'),(9,8,29,'purchase','BT324930FOWU',NULL,1,0,656.88,904.74,0.00,0.00,656.88,'2026-08-17 07:04:24','2026-08-17 07:04:24'),(13,10,21,'purchase','BT958641LWNF',NULL,3,2,932.80,1229.26,0.00,0.00,2798.40,'2026-08-17 07:27:48','2026-08-17 07:27:48'),(14,11,21,'purchase','BT733464WMLL',NULL,3,2,932.80,1229.26,0.00,0.00,2798.40,'2026-08-17 07:29:37','2026-08-17 07:29:37'),(15,9,17,'purchase','BT264381GGML','2026-09-05',4,2,33.91,103.38,0.00,0.00,135.64,'2026-08-17 07:52:39','2026-08-17 07:52:39'),(16,NULL,47,'opening_stock','OPENING-47','2026-08-19',411,0,100.96,264.43,0.00,0.00,41494.56,'2026-08-18 10:59:21','2026-08-18 10:59:21'),(17,NULL,18,'adjustment','ADJ-2-18','2025-08-20',5,0,280.24,447.81,0.00,0.00,1401.20,'2026-08-19 06:46:32','2026-08-19 06:46:32'),(18,NULL,1,'opening_stock','OPENING-1',NULL,79,0,982.46,1116.72,0.00,0.00,77614.34,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(19,NULL,2,'opening_stock','OPENING-2',NULL,65,0,552.80,806.81,0.00,0.00,35932.00,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(20,NULL,3,'opening_stock','OPENING-3',NULL,85,0,832.40,894.78,0.00,0.00,70754.00,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(21,NULL,4,'opening_stock','OPENING-4',NULL,115,0,253.05,365.13,0.00,0.00,29100.75,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(22,NULL,5,'opening_stock','OPENING-5',NULL,498,0,723.83,853.97,0.00,0.00,360467.34,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(23,NULL,6,'opening_stock','OPENING-6',NULL,384,0,473.87,528.86,0.00,0.00,181966.08,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(24,NULL,7,'opening_stock','OPENING-7',NULL,255,0,574.67,629.06,0.00,0.00,146540.85,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(25,NULL,8,'opening_stock','OPENING-8',NULL,287,0,588.90,741.06,0.00,0.00,169014.30,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(26,NULL,9,'opening_stock','OPENING-9',NULL,472,0,828.52,1027.76,0.00,0.00,391061.44,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(27,NULL,10,'opening_stock','OPENING-10',NULL,159,0,731.13,991.50,0.00,0.00,116249.67,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(28,NULL,11,'opening_stock','OPENING-11',NULL,491,0,279.76,391.85,0.00,0.00,137362.16,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(29,NULL,12,'opening_stock','OPENING-12',NULL,499,0,779.97,1064.84,0.00,0.00,389205.03,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(30,NULL,13,'opening_stock','OPENING-13',NULL,44,0,838.00,851.33,0.00,0.00,36872.00,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(31,NULL,14,'opening_stock','OPENING-14',NULL,156,0,999.21,1059.89,0.00,0.00,155876.76,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(32,NULL,15,'opening_stock','OPENING-15',NULL,452,0,725.08,887.74,0.00,0.00,327736.16,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(33,NULL,16,'opening_stock','OPENING-16',NULL,359,0,454.43,697.75,0.00,0.00,163140.37,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(34,NULL,17,'opening_stock','OPENING-17',NULL,392,0,33.91,103.38,0.00,0.00,13292.72,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(35,NULL,18,'opening_stock','OPENING-18',NULL,111,0,280.24,447.81,0.00,0.00,31106.64,'2026-08-19 10:11:33','2026-08-19 10:11:33'),(36,NULL,19,'opening_stock','OPENING-19',NULL,24,0,819.40,960.40,0.00,0.00,19665.60,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(37,NULL,20,'opening_stock','OPENING-20',NULL,275,0,808.79,906.72,0.00,0.00,222417.25,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(38,NULL,21,'opening_stock','OPENING-21',NULL,361,0,932.80,1229.26,0.00,0.00,336740.80,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(39,NULL,22,'opening_stock','OPENING-22',NULL,263,0,588.36,671.97,0.00,0.00,154738.68,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(40,NULL,23,'opening_stock','OPENING-23',NULL,436,0,754.12,972.29,0.00,0.00,328796.32,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(41,NULL,24,'opening_stock','OPENING-24',NULL,73,0,364.42,578.82,0.00,0.00,26602.66,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(42,NULL,25,'opening_stock','OPENING-25',NULL,468,0,314.75,338.54,0.00,0.00,147303.00,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(43,NULL,26,'opening_stock','OPENING-26',NULL,452,0,934.38,1068.25,0.00,0.00,422339.76,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(44,NULL,27,'opening_stock','OPENING-27',NULL,125,0,908.72,1068.80,0.00,0.00,113590.00,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(45,NULL,28,'opening_stock','OPENING-28',NULL,305,0,963.13,1213.78,0.00,0.00,293754.65,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(46,NULL,29,'opening_stock','OPENING-29',NULL,294,0,656.88,904.74,0.00,0.00,193122.72,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(47,NULL,30,'opening_stock','OPENING-30',NULL,135,0,514.78,619.17,0.00,0.00,69495.30,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(48,NULL,31,'opening_stock','OPENING-31',NULL,69,0,27.83,253.60,0.00,0.00,1920.27,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(49,NULL,32,'opening_stock','OPENING-32',NULL,432,0,142.58,411.65,0.00,0.00,61594.56,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(50,NULL,33,'opening_stock','OPENING-33',NULL,243,0,519.40,694.70,0.00,0.00,126214.20,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(51,NULL,34,'opening_stock','OPENING-34',NULL,124,0,238.84,263.70,0.00,0.00,29616.16,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(52,NULL,35,'opening_stock','OPENING-35',NULL,283,0,993.14,1140.95,0.00,0.00,281058.62,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(53,NULL,36,'opening_stock','OPENING-36',NULL,218,0,549.22,763.72,0.00,0.00,119729.96,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(54,NULL,37,'opening_stock','OPENING-37',NULL,274,0,219.41,352.26,0.00,0.00,60118.34,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(55,NULL,38,'opening_stock','OPENING-38',NULL,50,0,50.54,315.84,0.00,0.00,2527.00,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(56,NULL,39,'opening_stock','OPENING-39',NULL,65,0,497.85,647.98,0.00,0.00,32360.25,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(57,NULL,40,'opening_stock','OPENING-40',NULL,477,0,863.61,978.71,0.00,0.00,411941.97,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(58,NULL,41,'opening_stock','OPENING-41',NULL,275,0,803.70,1096.52,0.00,0.00,221017.50,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(59,NULL,42,'opening_stock','OPENING-42',NULL,375,0,620.68,636.33,0.00,0.00,232755.00,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(60,NULL,43,'opening_stock','OPENING-43',NULL,236,0,281.29,373.91,0.00,0.00,66384.44,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(61,NULL,44,'opening_stock','OPENING-44',NULL,450,0,846.80,1002.43,0.00,0.00,381060.00,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(62,NULL,45,'opening_stock','OPENING-45',NULL,19,0,520.09,599.53,0.00,0.00,9881.71,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(63,NULL,46,'opening_stock','OPENING-46',NULL,443,0,621.68,848.18,0.00,0.00,275404.24,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(64,NULL,48,'opening_stock','OPENING-48',NULL,142,0,498.93,607.09,0.00,0.00,70848.06,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(65,NULL,49,'opening_stock','OPENING-49',NULL,407,0,807.94,1003.37,0.00,0.00,328831.58,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(66,NULL,50,'opening_stock','OPENING-50',NULL,229,0,939.42,1162.29,0.00,0.00,215127.18,'2026-08-19 10:11:34','2026-08-19 10:11:34'),(68,NULL,41,'adjustment','ADJ-5-41',NULL,12,0,803.70,1096.52,0.00,0.00,9644.40,'2026-08-19 15:35:33','2026-08-19 15:35:33'),(69,12,17,'purchase','BT836412UXZY',NULL,122,0,33.91,103.38,0.00,0.00,4137.02,'2026-08-21 23:56:37','2026-08-21 23:56:37'),(70,13,37,'purchase','BT988918UFRA',NULL,677,0,219.41,352.26,0.00,0.00,148540.57,'2026-08-21 23:59:02','2026-08-21 23:59:02');
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `purchase_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_return_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_return_id` bigint unsigned NOT NULL,
  `purchase_item_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_return_items_purchase_return_id_index` (`purchase_return_id`),
  KEY `purchase_return_items_purchase_item_id_index` (`purchase_item_id`),
  KEY `purchase_return_items_medicine_id_index` (`medicine_id`),
  CONSTRAINT `purchase_return_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `purchase_return_items_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `purchase_return_items_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `purchase_return_items` WRITE;
/*!40000 ALTER TABLE `purchase_return_items` DISABLE KEYS */;
INSERT INTO `purchase_return_items` VALUES (3,2,6,7,'BT689150MXDB',NULL,1,574.67,574.67,'2026-08-18 02:47:40','2026-08-18 02:47:40'),(5,3,14,21,'BT733464WMLL',NULL,2,932.80,1865.60,'2026-08-18 03:10:52','2026-08-18 03:10:52');
/*!40000 ALTER TABLE `purchase_return_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `purchase_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_returns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `return_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `return_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('Fixed','Percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Fixed',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_type` enum('Fixed','Percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Fixed',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Completed','Draft','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Completed',
  `stock_applied` tinyint(1) NOT NULL DEFAULT '0',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_returns_return_number_unique` (`return_number`),
  KEY `purchase_returns_created_by_foreign` (`created_by`),
  KEY `purchase_returns_updated_by_foreign` (`updated_by`),
  KEY `purchase_returns_deleted_by_foreign` (`deleted_by`),
  KEY `purchase_returns_return_number_index` (`return_number`),
  KEY `purchase_returns_purchase_id_index` (`purchase_id`),
  KEY `purchase_returns_supplier_id_index` (`supplier_id`),
  KEY `purchase_returns_return_date_index` (`return_date`),
  KEY `purchase_returns_status_index` (`status`),
  CONSTRAINT `purchase_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_returns_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `purchase_returns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `purchase_returns` WRITE;
/*!40000 ALTER TABLE `purchase_returns` DISABLE KEYS */;
INSERT INTO `purchase_returns` VALUES (2,'PR-000001',7,3,'2026-08-18',574.67,'Fixed',0.00,'Fixed',0.00,574.67,'Completed',1,NULL,NULL,1,1,NULL,'2026-08-18 02:47:40','2026-08-18 02:47:40',NULL),(3,'PR-000003',11,12,'2026-08-18',1865.60,'Fixed',0.00,'Fixed',0.00,1865.60,'Completed',1,'asdfasdf','sldjf;lasjd;fl',1,1,NULL,'2026-08-18 03:01:05','2026-08-18 03:10:52',NULL);
/*!40000 ALTER TABLE `purchase_returns` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('Fixed','Percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Fixed',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_type` enum('Fixed','Percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Percentage',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(15,2) NOT NULL DEFAULT '0.00',
  `other_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('Paid','Partially Paid','Unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `status` enum('Draft','Completed','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Completed',
  `stock_applied` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_purchase_number_unique` (`purchase_number`),
  KEY `purchases_created_by_foreign` (`created_by`),
  KEY `purchases_updated_by_foreign` (`updated_by`),
  KEY `purchases_deleted_by_foreign` (`deleted_by`),
  KEY `purchases_purchase_number_index` (`purchase_number`),
  KEY `purchases_purchase_date_index` (`purchase_date`),
  KEY `purchases_invoice_number_index` (`invoice_number`),
  KEY `purchases_supplier_id_index` (`supplier_id`),
  KEY `purchases_status_index` (`status`),
  KEY `purchases_payment_status_index` (`payment_status`),
  CONSTRAINT `purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchases_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `purchases_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (2,'PUR-20260817-0001',NULL,'REF-20260817-0001',12,'2026-08-17',1401.20,'Fixed',0.00,'Percentage',0.00,0.00,0.00,1401.20,1401.20,0.00,'Paid','Cancelled',0,NULL,1,1,NULL,'2026-08-17 02:36:51','2026-08-17 07:54:03',NULL),(3,'PUR-20260817-0002',NULL,'REF-20260817-0002',16,'2026-08-17',2764.00,'Fixed',0.00,'Percentage',0.00,0.00,0.00,2764.00,0.00,2764.00,'Unpaid','Cancelled',0,NULL,1,NULL,NULL,'2026-08-17 02:39:49','2026-08-17 07:57:11',NULL),(4,'PUR-20260817-0003',NULL,'REF-20260817-0003',19,'2026-08-17',3852.52,'Fixed',0.00,'Percentage',0.00,0.00,0.00,3852.52,2000.00,1852.52,'Partially Paid','Cancelled',0,NULL,1,1,NULL,'2026-08-17 02:44:01','2026-08-17 07:58:19',NULL),(6,'PUR-20260817-0004',NULL,'REF-20260817-0004',11,'2026-08-17',0.00,'Fixed',0.00,'Percentage',0.00,0.00,0.00,0.00,0.00,0.00,'Unpaid','Draft',0,NULL,1,NULL,1,'2026-08-17 06:36:01','2026-08-17 06:45:27','2026-08-17 06:45:27'),(7,'PUR-20260817-0005',NULL,'REF-20260817-0005',3,'2026-08-17',574.67,'Fixed',0.00,'Percentage',0.00,0.00,0.00,574.67,574.67,0.00,'Paid','Completed',1,NULL,1,1,NULL,'2026-08-17 06:45:02','2026-08-17 06:47:24',NULL),(8,'PUR-20260817-0006',NULL,'REF-20260817-0006',11,'2026-08-17',656.88,'Fixed',0.00,'Percentage',0.00,0.00,0.00,656.88,656.88,0.00,'Paid','Completed',0,NULL,1,1,NULL,'2026-08-17 06:53:58','2026-08-19 15:40:57',NULL),(9,'PUR-20260817-0007',NULL,'REF-20260817-0007',13,'2026-08-17',135.64,'Fixed',0.00,'Percentage',0.00,0.00,0.00,135.64,135.64,0.00,'Paid','Completed',1,NULL,1,1,NULL,'2026-08-17 07:06:48','2026-08-17 07:52:39',NULL),(10,'PUR-20260817-0008',NULL,'REF-20260817-0008',11,'2026-08-17',2798.40,'Fixed',0.00,'Percentage',0.00,0.00,0.00,2798.40,2798.40,0.00,'Paid','Completed',0,NULL,1,1,NULL,'2026-08-17 07:27:04','2026-08-17 07:27:48',NULL),(11,'PUR-20260817-0009',NULL,'REF-20260817-0009',12,'2026-08-17',2798.40,'Fixed',0.00,'Percentage',0.00,0.00,0.00,2798.40,2798.40,0.00,'Paid','Completed',0,NULL,1,NULL,NULL,'2026-08-17 07:29:37','2026-08-17 08:44:17',NULL),(12,'PUR-20260822-0001',NULL,'REF-20260822-0001',12,'2026-08-22',4137.02,'Fixed',0.00,'Percentage',0.00,0.00,0.00,4137.02,4137.02,0.00,'Paid','Completed',1,NULL,1,NULL,NULL,'2026-08-21 23:56:37','2026-08-21 23:59:50',NULL),(13,'PUR-20260822-0002',NULL,'REF-20260822-0002',8,'2026-08-22',148540.57,'Fixed',0.00,'Percentage',0.00,0.00,0.00,148540.57,148540.57,0.00,'Paid','Draft',0,NULL,1,NULL,NULL,'2026-08-21 23:59:02','2026-08-21 23:59:16',NULL);
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1),(101,1),(102,1),(103,1),(104,1),(105,1),(106,1),(107,1),(108,1),(109,1),(110,1),(111,1),(112,1),(113,1),(114,1),(115,1),(116,1),(117,1),(118,1),(119,1),(120,1),(121,1),(122,1),(123,1),(124,1),(125,1),(126,1),(127,1),(128,1),(129,1),(130,1),(131,1),(132,1),(133,1),(134,1),(135,1),(136,1),(137,1),(138,1),(139,1),(140,1),(141,1),(142,1),(143,1),(144,1),(145,1),(146,1),(147,1),(148,1),(149,1),(150,1),(151,1),(152,1),(153,1),(154,1),(155,1),(156,1),(157,1),(158,1),(159,1),(160,1),(161,1),(162,1),(163,1),(164,1),(165,1),(166,1),(167,1),(168,1),(169,1),(170,1),(171,1),(172,1),(173,1),(174,1),(175,1),(176,1),(177,1),(178,1),(179,1),(180,1),(181,1),(182,1),(183,1),(184,1),(185,1),(186,1),(187,1),(188,1),(189,1),(190,1),(191,1),(192,1),(193,1),(194,1),(195,1),(196,1),(197,1),(198,1),(199,1),(200,1),(201,1),(202,1),(203,1),(204,1),(205,1),(206,1),(207,1),(208,1),(209,1),(210,1),(211,1),(212,1),(213,1),(214,1),(215,1),(216,1),(217,1),(218,1),(219,1),(220,1),(221,1),(222,1),(223,1),(224,1),(225,1),(226,1),(227,1),(228,1),(229,1),(230,1),(231,1),(232,1),(233,1),(234,1),(235,1),(236,1),(237,1),(238,1),(239,1),(240,1),(241,1),(242,1),(243,1),(244,1),(245,1),(246,1),(247,1),(248,1),(249,1),(250,1),(251,1),(252,1),(253,1),(254,1),(255,1),(256,1),(257,1),(258,1),(259,1),(260,1),(261,1),(262,1),(263,1),(264,1),(265,1),(266,1),(267,1),(268,1),(269,1),(270,1),(271,1),(272,1),(273,1),(274,1),(275,1),(276,1),(277,1),(278,1),(279,1),(280,1),(281,1),(282,1),(283,1),(284,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(39,2),(40,2),(41,2),(42,2),(43,2),(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(61,2),(62,2),(63,2),(64,2),(65,2),(66,2),(67,2),(68,2),(69,2),(70,2),(71,2),(72,2),(73,2),(74,2),(75,2),(76,2),(77,2),(78,2),(79,2),(80,2),(81,2),(82,2),(83,2),(84,2),(85,2),(86,2),(87,2),(88,2),(89,2),(90,2),(91,2),(92,2),(93,2),(94,2),(95,2),(96,2),(97,2),(98,2),(99,2),(100,2),(101,2),(102,2),(103,2),(104,2),(105,2),(106,2),(107,2),(108,2),(109,2),(110,2),(111,2),(112,2),(113,2),(114,2),(115,2),(116,2),(117,2),(118,2),(119,2),(120,2),(121,2),(122,2),(123,2),(124,2),(125,2),(126,2),(127,2),(128,2),(129,2),(130,2),(131,2),(132,2),(133,2),(134,2),(135,2),(136,2),(137,2),(138,2),(139,2),(140,2),(141,2),(142,2),(143,2),(144,2),(145,2),(146,2),(147,2),(148,2),(149,2),(150,2),(151,2),(152,2),(153,2),(154,2),(155,2),(156,2),(157,2),(158,2),(159,2),(160,2),(161,2),(162,2),(163,2),(164,2),(165,2),(166,2),(167,2),(168,2),(169,2),(170,2),(171,2),(172,2),(173,2),(174,2),(175,2),(176,2),(177,2),(178,2),(179,2),(180,2),(181,2),(182,2),(183,2),(184,2),(185,2),(186,2),(187,2),(188,2),(189,2),(190,2),(191,2),(192,2),(193,2),(194,2),(195,2),(196,2),(197,2),(198,2),(199,2),(200,2),(201,2),(202,2),(203,2),(204,2),(205,2),(206,2),(207,2),(208,2),(209,2),(210,2),(211,2),(212,2),(213,2),(214,2),(215,2),(216,2),(217,2),(218,2),(219,2),(220,2),(221,2),(222,2),(223,2),(224,2),(225,2),(226,2),(227,2),(228,2),(229,2),(230,2),(231,2),(232,2),(233,2),(234,2),(235,2),(236,2),(237,2),(238,2),(239,2),(240,2),(241,2),(242,2),(243,2),(244,2),(245,2),(246,2),(247,2),(248,2),(249,2),(250,2),(251,2),(252,2),(253,2),(254,2),(255,2),(256,2),(1,3),(9,3),(41,3),(42,3),(43,3),(49,3),(50,3),(51,3),(57,3),(58,3),(59,3),(65,3),(66,3),(67,3),(73,3),(74,3),(75,3),(76,3),(79,3),(80,3),(81,3),(82,3),(83,3),(89,3),(90,3),(91,3),(97,3),(98,3),(99,3),(113,3),(114,3),(115,3),(153,3),(177,3),(178,3),(179,3),(180,3),(181,3),(182,3),(1,4),(41,4),(49,4),(57,4),(65,4),(73,4),(74,4),(75,4),(89,4),(177,4),(178,4),(179,4),(180,4),(1,5),(73,5),(89,5),(90,5),(113,5),(114,5),(1,6),(41,6),(49,6),(57,6),(65,6),(73,6),(81,6),(97,6),(98,6),(178,6),(181,6);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','web','2026-08-16 08:56:04','2026-08-16 08:56:04',NULL),(2,'Admin','web','2026-08-16 08:56:04','2026-08-16 08:56:04',NULL),(3,'Manager','web','2026-08-16 08:56:04','2026-08-16 08:56:04',NULL),(4,'Pharmacist','web','2026-08-16 08:56:04','2026-08-16 08:56:04',NULL),(5,'Cashier','web','2026-08-16 08:56:04','2026-08-16 08:56:04',NULL),(6,'Store Keeper','web','2026-08-16 08:56:04','2026-08-16 08:56:04',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `purchase_item_id` bigint unsigned DEFAULT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` int NOT NULL,
  `free_quantity` int NOT NULL DEFAULT '0',
  `purchase_price` decimal(12,2) NOT NULL,
  `selling_price` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_medicine_id_foreign` (`medicine_id`),
  KEY `sale_items_purchase_item_id_foreign` (`purchase_item_id`),
  CONSTRAINT `sale_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,18,NULL,NULL,NULL,5,7,280.24,447.81,0.00,0.00,2239.05,'2026-08-18 06:12:06','2026-08-18 06:12:06'),(3,2,47,16,'OPENING-47',NULL,7,3,100.96,264.43,0.00,0.00,1851.01,'2026-08-18 11:09:04','2026-08-18 11:09:04'),(5,3,47,16,'OPENING-47',NULL,1,0,100.96,264.43,0.00,0.00,264.43,'2026-08-18 11:50:52','2026-08-18 11:50:52'),(6,4,2,3,'BT726384UJUN',NULL,7,0,552.80,806.81,0.00,0.00,5647.67,'2026-08-19 15:26:44','2026-08-19 15:26:44'),(7,4,2,19,'OPENING-2',NULL,0,3,552.80,806.81,0.00,0.00,0.00,'2026-08-19 15:26:44','2026-08-19 15:26:44'),(8,5,16,33,'OPENING-16',NULL,3,0,454.43,697.75,0.00,0.00,2093.25,'2026-08-20 22:58:04','2026-08-20 22:58:04'),(9,5,18,17,'ADJ-2-18','2025-08-20',1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-20 22:58:04','2026-08-20 22:58:04'),(10,5,25,42,'OPENING-25',NULL,4,0,314.75,338.54,0.00,0.00,1354.16,'2026-08-20 22:58:04','2026-08-20 22:58:04'),(11,5,36,53,'OPENING-36',NULL,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-20 22:58:04','2026-08-20 22:58:04'),(12,6,27,44,'OPENING-27',NULL,1,0,908.72,1068.80,0.00,0.00,1068.80,'2026-08-21 00:51:05','2026-08-21 00:51:05'),(13,7,23,40,'OPENING-23',NULL,1,0,754.12,972.29,0.00,0.00,972.29,'2026-08-21 02:30:29','2026-08-21 02:30:29'),(14,8,16,33,'OPENING-16',NULL,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-21 12:58:09','2026-08-21 12:58:09'),(15,8,18,17,'ADJ-2-18','2025-08-20',1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-21 12:58:09','2026-08-21 12:58:09'),(16,8,36,53,'OPENING-36',NULL,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-21 12:58:09','2026-08-21 12:58:09'),(17,9,22,39,'OPENING-22',NULL,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-21 12:59:12','2026-08-21 12:59:12'),(18,9,25,42,'OPENING-25',NULL,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-21 12:59:12','2026-08-21 12:59:12'),(19,9,37,54,'OPENING-37',NULL,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 12:59:12','2026-08-21 12:59:12'),(20,10,5,22,'OPENING-5',NULL,1,0,723.83,853.97,0.00,0.00,853.97,'2026-08-21 13:12:40','2026-08-21 13:12:40'),(21,10,12,29,'OPENING-12',NULL,1,0,779.97,1064.84,0.00,0.00,1064.84,'2026-08-21 13:12:40','2026-08-21 13:12:40'),(22,10,37,54,'OPENING-37',NULL,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 13:12:40','2026-08-21 13:12:40'),(23,10,42,59,'OPENING-42',NULL,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-21 13:12:40','2026-08-21 13:12:40'),(24,11,16,33,'OPENING-16',NULL,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-21 13:42:09','2026-08-21 13:42:09'),(25,11,37,54,'OPENING-37',NULL,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-21 13:42:09','2026-08-21 13:42:09'),(26,11,42,59,'OPENING-42',NULL,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-21 13:42:09','2026-08-21 13:42:09'),(27,12,16,33,'OPENING-16',NULL,2,0,454.43,697.75,0.00,0.00,1395.50,'2026-08-22 00:01:36','2026-08-22 00:01:36'),(28,12,18,17,'ADJ-2-18','2025-08-20',2,0,280.24,447.81,0.00,0.00,895.62,'2026-08-22 00:01:36','2026-08-22 00:01:36'),(29,12,25,42,'OPENING-25',NULL,7,0,314.75,338.54,0.00,0.00,2369.78,'2026-08-22 00:01:36','2026-08-22 00:01:36'),(30,12,36,53,'OPENING-36',NULL,2,0,549.22,763.72,0.00,0.00,1527.44,'2026-08-22 00:01:36','2026-08-22 00:01:36'),(31,12,37,54,'OPENING-37',NULL,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-22 00:01:36','2026-08-22 00:01:36'),(32,13,41,58,'OPENING-41',NULL,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 00:44:11','2026-08-22 00:44:11'),(33,14,25,42,'OPENING-25',NULL,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-22 00:49:09','2026-08-22 00:49:09'),(34,14,41,58,'OPENING-41',NULL,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 00:49:09','2026-08-22 00:49:09'),(35,15,41,58,'OPENING-41',NULL,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 00:49:36','2026-08-22 00:49:36'),(36,16,12,29,'OPENING-12',NULL,1,0,779.97,1064.84,0.00,0.00,1064.84,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(37,16,17,15,'BT264381GGML','2026-09-05',1,0,33.91,103.38,0.00,0.00,103.38,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(38,16,18,17,'ADJ-2-18','2025-08-20',1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(39,16,18,2,'BT489713XECD',NULL,2,0,280.24,447.81,0.00,0.00,895.62,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(40,16,28,4,'BT504684IOPW','2026-08-31',1,0,963.13,1213.78,0.00,0.00,1213.78,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(41,16,30,47,'OPENING-30',NULL,1,0,514.78,619.17,0.00,0.00,619.17,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(42,16,37,54,'OPENING-37',NULL,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(43,16,41,58,'OPENING-41',NULL,1,0,803.70,1096.52,0.00,0.00,1096.52,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(44,16,47,16,'OPENING-47','2026-08-19',1,0,100.96,264.43,0.00,0.00,264.43,'2026-08-22 01:28:03','2026-08-22 01:28:03'),(45,17,5,22,'OPENING-5',NULL,5,0,723.83,853.97,0.00,0.00,4269.85,'2026-08-22 14:17:48','2026-08-22 14:17:48'),(46,17,18,2,'BT489713XECD',NULL,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-22 14:17:48','2026-08-22 14:17:48'),(47,17,22,39,'OPENING-22',NULL,1,0,588.36,671.97,0.00,0.00,671.97,'2026-08-22 14:17:48','2026-08-22 14:17:48'),(48,17,36,53,'OPENING-36',NULL,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-22 14:17:48','2026-08-22 14:17:48'),(49,17,37,54,'OPENING-37',NULL,1,0,219.41,352.26,0.00,0.00,352.26,'2026-08-22 14:17:48','2026-08-22 14:17:48'),(50,17,42,59,'OPENING-42',NULL,1,0,620.68,636.33,0.00,0.00,636.33,'2026-08-22 14:17:48','2026-08-22 14:17:48'),(51,18,18,2,'BT489713XECD',NULL,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-25 12:45:51','2026-08-25 12:45:51'),(52,18,25,42,'OPENING-25',NULL,2,0,314.75,338.54,0.00,0.00,677.08,'2026-08-25 12:45:51','2026-08-25 12:45:51'),(53,18,36,53,'OPENING-36',NULL,1,0,549.22,763.72,0.00,0.00,763.72,'2026-08-25 12:45:51','2026-08-25 12:45:51'),(54,18,37,54,'OPENING-37',NULL,6,0,219.41,352.26,0.00,0.00,2113.56,'2026-08-25 12:45:51','2026-08-25 12:45:51'),(55,19,18,2,'BT489713XECD',NULL,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-28 12:54:25','2026-08-28 12:54:25'),(56,19,25,42,'OPENING-25',NULL,2,0,314.75,338.54,0.00,0.00,677.08,'2026-08-28 12:54:25','2026-08-28 12:54:25'),(57,20,16,33,'OPENING-16',NULL,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-28 13:01:28','2026-08-28 13:01:28'),(58,21,16,33,'OPENING-16',NULL,1,0,454.43,697.75,0.00,0.00,697.75,'2026-08-29 09:39:37','2026-08-29 09:39:37'),(59,21,25,42,'OPENING-25',NULL,1,0,314.75,338.54,0.00,0.00,338.54,'2026-08-29 09:39:37','2026-08-29 09:39:37');
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sale_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_return_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_return_id` bigint unsigned NOT NULL,
  `sale_item_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `purchase_item_id` bigint unsigned DEFAULT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '0',
  `free_quantity` int unsigned NOT NULL DEFAULT '0',
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_return_items_sale_return_id_foreign` (`sale_return_id`),
  KEY `sale_return_items_sale_item_id_foreign` (`sale_item_id`),
  KEY `sale_return_items_medicine_id_index` (`medicine_id`),
  KEY `sale_return_items_purchase_item_id_index` (`purchase_item_id`),
  CONSTRAINT `sale_return_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sale_return_items_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_return_items_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sale_return_items_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sale_return_items` WRITE;
/*!40000 ALTER TABLE `sale_return_items` DISABLE KEYS */;
INSERT INTO `sale_return_items` VALUES (1,1,1,18,NULL,NULL,NULL,1,0,280.24,447.81,0.00,0.00,447.81,'2026-08-18 10:07:49','2026-08-18 10:07:49'),(2,2,5,47,16,'OPENING-47',NULL,1,0,100.96,264.43,0.00,0.00,264.43,'2026-08-18 12:05:16','2026-08-18 12:05:16'),(3,3,1,18,NULL,NULL,NULL,3,2,280.24,447.81,0.00,0.00,1343.43,'2026-08-19 14:48:25','2026-08-19 14:48:25'),(4,4,3,47,16,'OPENING-47',NULL,4,1,100.96,264.43,0.00,0.00,1057.72,'2026-08-19 15:23:01','2026-08-19 15:23:01'),(5,5,6,2,3,'BT726384UJUN',NULL,3,0,552.80,806.81,0.00,0.00,2420.43,'2026-08-19 15:28:46','2026-08-19 15:28:46'),(6,5,7,2,19,'OPENING-2',NULL,0,2,552.80,806.81,0.00,0.00,0.00,'2026-08-19 15:28:46','2026-08-19 15:28:46'),(7,6,3,47,16,'OPENING-47',NULL,1,0,100.96,264.43,0.00,0.00,264.43,'2026-08-19 15:32:47','2026-08-19 15:32:47');
/*!40000 ALTER TABLE `sale_return_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sale_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_returns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `return_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `other_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sale_returns_return_number_unique` (`return_number`),
  KEY `sale_returns_sale_id_foreign` (`sale_id`),
  KEY `sale_returns_customer_id_foreign` (`customer_id`),
  KEY `sale_returns_created_by_foreign` (`created_by`),
  KEY `sale_returns_updated_by_foreign` (`updated_by`),
  KEY `sale_returns_deleted_by_foreign` (`deleted_by`),
  KEY `sale_returns_return_date_index` (`return_date`),
  KEY `sale_returns_status_index` (`status`),
  CONSTRAINT `sale_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_returns_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sale_returns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sale_returns` WRITE;
/*!40000 ALTER TABLE `sale_returns` DISABLE KEYS */;
INSERT INTO `sale_returns` VALUES (1,1,59,'SRET-355542','2026-08-18',447.81,0.00,0.00,0.00,447.81,'completed',NULL,1,1,NULL,'2026-08-18 10:07:49','2026-08-18 10:09:23',NULL),(2,3,59,'SRET-642023','2026-08-18',264.43,0.00,0.00,0.00,264.43,'completed',NULL,1,1,NULL,'2026-08-18 12:05:16','2026-08-18 12:05:16',NULL),(3,1,59,'SRET-786974','2026-08-19',1343.43,0.00,0.00,0.00,1343.43,'completed',NULL,1,1,NULL,'2026-08-19 14:48:25','2026-08-19 14:48:25',NULL),(4,2,59,'SRET-432119','2026-08-19',1057.72,0.00,0.00,0.00,1057.72,'completed',NULL,1,1,NULL,'2026-08-19 15:23:01','2026-08-19 15:23:01',NULL),(5,4,59,'SRET-662580','2026-08-19',2420.43,0.00,0.00,0.00,2420.43,'completed',NULL,1,1,NULL,'2026-08-19 15:28:46','2026-08-19 15:28:46',NULL),(6,2,59,'SRET-953721','2026-08-19',264.43,0.00,0.00,0.00,264.43,'draft',NULL,1,1,NULL,'2026-08-19 15:32:47','2026-08-19 15:32:47',NULL);
/*!40000 ALTER TABLE `sale_returns` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `doctor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_date` date NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `other_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('paid','partial','due') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'due',
  `status` enum('draft','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_invoice_number_unique` (`invoice_number`),
  KEY `sales_customer_id_foreign` (`customer_id`),
  KEY `sales_created_by_foreign` (`created_by`),
  KEY `sales_updated_by_foreign` (`updated_by`),
  KEY `sales_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'SAL-000001',59,'New','2026-08-18',2239.05,'fixed',0.00,'fixed',0.00,0.00,0.00,2239.05,0.00,2239.05,'due','completed','khkjhjkhkkhjkjhk',1,1,NULL,'2026-08-18 06:12:06','2026-08-21 23:59:58',NULL),(2,'SAL-000002',91,'New','2026-08-18',1851.01,'fixed',0.00,'fixed',0.00,0.00,0.00,1851.01,1851.01,0.00,'paid','completed',NULL,1,1,NULL,'2026-08-18 06:25:30','2026-08-21 12:53:09',NULL),(3,'SAL-000003',59,'New','2026-08-18',264.43,'fixed',0.00,'fixed',0.00,0.00,0.00,264.43,264.43,0.00,'paid','completed',NULL,1,1,NULL,'2026-08-18 11:50:07','2026-08-21 12:56:06',NULL),(4,'SAL-000004',30,'Lelia Cummerata','2026-08-19',5647.67,'fixed',0.00,'fixed',0.00,0.00,0.00,5647.67,5647.67,0.00,'paid','completed','asdfasd',1,1,NULL,'2026-08-19 15:26:43','2026-08-20 00:55:37',NULL),(5,'SAL-000005',21,NULL,'2026-08-21',4658.94,'fixed',400.00,'fixed',0.00,0.00,0.00,4258.94,4258.94,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-20 22:58:04','2026-08-20 22:58:04',NULL),(6,'SAL-000006',NULL,NULL,'2026-08-21',1068.80,'fixed',56.00,'fixed',0.00,0.00,0.00,1012.80,1012.80,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-21 00:51:05','2026-08-21 00:51:05',NULL),(7,'SAL-000007',NULL,NULL,'2026-08-21',972.29,'fixed',0.00,'fixed',0.00,0.00,0.00,972.29,972.29,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-21 02:30:29','2026-08-21 02:30:29',NULL),(8,'SAL-000008',NULL,NULL,'2026-08-21',1909.28,'fixed',0.00,'fixed',0.00,0.00,0.00,1909.28,1909.28,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-21 12:58:09','2026-08-21 12:58:09',NULL),(9,'SAL-000009',NULL,NULL,'2026-08-21',1362.77,'fixed',0.00,'fixed',0.00,0.00,0.00,1362.77,1362.77,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-21 12:59:12','2026-08-21 13:10:25',NULL),(10,'SAL-000010',62,NULL,'2026-08-21',2907.40,'fixed',0.00,'fixed',0.00,0.00,0.00,2907.40,2907.40,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-21 13:12:40','2026-08-21 13:13:03',NULL),(11,'SAL-000011',49,NULL,'2026-08-21',1686.34,'fixed',0.00,'fixed',0.00,0.00,0.00,1686.34,1686.34,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-21 13:42:09','2026-08-21 23:55:43',NULL),(12,'SAL-000012',NULL,NULL,'2026-08-22',6540.60,'fixed',0.00,'fixed',0.00,0.00,0.00,6540.60,6540.60,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-22 00:01:36','2026-08-22 00:01:36',NULL),(13,'SAL-000013',NULL,NULL,'2026-08-22',1096.52,'fixed',0.00,'fixed',0.00,0.00,0.00,1096.52,1096.52,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-22 00:44:11','2026-08-22 00:44:11',NULL),(14,'SAL-000014',NULL,NULL,'2026-08-22',1435.06,'fixed',0.00,'fixed',0.00,0.00,0.00,1435.06,143.00,1292.06,'partial','completed',NULL,1,NULL,NULL,'2026-08-22 00:49:09','2026-08-22 00:49:09',NULL),(15,'SAL-000015',NULL,NULL,'2026-08-22',1096.52,'fixed',0.00,'fixed',0.00,0.00,0.00,1096.52,0.00,1096.52,'due','completed',NULL,1,NULL,NULL,'2026-08-22 00:49:36','2026-08-22 00:49:36',NULL),(16,'SAL-000016',16,NULL,'2026-08-22',6057.81,'fixed',300.00,'fixed',0.00,0.00,0.00,5757.81,5757.81,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-22 01:28:03','2026-08-22 01:28:03',NULL),(17,'SAL-000017',NULL,NULL,'2026-08-22',7141.94,'fixed',0.00,'fixed',0.00,0.00,0.00,7141.94,7141.94,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-22 14:17:48','2026-08-22 14:17:48',NULL),(18,'SAL-000018',NULL,NULL,'2026-08-25',4002.17,'fixed',300.00,'fixed',0.00,0.00,0.00,3702.17,3702.17,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-25 12:45:51','2026-08-25 12:45:51',NULL),(19,'SAL-000019',NULL,NULL,'2026-08-28',1124.89,'fixed',0.00,'fixed',0.00,0.00,0.00,1124.89,1124.89,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-28 12:54:25','2026-08-28 12:54:25',NULL),(20,'SAL-000020',NULL,NULL,'2026-08-28',697.75,'fixed',0.00,'fixed',0.00,0.00,0.00,697.75,697.75,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-28 13:01:28','2026-08-28 13:01:28',NULL),(21,'SAL-000021',NULL,NULL,'2026-08-29',1036.29,'fixed',0.00,'fixed',0.00,0.00,0.00,1036.29,1036.29,0.00,'paid','completed',NULL,1,NULL,NULL,'2026-08-29 09:39:37','2026-08-29 09:39:37',NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('nv4XN7viHatHlGRT9esuGPnYO8yfiZf8wxBnKvLD',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJuMjVZWkdvRkJta1o4NTZBNUM0dEZ2dE80dWR5TUtTVDNiUzJrYmp1IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9iYWNrdXBzIiwicm91dGUiOiJiYWNrdXBzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsImF1dGgiOnsicGFzc3dvcmRfY29uZmlybWVkX2F0IjoxNzg4MTgxMTM1fX0=',1788187669);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'store_name','Awami Medical Store','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(2,'owner_name','Awami','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(3,'email','ishaq24529@gmail.com','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(4,'phone','03151250978','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(5,'address','Frontier Colony No: 3, Orangi Town','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(6,'currency','PKR','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(7,'timezone','Asia/Karachi','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05'),(8,'tax_percentage','0','string',NULL,'2026-08-20 00:54:05','2026-08-20 00:54:05');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `stock_adjustment_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_adjustment_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_adjustment_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `purchase_item_id` bigint unsigned DEFAULT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustment_items_stock_adjustment_id_index` (`stock_adjustment_id`),
  KEY `stock_adjustment_items_medicine_id_index` (`medicine_id`),
  KEY `stock_adjustment_items_purchase_item_id_index` (`purchase_item_id`),
  KEY `stock_adjustment_items_batch_number_index` (`batch_number`),
  CONSTRAINT `stock_adjustment_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_adjustment_items_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_adjustment_items_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `stock_adjustment_items` WRITE;
/*!40000 ALTER TABLE `stock_adjustment_items` DISABLE KEYS */;
INSERT INTO `stock_adjustment_items` VALUES (1,1,21,13,'BT958641LWNF',NULL,2,932.80,1229.26,NULL,'2026-08-19 05:56:42','2026-08-19 05:56:42'),(2,2,18,17,'ADJ-2-18',NULL,5,280.24,447.81,NULL,'2026-08-19 06:45:49','2026-08-19 06:46:32'),(3,3,47,16,'OPENING-47',NULL,101,100.96,264.43,NULL,'2026-08-19 07:22:26','2026-08-19 07:22:26'),(4,4,47,16,'OPENING-47',NULL,50,100.96,264.43,NULL,'2026-08-19 07:48:12','2026-08-19 07:48:12'),(5,5,41,68,'ADJ-5-41',NULL,12,803.70,1096.52,NULL,'2026-08-19 10:13:46','2026-08-19 15:35:33');
/*!40000 ALTER TABLE `stock_adjustment_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `stock_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `adjustment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('increase','decrease') COLLATE utf8mb4_unicode_ci NOT NULL,
  `adjustment_date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `stock_applied` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_adjustments_adjustment_number_unique` (`adjustment_number`),
  KEY `stock_adjustments_created_by_foreign` (`created_by`),
  KEY `stock_adjustments_updated_by_foreign` (`updated_by`),
  KEY `stock_adjustments_deleted_by_foreign` (`deleted_by`),
  KEY `stock_adjustments_adjustment_number_index` (`adjustment_number`),
  KEY `stock_adjustments_type_index` (`type`),
  KEY `stock_adjustments_adjustment_date_index` (`adjustment_date`),
  KEY `stock_adjustments_status_index` (`status`),
  KEY `stock_adjustments_stock_applied_index` (`stock_applied`),
  CONSTRAINT `stock_adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_adjustments_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_adjustments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `stock_adjustments` WRITE;
/*!40000 ALTER TABLE `stock_adjustments` DISABLE KEYS */;
INSERT INTO `stock_adjustments` VALUES (1,'ADJ-20260819-0001','decrease','2026-08-19','phyasical count correction','draft','completed',1,1,1,NULL,'2026-08-19 05:56:42','2026-08-19 06:30:07',NULL),(2,'ADJ-20260819-0002','increase','2026-08-19','sdSDsd',NULL,'completed',1,1,1,NULL,'2026-08-19 06:45:49','2026-08-19 06:46:32',NULL),(3,'ADJ-20260819-0003','decrease','2026-08-19','asdfasdfa','sdfds','completed',1,1,1,NULL,'2026-08-19 07:22:26','2026-08-19 07:22:52',NULL),(4,'ADJ-20260819-0004','decrease','2026-08-19','dfgdfgsdfgsdfs','adgfasdfa','completed',1,1,1,NULL,'2026-08-19 07:48:12','2026-08-19 07:48:12',NULL),(5,'ADJ-20260819-0005','increase','2026-08-19','asdfasdfasd','asdfasdfasd','completed',1,1,1,NULL,'2026-08-19 10:13:46','2026-08-19 15:35:33',NULL);
/*!40000 ALTER TABLE `stock_adjustments` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pakistan',
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ntn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_type` enum('Payable','Receivable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Payable',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_supplier_code_unique` (`supplier_code`),
  KEY `suppliers_created_by_foreign` (`created_by`),
  KEY `suppliers_updated_by_foreign` (`updated_by`),
  KEY `suppliers_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `suppliers_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `suppliers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'SUP-00001','Rempel Group','Batz LLC','Antonetta Grant PhD','703.681.8041','+1 (603) 713-8330','oortiz@example.com','http://www.cassin.com/doloribus-eos-placeat-rerum-nesciunt-possimus-vitae-odit.html','63380 Cathrine Garden\nJastchester, OH 15653-0807','Schulistland','Montana','Pakistan','52819-5114','086567-8','13729548395',14310.12,'Payable','Maxime ullam eum rerum eum voluptatem voluptas quia.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:28','2026-08-16 09:04:28'),(2,'SUP-00002','Batz Ltd','Jaskolski-Hamill','Dr. Ernie Frami','(929) 719-5050','872.931.4208','qfahey@example.org','https://www.gleason.biz/porro-vero-et-temporibus-nisi','60974 Bulah Trail\nEast Autumnmouth, NC 30335-9318','South Julian','Arizona','Pakistan','46683','368402-6','03652661258',35942.97,'Receivable','Et nobis temporibus totam porro in.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:28','2026-08-16 09:04:28'),(3,'SUP-00003','Parker, Schumm and Bahringer','O\'Hara, Cartwright and Ullrich','Marty Rice','272.566.9074','575-391-0177','dubuque.janessa@example.net','http://murazik.com/','1892 Wehner Field Suite 402\nHaleybury, TN 83282-1455','Jaquelinview','Hawaii','Pakistan','85066','364201-6','89853612132',2673.49,'Receivable','Et consequatur est ex dolores vel harum.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:28','2026-08-16 09:04:28'),(4,'SUP-00004','Zieme, Raynor and Brown','Langworth-Ullrich','Verda Medhurst','+1-785-470-1345','+1 (256) 872-3744','audra.roob@example.com','http://www.block.net/tempora-quidem-rerum-odio-consequatur-dolor-recusandae','334 Kimberly Expressway\nNew Brice, MN 73028','South Ansley','Massachusetts','Pakistan','43397','537115-2','95888512466',33115.73,'Receivable','Autem est sequi distinctio ut non tempore omnis dolor.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(5,'SUP-00005','Pfannerstill Inc','Boyle-Braun','Monte Boehm','(931) 680-1904','254.734.4365','sabryna07@example.com','http://pollich.com/quasi-qui-optio-aliquam-earum-enim-et-temporibus','921 Darrel Shoals Suite 488\nWest Yvetteview, PA 55526','Lake Katelinfurt','New Jersey','Pakistan','89077-0757','136657-6','85494575578',21070.02,'Payable','Delectus sequi culpa nulla dolore cumque fuga aut.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(6,'SUP-00006','Hodkiewicz-Parisian','Fadel and Sons','Ryan Conn','539-323-1729','442-754-7372','harvey.alexandro@example.net','http://www.jakubowski.com/dolorem-iure-aut-quidem-rem-aut-est-sed.html','23944 Lind Mall Apt. 789\nHegmannfort, LA 56132','Timmothychester','District of Columbia','Pakistan','41942-4273','244779-8','37848171415',24374.10,'Payable','Itaque dolor repudiandae quibusdam qui harum.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(7,'SUP-00007','Windler Ltd','Miller LLC','Tamara Cormier','423.300.9505','1-971-410-0428','darrell.schumm@example.net','https://beahan.info/eum-quas-excepturi-ut-delectus-neque-sit-dignissimos.html','277 Louvenia Passage Apt. 233\nAlfredaton, ME 90696-8911','Collierhaven','South Dakota','Pakistan','70415','540100-4','23447020537',45874.30,'Receivable','Enim veniam aperiam architecto temporibus.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(8,'SUP-00008','Hermann-Stracke','Toy LLC','Mr. Charley Williamson','1-248-601-8587','276.843.9406','angie95@example.org','http://hintz.com/vel-repellendus-assumenda-corrupti-maiores-dolores-sit-in','458 Rosemarie Valleys Suite 741\nBeahanmouth, ME 84317-8550','West Savanna','Vermont','Pakistan','15939-6252','950671-1','22697711111',46773.89,'Receivable','Pariatur qui praesentium nobis eos.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(9,'SUP-00009','Flatley-Stracke','Sanford and Sons','Idell Haag','+1-518-513-9248','+12345089576','gutkowski.jarod@example.com','http://www.dicki.info/nam-ut-repellendus-sunt-rerum-iusto.html','1892 Pfeffer Point\nAnissaberg, OH 07039-1573','West Luther','Utah','Pakistan','23602','071850-3','75198698072',10792.95,'Receivable','Similique exercitationem et magni minus amet.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(10,'SUP-00010','Schneider-Jacobs','Cummings Inc','Prof. Vivian Stehr Sr.','+1-954-601-5607','+16809162990','yconn@example.org','https://herman.net/et-tenetur-repudiandae-suscipit-sunt-accusantium-facilis-modi.html','52355 Tyra Prairie Suite 976\nPort Sadieborough, IL 26664','Hartmannbury','District of Columbia','Pakistan','21719-8151','711767-9','21022428358',2392.02,'Receivable','Vitae illum hic aperiam quia sed ut.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(11,'SUP-00011','Price LLC','Gutmann Group','Karine Auer','1-678-329-6091','+1-815-990-6730','wintheiser.harley@example.com','http://www.collier.net/','5430 Crystel Way Suite 648\nNitzschefort, CO 08784','Schmidtville','Michigan','Pakistan','52186','199535-4','08069026162',48578.26,'Receivable','Repudiandae iure qui qui eos numquam.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(12,'SUP-00012','Abernathy-Cummerata','Ziemann Ltd','Guillermo Von DDS','+14097070937','1-848-779-0182','dickinson.ila@example.org','http://www.lubowitz.net/','2719 Swift Extensions Apt. 457\nEloisaport, AK 93401-2700','Anitamouth','Minnesota','Pakistan','22383','234148-0','52665241030',34493.25,'Payable','Doloribus dignissimos porro est.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(13,'SUP-00013','Armstrong Ltd','Stanton Inc','Miss Maria Lemke','(681) 353-8940','+1.838.980.7857','emmett.greenfelder@example.net','https://www.jones.biz/est-neque-maxime-omnis-deserunt-sunt-consequatur-deleniti-pariatur','5384 Ziemann Lane Suite 221\nMariohaven, AK 02527','Runolfsdottirshire','New Jersey','Pakistan','72486-7604','692353-8','58684563441',12294.53,'Receivable','Perspiciatis facere ratione deleniti perspiciatis dicta maxime amet laboriosam.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(14,'SUP-00014','Konopelski, Heathcote and Denesik','Weissnat Ltd','Coby DuBuque','+1 (940) 786-4312','+1-316-589-8225','katheryn.yost@example.net','http://www.mayer.com/eum-rem-nihil-nam-alias-quia-eaque.html','869 Anjali Mews Suite 598\nElbertstad, DC 98264-4616','Port Baronville','Tennessee','Pakistan','58830-0814','990722-3','03545307943',11680.34,'Receivable','Deserunt placeat voluptate quasi.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(15,'SUP-00015','Marquardt, Kozey and Towne','Pfannerstill-Reynolds','Mrs. Elyssa Waters','856-248-3943','1-862-286-5765','moses.turcotte@example.com','http://www.renner.com/enim-rem-aut-cumque-ut-perferendis','1139 Quigley Track\nMantefurt, NC 84204-7312','Quintonchester','District of Columbia','Pakistan','87370-0105','462019-5','68365472303',30838.94,'Receivable','Rerum in sequi et laudantium perferendis similique inventore.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(16,'SUP-00016','Reichert-Welch','Upton and Sons','Art Hackett','620.521.7616','+16175687402','edwardo.stokes@example.com','http://www.yundt.info/placeat-sunt-sequi-molestiae-quis-sequi-expedita','45384 Schultz Station\nEast Kaitlynside, MN 45498','Hodkiewiczberg','Pennsylvania','Pakistan','87067','480921-8','95516035292',46793.64,'Payable','Ad non natus est atque.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(17,'SUP-00017','Walsh-Crooks','Dickens and Sons','Miss Lelah Spencer V','732.570.2197','+16285425634','nader.trevor@example.net','http://koepp.com/corrupti-minima-quia-eligendi-illum-accusantium-amet-molestiae','39762 Frederique Valleys Apt. 579\nWest Edd, HI 88747','New Michele','Delaware','Pakistan','19840','511753-2','45031899022',7899.49,'Receivable','Dolorem similique debitis harum iure adipisci necessitatibus quo pariatur.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(18,'SUP-00018','Welch, Aufderhar and Lubowitz','Conroy-Lang','Sasha Keeling','+1.484.952.5500','+12696726475','modesto.kohler@example.org','http://bechtelar.net/','949 Spencer Valleys Apt. 586\nLake Danberg, OH 03410-1581','Harrisstad','Missouri','Pakistan','26491','795684-2','14085468878',2705.27,'Payable','Qui ea eum aperiam perferendis.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(19,'SUP-00019','Murphy, McGlynn and Corwin','Kris LLC','Maude Dickinson','831-414-4170','1-747-546-6935','waino15@example.com','http://www.labadie.com/','91750 Upton Port Apt. 408\nWest Asha, PA 37035','North Nya','Oregon','Pakistan','06351','531661-4','75900592107',4981.76,'Receivable','Similique aperiam in eius quis.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29'),(20,'SUP-00020','Crona, Beahan and Okuneva','Runte, Gulgowski and Farrell','Prof. Elnora Braun','1-573-370-8255','+1.657.717.7817','hirthe.oral@example.com','http://schumm.net/cum-repellat-omnis-commodi.html','1827 Paula Glen\nWeissnatport, CT 19326-9119','Ricoland','Nebraska','Pakistan','88653-2125','515487-1','06417057125',10718.05,'Payable','Eaque et possimus optio.',1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:04:29','2026-08-16 09:04:29');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `units_created_by_foreign` (`created_by`),
  KEY `units_updated_by_foreign` (`updated_by`),
  KEY `units_deleted_by_foreign` (`deleted_by`),
  KEY `units_status_index` (`status`),
  KEY `units_sort_order_index` (`sort_order`),
  CONSTRAINT `units_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `units_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `units_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'Tablet','Tab',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(2,'Capsule','Cap',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(3,'Bottle','Btl',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(4,'Strip','Strip',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(5,'Box','Box',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(6,'Tube','Tube',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(7,'Ampoule','Amp',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(8,'Vial','Vial',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(9,'Sachet','Sach',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(10,'Piece','Pcs',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(11,'Milliliter','ml',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(12,'Liter','L',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(13,'Gram','g',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49'),(14,'Kilogram','kg',NULL,1,0,NULL,NULL,NULL,NULL,'2026-08-16 09:02:49','2026-08-16 09:02:49');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pakistan',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_created_by_foreign` (`created_by`),
  KEY `users_updated_by_foreign` (`updated_by`),
  KEY `users_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin@example.com','03000000000',NULL,NULL,NULL,NULL,'Pakistan',NULL,'$2y$12$jOxBf7YGw4g7e3wxGZaGR.52NKnyPX8dy6eBhpAsYrhQaUZZTLTfi',NULL,NULL,1,NULL,NULL,NULL,NULL,'2026-08-16 08:55:36','2026-08-16 08:55:36'),(2,'Administrator','admin@example.com','03111111111',NULL,NULL,NULL,NULL,'Pakistan',NULL,'$2y$12$xbudi7fwRZPmW/OxCxpTa.eU9eN9AUZ9VaYtRw6uVJOyYKQaf0Bzy',NULL,NULL,1,NULL,NULL,NULL,NULL,'2026-08-16 08:57:02','2026-08-16 08:57:02'),(3,'Manager','manager@example.com','03222222222',NULL,NULL,NULL,NULL,'Pakistan',NULL,'$2y$12$igOryn5zsXid9jk/SGaty.XzMFjdFCQPtZROO8/eSugvtJmVR/yO2',NULL,NULL,1,NULL,NULL,NULL,NULL,'2026-08-16 08:57:02','2026-08-16 08:57:02'),(4,'Pharmacist','pharmacist@example.com','03333333333',NULL,NULL,NULL,NULL,'Pakistan',NULL,'$2y$12$d2azmX67d1l.r97QdPFs/O1zqqnCqCagEwSfAc.HAFpgeLygVV5ki',NULL,NULL,1,NULL,NULL,NULL,NULL,'2026-08-16 08:57:02','2026-08-16 08:57:02'),(5,'Cashier','cashier@example.com','03444444444',NULL,NULL,NULL,NULL,'Pakistan',NULL,'$2y$12$8Iiwy6CILMq2u7yN1mE2CuJKosN.Z6oBar7i2Dm7JkWmtx9T4rTci',NULL,NULL,1,NULL,NULL,NULL,NULL,'2026-08-16 08:57:03','2026-08-16 08:57:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

