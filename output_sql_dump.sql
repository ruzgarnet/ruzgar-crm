/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contract_type_id` bigint(20) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  UNIQUE KEY `categories_key_unique` (`key`),
  KEY `categories_contract_type_id_foreign` (`contract_type_id`),
  CONSTRAINT `categories_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'Adana'),(2,'Adıyaman'),(3,'Afyonkarahisar'),(4,'Ağrı'),(5,'Aksaray'),(6,'Amasya'),(7,'Ankara'),(8,'Antalya'),(9,'Ardahan'),(10,'Artvin'),(11,'Aydın'),(12,'Balıkesir'),(13,'Bartın'),(14,'Batman'),(15,'Bayburt'),(16,'Bilecik'),(17,'Bingöl'),(18,'Bitlis'),(19,'Bolu'),(20,'Burdur'),(21,'Bursa'),(22,'Çanakkale'),(23,'Çankırı'),(24,'Çorum'),(25,'Denizli'),(26,'Diyarbakır'),(27,'Düzce'),(28,'Edirne'),(29,'Elazığ'),(30,'Erzincan'),(31,'Erzurum'),(32,'Eskişehir'),(33,'Gaziantep'),(34,'Giresun'),(35,'Gümüşhane'),(36,'Hakkari'),(37,'Hatay'),(38,'Iğdır'),(39,'Isparta'),(40,'İstanbul'),(41,'İzmir'),(42,'Kahramanmaraş'),(43,'Karabük'),(44,'Karaman'),(45,'Kars'),(46,'Kastamonu'),(47,'Kayseri'),(48,'Kırıkkale'),(49,'Kırklareli'),(50,'Kırşehir'),(51,'Kilis'),(52,'Kocaeli'),(53,'Konya'),(54,'Kütahya'),(55,'Malatya'),(56,'Manisa'),(57,'Mardin'),(58,'Mersin'),(59,'Muğla'),(60,'Muş'),(61,'Nevşehir'),(62,'Niğde'),(63,'Ordu'),(64,'Osmaniye'),(65,'Rize'),(66,'Sakarya'),(67,'Samsun'),(68,'Siirt'),(69,'Sinop'),(70,'Sivas'),(71,'Şanlıurfa'),(72,'Şırnak'),(73,'Tekirdağ'),(74,'Tokat'),(75,'Trabzon'),(76,'Tunceli'),(77,'Uşak'),(78,'Van'),(79,'Yalova'),(80,'Yozgat'),(81,'Zonguldak');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
DROP TABLE IF EXISTS `contract_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `view` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `contract_types_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `contract_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract_types` ENABLE KEYS */;
DROP TABLE IF EXISTS `customer_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `secondary_telephone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(3) unsigned NOT NULL,
  `birthday` date NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `district_id` bigint(20) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_info_customer_id_foreign` (`customer_id`),
  KEY `customer_info_city_id_foreign` (`city_id`),
  KEY `customer_info_district_id_foreign` (`district_id`),
  CONSTRAINT `customer_info_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_info_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_info_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `customer_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_info` ENABLE KEYS */;
DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_no` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `reference_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_customer_no_unique` (`customer_no`),
  UNIQUE KEY `customers_identification_number_unique` (`identification_number`),
  UNIQUE KEY `customers_reference_code_unique` (`reference_code`),
  UNIQUE KEY `customers_payment_code_unique` (`payment_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
DROP TABLE IF EXISTS `dealers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dealers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `district_id` bigint(20) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `started_at` date NOT NULL,
  `ended_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `dealers_tax_number_unique` (`tax_number`),
  KEY `dealers_city_id_foreign` (`city_id`),
  KEY `dealers_district_id_foreign` (`district_id`),
  CONSTRAINT `dealers_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dealers_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `dealers` DISABLE KEYS */;
INSERT INTO `dealers` VALUES (1,'RuzgarMERKEZ','000',62,752,'Merkez/Niğde','5550005500','2021-05-12',NULL,'2021-05-25 07:08:56',NULL);
/*!40000 ALTER TABLE `dealers` ENABLE KEYS */;
DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `districts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_city_id_foreign` (`city_id`),
  CONSTRAINT `districts_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=974 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
INSERT INTO `districts` VALUES (1,1,'Aladağ'),(2,1,'Ceyhan'),(3,1,'Çukurova'),(4,1,'Feke'),(5,1,'İmamoğlu'),(6,1,'Karaisalı'),(7,1,'Karataş'),(8,1,'Kozan'),(9,1,'Pozantı'),(10,1,'Saimbeyli'),(11,1,'Sarıçam'),(12,1,'Seyhan'),(13,1,'Tufanbeyli'),(14,1,'Yumurtalık'),(15,1,'Yüreğir'),(16,2,'Besni'),(17,2,'Çelikhan'),(18,2,'Gerger'),(19,2,'Gölbaşı'),(20,2,'Kahta'),(21,2,'Merkez'),(22,2,'Samsat'),(23,2,'Sincik'),(24,2,'Tut'),(25,3,'Başmakçı'),(26,3,'Bayat'),(27,3,'Bolvadin'),(28,3,'Çay'),(29,3,'Çobanlar'),(30,3,'Dazkırı'),(31,3,'Dinar'),(32,3,'Emirdağ'),(33,3,'Evciler'),(34,3,'Hocalar'),(35,3,'İhsaniye'),(36,3,'İscehisar'),(37,3,'Kızılören'),(38,3,'Merkez'),(39,3,'Sandıklı'),(40,3,'Sinanpaşa'),(41,3,'Sultandağı'),(42,3,'Şuhut'),(43,4,'Diyadin'),(44,4,'Doğubayazıt'),(45,4,'Eleşkirt'),(46,4,'Hamur'),(47,4,'Merkez'),(48,4,'Patnos'),(49,4,'Taşlıçay'),(50,4,'Tutak'),(51,5,'Ağaçören'),(52,5,'Eskil'),(53,5,'Gülağaç'),(54,5,'Güzelyurt'),(55,5,'Merkez'),(56,5,'Ortaköy'),(57,5,'Sarıyahşi'),(58,5,'Sultanhanı'),(59,6,'Göynücek'),(60,6,'Gümüşhacıköy'),(61,6,'Hamamözü'),(62,6,'Merkez'),(63,6,'Merzifon'),(64,6,'Suluova'),(65,6,'Taşova'),(66,7,'Akyurt'),(67,7,'Altındağ'),(68,7,'Ayaş'),(69,7,'Bala'),(70,7,'Beypazarı'),(71,7,'Çamlıdere'),(72,7,'Çankaya'),(73,7,'Çubuk'),(74,7,'Elmadağ'),(75,7,'Etimesgut'),(76,7,'Evren'),(77,7,'Gölbaşı'),(78,7,'Güdül'),(79,7,'Haymana'),(80,7,'Kahramankazan'),(81,7,'Kalecik'),(82,7,'Keçiören'),(83,7,'Kızılcahamam'),(84,7,'Mamak'),(85,7,'Nallıhan'),(86,7,'Polatlı'),(87,7,'Pursaklar'),(88,7,'Sincan'),(89,7,'Şereflikoçhisar'),(90,7,'Yenimahalle'),(91,8,'Akseki'),(92,8,'Aksu'),(93,8,'Alanya'),(94,8,'Demre'),(95,8,'Döşemealtı'),(96,8,'Elmalı'),(97,8,'Finike'),(98,8,'Gazipaşa'),(99,8,'Gündoğmuş'),(100,8,'İbradı'),(101,8,'Kaş'),(102,8,'Kemer'),(103,8,'Kepez'),(104,8,'Konyaaltı'),(105,8,'Korkuteli'),(106,8,'Kumluca'),(107,8,'Manavgat'),(108,8,'Muratpaşa'),(109,8,'Serik'),(110,9,'Çıldır'),(111,9,'Damal'),(112,9,'Göle'),(113,9,'Hanak'),(114,9,'Merkez'),(115,9,'Posof'),(116,10,'Ardanuç'),(117,10,'Arhavi'),(118,10,'Borçka'),(119,10,'Hopa'),(120,10,'Kemalpaşa'),(121,10,'Merkez'),(122,10,'Murgul'),(123,10,'Şavşat'),(124,10,'Yusufeli'),(125,11,'Bozdoğan'),(126,11,'Buharkent'),(127,11,'Çine'),(128,11,'Didim'),(129,11,'Efeler'),(130,11,'Germencik'),(131,11,'İncirliova'),(132,11,'Karacasu'),(133,11,'Karpuzlu'),(134,11,'Koçarlı'),(135,11,'Köşk'),(136,11,'Kuşadası'),(137,11,'Kuyucak'),(138,11,'Nazilli'),(139,11,'Söke'),(140,11,'Sultanhisar'),(141,11,'Yenipazar'),(142,12,'Altıeylül'),(143,12,'Ayvalık'),(144,12,'Balya'),(145,12,'Bandırma'),(146,12,'Bigadiç'),(147,12,'Burhaniye'),(148,12,'Dursunbey'),(149,12,'Edremit'),(150,12,'Erdek'),(151,12,'Gömeç'),(152,12,'Gönen'),(153,12,'Havran'),(154,12,'İvrindi'),(155,12,'Karesi'),(156,12,'Kepsut'),(157,12,'Manyas'),(158,12,'Marmara'),(159,12,'Savaştepe'),(160,12,'Sındırgı'),(161,12,'Susurluk'),(162,13,'Amasra'),(163,13,'Kurucaşile'),(164,13,'Merkez'),(165,13,'Ulus'),(166,14,'Beşiri'),(167,14,'Gercüş'),(168,14,'Hasankeyf'),(169,14,'Kozluk'),(170,14,'Merkez'),(171,14,'Sason'),(172,15,'Aydıntepe'),(173,15,'Demirözü'),(174,15,'Merkez'),(175,16,'Bozüyük'),(176,16,'Gölpazarı'),(177,16,'İnhisar'),(178,16,'Merkez'),(179,16,'Osmaneli'),(180,16,'Pazaryeri'),(181,16,'Söğüt'),(182,16,'Yenipazar'),(183,17,'Adaklı'),(184,17,'Genç'),(185,17,'Karlıova'),(186,17,'Kiğı'),(187,17,'Merkez'),(188,17,'Solhan'),(189,17,'Yayladere'),(190,17,'Yedisu'),(191,18,'Adilcevaz'),(192,18,'Ahlat'),(193,18,'Güroymak'),(194,18,'Hizan'),(195,18,'Merkez'),(196,18,'Mutki'),(197,18,'Tatvan'),(198,19,'Dörtdivan'),(199,19,'Gerede'),(200,19,'Göynük'),(201,19,'Kıbrıscık'),(202,19,'Mengen'),(203,19,'Merkez'),(204,19,'Mudurnu'),(205,19,'Seben'),(206,19,'Yeniçağa'),(207,20,'Ağlasun'),(208,20,'Altınyayla'),(209,20,'Bucak'),(210,20,'Çavdır'),(211,20,'Çeltikçi'),(212,20,'Gölhisar'),(213,20,'Karamanlı'),(214,20,'Kemer'),(215,20,'Merkez'),(216,20,'Tefenni'),(217,20,'Yeşilova'),(218,21,'Büyükorhan'),(219,21,'Gemlik'),(220,21,'Gürsu'),(221,21,'Harmancık'),(222,21,'İnegöl'),(223,21,'İznik'),(224,21,'Karacabey'),(225,21,'Keles'),(226,21,'Kestel'),(227,21,'Mudanya'),(228,21,'Mustafakemalpaşa'),(229,21,'Nilüfer'),(230,21,'Orhaneli'),(231,21,'Orhangazi'),(232,21,'Osmangazi'),(233,21,'Yenişehir'),(234,21,'Yıldırım'),(235,22,'Ayvacık'),(236,22,'Bayramiç'),(237,22,'Biga'),(238,22,'Bozcaada'),(239,22,'Çan'),(240,22,'Eceabat'),(241,22,'Ezine'),(242,22,'Gelibolu'),(243,22,'Gökçeada'),(244,22,'Lapseki'),(245,22,'Merkez'),(246,22,'Yenice'),(247,23,'Atkaracalar'),(248,23,'Bayramören'),(249,23,'Çerkeş'),(250,23,'Eldivan'),(251,23,'Ilgaz'),(252,23,'Kızılırmak'),(253,23,'Korgun'),(254,23,'Kurşunlu'),(255,23,'Merkez'),(256,23,'Orta'),(257,23,'Şabanözü'),(258,23,'Yapraklı'),(259,24,'Alaca'),(260,24,'Bayat'),(261,24,'Boğazkale'),(262,24,'Dodurga'),(263,24,'İskilip'),(264,24,'Kargı'),(265,24,'Laçin'),(266,24,'Mecitözü'),(267,24,'Merkez'),(268,24,'Oğuzlar'),(269,24,'Ortaköy'),(270,24,'Osmancık'),(271,24,'Sungurlu'),(272,24,'Uğurludağ'),(273,25,'Acıpayam'),(274,25,'Babadağ'),(275,25,'Baklan'),(276,25,'Bekilli'),(277,25,'Beyağaç'),(278,25,'Bozkurt'),(279,25,'Buldan'),(280,25,'Çal'),(281,25,'Çameli'),(282,25,'Çardak'),(283,25,'Çivril'),(284,25,'Güney'),(285,25,'Honaz'),(286,25,'Kale'),(287,25,'Merkezefendi'),(288,25,'Pamukkale'),(289,25,'Sarayköy'),(290,25,'Serinhisar'),(291,25,'Tavas'),(292,26,'Bağlar'),(293,26,'Bismil'),(294,26,'Çermik'),(295,26,'Çınar'),(296,26,'Çüngüş'),(297,26,'Dicle'),(298,26,'Eğil'),(299,26,'Ergani'),(300,26,'Hani'),(301,26,'Hazro'),(302,26,'Kayapınar'),(303,26,'Kocaköy'),(304,26,'Kulp'),(305,26,'Lice'),(306,26,'Silvan'),(307,26,'Sur'),(308,26,'Yenişehir'),(309,27,'Akçakoca'),(310,27,'Cumayeri'),(311,27,'Çilimli'),(312,27,'Gölyaka'),(313,27,'Gümüşova'),(314,27,'Kaynaşlı'),(315,27,'Merkez'),(316,27,'Yığılca'),(317,28,'Enez'),(318,28,'Havsa'),(319,28,'İpsala'),(320,28,'Keşan'),(321,28,'Lalapaşa'),(322,28,'Meriç'),(323,28,'Merkez'),(324,28,'Süloğlu'),(325,28,'Uzunköprü'),(326,29,'Ağın'),(327,29,'Alacakaya'),(328,29,'Arıcak'),(329,29,'Baskil'),(330,29,'Karakoçan'),(331,29,'Keban'),(332,29,'Kovancılar'),(333,29,'Maden'),(334,29,'Merkez'),(335,29,'Palu'),(336,29,'Sivrice'),(337,30,'Çayırlı'),(338,30,'İliç'),(339,30,'Kemah'),(340,30,'Kemaliye'),(341,30,'Merkez'),(342,30,'Otlukbeli'),(343,30,'Refahiye'),(344,30,'Tercan'),(345,30,'Üzümlü'),(346,31,'Aşkale'),(347,31,'Aziziye'),(348,31,'Çat'),(349,31,'Hınıs'),(350,31,'Horasan'),(351,31,'İspir'),(352,31,'Karaçoban'),(353,31,'Karayazı'),(354,31,'Köprüköy'),(355,31,'Narman'),(356,31,'Oltu'),(357,31,'Olur'),(358,31,'Palandöken'),(359,31,'Pasinler'),(360,31,'Pazaryolu'),(361,31,'Şenkaya'),(362,31,'Tekman'),(363,31,'Tortum'),(364,31,'Uzundere'),(365,31,'Yakutiye'),(366,32,'Alpu'),(367,32,'Beylikova'),(368,32,'Çifteler'),(369,32,'Günyüzü'),(370,32,'Han'),(371,32,'İnönü'),(372,32,'Mahmudiye'),(373,32,'Mihalgazi'),(374,32,'Mihalıççık'),(375,32,'Odunpazarı'),(376,32,'Sarıcakaya'),(377,32,'Seyitgazi'),(378,32,'Sivrihisar'),(379,32,'Tepebaşı'),(380,33,'Araban'),(381,33,'İslahiye'),(382,33,'Karkamış'),(383,33,'Nizip'),(384,33,'Nurdağı'),(385,33,'Oğuzeli'),(386,33,'Şahinbey'),(387,33,'Şehitkamil'),(388,33,'Yavuzeli'),(389,34,'Alucra'),(390,34,'Bulancak'),(391,34,'Çamoluk'),(392,34,'Çanakçı'),(393,34,'Dereli'),(394,34,'Doğankent'),(395,34,'Espiye'),(396,34,'Eynesil'),(397,34,'Görele'),(398,34,'Güce'),(399,34,'Keşap'),(400,34,'Merkez'),(401,34,'Piraziz'),(402,34,'Şebinkarahisar'),(403,34,'Tirebolu'),(404,34,'Yağlıdere'),(405,35,'Kelkit'),(406,35,'Köse'),(407,35,'Kürtün'),(408,35,'Merkez'),(409,35,'Şiran'),(410,35,'Torul'),(411,36,'Çukurca'),(412,36,'Derecik'),(413,36,'Merkez'),(414,36,'Şemdinli'),(415,36,'Yüksekova'),(416,37,'Altınözü'),(417,37,'Antakya'),(418,37,'Arsuz'),(419,37,'Belen'),(420,37,'Defne'),(421,37,'Dörtyol'),(422,37,'Erzin'),(423,37,'Hassa'),(424,37,'İskenderun'),(425,37,'Kırıkhan'),(426,37,'Kumlu'),(427,37,'Payas'),(428,37,'Reyhanlı'),(429,37,'Samandağ'),(430,37,'Yayladağı'),(431,38,'Aralık'),(432,38,'Karakoyunlu'),(433,38,'Merkez'),(434,38,'Tuzluca'),(435,39,'Aksu'),(436,39,'Atabey'),(437,39,'Eğirdir'),(438,39,'Gelendost'),(439,39,'Gönen'),(440,39,'Keçiborlu'),(441,39,'Merkez'),(442,39,'Senirkent'),(443,39,'Sütçüler'),(444,39,'Şarkikaraağaç'),(445,39,'Uluborlu'),(446,39,'Yalvaç'),(447,39,'Yenişarbademli'),(448,40,'Adalar'),(449,40,'Arnavutköy'),(450,40,'Ataşehir'),(451,40,'Avcılar'),(452,40,'Bağcılar'),(453,40,'Bahçelievler'),(454,40,'Bakırköy'),(455,40,'Başakşehir'),(456,40,'Bayrampaşa'),(457,40,'Beşiktaş'),(458,40,'Beykoz'),(459,40,'Beylikdüzü'),(460,40,'Beyoğlu'),(461,40,'Büyükçekmece'),(462,40,'Çatalca'),(463,40,'Çekmeköy'),(464,40,'Esenler'),(465,40,'Esenyurt'),(466,40,'Eyüpsultan'),(467,40,'Fatih'),(468,40,'Gaziosmanpaşa'),(469,40,'Güngören'),(470,40,'Kadıköy'),(471,40,'Kağıthane'),(472,40,'Kartal'),(473,40,'Küçükçekmece'),(474,40,'Maltepe'),(475,40,'Pendik'),(476,40,'Sancaktepe'),(477,40,'Sarıyer'),(478,40,'Silivri'),(479,40,'Sultanbeyli'),(480,40,'Sultangazi'),(481,40,'Şile'),(482,40,'Şişli'),(483,40,'Tuzla'),(484,40,'Ümraniye'),(485,40,'Üsküdar'),(486,40,'Zeytinburnu'),(487,41,'Aliağa'),(488,41,'Balçova'),(489,41,'Bayındır'),(490,41,'Bayraklı'),(491,41,'Bergama'),(492,41,'Beydağ'),(493,41,'Bornova'),(494,41,'Buca'),(495,41,'Çeşme'),(496,41,'Çiğli'),(497,41,'Dikili'),(498,41,'Foça'),(499,41,'Gaziemir'),(500,41,'Güzelbahçe'),(501,41,'Karabağlar'),(502,41,'Karaburun'),(503,41,'Karşıyaka'),(504,41,'Kemalpaşa'),(505,41,'Kınık'),(506,41,'Kiraz'),(507,41,'Konak'),(508,41,'Menderes'),(509,41,'Menemen'),(510,41,'Narlıdere'),(511,41,'Ödemiş'),(512,41,'Seferihisar'),(513,41,'Selçuk'),(514,41,'Tire'),(515,41,'Torbalı'),(516,41,'Urla'),(517,42,'Afşin'),(518,42,'Andırın'),(519,42,'Çağlayancerit'),(520,42,'Dulkadiroğlu'),(521,42,'Ekinözü'),(522,42,'Elbistan'),(523,42,'Göksun'),(524,42,'Nurhak'),(525,42,'Onikişubat'),(526,42,'Pazarcık'),(527,42,'Türkoğlu'),(528,43,'Eflani'),(529,43,'Eskipazar'),(530,43,'Merkez'),(531,43,'Ovacık'),(532,43,'Safranbolu'),(533,43,'Yenice'),(534,44,'Ayrancı'),(535,44,'Başyayla'),(536,44,'Ermenek'),(537,44,'Kazımkarabekir'),(538,44,'Merkez'),(539,44,'Sarıveliler'),(540,45,'Akyaka'),(541,45,'Arpaçay'),(542,45,'Digor'),(543,45,'Kağızman'),(544,45,'Merkez'),(545,45,'Sarıkamış'),(546,45,'Selim'),(547,45,'Susuz'),(548,46,'Abana'),(549,46,'Ağlı'),(550,46,'Araç'),(551,46,'Azdavay'),(552,46,'Bozkurt'),(553,46,'Cide'),(554,46,'Çatalzeytin'),(555,46,'Daday'),(556,46,'Devrekani'),(557,46,'Doğanyurt'),(558,46,'Hanönü'),(559,46,'İhsangazi'),(560,46,'İnebolu'),(561,46,'Küre'),(562,46,'Merkez'),(563,46,'Pınarbaşı'),(564,46,'Seydiler'),(565,46,'Şenpazar'),(566,46,'Taşköprü'),(567,46,'Tosya'),(568,47,'Akkışla'),(569,47,'Bünyan'),(570,47,'Develi'),(571,47,'Felahiye'),(572,47,'Hacılar'),(573,47,'İncesu'),(574,47,'Kocasinan'),(575,47,'Melikgazi'),(576,47,'Özvatan'),(577,47,'Pınarbaşı'),(578,47,'Sarıoğlan'),(579,47,'Sarız'),(580,47,'Talas'),(581,47,'Tomarza'),(582,47,'Yahyalı'),(583,47,'Yeşilhisar'),(584,48,'Bahşılı'),(585,48,'Balışeyh'),(586,48,'Çelebi'),(587,48,'Delice'),(588,48,'Karakeçili'),(589,48,'Keskin'),(590,48,'Merkez'),(591,48,'Sulakyurt'),(592,48,'Yahşihan'),(593,49,'Babaeski'),(594,49,'Demirköy'),(595,49,'Kofçaz'),(596,49,'Lüleburgaz'),(597,49,'Merkez'),(598,49,'Pehlivanköy'),(599,49,'Pınarhisar'),(600,49,'Vize'),(601,50,'Akçakent'),(602,50,'Akpınar'),(603,50,'Boztepe'),(604,50,'Çiçekdağı'),(605,50,'Kaman'),(606,50,'Merkez'),(607,50,'Mucur'),(608,51,'Elbeyli'),(609,51,'Merkez'),(610,51,'Musabeyli'),(611,51,'Polateli'),(612,52,'Başiskele'),(613,52,'Çayırova'),(614,52,'Darıca'),(615,52,'Derince'),(616,52,'Dilovası'),(617,52,'Gebze'),(618,52,'Gölcük'),(619,52,'İzmit'),(620,52,'Kandıra'),(621,52,'Karamürsel'),(622,52,'Kartepe'),(623,52,'Körfez'),(624,53,'Ahırlı'),(625,53,'Akören'),(626,53,'Akşehir'),(627,53,'Altınekin'),(628,53,'Beyşehir'),(629,53,'Bozkır'),(630,53,'Cihanbeyli'),(631,53,'Çeltik'),(632,53,'Çumra'),(633,53,'Derbent'),(634,53,'Derebucak'),(635,53,'Doğanhisar'),(636,53,'Emirgazi'),(637,53,'Ereğli'),(638,53,'Güneysınır'),(639,53,'Hadim'),(640,53,'Halkapınar'),(641,53,'Hüyük'),(642,53,'Ilgın'),(643,53,'Kadınhanı'),(644,53,'Karapınar'),(645,53,'Karatay'),(646,53,'Kulu'),(647,53,'Meram'),(648,53,'Sarayönü'),(649,53,'Selçuklu'),(650,53,'Seydişehir'),(651,53,'Taşkent'),(652,53,'Tuzlukçu'),(653,53,'Yalıhüyük'),(654,53,'Yunak'),(655,54,'Altıntaş'),(656,54,'Aslanapa'),(657,54,'Çavdarhisar'),(658,54,'Domaniç'),(659,54,'Dumlupınar'),(660,54,'Emet'),(661,54,'Gediz'),(662,54,'Hisarcık'),(663,54,'Merkez'),(664,54,'Pazarlar'),(665,54,'Simav'),(666,54,'Şaphane'),(667,54,'Tavşanlı'),(668,55,'Akçadağ'),(669,55,'Arapgir'),(670,55,'Arguvan'),(671,55,'Battalgazi'),(672,55,'Darende'),(673,55,'Doğanşehir'),(674,55,'Doğanyol'),(675,55,'Hekimhan'),(676,55,'Kale'),(677,55,'Kuluncak'),(678,55,'Pütürge'),(679,55,'Yazıhan'),(680,55,'Yeşilyurt'),(681,56,'Ahmetli'),(682,56,'Akhisar'),(683,56,'Alaşehir'),(684,56,'Demirci'),(685,56,'Gölmarmara'),(686,56,'Gördes'),(687,56,'Kırkağaç'),(688,56,'Köprübaşı'),(689,56,'Kula'),(690,56,'Salihli'),(691,56,'Sarıgöl'),(692,56,'Saruhanlı'),(693,56,'Selendi'),(694,56,'Soma'),(695,56,'Şehzadeler'),(696,56,'Turgutlu'),(697,56,'Yunusemre'),(698,57,'Artuklu'),(699,57,'Dargeçit'),(700,57,'Derik'),(701,57,'Kızıltepe'),(702,57,'Mazıdağı'),(703,57,'Midyat'),(704,57,'Nusaybin'),(705,57,'Ömerli'),(706,57,'Savur'),(707,57,'Yeşilli'),(708,58,'Akdeniz'),(709,58,'Anamur'),(710,58,'Aydıncık'),(711,58,'Bozyazı'),(712,58,'Çamlıyayla'),(713,58,'Erdemli'),(714,58,'Gülnar'),(715,58,'Mezitli'),(716,58,'Mut'),(717,58,'Silifke'),(718,58,'Tarsus'),(719,58,'Toroslar'),(720,58,'Yenişehir'),(721,59,'Bodrum'),(722,59,'Dalaman'),(723,59,'Datça'),(724,59,'Fethiye'),(725,59,'Kavaklıdere'),(726,59,'Köyceğiz'),(727,59,'Marmaris'),(728,59,'Menteşe'),(729,59,'Milas'),(730,59,'Ortaca'),(731,59,'Seydikemer'),(732,59,'Ula'),(733,59,'Yatağan'),(734,60,'Bulanık'),(735,60,'Hasköy'),(736,60,'Korkut'),(737,60,'Malazgirt'),(738,60,'Merkez'),(739,60,'Varto'),(740,61,'Acıgöl'),(741,61,'Avanos'),(742,61,'Derinkuyu'),(743,61,'Gülşehir'),(744,61,'Hacıbektaş'),(745,61,'Kozaklı'),(746,61,'Merkez'),(747,61,'Ürgüp'),(748,62,'Altunhisar'),(749,62,'Bor'),(750,62,'Çamardı'),(751,62,'Çiftlik'),(752,62,'Merkez'),(753,62,'Ulukışla'),(754,63,'Akkuş'),(755,63,'Altınordu'),(756,63,'Aybastı'),(757,63,'Çamaş'),(758,63,'Çatalpınar'),(759,63,'Çaybaşı'),(760,63,'Fatsa'),(761,63,'Gölköy'),(762,63,'Gülyalı'),(763,63,'Gürgentepe'),(764,63,'İkizce'),(765,63,'Kabadüz'),(766,63,'Kabataş'),(767,63,'Korgan'),(768,63,'Kumru'),(769,63,'Mesudiye'),(770,63,'Perşembe'),(771,63,'Ulubey'),(772,63,'Ünye'),(773,64,'Bahçe'),(774,64,'Düziçi'),(775,64,'Hasanbeyli'),(776,64,'Kadirli'),(777,64,'Merkez'),(778,64,'Sumbas'),(779,64,'Toprakkale'),(780,65,'Ardeşen'),(781,65,'Çamlıhemşin'),(782,65,'Çayeli'),(783,65,'Derepazarı'),(784,65,'Fındıklı'),(785,65,'Güneysu'),(786,65,'Hemşin'),(787,65,'İkizdere'),(788,65,'İyidere'),(789,65,'Kalkandere'),(790,65,'Merkez'),(791,65,'Pazar'),(792,66,'Adapazarı'),(793,66,'Akyazı'),(794,66,'Arifiye'),(795,66,'Erenler'),(796,66,'Ferizli'),(797,66,'Geyve'),(798,66,'Hendek'),(799,66,'Karapürçek'),(800,66,'Karasu'),(801,66,'Kaynarca'),(802,66,'Kocaali'),(803,66,'Pamukova'),(804,66,'Sapanca'),(805,66,'Serdivan'),(806,66,'Söğütlü'),(807,66,'Taraklı'),(808,67,'19 Mayıs'),(809,67,'Alaçam'),(810,67,'Asarcık'),(811,67,'Atakum'),(812,67,'Ayvacık'),(813,67,'Bafra'),(814,67,'Canik'),(815,67,'Çarşamba'),(816,67,'Havza'),(817,67,'İlkadım'),(818,67,'Kavak'),(819,67,'Ladik'),(820,67,'Salıpazarı'),(821,67,'Tekkeköy'),(822,67,'Terme'),(823,67,'Vezirköprü'),(824,67,'Yakakent'),(825,68,'Baykan'),(826,68,'Eruh'),(827,68,'Kurtalan'),(828,68,'Merkez'),(829,68,'Pervari'),(830,68,'Şirvan'),(831,68,'Tillo'),(832,69,'Ayancık'),(833,69,'Boyabat'),(834,69,'Dikmen'),(835,69,'Durağan'),(836,69,'Erfelek'),(837,69,'Gerze'),(838,69,'Merkez'),(839,69,'Saraydüzü'),(840,69,'Türkeli'),(841,70,'Akıncılar'),(842,70,'Altınyayla'),(843,70,'Divriği'),(844,70,'Doğanşar'),(845,70,'Gemerek'),(846,70,'Gölova'),(847,70,'Gürün'),(848,70,'Hafik'),(849,70,'İmranlı'),(850,70,'Kangal'),(851,70,'Koyulhisar'),(852,70,'Merkez'),(853,70,'Suşehri'),(854,70,'Şarkışla'),(855,70,'Ulaş'),(856,70,'Yıldızeli'),(857,70,'Zara'),(858,71,'Akçakale'),(859,71,'Birecik'),(860,71,'Bozova'),(861,71,'Ceylanpınar'),(862,71,'Eyyübiye'),(863,71,'Halfeti'),(864,71,'Haliliye'),(865,71,'Harran'),(866,71,'Hilvan'),(867,71,'Karaköprü'),(868,71,'Siverek'),(869,71,'Suruç'),(870,71,'Viranşehir'),(871,72,'Beytüşşebap'),(872,72,'Cizre'),(873,72,'Güçlükonak'),(874,72,'İdil'),(875,72,'Merkez'),(876,72,'Silopi'),(877,72,'Uludere'),(878,73,'Çerkezköy'),(879,73,'Çorlu'),(880,73,'Ergene'),(881,73,'Hayrabolu'),(882,73,'Kapaklı'),(883,73,'Malkara'),(884,73,'Marmaraereğlisi'),(885,73,'Muratlı'),(886,73,'Saray'),(887,73,'Süleymanpaşa'),(888,73,'Şarköy'),(889,74,'Almus'),(890,74,'Artova'),(891,74,'Başçiftlik'),(892,74,'Erbaa'),(893,74,'Merkez'),(894,74,'Niksar'),(895,74,'Pazar'),(896,74,'Reşadiye'),(897,74,'Sulusaray'),(898,74,'Turhal'),(899,74,'Yeşilyurt'),(900,74,'Zile'),(901,75,'Akçaabat'),(902,75,'Araklı'),(903,75,'Arsin'),(904,75,'Beşikdüzü'),(905,75,'Çarşıbaşı'),(906,75,'Çaykara'),(907,75,'Dernekpazarı'),(908,75,'Düzköy'),(909,75,'Hayrat'),(910,75,'Köprübaşı'),(911,75,'Maçka'),(912,75,'Of'),(913,75,'Ortahisar'),(914,75,'Sürmene'),(915,75,'Şalpazarı'),(916,75,'Tonya'),(917,75,'Vakfıkebir'),(918,75,'Yomra'),(919,76,'Çemişgezek'),(920,76,'Hozat'),(921,76,'Mazgirt'),(922,76,'Merkez'),(923,76,'Nazımiye'),(924,76,'Ovacık'),(925,76,'Pertek'),(926,76,'Pülümür'),(927,77,'Banaz'),(928,77,'Eşme'),(929,77,'Karahallı'),(930,77,'Merkez'),(931,77,'Sivaslı'),(932,77,'Ulubey'),(933,78,'Bahçesaray'),(934,78,'Başkale'),(935,78,'Çaldıran'),(936,78,'Çatak'),(937,78,'Edremit'),(938,78,'Erciş'),(939,78,'Gevaş'),(940,78,'Gürpınar'),(941,78,'İpekyolu'),(942,78,'Muradiye'),(943,78,'Özalp'),(944,78,'Saray'),(945,78,'Tuşba'),(946,79,'Altınova'),(947,79,'Armutlu'),(948,79,'Çınarcık'),(949,79,'Çiftlikköy'),(950,79,'Merkez'),(951,79,'Termal'),(952,80,'Akdağmadeni'),(953,80,'Aydıncık'),(954,80,'Boğazlıyan'),(955,80,'Çandır'),(956,80,'Çayıralan'),(957,80,'Çekerek'),(958,80,'Kadışehri'),(959,80,'Merkez'),(960,80,'Saraykent'),(961,80,'Sarıkaya'),(962,80,'Sorgun'),(963,80,'Şefaatli'),(964,80,'Yenifakılı'),(965,80,'Yerköy'),(966,81,'Alaplı'),(967,81,'Çaycuma'),(968,81,'Devrek'),(969,81,'Ereğli'),(970,81,'Gökçebey'),(971,81,'Kilimli'),(972,81,'Kozlu'),(973,81,'Merkez');
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2021_05_10_113512_create_cities_table',1),(2,'2021_05_10_114948_create_dealers_table',1),(3,'2021_05_10_115357_create_staff_table',1),(4,'2021_05_10_120000_create_users_table',1),(5,'2021_05_10_120100_create_password_resets_table',1),(6,'2021_05_10_120200_create_customers_table',1),(7,'2021_05_10_121050_create_contract_types_table',1),(8,'2021_05_10_121303_create_categories_table',1),(9,'2021_05_10_121754_create_products_table',1),(10,'2021_05_10_124641_create_services_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_model_unique` (`model`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_model_unique` (`model`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_category_id_foreign` (`category_id`),
  CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dealer_id` bigint(20) unsigned NOT NULL,
  `identification_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` tinyint(3) unsigned NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secondary_telephone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `started_at` date NOT NULL,
  `released_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_identification_number_unique` (`identification_number`),
  KEY `staff_dealer_id_foreign` (`dealer_id`),
  CONSTRAINT `staff_dealer_id_foreign` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,1,'00000000000','Rüzgar','Personel',1,'5550005500','info@ruzgarnet.com.tr',NULL,'2021-05-12','Merkez/Niğde','2021-05-12',NULL,'2021-05-25 07:10:08',NULL);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_staff_id_foreign` (`staff_id`),
  CONSTRAINT `users_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'admin','info@ruzgarnet.com.tr','$2y$10$fd.EE2OFZ3MxPfTKGc48Sui9gy0Ti4JlZwdYgJNbibDKdNqtS1J.q',NULL,'2021-05-25 07:11:48','2021-05-25 07:11:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

