-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: localhost    Database: ce
-- ------------------------------------------------------
-- Server version	5.7.32-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `backup`
--

DROP TABLE IF EXISTS `backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(1024) CHARACTER SET utf8mb4 NOT NULL COMMENT '备份文件名',
  `shijian` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '备份时间',
  `type` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '备份类型，0：数据库；1：文件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup`
--

LOCK TABLES `backup` WRITE;
/*!40000 ALTER TABLE `backup` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bgszichan`
--

DROP TABLE IF EXISTS `bgszichan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bgszichan` (
  `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `zcbh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产编号',
  `xlh` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '序列号',
  `zclx` int(10) unsigned NOT NULL COMMENT '资产类型',
  `cw` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zczt` int(10) unsigned NOT NULL COMMENT '资产状态I',
  `bm` int(10) unsigned NOT NULL COMMENT '所属单位',
  `bgr` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '保管人',
  `dz` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '保存地址',
  `cgsj` int(10) unsigned DEFAULT NULL COMMENT '采购时间',
  `rzsj` int(10) unsigned DEFAULT NULL COMMENT '入账时间',
  `zbsc` int(2) DEFAULT NULL COMMENT '质保时长（年）',
  `sysc` int(2) DEFAULT NULL COMMENT '报废年限（年）',
  `pp` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌',
  `xh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '型号',
  `zcly` varchar(10) CHARACTER SET utf8mb4 DEFAULT '自购' COMMENT '资产来源',
  `zcjz` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '资产价值',
  `gg` varchar(1024) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '规格',
  `bz` text CHARACTER SET utf8mb4 COMMENT '备注',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产图片',
  `ll` text CHARACTER SET utf8mb4 COMMENT '履历',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='办公室资产数据表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bgszichan`
--

LOCK TABLES `bgszichan` WRITE;
/*!40000 ALTER TABLE `bgszichan` DISABLE KEYS */;
/*!40000 ALTER TABLE `bgszichan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 NOT NULL COMMENT '项目',
  `value` varchar(1024) CHARACTER SET utf8mb4 NOT NULL COMMENT '项目制',
  `kapian` text CHARACTER SET utf8mb4 NOT NULL COMMENT '卡片模板',
  `kpyl` mediumtext CHARACTER SET utf8mb4 COMMENT '卡片模板HTML',
  `sm` varchar(1024) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'xxzxauto','0','',NULL,'信息中心资产添加后是否保留上次数值'),(2,'bgsauto','1','',NULL,''),(3,'wgbauto','1','',NULL,''),(4,'xxkapian','{\"title\":\"信息中心资产卡片\",\"ma\":\"1\",\"bianma\":\"xlh\",\"content\":[\"zcbh\",\"xlh\",\"zclx\",\"cw\",\"bm\",\"bgr\"]}','{\"panels\":[{\"index\":0,\"height\":40,\"width\":80,\"paperHeader\":-1400,\"paperFooter\":113.38582677165356,\"printElements\":[{\"tid\":\"configModule.name\",\"options\":{\"left\":37.5,\"top\":1.5,\"height\":28,\"width\":150,\"fontSize\":15,\"fontWeight\":\"600\",\"textAlign\":\"center\",\"lineHeight\":28,\"hideTitle\":true,\"field\":\"name\",\"testData\":\"模板盒子资产卡片\"}},{\"tid\":\"configModule.gender\",\"options\":{\"left\":18,\"top\":37.5,\"height\":9.75,\"width\":120,\"title\":\"资产编号\",\"field\":\"zcbh\",\"testData\":\"A202010104321\"}},{\"tid\":\"configModule.mySite\",\"options\":{\"left\":154.5,\"top\":49.5,\"height\":50,\"width\":50,\"fontSize\":19,\"fontWeight\":\"700\",\"textAlign\":\"center\",\"lineHeight\":39,\"hideTitle\":true,\"textType\":\"qrcode\",\"field\":\"ewm\",\"title\":\"123\",\"testData\":\"123\"}},{\"tid\":\"configModule.email\",\"options\":{\"left\":18,\"top\":55.5,\"height\":9.75,\"width\":120,\"title\":\"财务资产名\",\"field\":\"cw\",\"testData\":\"联想台式机\"}},{\"tid\":\"configModule.address\",\"options\":{\"left\":19.5,\"top\":73.5,\"height\":9.75,\"width\":120,\"title\":\"所属单位\",\"field\":\"bm\",\"testData\":\"信息中心\"}},{\"tid\":\"configModule.phone\",\"options\":{\"left\":18,\"top\":94.5,\"height\":9.75,\"width\":120,\"title\":\"保管人\",\"field\":\"bgr\",\"testData\":\"李文明\"}}],\"paperNumberLeft\":-565,\"paperNumberTop\":-819,\"paperNumberDisabled\":true,\"paperNumberFormat\":\"paperCount\"}]}','<div class=\"hiprint-printTemplate\"><div class=\"hiprint-printPanel panel-index-0\"> <style printstyle=\"\">\n        @page\n        {\n             border:0;\n             padding:0cm;\n             margin:0cm;\n             size:80mm 40mm ;\n        }\n        </style>\n        <div class=\"hiprint-printPaper\" original-height=\"40\" style=\"width: 80mm; height: 39mm;\"><div class=\"hiprint-printPaper-content\"><div tabindex=\"1\" class=\"hiprint-printElement hiprint-printElement-text\" style=\"position: absolute; width: 150pt; height: 28pt; font-size: 15pt; font-weight: 600; text-align: center; line-height: 28pt; left: 37.5pt; top: 1.5pt;\"><div class=\"hiprint-printElement-text-content hiprint-printElement-content\" style=\"height:100%;width:100%\">文登税务资产卡片</div></div><div tabindex=\"1\" class=\"hiprint-printElement hiprint-printElement-text\" style=\"position: absolute; width: 120pt; height: 9.75pt; left: 18pt; top: 37.5pt;\"><div class=\"hiprint-printElement-text-content hiprint-printElement-content\" style=\"height:100%;width:100%\">资产编号：A202001105631</div></div><div tabindex=\"1\" class=\"hiprint-printElement hiprint-printElement-text\" style=\"position: absolute; width: 50pt; height: 50pt; font-size: 19pt; font-weight: 700; text-align: center; line-height: 39pt; left: 154.5pt; top: 49.5pt;\"><div class=\"hiprint-printElement-text-content hiprint-printElement-content\" style=\"height:100%;width:100%\" title=\"qrcode\"><svg viewBox=\"0 0 25 25\" width=\"100%\" height=\"100%\" fill=\"#ffffff\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><rect fill=\"#ffffff\" width=\"100%\" height=\"100%\"></rect><rect fill=\"#000000\" width=\"1\" height=\"1\" id=\"template\"></rect><use x=\"0\" y=\"0\" xlink:href=\"#template\"></use><use x=\"1\" y=\"0\" xlink:href=\"#template\"></use><use x=\"2\" y=\"0\" xlink:href=\"#template\"></use><use x=\"3\" y=\"0\" xlink:href=\"#template\"></use><use x=\"4\" y=\"0\" xlink:href=\"#template\"></use><use x=\"5\" y=\"0\" xlink:href=\"#template\"></use><use x=\"6\" y=\"0\" xlink:href=\"#template\"></use><use x=\"8\" y=\"0\" xlink:href=\"#template\"></use><use x=\"9\" y=\"0\" xlink:href=\"#template\"></use><use x=\"12\" y=\"0\" xlink:href=\"#template\"></use><use x=\"13\" y=\"0\" xlink:href=\"#template\"></use><use x=\"15\" y=\"0\" xlink:href=\"#template\"></use><use x=\"16\" y=\"0\" xlink:href=\"#template\"></use><use x=\"18\" y=\"0\" xlink:href=\"#template\"></use><use x=\"19\" y=\"0\" xlink:href=\"#template\"></use><use x=\"20\" y=\"0\" xlink:href=\"#template\"></use><use x=\"21\" y=\"0\" xlink:href=\"#template\"></use><use x=\"22\" y=\"0\" xlink:href=\"#template\"></use><use x=\"23\" y=\"0\" xlink:href=\"#template\"></use><use x=\"24\" y=\"0\" xlink:href=\"#template\"></use><use x=\"0\" y=\"1\" xlink:href=\"#template\"></use><use x=\"6\" y=\"1\" xlink:href=\"#template\"></use><use x=\"8\" y=\"1\" xlink:href=\"#template\"></use><use x=\"11\" y=\"1\" xlink:href=\"#template\"></use><use x=\"13\" y=\"1\" xlink:href=\"#template\"></use><use x=\"15\" y=\"1\" xlink:href=\"#template\"></use><use x=\"16\" y=\"1\" xlink:href=\"#template\"></use><use x=\"18\" y=\"1\" xlink:href=\"#template\"></use><use x=\"24\" y=\"1\" xlink:href=\"#template\"></use><use x=\"0\" y=\"2\" xlink:href=\"#template\"></use><use x=\"2\" y=\"2\" xlink:href=\"#template\"></use><use x=\"3\" y=\"2\" xlink:href=\"#template\"></use><use x=\"4\" y=\"2\" xlink:href=\"#template\"></use><use x=\"6\" y=\"2\" xlink:href=\"#template\"></use><use x=\"10\" y=\"2\" xlink:href=\"#template\"></use><use x=\"11\" y=\"2\" xlink:href=\"#template\"></use><use x=\"13\" y=\"2\" xlink:href=\"#template\"></use><use x=\"14\" y=\"2\" xlink:href=\"#template\"></use><use x=\"16\" y=\"2\" xlink:href=\"#template\"></use><use x=\"18\" y=\"2\" xlink:href=\"#template\"></use><use x=\"20\" y=\"2\" xlink:href=\"#template\"></use><use x=\"21\" y=\"2\" xlink:href=\"#template\"></use><use x=\"22\" y=\"2\" xlink:href=\"#template\"></use><use x=\"24\" y=\"2\" xlink:href=\"#template\"></use><use x=\"0\" y=\"3\" xlink:href=\"#template\"></use><use x=\"2\" y=\"3\" xlink:href=\"#template\"></use><use x=\"3\" y=\"3\" xlink:href=\"#template\"></use><use x=\"4\" y=\"3\" xlink:href=\"#template\"></use><use x=\"6\" y=\"3\" xlink:href=\"#template\"></use><use x=\"8\" y=\"3\" xlink:href=\"#template\"></use><use x=\"9\" y=\"3\" xlink:href=\"#template\"></use><use x=\"11\" y=\"3\" xlink:href=\"#template\"></use><use x=\"12\" y=\"3\" xlink:href=\"#template\"></use><use x=\"13\" y=\"3\" xlink:href=\"#template\"></use><use x=\"14\" y=\"3\" xlink:href=\"#template\"></use><use x=\"16\" y=\"3\" xlink:href=\"#template\"></use><use x=\"18\" y=\"3\" xlink:href=\"#template\"></use><use x=\"20\" y=\"3\" xlink:href=\"#template\"></use><use x=\"21\" y=\"3\" xlink:href=\"#template\"></use><use x=\"22\" y=\"3\" xlink:href=\"#template\"></use><use x=\"24\" y=\"3\" xlink:href=\"#template\"></use><use x=\"0\" y=\"4\" xlink:href=\"#template\"></use><use x=\"2\" y=\"4\" xlink:href=\"#template\"></use><use x=\"3\" y=\"4\" xlink:href=\"#template\"></use><use x=\"4\" y=\"4\" xlink:href=\"#template\"></use><use x=\"6\" y=\"4\" xlink:href=\"#template\"></use><use x=\"8\" y=\"4\" xlink:href=\"#template\"></use><use x=\"9\" y=\"4\" xlink:href=\"#template\"></use><use x=\"12\" y=\"4\" xlink:href=\"#template\"></use><use x=\"13\" y=\"4\" xlink:href=\"#template\"></use><use x=\"14\" y=\"4\" xlink:href=\"#template\"></use><use x=\"15\" y=\"4\" xlink:href=\"#template\"></use><use x=\"16\" y=\"4\" xlink:href=\"#template\"></use><use x=\"18\" y=\"4\" xlink:href=\"#template\"></use><use x=\"20\" y=\"4\" xlink:href=\"#template\"></use><use x=\"21\" y=\"4\" xlink:href=\"#template\"></use><use x=\"22\" y=\"4\" xlink:href=\"#template\"></use><use x=\"24\" y=\"4\" xlink:href=\"#template\"></use><use x=\"0\" y=\"5\" xlink:href=\"#template\"></use><use x=\"6\" y=\"5\" xlink:href=\"#template\"></use><use x=\"8\" y=\"5\" xlink:href=\"#template\"></use><use x=\"9\" y=\"5\" xlink:href=\"#template\"></use><use x=\"12\" y=\"5\" xlink:href=\"#template\"></use><use x=\"13\" y=\"5\" xlink:href=\"#template\"></use><use x=\"15\" y=\"5\" xlink:href=\"#template\"></use><use x=\"18\" y=\"5\" xlink:href=\"#template\"></use><use x=\"24\" y=\"5\" xlink:href=\"#template\"></use><use x=\"0\" y=\"6\" xlink:href=\"#template\"></use><use x=\"1\" y=\"6\" xlink:href=\"#template\"></use><use x=\"2\" y=\"6\" xlink:href=\"#template\"></use><use x=\"3\" y=\"6\" xlink:href=\"#template\"></use><use x=\"4\" y=\"6\" xlink:href=\"#template\"></use><use x=\"5\" y=\"6\" xlink:href=\"#template\"></use><use x=\"6\" y=\"6\" xlink:href=\"#template\"></use><use x=\"8\" y=\"6\" xlink:href=\"#template\"></use><use x=\"10\" y=\"6\" xlink:href=\"#template\"></use><use x=\"12\" y=\"6\" xlink:href=\"#template\"></use><use x=\"14\" y=\"6\" xlink:href=\"#template\"></use><use x=\"16\" y=\"6\" xlink:href=\"#template\"></use><use x=\"18\" y=\"6\" xlink:href=\"#template\"></use><use x=\"19\" y=\"6\" xlink:href=\"#template\"></use><use x=\"20\" y=\"6\" xlink:href=\"#template\"></use><use x=\"21\" y=\"6\" xlink:href=\"#template\"></use><use x=\"22\" y=\"6\" xlink:href=\"#template\"></use><use x=\"23\" y=\"6\" xlink:href=\"#template\"></use><use x=\"24\" y=\"6\" xlink:href=\"#template\"></use><use x=\"10\" y=\"7\" xlink:href=\"#template\"></use><use x=\"14\" y=\"7\" xlink:href=\"#template\"></use><use x=\"3\" y=\"8\" xlink:href=\"#template\"></use><use x=\"6\" y=\"8\" xlink:href=\"#template\"></use><use x=\"10\" y=\"8\" xlink:href=\"#template\"></use><use x=\"11\" y=\"8\" xlink:href=\"#template\"></use><use x=\"14\" y=\"8\" xlink:href=\"#template\"></use><use x=\"15\" y=\"8\" xlink:href=\"#template\"></use><use x=\"16\" y=\"8\" xlink:href=\"#template\"></use><use x=\"19\" y=\"8\" xlink:href=\"#template\"></use><use x=\"20\" y=\"8\" xlink:href=\"#template\"></use><use x=\"21\" y=\"8\" xlink:href=\"#template\"></use><use x=\"23\" y=\"8\" xlink:href=\"#template\"></use><use x=\"24\" y=\"8\" xlink:href=\"#template\"></use><use x=\"0\" y=\"9\" xlink:href=\"#template\"></use><use x=\"4\" y=\"9\" xlink:href=\"#template\"></use><use x=\"5\" y=\"9\" xlink:href=\"#template\"></use><use x=\"7\" y=\"9\" xlink:href=\"#template\"></use><use x=\"8\" y=\"9\" xlink:href=\"#template\"></use><use x=\"9\" y=\"9\" xlink:href=\"#template\"></use><use x=\"10\" y=\"9\" xlink:href=\"#template\"></use><use x=\"13\" y=\"9\" xlink:href=\"#template\"></use><use x=\"17\" y=\"9\" xlink:href=\"#template\"></use><use x=\"20\" y=\"9\" xlink:href=\"#template\"></use><use x=\"21\" y=\"9\" xlink:href=\"#template\"></use><use x=\"23\" y=\"9\" xlink:href=\"#template\"></use><use x=\"24\" y=\"9\" xlink:href=\"#template\"></use><use x=\"0\" y=\"10\" xlink:href=\"#template\"></use><use x=\"1\" y=\"10\" xlink:href=\"#template\"></use><use x=\"2\" y=\"10\" xlink:href=\"#template\"></use><use x=\"5\" y=\"10\" xlink:href=\"#template\"></use><use x=\"6\" y=\"10\" xlink:href=\"#template\"></use><use x=\"8\" y=\"10\" xlink:href=\"#template\"></use><use x=\"11\" y=\"10\" xlink:href=\"#template\"></use><use x=\"12\" y=\"10\" xlink:href=\"#template\"></use><use x=\"13\" y=\"10\" xlink:href=\"#template\"></use><use x=\"18\" y=\"10\" xlink:href=\"#template\"></use><use x=\"19\" y=\"10\" xlink:href=\"#template\"></use><use x=\"21\" y=\"10\" xlink:href=\"#template\"></use><use x=\"22\" y=\"10\" xlink:href=\"#template\"></use><use x=\"23\" y=\"10\" xlink:href=\"#template\"></use><use x=\"24\" y=\"10\" xlink:href=\"#template\"></use><use x=\"0\" y=\"11\" xlink:href=\"#template\"></use><use x=\"1\" y=\"11\" xlink:href=\"#template\"></use><use x=\"2\" y=\"11\" xlink:href=\"#template\"></use><use x=\"5\" y=\"11\" xlink:href=\"#template\"></use><use x=\"7\" y=\"11\" xlink:href=\"#template\"></use><use x=\"8\" y=\"11\" xlink:href=\"#template\"></use><use x=\"9\" y=\"11\" xlink:href=\"#template\"></use><use x=\"10\" y=\"11\" xlink:href=\"#template\"></use><use x=\"11\" y=\"11\" xlink:href=\"#template\"></use><use x=\"14\" y=\"11\" xlink:href=\"#template\"></use><use x=\"15\" y=\"11\" xlink:href=\"#template\"></use><use x=\"16\" y=\"11\" xlink:href=\"#template\"></use><use x=\"17\" y=\"11\" xlink:href=\"#template\"></use><use x=\"24\" y=\"11\" xlink:href=\"#template\"></use><use x=\"0\" y=\"12\" xlink:href=\"#template\"></use><use x=\"2\" y=\"12\" xlink:href=\"#template\"></use><use x=\"3\" y=\"12\" xlink:href=\"#template\"></use><use x=\"6\" y=\"12\" xlink:href=\"#template\"></use><use x=\"7\" y=\"12\" xlink:href=\"#template\"></use><use x=\"9\" y=\"12\" xlink:href=\"#template\"></use><use x=\"11\" y=\"12\" xlink:href=\"#template\"></use><use x=\"13\" y=\"12\" xlink:href=\"#template\"></use><use x=\"14\" y=\"12\" xlink:href=\"#template\"></use><use x=\"15\" y=\"12\" xlink:href=\"#template\"></use><use x=\"18\" y=\"12\" xlink:href=\"#template\"></use><use x=\"19\" y=\"12\" xlink:href=\"#template\"></use><use x=\"20\" y=\"12\" xlink:href=\"#template\"></use><use x=\"24\" y=\"12\" xlink:href=\"#template\"></use><use x=\"3\" y=\"13\" xlink:href=\"#template\"></use><use x=\"4\" y=\"13\" xlink:href=\"#template\"></use><use x=\"5\" y=\"13\" xlink:href=\"#template\"></use><use x=\"8\" y=\"13\" xlink:href=\"#template\"></use><use x=\"10\" y=\"13\" xlink:href=\"#template\"></use><use x=\"12\" y=\"13\" xlink:href=\"#template\"></use><use x=\"13\" y=\"13\" xlink:href=\"#template\"></use><use x=\"14\" y=\"13\" xlink:href=\"#template\"></use><use x=\"15\" y=\"13\" xlink:href=\"#template\"></use><use x=\"16\" y=\"13\" xlink:href=\"#template\"></use><use x=\"24\" y=\"13\" xlink:href=\"#template\"></use><use x=\"0\" y=\"14\" xlink:href=\"#template\"></use><use x=\"2\" y=\"14\" xlink:href=\"#template\"></use><use x=\"4\" y=\"14\" xlink:href=\"#template\"></use><use x=\"5\" y=\"14\" xlink:href=\"#template\"></use><use x=\"6\" y=\"14\" xlink:href=\"#template\"></use><use x=\"7\" y=\"14\" xlink:href=\"#template\"></use><use x=\"9\" y=\"14\" xlink:href=\"#template\"></use><use x=\"10\" y=\"14\" xlink:href=\"#template\"></use><use x=\"14\" y=\"14\" xlink:href=\"#template\"></use><use x=\"19\" y=\"14\" xlink:href=\"#template\"></use><use x=\"21\" y=\"14\" xlink:href=\"#template\"></use><use x=\"24\" y=\"14\" xlink:href=\"#template\"></use><use x=\"1\" y=\"15\" xlink:href=\"#template\"></use><use x=\"2\" y=\"15\" xlink:href=\"#template\"></use><use x=\"4\" y=\"15\" xlink:href=\"#template\"></use><use x=\"7\" y=\"15\" xlink:href=\"#template\"></use><use x=\"8\" y=\"15\" xlink:href=\"#template\"></use><use x=\"9\" y=\"15\" xlink:href=\"#template\"></use><use x=\"12\" y=\"15\" xlink:href=\"#template\"></use><use x=\"13\" y=\"15\" xlink:href=\"#template\"></use><use x=\"15\" y=\"15\" xlink:href=\"#template\"></use><use x=\"17\" y=\"15\" xlink:href=\"#template\"></use><use x=\"18\" y=\"15\" xlink:href=\"#template\"></use><use x=\"19\" y=\"15\" xlink:href=\"#template\"></use><use x=\"0\" y=\"16\" xlink:href=\"#template\"></use><use x=\"1\" y=\"16\" xlink:href=\"#template\"></use><use x=\"3\" y=\"16\" xlink:href=\"#template\"></use><use x=\"6\" y=\"16\" xlink:href=\"#template\"></use><use x=\"7\" y=\"16\" xlink:href=\"#template\"></use><use x=\"11\" y=\"16\" xlink:href=\"#template\"></use><use x=\"13\" y=\"16\" xlink:href=\"#template\"></use><use x=\"16\" y=\"16\" xlink:href=\"#template\"></use><use x=\"17\" y=\"16\" xlink:href=\"#template\"></use><use x=\"18\" y=\"16\" xlink:href=\"#template\"></use><use x=\"19\" y=\"16\" xlink:href=\"#template\"></use><use x=\"20\" y=\"16\" xlink:href=\"#template\"></use><use x=\"22\" y=\"16\" xlink:href=\"#template\"></use><use x=\"24\" y=\"16\" xlink:href=\"#template\"></use><use x=\"8\" y=\"17\" xlink:href=\"#template\"></use><use x=\"9\" y=\"17\" xlink:href=\"#template\"></use><use x=\"13\" y=\"17\" xlink:href=\"#template\"></use><use x=\"14\" y=\"17\" xlink:href=\"#template\"></use><use x=\"15\" y=\"17\" xlink:href=\"#template\"></use><use x=\"16\" y=\"17\" xlink:href=\"#template\"></use><use x=\"20\" y=\"17\" xlink:href=\"#template\"></use><use x=\"23\" y=\"17\" xlink:href=\"#template\"></use><use x=\"24\" y=\"17\" xlink:href=\"#template\"></use><use x=\"0\" y=\"18\" xlink:href=\"#template\"></use><use x=\"1\" y=\"18\" xlink:href=\"#template\"></use><use x=\"2\" y=\"18\" xlink:href=\"#template\"></use><use x=\"3\" y=\"18\" xlink:href=\"#template\"></use><use x=\"4\" y=\"18\" xlink:href=\"#template\"></use><use x=\"5\" y=\"18\" xlink:href=\"#template\"></use><use x=\"6\" y=\"18\" xlink:href=\"#template\"></use><use x=\"13\" y=\"18\" xlink:href=\"#template\"></use><use x=\"16\" y=\"18\" xlink:href=\"#template\"></use><use x=\"18\" y=\"18\" xlink:href=\"#template\"></use><use x=\"20\" y=\"18\" xlink:href=\"#template\"></use><use x=\"22\" y=\"18\" xlink:href=\"#template\"></use><use x=\"23\" y=\"18\" xlink:href=\"#template\"></use><use x=\"24\" y=\"18\" xlink:href=\"#template\"></use><use x=\"0\" y=\"19\" xlink:href=\"#template\"></use><use x=\"6\" y=\"19\" xlink:href=\"#template\"></use><use x=\"9\" y=\"19\" xlink:href=\"#template\"></use><use x=\"10\" y=\"19\" xlink:href=\"#template\"></use><use x=\"11\" y=\"19\" xlink:href=\"#template\"></use><use x=\"12\" y=\"19\" xlink:href=\"#template\"></use><use x=\"14\" y=\"19\" xlink:href=\"#template\"></use><use x=\"16\" y=\"19\" xlink:href=\"#template\"></use><use x=\"20\" y=\"19\" xlink:href=\"#template\"></use><use x=\"22\" y=\"19\" xlink:href=\"#template\"></use><use x=\"23\" y=\"19\" xlink:href=\"#template\"></use><use x=\"24\" y=\"19\" xlink:href=\"#template\"></use><use x=\"0\" y=\"20\" xlink:href=\"#template\"></use><use x=\"2\" y=\"20\" xlink:href=\"#template\"></use><use x=\"3\" y=\"20\" xlink:href=\"#template\"></use><use x=\"4\" y=\"20\" xlink:href=\"#template\"></use><use x=\"6\" y=\"20\" xlink:href=\"#template\"></use><use x=\"14\" y=\"20\" xlink:href=\"#template\"></use><use x=\"15\" y=\"20\" xlink:href=\"#template\"></use><use x=\"16\" y=\"20\" xlink:href=\"#template\"></use><use x=\"17\" y=\"20\" xlink:href=\"#template\"></use><use x=\"18\" y=\"20\" xlink:href=\"#template\"></use><use x=\"19\" y=\"20\" xlink:href=\"#template\"></use><use x=\"20\" y=\"20\" xlink:href=\"#template\"></use><use x=\"24\" y=\"20\" xlink:href=\"#template\"></use><use x=\"0\" y=\"21\" xlink:href=\"#template\"></use><use x=\"2\" y=\"21\" xlink:href=\"#template\"></use><use x=\"3\" y=\"21\" xlink:href=\"#template\"></use><use x=\"4\" y=\"21\" xlink:href=\"#template\"></use><use x=\"6\" y=\"21\" xlink:href=\"#template\"></use><use x=\"8\" y=\"21\" xlink:href=\"#template\"></use><use x=\"9\" y=\"21\" xlink:href=\"#template\"></use><use x=\"10\" y=\"21\" xlink:href=\"#template\"></use><use x=\"12\" y=\"21\" xlink:href=\"#template\"></use><use x=\"13\" y=\"21\" xlink:href=\"#template\"></use><use x=\"15\" y=\"21\" xlink:href=\"#template\"></use><use x=\"16\" y=\"21\" xlink:href=\"#template\"></use><use x=\"18\" y=\"21\" xlink:href=\"#template\"></use><use x=\"19\" y=\"21\" xlink:href=\"#template\"></use><use x=\"23\" y=\"21\" xlink:href=\"#template\"></use><use x=\"0\" y=\"22\" xlink:href=\"#template\"></use><use x=\"2\" y=\"22\" xlink:href=\"#template\"></use><use x=\"3\" y=\"22\" xlink:href=\"#template\"></use><use x=\"4\" y=\"22\" xlink:href=\"#template\"></use><use x=\"6\" y=\"22\" xlink:href=\"#template\"></use><use x=\"10\" y=\"22\" xlink:href=\"#template\"></use><use x=\"12\" y=\"22\" xlink:href=\"#template\"></use><use x=\"15\" y=\"22\" xlink:href=\"#template\"></use><use x=\"16\" y=\"22\" xlink:href=\"#template\"></use><use x=\"18\" y=\"22\" xlink:href=\"#template\"></use><use x=\"21\" y=\"22\" xlink:href=\"#template\"></use><use x=\"24\" y=\"22\" xlink:href=\"#template\"></use><use x=\"0\" y=\"23\" xlink:href=\"#template\"></use><use x=\"6\" y=\"23\" xlink:href=\"#template\"></use><use x=\"9\" y=\"23\" xlink:href=\"#template\"></use><use x=\"13\" y=\"23\" xlink:href=\"#template\"></use><use x=\"19\" y=\"23\" xlink:href=\"#template\"></use><use x=\"21\" y=\"23\" xlink:href=\"#template\"></use><use x=\"0\" y=\"24\" xlink:href=\"#template\"></use><use x=\"1\" y=\"24\" xlink:href=\"#template\"></use><use x=\"2\" y=\"24\" xlink:href=\"#template\"></use><use x=\"3\" y=\"24\" xlink:href=\"#template\"></use><use x=\"4\" y=\"24\" xlink:href=\"#template\"></use><use x=\"5\" y=\"24\" xlink:href=\"#template\"></use><use x=\"6\" y=\"24\" xlink:href=\"#template\"></use><use x=\"9\" y=\"24\" xlink:href=\"#template\"></use><use x=\"12\" y=\"24\" xlink:href=\"#template\"></use><use x=\"16\" y=\"24\" xlink:href=\"#template\"></use><use x=\"17\" y=\"24\" xlink:href=\"#template\"></use><use x=\"18\" y=\"24\" xlink:href=\"#template\"></use><use x=\"19\" y=\"24\" xlink:href=\"#template\"></use><use x=\"22\" y=\"24\" xlink:href=\"#template\"></use><use x=\"23\" y=\"24\" xlink:href=\"#template\"></use><use x=\"24\" y=\"24\" xlink:href=\"#template\"></use></svg></div></div><div tabindex=\"1\" class=\"hiprint-printElement hiprint-printElement-text\" style=\"position: absolute; width: 120pt; height: 9.75pt; left: 18pt; top: 55.5pt;\"><div class=\"hiprint-printElement-text-content hiprint-printElement-content\" style=\"height:100%;width:100%\">财务资产名：联想台式机</div></div><div tabindex=\"1\" class=\"hiprint-printElement hiprint-printElement-text\" style=\"position: absolute; width: 120pt; height: 9.75pt; left: 19.5pt; top: 73.5pt;\"><div class=\"hiprint-printElement-text-content hiprint-printElement-content\" style=\"height:100%;width:100%\">所属单位：信息中心</div></div><div tabindex=\"1\" class=\"hiprint-printElement hiprint-printElement-text\" style=\"position: absolute; width: 120pt; height: 9.75pt; left: 18pt; top: 94.5pt;\"><div class=\"hiprint-printElement-text-content hiprint-printElement-content\" style=\"height:100%;width:100%\">保管人：李文明</div></div><span class=\"hiprint-paperNumber\" style=\"position: absolute; top: -819pt; left: -565pt; display: none;\">1</span></div></div></div></div>','资产卡片配置'),(5,'bgskapian','{\"title\":\"模板盒子资产卡片\",\"ma\":\"0\",\"bianma\":\"zcbh\",\"content\":[\"zcbh\",\"zclx\",\"bgr\"]}','',NULL,''),(6,'wgbkapian','{\"title\":\"模板盒子资产卡片\",\"ma\":\"0\",\"bianma\":\"zcbh\",\"content\":[\"zcbh\",\"xlh\",\"zclx\",\"bgr\",\"zbsc\"]}','{\"panels\":[{\"index\":0,\"height\":40,\"width\":80,\"paperHeader\":-1400,\"paperFooter\":113.38582677165356,\"printElements\":[{\"tid\":\"configModule.name\",\"options\":{\"left\":85.5,\"top\":4.5,\"height\":28,\"width\":150,\"fontSize\":15,\"fontWeight\":\"600\",\"textAlign\":\"center\",\"lineHeight\":28,\"hideTitle\":true,\"field\":\"name\",\"testData\":\"模板盒子资产卡片\"}},{\"tid\":\"configModule.like\",\"options\":{\"left\":9,\"top\":40.5,\"height\":9.75,\"width\":120,\"title\":\"资产类型\",\"field\":\"zclx\",\"testData\":\"台式计算机\"}},{\"tid\":\"configModule.gender\",\"options\":{\"left\":7.5,\"top\":54,\"height\":9.75,\"width\":120,\"title\":\"资产编号\",\"field\":\"zcbh\",\"testData\":\"A202010104321\"}},{\"tid\":\"configModule.email\",\"options\":{\"left\":9,\"top\":69,\"height\":9.75,\"width\":120,\"title\":\"保管人\",\"field\":\"bgr\",\"testData\":\"李文明\"}},{\"tid\":\"configModule.barcode\",\"options\":{\"left\":42,\"top\":88.5,\"height\":15,\"width\":150,\"textAlign\":\"center\",\"textType\":\"barcode\",\"fontFamily\":\"Microsoft YaHei\",\"hideTitle\":\"true\",\"barcodeMode\":\"CODE128\",\"field\":\"txm\",\"title\":\"321\",\"testData\":\"321\"}}],\"paperNumberLeft\":-565,\"paperNumberTop\":-819,\"paperNumberDisabled\":true,\"paperNumberFormat\":\"paperCount\"}]}',NULL,'');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `danwei`
--

DROP TABLE IF EXISTS `danwei`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `danwei` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '单位名',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `danwei`
--

LOCK TABLES `danwei` WRITE;
/*!40000 ALTER TABLE `danwei` DISABLE KEYS */;
INSERT INTO `danwei` VALUES (1,'党组',1),(2,'办公室',1);
/*!40000 ALTER TABLE `danwei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `haocai`
--

DROP TABLE IF EXISTS `haocai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `haocai` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) CHARACTER SET utf8mb4 NOT NULL COMMENT '什么东西',
  `pp` varchar(512) CHARACTER SET utf8mb4 NOT NULL COMMENT '品牌',
  `gg` varchar(512) CHARACTER SET utf8mb4 NOT NULL COMMENT '规格',
  `dj` decimal(10,2) unsigned NOT NULL COMMENT '单价',
  `num` int(10) unsigned NOT NULL COMMENT '剩余数量',
  `zs` int(10) unsigned NOT NULL COMMENT '入库数量',
  `rksj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `haocai`
--

LOCK TABLES `haocai` WRITE;
/*!40000 ALTER TABLE `haocai` DISABLE KEYS */;
INSERT INTO `haocai` VALUES (1,'11','11','11',11.00,11,11,'2021-01-12 05:44:29');
/*!40000 ALTER TABLE `haocai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juese`
--

DROP TABLE IF EXISTS `juese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juese` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '角色名',
  `value` varchar(1024) CHARACTER SET utf8mb4 NOT NULL COMMENT '权限菜单ID',
  `bm` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限部门ID，0：全局（废弃）',
  `shanchu` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否具有删除权限',
  `xiugai` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否具有修改权限',
  `status` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juese`
--

LOCK TABLES `juese` WRITE;
/*!40000 ALTER TABLE `juese` DISABLE KEYS */;
INSERT INTO `juese` VALUES (1,'管理员','4,5,6,7,8,9,10,36,29,30,34,35,11,12,14,13,23,26,32,15,16,18,17,24,27,31,19,20,22,21,25,28,33,37,38,39,40,41,',0,1,1,1),(10,'信息安全员_查询','11,12,14,',0,0,0,1),(11,'信息资产管理员','11,12,14,13,23,26,32,',0,0,1,1),(12,'信息管理','11,12,14,13,23,26,32,37,38,39,40,41,',0,1,1,1);
/*!40000 ALTER TABLE `juese` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '用户',
  `action` varchar(1024) CHARACTER SET utf8mb4 NOT NULL COMMENT '所执行的操作',
  `ip` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=210 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (192,'admin','获取资产类型列表','180.168.251.4','2021-01-14 05:35:56'),(191,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:35:52'),(190,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:33:28'),(189,'admin','登录成功','180.168.251.4','2021-01-14 05:33:21'),(188,'admin','退出成功','180.168.251.4','2021-01-14 05:32:59'),(187,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:32:59'),(186,'admin','登录成功','180.168.251.4','2021-01-14 05:32:52'),(185,'admin','登录失败，密码错误','180.168.251.4','2021-01-14 05:32:49'),(184,'admin','退出成功','180.168.251.4','2021-01-14 05:32:42'),(183,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:32:39'),(182,'admin','登录成功','180.168.251.4','2021-01-14 05:32:25'),(181,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:13:00'),(180,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:11:25'),(179,'admin','修改 xinxizichan 资产 1 成功 ','180.168.251.4','2021-01-14 04:11:23'),(178,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:04:36'),(177,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:04:01'),(176,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:03:31'),(175,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:02:57'),(174,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:02:49'),(173,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 04:02:07'),(172,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:59:26'),(171,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:58:11'),(170,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:57:49'),(169,'admin','登录成功','180.168.251.4','2021-01-14 03:57:42'),(168,'admin','退出成功','180.168.251.4','2021-01-14 03:57:32'),(167,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:57:30'),(166,'admin','登录成功','180.168.251.4','2021-01-14 03:57:22'),(165,'admin','退出成功','180.168.251.4','2021-01-14 03:57:16'),(164,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:55:38'),(163,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:55:00'),(162,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:33:06'),(161,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:31:56'),(160,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:30:44'),(159,'admin','获取角色列表','180.168.251.4','2021-01-14 03:30:42'),(158,'admin','获取角色列表','180.168.251.4','2021-01-14 03:30:03'),(157,'admin','获取日志','180.168.251.4','2021-01-14 03:29:24'),(156,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:29:07'),(155,'admin','登录成功','180.168.251.4','2021-01-14 03:28:44'),(154,'admin','退出成功','180.168.251.4','2021-01-14 03:28:38'),(153,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 03:28:18'),(193,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:37:34'),(194,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:37:42'),(195,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:37:45'),(196,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:38:05'),(197,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:38:37'),(198,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:39:06'),(199,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:39:08'),(200,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:41:25'),(201,'admin','修改资产卡片配置','180.168.251.4','2021-01-14 05:41:28'),(202,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:45:41'),(203,'admin','获取资产类型列表','180.168.251.4','2021-01-14 05:45:48'),(204,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:45:51'),(205,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:48:14'),(206,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 05:55:52'),(207,'admin','修改参数','180.168.251.4','2021-01-14 05:55:56'),(208,'admin','获取 xinxizichan  资产列表','180.168.251.4','2021-01-14 06:31:14'),(209,'admin','获取资产状态列表','180.168.251.4','2021-01-14 06:34:09');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shanchu`
--

DROP TABLE IF EXISTS `shanchu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shanchu` (
  `id` bigint(12) NOT NULL COMMENT 'id',
  `zcbh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产编号',
  `xlh` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '序列号',
  `zclx` int(10) unsigned NOT NULL COMMENT '资产类型',
  `cw` varchar(512) CHARACTER SET utf8mb4 NOT NULL COMMENT '财务资产名',
  `zczt` int(10) unsigned NOT NULL COMMENT '资产状态',
  `bm` int(10) unsigned NOT NULL COMMENT '所属单位',
  `bgr` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '保管人',
  `dz` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '保存地址',
  `cgsj` int(10) unsigned DEFAULT NULL COMMENT '采购时间',
  `rzsj` int(10) unsigned DEFAULT NULL COMMENT '入账时间',
  `zbsc` int(2) DEFAULT NULL COMMENT '质保时长（年）',
  `sysc` int(2) DEFAULT NULL COMMENT '报废年限（年）',
  `pp` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌',
  `xh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '型号',
  `zcly` varchar(10) CHARACTER SET utf8mb4 DEFAULT '自购' COMMENT '资产来源',
  `zcjz` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '资产价值',
  `gg` varchar(1024) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '规格',
  `bz` text CHARACTER SET utf8mb4 COMMENT '备注',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产图片',
  `wlbs` int(1) unsigned DEFAULT NULL COMMENT '网络标识',
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `xsq` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '显示器信息',
  `yp` int(5) unsigned DEFAULT NULL COMMENT '硬盘信息',
  `nc` int(5) unsigned DEFAULT NULL COMMENT '内存信息',
  `ll` mediumtext CHARACTER SET utf8mb4 COMMENT '履历',
  `dotime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='信息中心资产数据表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shanchu`
--

LOCK TABLES `shanchu` WRITE;
/*!40000 ALTER TABLE `shanchu` DISABLE KEYS */;
/*!40000 ALTER TABLE `shanchu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_menu`
--

DROP TABLE IF EXISTS `system_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `href` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `target` varchar(20) NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `delete_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `href` (`href`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统菜单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_menu`
--

LOCK TABLES `system_menu` WRITE;
/*!40000 ALTER TABLE `system_menu` DISABLE KEYS */;
INSERT INTO `system_menu` VALUES (4,0,'系统管理','fa fa-address-book','','_self',4,1,'','2020-10-23 02:04:40','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,4,'系统设置','fa fa-gears','','_self',3,1,'','2020-10-23 02:04:40','0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,5,'角色管理','fa fa-user-secret','page/juese.php','_self',5,1,'','2020-10-23 02:04:40','0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,5,'用户管理','fa fa-user','page/user.php','_self',4,1,'','2020-10-23 02:04:40','0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,5,'单位管理','fa fa-bank','page/danwei.php','_self',3,1,'','2020-10-23 02:04:40','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,5,'资产类型','fa fa-joomla','page/leixing.php','_self',2,1,'','2020-10-23 02:04:40','0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,5,'资产状态','fa fa-cube','page/zhuangtai.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,0,'信息中心资产','fa fa-address-book','','_self',3,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,11,'资产管理','fa fa-newspaper-o','','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(13,12,'信息资产录入','fa fa-plus-square','page/add_xinxi.php','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(14,12,'信息资产查询','fa fa-search','page/search_xinxi.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(15,0,'物管办资产','fa fa-address-book','','_self',2,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(16,15,'资产管理','fa fa-newspaper-o','','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(17,16,'资产录入','fa fa-plus-square','page/add_wgb.php','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(18,16,'资产查询','fa fa-search','page/search_wgb.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(19,0,'办公室资产','fa fa-address-book','','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(20,19,'资产管理','fa fa-newspaper-o','','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(21,20,'资产录入','fa fa-plus-square','page/add_bgs.php','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(22,20,'资产查询','fa fa-search','page/search_bgs.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(23,11,'系统设置','fa fa-newspaper-o','','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(24,15,'系统设置','fa fa-newspaper-o','','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(25,19,'系统设置','fa fa-newspaper-o','','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(26,23,'资产类型','fa fa-newspaper-o','page/xxzclx.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(27,24,'资产类型','fa fa-newspaper-o','page/wgbzclx.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(28,25,'资产类型','fa fa-newspaper-o','page/bgszclx.php','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(29,4,'系统日志','fa fa-file-text','','_self',2,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(30,29,'操作日志','fa fa-file-text-o','page/log.php','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(31,24,'参数设置','fa fa-sun-o','page/canshu.php?zz=3','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(32,23,'参数设置','fa fa-sun-o','page/canshu.php?zz=1','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(33,25,'参数设置','fa fa-sun-o','page/canshu.php?zz=2','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(34,4,'数据安全','fa fa-database','','_self',1,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(35,34,'备份导出','fa fa-download','page/sqlbackup.php','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00'),(36,5,'菜单管理','fa fa-newspaper-o','page/menu.php','_self',0,1,'','2020-10-23 02:04:41','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `system_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) CHARACTER SET utf8mb4 NOT NULL COMMENT '用户名',
  `password` varchar(1024) CHARACTER SET utf8mb4 NOT NULL COMMENT '密码',
  `juese` int(2) unsigned NOT NULL COMMENT '角色ID',
  `bm` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '权限部门ID，0：全局',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','sha512:10000:24:hXJefLjmwWFX4gcbuo3+/gHyJoAV8FFd:/JDiHC7RyWfjJljsshZZvKcx1KJFoCK+',1,'0',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wgbzichan`
--

DROP TABLE IF EXISTS `wgbzichan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wgbzichan` (
  `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `zcbh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产编号',
  `xlh` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '序列号',
  `zclx` int(10) unsigned NOT NULL COMMENT '资产类型',
  `cw` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zczt` int(10) unsigned NOT NULL COMMENT '资产状态',
  `bm` int(10) unsigned NOT NULL COMMENT '所属单位',
  `bgr` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '保管人',
  `dz` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '保存地址',
  `cgsj` int(10) unsigned DEFAULT NULL COMMENT '采购时间',
  `rzsj` int(10) unsigned DEFAULT NULL COMMENT '入账时间',
  `zbsc` int(2) DEFAULT NULL COMMENT '质保时长（年）',
  `sysc` int(2) DEFAULT NULL COMMENT '报废年限（年）',
  `pp` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌',
  `xh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '型号',
  `zcly` varchar(10) CHARACTER SET utf8mb4 DEFAULT '自购' COMMENT '资产来源',
  `zcjz` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '资产价值',
  `gg` varchar(1024) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '规格',
  `bz` text CHARACTER SET utf8mb4 COMMENT '备注',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产图片',
  `ll` text CHARACTER SET utf8mb4 COMMENT '履历',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='物管办资产数据表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wgbzichan`
--

LOCK TABLES `wgbzichan` WRITE;
/*!40000 ALTER TABLE `wgbzichan` DISABLE KEYS */;
/*!40000 ALTER TABLE `wgbzichan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xinxizichan`
--

DROP TABLE IF EXISTS `xinxizichan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xinxizichan` (
  `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `zcbh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产编号',
  `xlh` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '序列号',
  `zclx` int(10) unsigned NOT NULL COMMENT '资产类型',
  `cw` varchar(512) CHARACTER SET utf8mb4 NOT NULL COMMENT '财务资产名',
  `zczt` int(10) unsigned NOT NULL COMMENT '资产状态',
  `bm` int(10) unsigned NOT NULL COMMENT '所属单位',
  `bgr` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '保管人',
  `dz` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '保存地址',
  `cgsj` int(10) unsigned DEFAULT NULL COMMENT '采购时间',
  `rzsj` int(10) unsigned DEFAULT NULL COMMENT '入账时间',
  `zbsc` int(2) DEFAULT NULL COMMENT '质保时长（年）',
  `sysc` int(2) DEFAULT NULL COMMENT '报废年限（年）',
  `pp` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌',
  `xh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '型号',
  `zcly` varchar(10) CHARACTER SET utf8mb4 DEFAULT '自购' COMMENT '资产来源',
  `zcjz` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '资产价值',
  `gg` varchar(1024) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '规格',
  `bz` text CHARACTER SET utf8mb4 COMMENT '备注',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资产图片',
  `wlbs` int(1) unsigned DEFAULT NULL COMMENT '网络标识',
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `xsq` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '显示器信息',
  `yp` int(5) unsigned DEFAULT NULL COMMENT '硬盘信息',
  `nc` int(5) unsigned DEFAULT NULL COMMENT '内存信息',
  `ll` mediumtext CHARACTER SET utf8mb4 COMMENT '履历',
  `dotime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='信息中心资产数据表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xinxizichan`
--

LOCK TABLES `xinxizichan` WRITE;
/*!40000 ALTER TABLE `xinxizichan` DISABLE KEYS */;
INSERT INTO `xinxizichan` VALUES (1,'','111111231',2,'ThinkPad T480s(20L7002XCD)',1,2,'测试','测试地址',1610380800,1610640000,3,5,'联想','T480s','自购',11111.00,'Intel 酷睿i7 8550U 8G内存 512G固态版','','/uploads/a14b3f3c41a1500f638de25ad80893ca.jpg',1,'','',0,0,'[{\"user\":\"admin\",\"time\":\"2021-01-12 19:12:43\",\"act\":\"新增\",\"new\":{\"zclx\":\"1\",\"zczt\":\"1\",\"cw\":\"11\",\"zcbh\":\"\",\"xlh\":\"11111\",\"bgr\":\"1111\",\"bm\":\"1\",\"dz\":\"11111\",\"cgsj\":\"2021-01-12\",\"rzsj\":\"2021-01-12\",\"zbsc\":\"0\",\"sysc\":\"0\",\"pp\":\"1111\",\"xh\":\"\",\"gg\":\"\",\"zcly\":\"自购\",\"zcjz\":\"11111.00\",\"bz\":\"\",\"file\":\"\",\"img\":\"\",\"wlbs\":\"0\",\"ip\":\"\",\"xsq\":\"\",\"yp\":\"\",\"nc\":\"\"}},{\"user\":\"admin\",\"time\":\"2021-01-12 19:17:21\",\"act\":\"修改\",\"new\":{\"zclx\":\"1\",\"zczt\":\"1\",\"cw\":\"11\",\"zcbh\":\"\",\"xlh\":\"11111\",\"bgr\":\"1111\",\"bm\":\"2\",\"dz\":\"11111\",\"cgsj\":\"2021-01-12\",\"rzsj\":\"2021-01-12\",\"zbsc\":\"0\",\"sysc\":\"0\",\"pp\":\"1111\",\"xh\":\"\",\"gg\":\"\",\"zcly\":\"自购\",\"zcjz\":\"11111.00\",\"bz\":\"\",\"file\":\"\",\"img\":\"\",\"wlbs\":\"0\",\"ip\":\"\",\"xsq\":\"\",\"yp\":\"0\",\"nc\":\"0\"}},{\"user\":\"admin\",\"time\":\"2021-01-12 19:30:38\",\"act\":\"修改\",\"new\":{\"zclx\":\"1\",\"zczt\":\"1\",\"cw\":\"11\",\"zcbh\":\"\",\"xlh\":\"11111\",\"bgr\":\"1111\",\"bm\":\"2\",\"dz\":\"11111\",\"cgsj\":\"2021-01-12\",\"rzsj\":\"2021-01-12\",\"zbsc\":\"0\",\"sysc\":\"0\",\"pp\":\"1111\",\"xh\":\"\",\"gg\":\"\",\"zcly\":\"自购\",\"zcjz\":\"11111.00\",\"bz\":\"\",\"file\":\"\",\"img\":\"\",\"wlbs\":\"0\",\"ip\":\"\",\"xsq\":\"\",\"yp\":\"0\",\"nc\":\"0\"}},{\"user\":\"admin\",\"time\":\"2021-01-13 09:08:34\",\"act\":\"修改\",\"new\":{\"id\":\"1\",\"zcbh\":\"\",\"xlh\":\"11111\",\"zclx\":1,\"zczt\":1,\"bm\":2,\"bgr\":\"1111\",\"dz\":\"11111\",\"cgsj\":\"2021-01-12\",\"rzsj\":\"2021-01-12\",\"dotime\":\"2021-01-12 19:30:38\",\"zbsc\":\"0\",\"sysc\":\"0\",\"pp\":\"1111\",\"xh\":\"\",\"zcly\":\"自购\",\"zcjz\":\"11111.00\",\"gg\":\"\",\"bz\":\"\",\"img\":\"\",\"wlbs\":0,\"ip\":\"\",\"xsq\":\"\",\"cw\":\"11\",\"yp\":\"0\",\"nc\":\"0\",\"SOUL_ROW_INDEX\":0}},{\"user\":\"111\",\"time\":\"2021-01-13 11:05:54\",\"act\":\"修改\",\"new\":{\"zclx\":\"1\",\"zczt\":\"1\",\"cw\":\"1111\",\"zcbh\":\"\",\"xlh\":\"11111\",\"bgr\":\"1111\",\"bm\":\"2\",\"dz\":\"11111\",\"cgsj\":\"2021-01-12\",\"rzsj\":\"2021-01-12\",\"zbsc\":\"0\",\"sysc\":\"0\",\"pp\":\"1111\",\"xh\":\"\",\"gg\":\"\",\"zcly\":\"自购\",\"zcjz\":\"11111.00\",\"bz\":\"\",\"file\":\"\",\"img\":\"\",\"wlbs\":\"0\",\"ip\":\"\",\"xsq\":\"\",\"yp\":\"0\",\"nc\":\"0\"}},{\"user\":\"admin\",\"time\":\"2021-01-14 12:11:23\",\"act\":\"修改\",\"new\":{\"zclx\":\"2\",\"zczt\":\"1\",\"cw\":\"ThinkPad T480s(20L7002XCD)\",\"zcbh\":\"\",\"xlh\":\"111111231\",\"bgr\":\"测试\",\"bm\":\"2\",\"dz\":\"测试地址\",\"cgsj\":\"2021-01-12\",\"rzsj\":\"2021-01-15\",\"zbsc\":\"3\",\"sysc\":\"5\",\"pp\":\"联想\",\"xh\":\"T480s\",\"gg\":\"Intel 酷睿i7 8550U 8G内存 512G固态版\",\"zcly\":\"自购\",\"zcjz\":\"11111.00\",\"bz\":\"\",\"file\":\"\",\"img\":\"/uploads/a14b3f3c41a1500f638de25ad80893ca.jpg\",\"wlbs\":\"1\",\"ip\":\"\",\"xsq\":\"\",\"yp\":\"0\",\"nc\":\"0\"}}]','2021-01-14 04:11:23');
/*!40000 ALTER TABLE `xinxizichan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zclx`
--

DROP TABLE IF EXISTS `zclx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zclx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '资产类型名称',
  `zcfl` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '资产分组，0：管理员管理的；1：信息中心管理的；2：办公室管理的；3：物管办管理的',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='资产类型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zclx`
--

LOCK TABLES `zclx` WRITE;
/*!40000 ALTER TABLE `zclx` DISABLE KEYS */;
INSERT INTO `zclx` VALUES (1,'台式电脑',1,1),(2,'笔记本电脑',1,1),(3,'电脑一体机',1,1),(4,'激光打印机',1,1),(5,'针式打印机',1,1),(6,'打复印一体机',1,1),(7,'交换机',1,1),(8,'路由器',1,1),(9,'防火墙',1,1),(10,'服务器',1,1),(11,'投影仪',1,1),(12,'会议终端',1,1),(18,'电视机',3,1),(35,'扫码枪',1,1),(34,'自助机',1,1),(33,'执法记录仪',1,1),(32,'碎纸机',1,1),(31,'扫描仪',1,1),(30,'高拍仪',1,1),(36,'自助办税终端',1,1),(37,'摄像头',1,1),(38,'地点',2,1);
/*!40000 ALTER TABLE `zclx` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zhuangtai`
--

DROP TABLE IF EXISTS `zhuangtai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zhuangtai` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT '状态名',
  `status` int(1) unsigned NOT NULL DEFAULT '1',
  `icon` varchar(64) CHARACTER SET utf8mb4 NOT NULL COMMENT '图标',
  `color` varchar(64) CHARACTER SET utf8mb4 NOT NULL COMMENT '颜色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zhuangtai`
--

LOCK TABLES `zhuangtai` WRITE;
/*!40000 ALTER TABLE `zhuangtai` DISABLE KEYS */;
INSERT INTO `zhuangtai` VALUES (1,'在用',1,'fa-circle','#06f530'),(2,'未分配',1,'fa-circle-o','#0352f5'),(3,'报废在用',1,'fa-dot-circle-o','#f58e0b'),(4,'报废待处置',1,'fa-adjust','#d60af3'),(5,'报废已处置',1,'fa-ban','#f50606'),(6,'借出',1,'fa-stop-circle','#1aa094');
/*!40000 ALTER TABLE `zhuangtai` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-14 14:40:39
