-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 16 2011 г., 16:57
-- Версия сервера: 5.1.54
-- Версия PHP: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `4otaku`
--

--
-- Структура таблицы `api_log`
--

CREATE TABLE IF NOT EXISTS `api_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `type` varchar(10) NOT NULL,
  `data` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `history` (`uid`,`type`,`date`),
  KEY `track` (`ip`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Структура таблицы `art`
--

CREATE TABLE IF NOT EXISTS `art` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `thumb` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `resized` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `animated` tinyint(3) unsigned NOT NULL,
  `author` text COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `tag` text COLLATE utf8_unicode_ci NOT NULL,
  `pool` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` mediumint(9) NOT NULL,
  `translator` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(4096) COLLATE utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- Дамп данных таблицы `art`
--

INSERT INTO `art` (`id`, `md5`, `thumb`, `extension`, `resized`, `animated`, `author`, `category`, `tag`, `pool`, `rating`, `translator`, `source`, `comment_count`, `last_comment`, `pretty_date`, `sortdate`, `area`) VALUES
(1, '0cbf7a519b34fce18506fe8d369299d6', '8972a3a63ef1b49d3e58051b6513fbe5', 'jpg', '', 0, '|_|', '|nsfw|', '|blue_eyes|blue_hair|red_eyes|red_hair|swimsuit|water|yuri|', '|', 0, '', '', 0, 0, 'Май 25, 2010', 1274812602000, 'main'),
(2, 'e43a2e32f39fcdedad43ebeda7824c2b', 'b1ffdd2bf29c32b2f9b83b1a5eba0e77', 'jpg', '2046x3026px; 1.4 мб', 0, '|nameless|', '|none|', '|animal_ears|blue_eyes|green_eyes|ribbon|weapon|', '|25|', 0, '', '', 0, 0, 'Май 25, 2010', 1274813489000, 'main'),
(4, '4faf384b64398038818e3fd76a2a2c47', 'e7ee38af190684c46dd48ea0b3390151', 'jpg', '', 0, '|lbiss|', '|none|', '|3girls|ayanami_rei|makinami_mari_illustrious|neon_genesis_evangelion|souryuu_asuka_langley|glasses|swimsuit|blue_hair|red_eyes|red_hair|blue_eyes|', '|27|', 0, '', '', 0, 0, 'Май 25, 2010', 1274815337000, 'main'),
(5, '504d4b1a38b620f097033063b4201d11', '1fa27df073884fda66a0734d4acceaa0', 'jpg', '', 0, '|lbiss|', '|none|', '|creepy|hatsune_miku|microfon|soul_crushingly_depressing|vocaloid|twintails|monochrome|akita_morgue|chair|glasses|long_hair|machine|microphone|microphone_stand|morbid|mustache|necktie|old_man|pantyhose|saliva|sepia|sitting|very_long_hair|', '|19|', 0, '', '', 1, 1299671770740, 'Май 25, 2010', 1274816374000, 'main'),
(6, '6c5f9f7b93bcf1d6640d953ad6d3edd6', 'ecd6099bf75c6c0a1fd5c30ed8d26c36', 'jpg', '849x522px; 371.5 кб', 0, '|lbiss|', '|none|', '|comic|hatsune_miku|long_hair|monochrome|vocaloid|twintails|', '|', 0, '', '', 0, 0, 'Сентябрь 21, 2010', 1285083275180, 'flea_market'),
(7, 'd75dcc00bb84b7f1f7926d36fc136fb0', 'a0481c4ca02aad8cfdfb9abee4e1e9b5', 'jpg', '1744x1191px; 327.4 кб', 0, '|w8m|', '|none|', '|blonde_hair|green_eyes|ueda_ryo|', '|27|', 0, '', '', 0, 0, 'Май 26, 2010', 1274891376000, 'main'),
(8, '2439265933107dfa4642cc46bec935ae', '4c2660528f6f1556581f77edc09f6578', 'jpg', '', 0, '|w8m|', '|game_cg|', '|oyari_ashito|rondo_leaflet|maid_uniform|deletion_request|', '|', 0, '', '', 0, 0, 'Сентябрь 23, 2010', 1285241067990, 'deleted'),
(9, '9f57c3a68e06b97e82fb5f217ed2a61c', '86fed4878d584a49bee2a742c149c644', 'jpg', '', 0, '|w8m|', '|game_cg|', '|oyari_ashito|rondo_leaflet|blue_eyes|blonde_hair|maid_uniform|', '|', 0, '', '', 0, 0, 'Сентябрь 23, 2010', 1285241062130, 'deleted'),
(10, 'd62c63f21747385cf17ab58cf398596b', 'e4d534d4000211f6ceee5520ca2ab782', 'jpg', '', 0, '|w8m|', '|game_cg|', '|oyari_ashito|rondo_leaflet|red_hair|black_hair|', '|', 0, '', '', 0, 0, 'Сентябрь 23, 2010', 1285241048470, 'deleted'),
(11, '14c3d3c60e4d2db8badd4adbd909f112', '6897d81917b67d1cd8b42b75ff2f1242', 'jpg', '', 0, '|w8m|', '|game_cg|', '|oyari_ashito|rondo_leaflet|brown_hair|solo|barefoot|', '|', 0, '', '', 0, 0, 'Сентябрь 23, 2010', 1285241055640, 'deleted'),
(12, '98e3f4e2cde35340605473f766acb4bb', '1a9d64e00d30dd3c700855cbc25ebb90', 'jpg', '', 0, '|w8m|', '|game_cg|', '|brown_hair|cat|green_eyes|oyari_ashito|rondo_leaflet|white_hair|', '|', 0, '', '', 0, 0, 'Сентябрь 23, 2010', 1285241036100, 'deleted'),
(13, 'b93932cf23743d90b2d7464e90fb699f', '28a484aaac42d350c0cf2356782118d9', 'jpg', '', 0, '|w8m|', '|game_cg|', '|oyari_ashito|rondo_leaflet|', '|', 0, '', '', 0, 0, 'Сентябрь 23, 2010', 1285241043510, 'deleted'),
(14, '7d2b9c94731040b4a72ef66928a10247', 'b65bc2dc0922726d4aa2a811c11321d5', 'jpg', '2498x3536px; 3.5 мб', 0, '|lbiss|', '|none|', '|2girls|blue_hair|cirno|green_hair|touhou|water|wings|sayori|', '|', 2, '', 'danbooru:638673', 1, 1284737342300, 'Май 26, 2010', 1274895781000, 'main'),
(15, 'c7c789a0d469e01191c4be372198eeb8', 'd7a986c397929ca8e655bebb5bfa29fc', 'jpg', '2494x3532px; 3.6 мб', 0, '|lbiss|', '|none|', '|konpaku_youmu|myon|saigyouji_yuyuko|touhou|sayori|', '|', 0, '', 'http://danbooru.donmai.us/post/show/638680 danbooru:638680', 11, 1311435268200, 'Май 26, 2010', 1274895912000, 'main'),
(16, '39d5ee916d8fd72a19c1cbef07c31024', '837eb055f2706380ca512fd705789f30', 'jpg', '', 0, '|w8m|', '|none|', '|yukirin|', '|27|', 0, '', '', 0, 0, 'Май 27, 2010', 1274956439000, 'main'),
(17, '75786ad732419edd0cfbcbd3a2922511', '09e73d0e2fbb0ec2700931f80ce0a110', 'jpg', '', 0, '|w8m|', '|nsfw|', '|yukirin|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274956439000, 'main'),
(18, 'cbbfe69089080ecb08541cd37ab7d2b0', '62b354441f4205bf1a11fee6a3b82001', 'jpg', '900x660px; 194.7 кб', 0, '|w8m|', '|nsfw|', '|yukirin|purple_hair|purple_eyes|', '|27|', 0, '', '', 0, 0, 'Май 27, 2010', 1274956649000, 'main'),
(19, '66a2ba0b26fff5598c25824b6b1c693d', '2ff24c2ccf5193cd0d6ac4d3d9fa12d8', 'jpg', '', 0, '|w8m|', '|none|', '|yukirin|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274956649000, 'main'),
(20, '81f8b4bbe8ecbeea9c67f27d9c3ed680', 'dad3dd95d053f28ca5a49d38f9f9c7c6', 'jpg', '900x643px; 118.3 кб', 0, '|w8m|', '|none|', '|yukirin|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274956649000, 'main'),
(21, 'b9f469b6b2800ee6d4100080e303770c', '261368e4ed33b3964ccd8748bd238cf2', 'jpg', '', 0, '|lbiss|', '|none|', '|ayanami_rei|blue_hair|linux|neon_genesis_evangelion|red_eyes|penguin|scarf|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274956992000, 'main'),
(22, '80c08e435e7a06122f7f8dad0b61f6fb', '44b2d25ee9dd6c5c2bfdb4f48fb55817', 'jpg', '1600x1067px; 952.6 кб', 0, '|w8m|', '|photo|', '|amano_ai|', '|25|', 0, '', 'pixiv:188370', 0, 0, 'Май 27, 2010', 1274957012000, 'main'),
(23, 'ac1346358c63dcdbe935453e05af7276', 'bba61fa23cf1a4c3e94a55e0502d18ac', 'jpg', '1067x1600px; 787.7 кб', 0, '|w8m|', '|photo|', '|amano_ai|', '|25|', 0, '', 'pixiv:188363', 0, 0, 'Май 27, 2010', 1274957012000, 'main'),
(24, '7316a1b99e38f08d42c7fdf18231fdbd', 'e253965dc1ef06e53958a39b8f8ecc71', 'jpg', '1600x1067px; 733.5 кб', 0, '|w8m|', '|photo|', '|amano_ai|', '|25|', 0, '', 'pixiv:188348', 0, 0, 'Май 27, 2010', 1274957012000, 'main'),
(25, '3bcf2bfdedc114f19cb21b41dbb67d41', '53e8e2b01ff8df307775912e3f92a635', 'jpg', '1067x1600px; 731.6 кб', 0, '|w8m|', '|photo|', '|amano_ai|', '|25|', 0, '', 'pixiv:188367', 1, 1276067551000, 'Май 27, 2010', 1274957116000, 'main'),
(26, '48e8602fd04acd00a64e8db2e5598ff6', '96a69280dbd6aa2ec1a789d1205835e3', 'jpg', '1024x768px; 391.3 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(27, '5c288d869310df06ca27856e7ee3c7e2', 'e38522e00923f29184e43417defb6308', 'jpg', '1024x768px; 360 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(28, '68ff585f42ab6198acfa4bc82dd8724d', '9570368f1d403e0ee357201950c552c6', 'jpg', '1024x768px; 322.4 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(29, 'a55307fe6f2c0ad5daea53567183c794', '3c4c4d9592a739b9e6771682881d8c04', 'jpg', '1024x768px; 381.4 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(30, '4406382312491eb1faf97fc31a371bb5', '7c2cb1a219943d2cf5ad5479aa11b648', 'jpg', '1024x768px; 344.4 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(31, '8f240e8fba5365beba72ea0449ae34d7', '5ef8e42dff2df19db16663afa1b39019', 'jpg', '1024x768px; 387.7 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(32, '32ed99416123d5a48fa8c0794ca2719c', '86128394bbb97326b2f4f236593d04d9', 'jpg', '1024x768px; 396.1 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(33, '6e629ec801f839b329dcfc106f06cdc9', '59b833de6344498fac59b75a362965b1', 'jpg', '1024x768px; 347.9 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(34, 'c4a4c00f5ff07548f1680db10b266d0a', '2756192acd292c641c1f7b014a150031', 'jpg', '1024x768px; 364.4 кб', 0, '|w8m|', '|none|', '|brown_hair|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(35, 'ca05be87a5c816e27249a7b93919c8c4', 'ee104052661c17373f804f390dfcbd6c', 'jpg', '1024x768px; 415.6 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(36, '54789170edb26af973a72fd4eaa24dfe', 'b9257cd7e2a06fbdebd36962f7f11150', 'jpg', '1024x768px; 422.2 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(37, '2adc75212465eb2c27de28c30f13d711', 'a9e6025d68f557c88699b3af10df6d5a', 'jpg', '1024x768px; 369.8 кб', 0, '|w8m|', '|none|', '|sister_princess|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274957792000, 'main'),
(38, 'd6ff35652230d887f1cd2cfe533f0ed9', 'fd361ac6a89fe15663a4be0e4fbe0e9b', 'jpg', '3508x2483px; 5.6 мб', 0, '|lbiss|', '|none|', '|saber|меха|sword|', '|', 0, '', 'danbooru:602034 pixiv:8243865', 0, 0, 'Май 27, 2010', 1274958701000, 'main'),
(39, '063f29a2df9031983587bc64165fd523', 'ad84fd7f0c8024f089750ed4d52bbef9', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274980936000, 'main'),
(40, '1207d6978ecc92db2123963ae0829859', '673e21af67d24e73b0a0ec48abb74113', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274980936000, 'main'),
(41, '804476aa87da6b4455a298fab88af993', '16dc661babca638da1efb5fe3c9dc256', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274980936000, 'main'),
(42, '8d27312c711894b2b60994f75b2d5311', 'ae5745e04ed0001388f3d46318e0f681', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274980936000, 'main'),
(43, '2ac7fcd0ded919bb25f722d5d4cb2ad5', '09191f284f51ff62da7665ccdeb8f91f', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274980936000, 'main'),
(44, 'b890cf0160a179fd81039f9b8ea112ca', '44511e7fe04b1f2b9f61ffd3df98a305', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(45, '0a75f4a52cdb706d144bafdc985bba4f', 'aec22ab7e2c67d9b3703715c9c8fde51', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(46, '6d2fad3be4a68f01023d2297d30cbca5', 'b7434e2cec2ab1eea7a47644bdd9d663', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|amputee|black_hair|creepy|from_behind|game_cg|kuchiki_toko|long_hair|painting|school_uniform|skull|socks|solo|sugina_miki|', '|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'deleted'),
(47, 'd6395bceba35eac12db5c46db4706a29', '9392cfe7407bd2288647e866efb8431e', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(48, '4fbbb531046aa0fc650927098bac9ecf', '86081a3aff4e17a32a77b626e6324c2c', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(49, 'e8978017041092e861a0e6a1e1046130', 'a11fff90d03acf9f003365366a15c85a', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(50, '5a680e2b5f6345c17e1086eeb2674745', 'fd12c2e5bd82b23fc749d9c391b72835', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(51, '4bb68ea948eb0607f739a8934c609117', '838588a172eb1f50ea72d177e14d5cb1', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(52, '91b75d6955a3e994153be62e14bb2ddf', '8f921213747d9e67c253f77ef6f5b670', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(53, '2b34c864ed34e112a083abb8d35d0bb5', '9fcecf5ed743ccca310b015e7ab33699', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(54, '4b54c4e5e2a3f3dcea48bbc3d114b5b0', '4e46ec9a930a3d2e9639115b3a844111', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(55, '656c0fcc5f29d08369a83d25e40be8e7', 'ef26204e0c4c200871f3b1e95a81f57e', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(56, '625855f26004c0a949e6bc9885d37e5d', 'd89f9fa1f66d7b0f0f2e37fe3b3e0a96', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|27|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(57, '5eebe75558988c41b5f199bd70a75fa6', 'a2ec9268f8c929e4148dd11851b80256', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|game_cg_map|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(58, '3c69c9a0bea98865072b42a301909470', '05a1369e50a5203420805f676ebaea53', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(59, '5a85bb198a577e59e8a6e4eee48f7fe9', 'eacfca3b5cb7c8b028b877980041f03b', 'jpg', '', 0, '|w8m|', '|game_cg|', '|kara_no_shoujo|', '|25|', 0, '', '', 0, 0, 'Май 27, 2010', 1274981068000, 'main'),
(60, '172efa53894d2f429ec4a826ec634446', '5da65b0210b0cfc3a87dcb5f18651c9a', 'jpg', '', 0, '|lbiss|', '|none|', '|toshiaki_takayama|меха|', '|', 0, '', 'danbooru:529210 pixiv:6276600', 0, 0, 'Май 28, 2010', 1275044911000, 'main'),
(61, '02316378a8ea89ab4558e5ba3d1940e2', 'cf4b54738e0c3f3830d4ea5d0b81fc92', 'jpg', '1920x1200px; 708.5 кб', 0, '|lbiss|', '|none|', '|clare|claymore|white_hair|yellow_eyes|armor|highres|sword|wallpapers|weapon|', '|', 0, '', '', 0, 0, 'Май 28, 2010', 1275045122000, 'main'),
(62, '2abe285ac5596f86f52e4c6d82105284', '3676aa2dbf465bc946e79f1c719731d3', 'jpg', '1920x1200px; 1.5 мб', 0, '|lbiss|', '|none|', '|ahoge|blonde_hair|armor|fate_stay_night|fire|saber|type_moon|wallpapers|sword|', '|25|', 0, '', '', 0, 0, 'Май 28, 2010', 1275048544000, 'main'),
(63, '5b85160bed0a6761f7780d68eec97ff8', '59eb3f659a8f906ae86221fb36867351', 'jpg', '1600x1200px; 1.4 мб', 0, '|lbiss|', '|none|', '|toshiaki_takayama|', '|', 0, '', 'danbooru:663561', 0, 0, 'Май 29, 2010', 1275152700000, 'main'),
(64, '3b33d2eb4556acfc6621eb6dea355087', '802085e71dbfeaf56886b00b6e46c37c', 'jpg', '', 0, '|lbiss|', '|none|', '|toshiaki_takayama|меха|', '|', 0, '', 'danbooru:529145 pixiv:6276751', 0, 0, 'Май 29, 2010', 1275153368000, 'main'),
(65, 'e61ca8c135fc0ee58672df6397371b81', '31aaf6a09c8fcc9300a41021b170cebc', 'jpg', '', 0, '|lbiss|', '|none|', '|toshiaki_takayama|', '|', 0, '', 'danbooru:663587 pixiv:10173140', 0, 0, 'Май 29, 2010', 1275153513000, 'main'),
(67, '6a9328b721bc133a817ef32c0413b551', '50d1dc91bdf6a0107baa40440ce225ab', 'jpg', '', 0, '|lbiss|', '|none|', '|toshiaki_takayama|меха|', '|', 0, '', 'danbooru:602419 pixiv:8321338', 0, 0, 'Май 29, 2010', 1275156990000, 'main'),
(68, '6cb6dca51a9d1b915fb64c524384ea6c', 'e5ba24b3daa19bf72a1a577d87821dde', 'jpg', '', 0, '|lbiss|', '|none|', '|toshiaki_takayama|меха|', '|', 0, '', 'danbooru:553405 pixiv:4824937', 0, 0, 'Май 29, 2010', 1275157005000, 'main'),
(69, '9e66f947bacc2ecd860cac42c7e9fcf1', '9f9b6bf003db47d67f95892af34a6e21', 'jpg', '', 0, '|lbiss|', '|none|', '|toshiaki_takayama|меха|', '|25|', 0, '', '', 0, 0, 'Май 29, 2010', 1275158020000, 'main'),
(70, 'a0b2984a4d0b3238b844f7a1919217fc', 'bdc0557fa40e39c276f755bd46696c4e', 'jpg', '850x579px; 192.9 кб', 0, '|lbiss|', '|none|', '|toshiaki_takayama|меха|', '|', 0, '', '', 0, 0, 'Май 29, 2010', 1275158284000, 'main'),
(71, '65d8def2ecda7a0a753dcecc13df021e', 'cafc36403d41e79027c8fb6537c35815', 'jpg', '1024x768px; 84.2 кб', 0, '|lbiss|', '|none|', '|blue_hair|fate_stay_night|lancer|type_moon|wolf|', '|', 0, '', 'Данбура! danbooru:43490', 0, 0, 'Май 29, 2010', 1275161243000, 'main'),
(72, '80384f2496bcfb0ac2113795359ba280', 'db0ccae3ea60ffb6debf198adc8b1f8d', 'jpg', '', 0, '|lbiss|', '|none|', '|blue_hair|fate_stay_night|lancer|red_eyes|type_moon|deletion_request|', '|', 0, '', 'danbooru:34262', 0, 0, 'Май 29, 2010', 1275161891000, 'main'),
(73, '047d50b09cd7b6f111d1a80c531ee7d4', '23a29804799e7390873609725986727c', 'jpg', '', 0, '|lbiss|', '|none|', '|2girls|blonde_hair|canaan|oosawa_maria|white_eyes|white_hair|yellow_eyes|ahoge|canaan__character_|', '|', 0, '', 'danbooru:528326', 0, 0, 'Май 29, 2010', 1275161963000, 'main'),
(74, 'ef0c5a0d9260ecaff25f17f1126230e0', '07ec6aaab5b67ce391a8b1658fe0f9eb', 'jpg', '1500x2065px; 433.2 кб', 0, '|lbiss|', '|none|', '|alphard|arcueid_brunestud|armor|black_hair|blonde_hair|brown_eyes|brown_hair|canaan|crossover|fate_stay_night|fate_unlimited_codes|gloves|green_eyes|grey_eyes|japanese_clothes|kara_no_kyoukai|kimono|kio_sayuki|liang_qi|red_eyes|ryougi_shiki|saber|saber_lily|tsukihime|type_moon|white_hair|ahoge|antennae|long_hair|short_hair|smoking|tail|chibi|bow|canaan__character_|', '|', 0, '', 'Данбура danbooru:578798 pixiv:7663586', 0, 0, 'Май 29, 2010', 1275162319000, 'main'),
(75, 'c85edb8f6e729e6755295fb9855808b2', '1c5bde7a0d3cabdaeb302b164a996237', 'jpg', '', 0, '|anonimus|', '|none|', '|brown_eyes|nagato_yuki|purple_hair|suzumiya_haruhi_no_yuuutsu|', '|25|', 0, '', 'pixiv:24228223', 0, 0, 'Май 30, 2010', 1275219203000, 'main'),
(76, 'cff0bcea462afab3eeae5f1a8d678810', '0557a61d2bc9995bb302a036c64fc26a', 'jpg', '', 0, '|anonimus|', '|none|', '|brown_eyes|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|25|', 0, '', 'pixiv:23388973', 0, 0, 'Май 30, 2010', 1275219406000, 'main'),
(77, '88a46c8e2fefcce059e1e1741e2b7744', 'b7f30339233260eb9f0912a1ad554bfc', 'jpg', '', 0, '|anonimus|', '|none|', '|brown_eyes|nagato_yuki|suzumiya_haruhi_no_yuuutsu|', '|', 0, '', 'danbooru:226120 pixiv:24641071', 0, 0, 'Май 30, 2010', 1275219583000, 'main'),
(78, '924aec1e7a178d4adbec5061e21bafc2', '10d38094248ed93a9cd104387d32a721', 'jpg', '', 0, '|anonimus|', '|none|', '|book|brown_eyes|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|', 0, '', 'danbooru:250921 pixiv:842374', 0, 0, 'Май 30, 2010', 1275220308000, 'main'),
(79, '910b9907273e4a8bb257dce9d22d97a7', '2ffd5592fb92e28bf79d6df50a36a03f', 'jpg', '', 0, '|anonimus|', '|none|', '|book|brown_eyes|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|', 0, '', 'danbooru:118426 pixiv:79082407', 0, 0, 'Май 30, 2010', 1275220308000, 'main'),
(80, '86822fbfd649e27a511443727c1efa46', '18076c9837e953aad5130f3da1fd5f3a', 'jpg', '', 0, '|anonimus|', '|none|', '|book|brown_eyes|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|', 0, '', 'danbooru:488587 pixiv:5287093', 0, 0, 'Май 30, 2010', 1275220308000, 'main'),
(81, 'fcd2ffdf64bbc909ea2d35d3a54d87ac', '75e0af376fdb196249fdd0569ec7c7a2', 'jpg', '', 0, '|anonimus|', '|none|', '|book|brown_eyes|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|', 0, '', 'danbooru:544406', 0, 0, 'Май 30, 2010', 1275220308000, 'main'),
(82, '90e5402f1620d7a9b7bb1f0481478f3e', '15ef89b18573ca432a67ff63d21eec5a', 'jpg', '', 0, '|lbiss|', '|none|', '|asahina_mikuru|nagato_yuki|suzumiya_haruhi|suzumiya_haruhi_no_yuuutsu|', '|27|', 0, '', '', 0, 0, 'Май 30, 2010', 1275220491000, 'main'),
(83, '85e37e2069a07da845e18cc3c66ad843', '88bb26acc32f74f9497b992e1f7c49cb', 'jpg', '', 0, '|anonimus|', '|none|', '|brown_eyes|glasses|nagato_yuki|suzumiya_haruhi_no_yuuutsu|', '|25|', 0, '', 'pixiv:90765105', 0, 0, 'Май 30, 2010', 1275221014000, 'main'),
(84, '645855e75c8ff07aa9d48accbe3aebec', 'fac94172a4ded58b84ac989730ccce3a', 'jpg', '1200x1200px; 115.3 кб', 0, '|anonimus|', '|none|', '|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|25|', 0, '', 'pixiv:90830483', 0, 0, 'Май 30, 2010', 1275221337000, 'main'),
(85, '67256b7eb136bbf6bdf74acaf57603f2', '2854d2ee8949ceeda81f1bf6002e3c13', 'jpg', '1100x1221px; 71.6 кб', 0, '|anonimus|', '|none|', '|glasses|monochrome|nagato_yuki|serafuku|suzumiya_haruhi_no_yuuutsu|', '|', 0, '', 'danbooru:45000 pixiv:90952300', 0, 0, 'Май 30, 2010', 1275221425000, 'main'),
(86, '18315ad0afb5a1b0d6ac25c1bcca33ee', '839b008c6d8b962b1f83436caee2c7aa', 'jpg', '5900x8272px; 2.7 мб', 0, '|anonimus|', '|none|', '|absurdres|book|cardigan|curtains|glasses|highres|nagato_yuki|nishiya_futoshi|purple_hair|school_uniform|smile|suzumiya_haruhi_no_shoushitsu|suzumiya_haruhi_no_yuuutsu|window|yellow_eyes|', '|27|', 1, '', 'pixiv:91064813', 0, 0, 'Май 30, 2010', 1275221760000, 'deleted'),
(87, '0da46e16b5a88e17ea473ee601ec70f0', '1ee6057829ba93691408c328ed4eda05', 'jpg', '', 0, '|Mervish|', '|none|', '|mazinger|меха|', '|', 0, '', 'Danbooru danbooru:355801 pixiv:1620627', 0, 0, 'Май 30, 2010', 1275223164000, 'main'),
(88, '8cbcae17a8f261ecf6942aa30c5f3f1f', '439755a4b824e30d71b8b17f361c6a98', 'png', '', 0, '|Mervish|', '|none|', '|devilmen|mazinger|меха|', '|', 0, '', 'Danbooru danbooru:627951 pixiv:9164016', 0, 0, 'Май 30, 2010', 1275223420000, 'main'),
(89, '2b6606d7e2add9ead5f8accb153f93ce', '0fabd4539309a685271add11763da93c', 'jpg', '1024x768px; 260.9 кб', 0, '|lbiss|', '|none|', '|blonde_hair|fate_stay_night|fate_unlimited_codes|green_eyes|moon|saber|saber_lily|star_sky|type_moon|wallpapers|sword|', '|25|', 0, '', '', 0, 0, 'Май 30, 2010', 1275224457000, 'main'),
(90, '503f6822f645d11441a2379eaf15e59e', '4e844600fc5f715c7ab79fe8bfee2655', 'jpg', '1920x1200px; 922 кб', 0, '|lbiss|', '|none|', '|green_hair|hatsune_miku|long_hair|vocaloid|wallpapers|twintails|', '|', 0, '', 'danbooru:673419', 0, 0, 'Май 30, 2010', 1275229561000, 'main'),
(91, 'e6ed182d14735eae95e4d04550afee75', '5be8890fcde1fc125c067d0c0e879849', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|editio_perfecta|oyari_ashito|loli|oral|cum|sex|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241084730, 'cg'),
(92, 'cb489daf7e61f57cf480f41448074a60', 'e7070f1edfc7f9b99593e4af1e563e80', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|editio_perfecta|oyari_ashito|loli|oral|sex|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241101070, 'cg'),
(93, '2c9131951a5e181f5e9cbe340882a9be', 'd1eb691d63e517f561511a340aba13c8', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|oyari_ashito|editio_perfecta|sex|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241107680, 'deleted'),
(94, 'b39afcf2a0134aa611169f342d94167e', '44d7eff70df51002d9681f8ca27efa0d', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|editio_perfecta|oyari_ashito|loli|oral|cum|sex|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241140320, 'cg'),
(95, '3405e477c7962ee6f9cd8103052a367f', '239e825705c1045130dfedbad09cdda5', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|editio_perfecta|oyari_ashito|loli|cum|sex|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241144550, 'cg'),
(96, 'c4f88cbd6c37090d3ac02db730e40fb6', 'bba39b82b025e1050827c77fe5a5a05a', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|editio_perfecta|oyari_ashito|loli|oral|sex|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241163600, 'cg'),
(97, '25c1cac48f559ea7e640665380b98503', 'fa4ca27b956470f3e607065126f57d52', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|oyari_ashito|editio_perfecta|sex|kiss|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241191040, 'deleted'),
(98, 'e7b681e9f6b2fc4230027dc02021893a', '2b946b2ffe0ed205e535fd8a93fe1856', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|oyari_ashito|editio_perfecta|sex|kiss|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241176090, 'deleted'),
(99, '6dc0c205fc4b66c77a1b17350fb43e52', '03a356e6be0ed7e21ed5c57951d1a1d4', 'jpg', '', 0, '|w8m|', '|nsfw|game_cg|', '|editio_perfecta|loli|oyari_ashito|game_cg_map|', '|', 0, '', 'LittleWitch Romanesque Editio Perfecta', 0, 0, 'Сентябрь 23, 2010', 1285241184840, 'cg'),
(100, '6053e4d6523c1c01f8b8cce29a03b688', '872dca29c8d1e31d31854270ceeaba21', 'jpg', '', 0, '|w8m|', '|game_cg|', '|gadget_trial|game_cg_map|mecha_musume|', '|27|', 0, '', '', 1, 1300519081980, 'Май 30, 2010', 1275239441000, 'main');

--
-- Триггеры `art`
--
DROP TRIGGER IF EXISTS `on_art_delete`;
DELIMITER //
CREATE TRIGGER `on_art_delete` AFTER DELETE ON `art`
 FOR EACH ROW BEGIN
delete from art_similar where id = old.id;
update art_similar set similar=replace(similar,concat('|',old.id,'|'),'|');
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `art_in_pack`
--

CREATE TABLE IF NOT EXISTS `art_in_pack` (
  `art_id` int(10) unsigned NOT NULL,
  `pack_id` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`art_id`,`pack_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `art_in_pack`
--

INSERT INTO `art_in_pack` (`art_id`, `pack_id`, `order`, `filename`) VALUES
(91, 73, 170, 'lw03v_01_06.jpg'),
(92, 73, 171, 'lw03v_02_05.jpg'),
(94, 73, 173, 'lw03v_04_07.jpg'),
(95, 73, 174, 'lw03v_05_05.jpg'),
(96, 73, 175, 'lw03v_07_05.jpg'),
(99, 73, 178, 'lw03v_08_01.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `art_pack`
--

CREATE TABLE IF NOT EXISTS `art_pack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(2040) NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `cover` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `date` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Дамп данных таблицы `art_pack`
--

INSERT INTO `art_pack` (`id`, `filename`, `weight`, `cover`, `title`, `text`, `pretty_text`, `comments`, `date`) VALUES
(73, 'Romanesque Editio Perfecta.zip', 0, 'cffa8b872c86a9f692e4a0e02bc8a3f1', 'Romanesque Editio Perfecta', '<a href="/go?http://vndb.org/v575">http://vndb.org/v575</a><br />\r\nShoujo Mahou Gaku Little Witch Romanesque<br />\r\n少女魔法学 リトルウィッチロマネスク', 'http://vndb.org/v575\r\nShoujo Mahou Gaku Little Witch Romanesque\r\n少女魔法学 リトルウィッチロマネスク', 0, '2010-09-15 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `art_pool`
--

CREATE TABLE IF NOT EXISTS `art_in_pool` (
  `art_id` int(10) unsigned NOT NULL,
  `pool_id` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`art_id`,`pool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `art_pool` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `art` text COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `sortdate` (`sortdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

--
-- Дамп данных таблицы `art_pool`
--

INSERT INTO `art_pool` (`id`, `name`, `text`, `pretty_text`, `count`, `art`, `password`, `email`, `sortdate`) VALUES
(19, 'Pain', 'Душа человека жива только пока она способна ощущать боль.', 'Душа человека жива только пока она способна ощущать боль.', 64, '|23629|68352|67041|27965|27214|27216|27215|27198|27194|13763|26982|26576|15042|24369|24366|22794|21661|21237|20923|19824|19442|17255|2576|18777|4125|18644|18346|2429|14203|5691|15365|16241|17264|17416|15611|15816|18615|18616|18617|18618|18619|18620|18621|18622|18623|18624|18625|18626|18627|18628|18629|18630|18631|18633|16229|15158|6217|5688|1317|1316|1315|1314|1313|5|', 'd41d8cd98f00b204e9800998ecf8427e', '', 1296340038980),
(25, 'не с данбуры', 'Арт, которого нет и никогда небыло на данбуре.', 'Арт, которого нет и никогда небыло на данбуре.', 4494, '|68775|27600|22269|2|17|20|19|21|24|23|22|25|37|36|35|34|33|32|31|30|29|28|27|26|43|42|41|40|39|59|58|57|55|54|53|52|51|50|49|48|47|45|44|62|69|75|76|83|84|89|121|119|126|125|124|123|122|136|135|134|133|132|131|130|129|128|127|143|142|141|140|139|137|151|150|149|148|147|146|145|144|166|165|164|163|162|161|160|159|158|157|156|155|154|153|152|180|179|178|177|176|175|174|173|172|171|170|169|168|167|181|188|186|185|184|183|182|203|201|200|199|198|197|196|195|194|193|192|191|190|189|218|217|216|215|214|213|212|211|210|209|208|207|206|205|204|225|224|223|222|221|220|219|239|237|236|234|233|232|231|230|228|227|226|240|242|243|245|246|247|248|249|250|251|252|253|254|255|256|257|258|259|260|261|262|263|264|265|266|267|268|269|270|271|272|273|274|275|276|277|278|279|280|281|282|283|284|285|286|287|288|289|293|303|302|301|313|312|311|310|309|308|307|306|305|304|323|322|321|320|319|318|317|316|315|314|331|343|342|339|355|354|352|369|362|360|383|382|381|380|379|378|375|409|408|405|400|396|393|391|429|416|415|410|442|437|436|432|444|443|454|474|466|465|464|463|462|461|458|486|505|502|501|500|494|506|508|509|521|520|519|518|516|514|513|512|511|555|554|552|551|550|549|548|546|545|544|543|542|541|537|536|535|534|533|532|531|530|529|528|527|526|525|524|586|585|582|577|569|567|565|593|602|604|714|715|752|751|755|753|762|760|758|764|767|778|777|776|775|774|773|772|771|770|769|779|783|782|781|780|788|832|831|830|833|844|870|868|861|859|857|856|855|853|847|882|881|879|877|876|875|874|884|885|886|887|888|889|890|891|892|893|895|897|899|900|901|902|903|904|905|906|908|909|911|912|913|920|923|1001|1002|1003|1004|1006|1007|1009|1010|1011|1012|1013|1014|1015|1018|1019|1021|1022|1023|1024|1025|1026|1027|1037|1047|1048|926|927|928|929|930|931|932|933|934|935|936|937|938|939|940|941|942|943|944|945|946|947|948|949|950|951|952|953|954|955|956|957|958|959|960|961|962|963|964|965|966|967|968|969|970|971|972|973|974|975|977|979|980|982|987|991|994|995|997|998|1000|1060|1064|1065|1066|1069|1070|1071|1072|1073|1074|1075|1076|1077|1078|1079|1080|1081|1083|1084|1088|1089|1090|1091|1092|1093|1094|1095|1096|1097|1098|1099|1100|1101|1102|1105|1106|1109|1111|1112|1113|1114|1116|1117|1119|1122|1123|1128|1133|1134|1135|1136|1137|1138|1139|1140|1141|1142|1143|1144|1145|1146|1147|1148|1149|1150|1151|1152|1153|1154|1184|1189|1190|1191|1192|1193|1194|1195|1196|1197|1198|1199|1200|1201|1202|1203|1204|1205|1206|1207|1208|1209|1210|1211|1212|1213|1214|1215|1216|1217|1218|1219|1220|1221|1222|1223|1224|1226|1227|1228|1229|1230|1231|1232|1233|1234|1235|1236|1237|1238|1239|1240|1241|1242|1243|1244|1245|1246|1247|1248|1249|1250|1251|1252|1253|1254|1255|1256|1257|1258|1259|1260|1261|1262|1263|1264|1265|1266|1267|1268|1269|1270|1271|1272|1273|1274|1275|1276|1277|1278|1279|1280|1281|1282|1283|1284|1285|1286|1287|1288|1289|1290|1291|1292|1293|1294|1295|1296|1297|1298|1299|1300|1301|1302|1303|1304|1305|1306|1308|1309|1319|1320|1321|1322|1324|1325|1326|1333|1335|1337|1338|1339|1340|1341|1342|1343|1344|1345|1346|1347|1348|1349|1350|1351|1352|1353|1357|1359|1360|1361|1362|1364|1365|1366|1367|1368|1369|1370|1371|1372|1373|1374|1376|1377|1379|1380|1381|1382|1383|1384|1385|1386|1387|1390|1391|1392|1393|1394|1395|1396|1397|1398|1399|1400|1401|1402|1403|1404|1411|1412|1413|1414|1415|1416|1417|1418|1419|1420|1421|1422|1423|1424|1425|1426|1427|1428|1429|1430|1431|1433|1436|1437|1438|1441|1442|1443|1445|1446|1447|1448|1449|1451|1452|1453|1454|1455|1457|1458|1462|1463|1464|1465|1467|1468|1473|1474|1475|1476|1484|1485|1486|1492|1495|1496|1497|1498|1499|1500|1501|1502|1504|1505|1506|1507|1509|1510|1511|1512|1513|1514|1515|1517|1518|1519|1520|1521|1523|1524|1525|1527|1528|1529|1530|1531|1532|1533|1534|1535|1536|1537|1538|1539|1540|1541|1542|1543|1544|1545|1546|1547|1548|1549|1550|1552|1553|1554|1555|1556|1557|1558|1559|1560|1561|1562|1563|1564|1565|1566|1567|1568|1569|1575|1582|1585|1589|1590|1591|1592|1593|1594|1595|1596|1597|1598|1599|1600|1601|1602|1603|1604|1605|1607|1608|1609|1610|1611|1612|1613|1614|1615|1616|1617|1618|1619|1620|1621|1622|1623|1624|1625|1626|1627|1628|1630|1632|1636|1637|1638|1639|1640|1651|1657|1658|1659|1661|1662|1663|1664|1665|1666|1667|1668|1669|1672|1674|1677|1694|1696|1701|1702|1703|1707|1708|1714|1715|1722|1723|1724|1725|1729|1731|1732|1737|1743|1748|1749|1750|1751|1753|1754|1758|1834|1836|1837|1838|1839|1840|1843|1844|1845|1846|1848|1849|1850|1851|1852|1853|1854|1855|1856|1857|1858|1863|1868|1871|1793|1794|1795|1798|1799|1800|1801|1804|1806|1807|1811|1812|1817|1820|1827|1828|1829|1830|1889|1892|1893|1894|1895|1896|1897|1898|1899|1900|1901|1902|1903|1904|1905|1906|1907|1908|1909|1910|1911|1912|1913|1914|1915|1916|1917|1918|1919|1920|1921|1922|1923|1924|1925|1926|1927|1928|1929|1930|1931|1932|1933|1934|1935|1936|1937|1938|1939|1940|1941|1942|1943|1944|1945|1946|1947|1948|1949|1950|1951|1952|1953|1954|1955|1956|1957|1965|1968|1971|1972|1983|1984|1985|1986|1987|1988|1989|1990|1991|1992|1993|1994|1995|1996|1997|1998|1999|2000|2001|2002|2003|2004|2005|2006|2007|2008|2009|2010|2011|2012|2013|2015|2016|2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031|2032|2033|2035|2036|2037|2038|2044|2049|2055|2056|2057|2061|2064|2068|2069|2070|2071|2072|2073|2074|2075|2076|2077|2078|2079|2080|2081|2082|2083|2084|2085|2086|2088|2089|2090|2091|2092|2093|2094|2095|2096|2097|2098|2099|2100|2101|2102|2103|2104|2105|2106|2107|2108|2109|2110|2111|2112|2113|2114|2117|2119|2120|2122|2123|2124|2125|2126|2128|2129|2130|2131|2132|2133|2134|2135|2136|2138|2139|2140|2142|2143|2144|2145|2146|2147|2148|2149|2150|2151|2152|2153|2154|2155|2156|2157|2158|2159|2160|2161|2162|2163|2164|2165|2166|2167|2168|2169|2170|2171|2172|2173|2174|2175|2176|2177|2178|2179|2180|2181|2182|2183|2184|2185|2186|2187|2188|2189|2190|2191|2192|2193|2194|2195|2196|2197|2198|2199|2200|2201|2202|2203|2204|2205|2206|2210|2211|2212|2213|2214|2222|2224|2225|2226|2227|2228|2230|2232|2235|2237|2238|2239|2240|2241|2242|2243|2244|2245|2246|2247|2248|2249|2251|2252|2254|2255|2256|2257|2258|2260|2261|2262|2264|2266|2267|2269|2272|2284|2285|2288|2291|2292|2293|2294|2295|2296|2298|2299|2300|2301|2302|2303|2304|2305|2310|2314|2315|2316|2317|2320|2321|2322|2323|2324|2325|2326|2330|2331|2332|2333|2334|2335|2340|2341|2342|2344|2347|2349|2353|2354|2355|2356|2357|2358|2360|2361|2362|2363|2364|2365|2366|2367|2368|2369|2370|2371|2372|2373|2374|2375|2376|2377|2378|2379|2380|2381|2382|2383|2384|2385|2386|2387|2388|2389|2390|2391|2392|2393|2394|2395|2396|2397|2398|2399|2400|2401|2402|2403|2404|2405|2406|2407|2408|2409|2410|2411|2412|2413|2414|2415|2416|2417|2418|2419|2420|2421|2422|2423|2424|2425|2426|2427|2428|2429|2430|2431|2432|2433|2434|2436|2446|2447|2448|2449|2450|2451|2452|2453|2454|2458|2459|2460|2461|2464|2465|2466|2467|2468|2469|2471|2473|2474|2476|2477|2480|2482|2485|2486|2487|2488|2489|2490|2491|2492|2493|2494|2495|2496|2497|2498|2499|2500|2501|2502|2504|2505|2506|2507|2508|2509|2510|2511|2512|2513|2514|2515|2516|2517|2518|2519|2520|2521|2522|2523|2524|2525|2528|2529|2530|2531|2532|2533|2534|2535|2536|2537|2538|2539|2540|2541|2542|2543|2544|2545|2546|2547|2548|2549|2550|2551|2552|2553|2554|2555|2556|2557|2558|2559|2560|2564|2565|2567|2569|2572|2573|2574|2576|2577|2578|2579|2580|2581|2582|2583|2584|2593|2601|2604|2605|2614|2625|2627|2631|2640|2642|2643|2648|2660|2661|2663|2664|2665|2666|2668|2669|2677|2678|2679|2680|2681|2682|2683|2684|2685|2687|2688|2689|2690|2691|2693|2694|2695|2696|2697|2698|2699|2700|2701|2702|2703|2704|2705|2706|2707|2708|2709|2710|2711|2712|2713|2714|2715|2716|2717|2718|2719|2720|2721|2722|2724|2725|2726|2727|2728|2729|2730|2731|2732|2733|2737|2738|2739|2740|2741|2742|2743|2744|2745|2746|2747|2760|2763|2791|2793|2794|2795|2796|2797|2798|2799|2800|2801|2802|2803|2804|2805|2806|2807|2808|2809|2810|2811|2812|2813|2814|2815|2816|2817|2818|2819|2820|2821|2822|2823|2824|2825|2826|2827|2828|2829|2830|2831|2832|2833|2834|2835|2836|2837|2838|2839|2840|2841|2842|2843|2844|2845|2846|2847|2848|2849|2850|2851|2852|2853|2854|2855|2856|2857|2858|2859|2860|2861|2862|2863|2864|2865|2866|2867|2868|2877|2880|2881|2887|2888|2889|2890|2891|2896|2897|2898|2899|2900|2901|2903|2906|2910|2911|2912|2913|2914|2915|2917|2918|2919|2923|2924|2926|2932|2935|2943|2946|2954|2955|2958|2962|2963|2964|2965|2966|2967|2968|2969|2970|2971|2972|2973|2974|2975|2976|2977|2987|2988|2991|2993|2996|3007|3012|3013|3016|3017|3018|3025|3026|3032|3035|3038|3040|3050|3054|3057|3059|3060|3061|3062|3063|3064|3065|3066|3067|3068|3070|3071|3146|3148|3149|3150|3154|3159|3160|3161|3162|3163|3164|3165|3166|3171|3172|3173|3174|3175|3177|3181|3182|3184|3185|3188|3194|3195|3198|3199|3201|3202|3207|3208|3209|3210|3211|3213|3216|3217|3218|3219|3220|3222|3224|3238|3271|3272|3274|3275|3277|3278|3280|3283|3285|3286|3289|3291|3292|3293|3294|3296|3299|3300|3301|3302|3303|3304|3306|3309|3310|3311|3312|3313|3314|3315|3317|3319|3320|3321|3323|3324|3325|3332|3337|3341|3342|3343|3344|3345|3346|3347|3348|3349|3350|3351|3353|3354|3355|3356|3357|3358|3359|3360|3361|3362|3363|3364|3365|3366|3367|3368|3369|3370|3371|3372|3373|3374|3375|3376|3377|3378|3380|3381|3382|3383|3384|3385|3387|3388|3389|3390|3391|3392|3393|3394|3395|3396|3398|3399|3400|3401|3402|3403|3404|3405|3406|3407|3408|3409|3410|3411|3412|3415|3416|3417|3418|3419|3420|3422|3423|3424|3425|3426|3427|3429|3430|3431|3433|3434|3435|3436|3437|3438|3439|3440|3441|3442|3443|3445|3447|3466|3474|3476|3477|3479|3480|3481|3482|3484|3493|3495|3497|3500|3501|3503|3516|3527|3528|3529|3530|3531|3532|3533|3534|3536|3537|3541|3542|3543|3548|3556|3560|3561|3562|3563|3564|3566|3571|3572|3574|3575|3579|3580|3581|3582|3583|3584|3585|3586|3587|3589|3590|3591|3592|3593|3594|3595|3596|3597|3598|3599|3600|3602|3603|3604|3606|3607|3608|3609|3610|3611|3612|3613|3614|3615|3616|3617|3618|3620|3621|3622|3623|3624|3625|3626|3627|3628|3629|3630|3631|3632|3633|3634|3635|3636|3637|3638|3639|3640|3641|3642|3643|3644|3645|3646|3647|3648|3649|3650|3651|3652|3653|3654|3655|3656|3657|3658|3659|3660|3661|3662|3663|3664|3665|3666|3667|3668|3669|3670|3671|3672|3673|3674|3675|3676|3677|3678|3679|3680|3681|3682|3683|3684|3685|3686|3687|3688|3689|3690|3691|3692|3693|3694|3695|3696|3697|3698|3699|3700|3701|3703|3704|3705|3706|3707|3708|3709|3710|3711|3712|3713|3714|3715|3716|3717|3718|3719|3720|3721|3722|3724|3725|3726|3728|3729|3730|3731|3732|3733|3734|3735|3736|3737|3738|3739|3740|3741|3742|3743|3744|3745|3746|3750|3755|3756|3757|3758|3759|3760|3761|3762|3763|3764|3765|3766|3767|3768|3769|3770|3771|3772|3773|3774|3775|3776|3777|3778|3779|3780|3781|3782|3783|3784|3785|3786|3787|3788|3789|3790|3791|3792|3793|3794|3795|3796|3797|3798|3799|3800|3801|3802|3803|3804|3805|3806|3807|3808|3809|3810|3811|3812|3813|3814|3815|3816|3817|3818|3819|3820|3821|3822|3823|3824|3825|3826|3827|3829|3830|3831|3832|3833|3834|3835|3836|3837|3838|3839|3840|3841|3842|3843|3844|3845|3846|3847|3848|3849|3850|3851|3852|3853|3854|3855|3856|3857|3858|3859|3860|3861|3862|3863|3864|3865|3866|3867|3868|3869|3870|3871|3872|3873|3874|3875|3876|3877|3878|3879|3880|3881|3882|3883|3884|3885|3886|3887|3888|3889|3890|3891|3892|3893|3894|3895|3896|3897|3898|3899|3900|3901|3902|3903|3904|3905|3906|3907|3909|3910|3911|3912|3913|3914|3918|3920|3926|3927|3928|3929|3930|3931|3932|3933|3934|3935|3936|3937|3938|3939|3940|3941|3942|3943|3944|3945|3946|3948|3949|3950|3951|3952|3953|3954|3955|3956|3957|3958|3959|3960|3961|3962|3963|3964|3965|3966|3967|3968|3969|3970|3971|3972|3973|3974|3975|3976|3977|3978|3979|3980|3981|3982|3983|3984|3985|3986|3987|3988|3989|3990|3991|3992|3993|3994|3995|3996|3997|3998|3999|4000|4001|4002|4003|4004|4005|4006|4007|4008|4009|4010|4011|4012|4013|4014|4015|4016|4017|4018|4019|4020|4021|4022|4023|4024|4025|4026|4027|4028|4029|4030|4031|4032|4033|4034|4035|4036|4037|4038|4039|4040|4041|4042|4043|4044|4045|4046|4047|4048|4049|4050|4051|4052|4054|4057|4059|4060|4061|4063|4065|4066|4068|4070|4071|4073|4077|4079|4080|4083|4084|4086|4088|4089|4091|4092|4096|4098|4100|4101|4102|4103|4137|4138|4139|4140|4141|4142|4143|4144|4145|4147|4148|4149|4150|4151|4152|4153|4154|4155|4156|4157|4158|4159|4160|4161|4162|4163|4164|4165|4166|4167|4168|4169|4170|4171|4172|4173|4174|4175|4176|4177|4178|4179|4180|4181|4182|4183|4184|4185|4186|4187|4188|4189|4190|4192|4193|4197|4198|4199|4200|4201|4203|4204|4209|4212|4213|4216|4217|4218|4222|4225|4226|4227|4228|4229|4231|4232|4233|4234|4235|4236|4237|4238|4239|4240|4244|4245|4246|4252|4255|4260|4266|4267|4268|4269|4270|4271|4272|4273|4274|4275|4276|4277|4278|4279|4281|4282|4283|4284|4285|4286|4290|4295|4297|4298|4299|4300|4301|4302|4303|4304|4306|4307|4308|4310|4311|4313|4315|4318|4323|4324|4326|4328|4329|4332|4333|4336|4337|4338|4340|4341|4342|4343|4344|4345|4346|4348|4349|4353|4355|4356|4357|4358|4359|4362|4364|4365|4366|4367|4368|4373|4374|4375|4378|4380|4382|4383|4392|4396|4397|4398|4406|4407|4408|4409|4410|4414|4415|4419|4420|4421|4422|4423|4430|4433|4436|4437|4438|4440|4441|4442|4443|4445|4446|4447|4448|4449|4450|4452|4453|4454|4455|4456|4458|4459|4460|4461|4462|4463|4464|4465|4466|4467|4468|4469|4471|4472|4473|4474|4475|4476|4477|4478|4479|4480|4481|4482|4483|4484|4485|4487|4488|4489|4490|4491|4492|4493|4494|4495|4496|4497|4498|4499|4500|4501|4502|4503|4504|4505|4506|4507|4508|4509|4510|4511|4512|4513|4514|4515|4516|4517|4518|4519|4520|4521|4522|4523|4525|4526|4527|4528|4529|4531|4532|4533|4535|4536|4537|4538|4539|4540|4541|4542|4543|4544|4545|4546|4547|4548|4549|4551|4552|4553|4554|4555|4556|4557|4558|4559|4560|4564|4565|4568|4569|4570|4571|4572|4573|4574|4575|4576|4577|4578|4579|4580|4581|4582|4584|4585|4586|4587|4588|4590|4592|4593|4594|4595|4596|4597|4599|4600|4601|4602|4603|4604|4605|4606|4607|4608|4609|4610|4611|4612|4613|4614|4615|4616|4617|4618|4619|4620|4621|4622|4623|4624|4625|4626|4627|4628|4629|4630|4631|4632|4633|4634|4635|4636|4637|4638|4639|4640|4641|4642|4643|4644|4645|4646|4647|4648|4649|4650|4651|4652|4653|4654|4655|4656|4657|4658|4661|4662|4663|4664|4665|4666|4667|4669|4670|4671|4672|4673|4674|4675|4676|4677|4678|4679|4681|4682|4683|4686|4687|4690|4693|4694|4695|4696|4698|4699|4700|4701|4702|4703|4704|4705|4706|4707|4708|4709|4710|4711|4712|4713|4714|4715|4716|4717|4718|4719|4720|4721|4722|4723|4724|4725|4726|4727|4728|4729|4730|4731|4732|4733|4734|4735|4736|4737|4738|4739|4740|4741|4742|4743|4744|4745|4746|4747|4748|4749|4750|4751|4752|4753|4754|4755|4756|4757|4758|4759|4760|4761|4762|4763|4764|4765|4766|4767|4768|4769|4770|4771|4772|4773|4774|4775|4776|4777|4778|4779|4780|4781|4782|4783|4784|4785|4786|4787|4788|4789|4790|4791|4792|4793|4794|4795|4796|4797|4798|4799|4800|4801|4802|4803|4804|4805|4806|4807|4808|4809|4810|4811|4812|4813|4814|4815|4816|4817|4818|4819|4820|4821|4822|4823|4824|4825|4826|4827|4828|4829|4830|4831|4832|4833|4834|4835|4836|4837|4838|4839|4840|4841|4842|4843|4844|4845|4846|4847|4848|4849|4850|4851|4852|4853|4854|4855|4856|4857|4858|4859|4860|4861|4862|4863|4864|4865|4866|4867|4868|4869|4870|4871|4872|4873|4874|4875|4876|4877|4878|4879|4880|4881|4882|4883|4884|4885|4886|4887|4888|4889|4890|4891|4892|4893|4894|4895|4896|4897|4898|4899|4900|4901|4902|4903|4904|4905|4906|4907|4908|4909|4910|4916|4917|4918|4919|4920|4921|4922|4923|4924|4925|4926|4927|4928|4929|4930|4931|4932|4933|4934|4935|4936|4937|4938|4939|4940|4941|4942|4943|4944|4945|4946|4948|4949|4950|4952|4953|4954|4955|4956|4957|4958|4959|4960|4961|4962|4963|4964|4965|4966|4967|4968|4969|4970|4971|4972|4974|4976|4977|4978|4979|4980|4981|4982|4983|4984|4985|4986|4988|4989|4992|4993|4994|4995|4996|4997|4998|4999|5000|5001|5002|5003|5004|5005|5006|5007|5008|5010|5011|5012|5014|5015|5016|5017|5018|5019|5020|5026|5027|5028|5029|5030|5031|5032|5033|5034|5035|5036|5037|5038|5039|5040|5041|5042|5043|5044|5045|5046|5047|5048|5049|5050|5051|5052|5053|5054|5055|5056|5057|5058|5059|5060|5061|5062|5063|5064|5065|5067|5068|5069|5070|5071|5072|5073|5074|5076|5077|5078|5079|5080|5081|5082|5083|5084|5085|5086|5087|5088|5089|5090|5091|5092|5093|5094|5095|5096|5097|5098|5099|5100|5101|5102|5103|5104|5105|5106|5107|5108|5109|5110|5111|5112|5113|5114|5115|5116|5117|5118|5119|5120|5121|5122|5123|5124|5125|5126|5127|5128|5130|5131|5132|5133|5135|5139|5140|5141|5142|5143|5144|5145|5146|5147|5148|5149|5150|5151|5152|5153|5154|5155|5156|5157|5159|5160|5161|5162|5163|5164|5165|5166|5167|5168|5169|5170|5171|5172|5173|5174|5175|5176|5177|5178|5179|5180|5181|5182|5183|5184|5185|5186|5187|5188|5189|5190|5191|5192|5193|5194|5195|5196|5197|5198|5199|5200|5201|5202|5203|5204|5205|5206|5207|5208|5209|5210|5211|5212|5213|5214|5215|5216|5217|5218|5219|5220|5221|5222|5223|5224|5225|5226|5227|5228|5229|5230|5231|5232|5233|5234|5235|5236|5237|5238|5239|5240|5241|5242|5243|5254|5255|5256|5257|5258|5259|5260|5261|5262|5263|5265|5266|5267|5268|5269|5272|5273|5274|5275|5276|5277|5278|5279|5280|5281|5282|5284|5285|5286|5287|5288|5289|5290|5291|5292|5293|5294|5295|5296|5297|5298|5299|5300|5301|5302|5304|5305|5306|5308|5309|5310|5311|5312|5313|5314|5315|5316|5317|5318|5319|5320|5321|5322|5323|5324|5325|5326|5327|5328|5329|5330|5331|5332|5333|5334|5335|5336|5337|5338|5339|5340|5341|5342|5343|5344|5345|5346|5347|5348|5349|5350|5351|5352|5353|5354|5355|5356|5357|5358|5359|5360|5362|5363|5364|5369|5370|5372|5539|5541|5542|5547|5551|5552|5554|5556|5559|5565|5595|5628|5684|5683|5669|5681|5686|5693|5705|5706|5707|5719|5714|5625|5685|5746|5744|5731|5747|5751|5796|5700|5728|5743|5808|5879|5948|5884|5988|5967|5987|6001|6046|6070|6056|6071|6129|5877|5876|5874|5958|6185|6177|6191|6192|6200|6201|6126|6217|6218|6220|6117|6195|6232|6239|6102|6264|6263|6262|6265|6289|6287|6336|6304|6303|6333|6328|6343|6342|6341|6340|6339|6338|6334|6393|6363|6321|6327|6326|6325|6324|6365|6364|6415|6435|6440|6442|6439|6438|6445|6450|6053|6387|6425|6452|6444|6446|6447|6448|6457|5748|5789|5790|5798|5800|5799|5823|6266|6297|6299|6458|6443|6449|6437|6436|6314|6454|6451|6441|6453|6302|6313|6892|6891|6845|6788|6715|6612|6602|6626|6456|6455|6896|6306|6429|6916|6963|11714|11716|11748|11754|11740|11742|11708|11712|11722|12856|6949|12877|12897|12974|12995|13023|13021|13060|13059|13051|13041|12947|12945|12935|12939|12942|6466|6465|12957|12948|12941|12940|12938|12959|12958|12950|12951|13098|12936|13093|13102|13135|13146|13147|13160|13176|13183|13180|13230|13191|13205|13170|13193|13171|13177|13189|13201|13206|13232|13165|13172|13178|13196|13152|13153|13212|13203|13224|13226|13233|13161|13164|13210|13215|13219|13151|13158|13181|13182|13195|13200|13222|13234|13159|13169|13179|13223|13174|13175|13231|13149|13162|13204|13148|13214|13167|13168|13185|13197|13217|13150|13154|13156|13188|13190|13216|13218|13220|13227|13157|13166|13186|13173|13192|13207|13187|13213|13229|13211|13163|13198|13155|13221|13209|13225|13228|13184|13202|13208|13730|13881|13854|13199|13888|13823|13880|13875|13868|13852|13848|13815|13814|13798|13799|13873|13833|13821|13797|13772|13274|13257|13081|13985|13984|13983|13982|13981|13980|13979|13978|13977|13976|13975|13974|13973|13966|13972|13971|13970|13969|13968|13967|13959|13960|13962|13961|13964|13958|13956|13963|13965|13957|13955|13954|13953|13952|13945|13946|13947|13948|13949|13950|13951|13944|13943|13942|13940|13941|13938|13939|13937|13936|13935|13934|13932|13931|13933|13930|13929|13928|13927|13925|13926|13924|13923|13922|13921|13986|13070|13992|13993|14006|14009|12924|14059|14058|14056|14060|14055|14057|14064|14065|14066|14067|14068|14069|14070|14071|14072|14073|14074|14075|14076|14077|14078|14079|14080|14081|14082|14083|14084|14085|14086|14087|14088|14090|14091|14092|14093|14094|14095|14096|14097|14098|14099|14100|14101|14102|14063|14062|14103|14104|14106|14107|14108|14110|14111|14112|14113|14114|14115|14116|14118|14119|14121|14120|14122|14123|14124|14125|14126|14127|14128|14129|14130|14131|14132|14134|14133|14135|14136|14137|14138|14140|14141|14142|14143|14145|14146|14147|14148|14149|14150|14151|14152|14153|14154|14155|14156|14157|14159|14160|14161|14162|14163|14164|14189|14203|14198|14200|14201|14199|14202|14231|14232|14262|13019|14282|6317|13020|14314|14340|14327|14342|12922|13275|14566|14565|14564|14563|14562|14561|12920|14569|14611|14588|14589|14608|14672|14674|14675|14673|14676|14681|14670|14671|14696|14700|12954|12857|12953|12932|12930|12927|14721|14722|14723|14718|14710|14711|6335|16000|16001|16004|16124|16172|16242|16271|16073|16070|16355|16354|16353|16352|16395|16397|16404|15990|16422|16438|16441|16442|16484|16485|16479|16478|16486|16587|16596|16607|16608|16613|16615|16666|16668|16679|16692|16691|16712|16714|16709|16711|16776|16826|6331|6434|16888|6371|6378|6380|14553|15976|16958|16961|16959|16965|16998|6318|6312|17188|17189|17190|17193|17261|17361|17389|6464|17398|17410|17411|17135|17391|17457|17474|17479|17475|17478|17477|17480|17476|17481|17369|17221|17364|14542|14048|15948|17580|17582|17587|17577|17583|14551|17609|17608|17607|17606|17610|17618|17671|17668|17667|17664|17662|17661|17657|17701|17672|17739|17611|17740|17778|17774|17599|12859|17603|17601|17371|17484|6927|17859|17917|17909|17890|17827|17895|17957|17962|17958|17960|17961|17959|17966|17965|17964|17963|17967|17968|17969|17970|17974|17972|17973|17971|17975|17976|17978|17977|17998|17997|17995|17996|17991|17992|17993|17994|17990|17989|17988|17987|17983|17984|17985|17986|17982|17981|17980|17979|18014|17916|17910|17907|17897|17854|17841|17904|17883|17413|18100|18104|18080|18106|18102|18142|18016|17861|17866|17860|17876|17843|17840|17842|17915|18198|18224|17882|17877|17899|18256|18250|17855|17845|17908|18017|17902|17914|17896|17912|17903|17913|17898|17893|17849|17835|14049|18276|18275|18262|18261|14047|18307|18370|18369|18368|18393|18400|18399|18404|17911|18423|18422|15994|14050|18428|18472|17894|18499|18498|18496|18497|18494|18495|18493|18502|18500|18504|18505|18507|18520|18538|17598|14751|18088|18087|18086|18085|18084|18083|18082|18081|6463|6462|6461|6459|6931|6930|6929|17602|17600|17830|15957|17597|18556|18551|18549|18546|18552|14021|18611|18609|18610|18614|18613|18647|18645|18816|18823|18821|18830|18827|18795|18798|18818|18826|18930|18900|18898|18895|18892|18891|18880|18889|18890|18888|18881|18882|18883|18886|18884|18879|18878|18877|18872|18858|18865|18851|18864|18871|18856|18862|18869|18868|18876|18874|18875|18866|18873|18867|18860|18859|18852|18853|18854|18861|18855|18850|18849|18848|18846|18845|18847|18844|18842|18843|18841|18829|18819|18779|18773|18772|17919|19068|19051|19049|19032|19058|19050|19031|19135|19132|19128|19133|19136|19175|19241|19231|19225|19219|19208|19211|19212|19205|19196|19180|19182|19198|19216|19326|19424|19515|19575|19579|19580|19612|19702|19700|19701|19630|19756|19680|19678|19725|19699|19683|19685|18719|18703|19460|19910|20079|20491|20053|20055|20054|20056|19697|20540|20539|20538|20537|20543|20546|20547|20548|20076|20774|20802|20807|20806|20841|20875|20947|20946|20981|20982|20983|20984|20986|20988|20990|20991|20992|20993|20987|20952|20803|20994|21036|21035|21034|21041|21047|19382|21077|21010|21078|19387|21103|21139|21118|21117|21136|21141|21137|21138|', 'd41d8cd98f00b204e9800998ecf8427e', '', 1300262664890),
(27, 'пожатое', 'Изображения в отличном от оригинала формате, разрешении или степени сжатия.', 'Изображения в отличном от оригинала формате, разрешении или степени сжатия.', 1704, '|70986|69937|69928|14518|26580|21038|21039|21040|21045|21048|21054|21055|21050|21059|21100|21101|21105|21140|20769|20876|20877|20882|20945|20948|20953|21011|21037|20078|20542|20550|20549|20554|20555|20551|20553|20561|20586|20582|20685|20801|20773|20772|19754|19672|19346|19338|19375|19774|19794|19808|19816|19987|19988|20015|20034|20502|19270|19242|19402|19401|19398|19397|19462|19461|19466|19463|19479|19518|19517|19520|19532|19534|19536|19577|19576|19034|19245|19243|19223|19228|19213|19193|19181|19176|18987|18986|18840|19026|19074|19053|19045|19043|19044|19038|18839|18770|18775|18776|18771|18777|18785|18796|18799|18806|18975|18974|18972|18973|18893|18718|18722|18721|18723|18724|18720|18640|18750|18758|6911|18646|18644|18643|18641|18638|18639|18637|18812|15983|18555|18553|18550|18548|18554|18545|18531|18456|18455|18501|18508|18506|18540|18542|18541|18280|18329|18330|18305|18306|18402|18403|18389|18392|18394|18396|18397|18398|18401|17271|18427|18205|18244|18249|18252|17418|18075|18061|18101|18103|18105|18143|18089|17857|17838|18197|18200|18206|17889|17885|17844|17846|17847|17851|17852|17853|17858|17863|17864|17872|17871|17870|17867|17837|17834|17878|17886|17829|17879|17880|17881|17887|18060|17875|17869|17856|17922|17926|17929|18013|17951|17947|17948|17659|17658|17663|17737|17776|13082|6968|17444|17419|17420|17784|17825|17892|17888|14538|14545|14550|17527|17482|15991|15997|17670|17669|17666|17665|17660|17362|17483|14558|14450|15992|14548|14443|15984|17138|17356|17132|17254|17428|17137|17266|14457|14445|14456|14464|17191|17192|17194|17136|17139|17262|17308|17325|17336|17355|17390|17392|17397|17401|17412|17140|16967|16972|16973|16974|16975|16976|16977|16960|17130|17131|17133|17160|17161|17165|17166|17163|17168|17164|17167|17162|16839|16841|16840|16842|16843|16844|16845|16846|16835|16887|16908|6309|14439|13087|14330|14546|14435|16954|16955|16962|16963|16680|16681|16682|16683|16684|16685|16686|16687|16695|16698|16697|16724|16729|14442|16827|16828|16829|16830|16831|16836|16834|16833|16832|16837|16838|16489|16488|16487|16498|16590|16594|16595|16606|16609|16617|16665|16676|16689|16693|16694|16415|16416|16417|16418|16419|16420|16421|16423|16424|16425|16426|16427|16428|16429|16430|16431|16432|16433|16434|16435|16436|16437|16439|16440|6427|16067|16040|16069|16048|16066|16068|16071|16038|16039|16057|16036|16035|16072|16075|16398|16400|16399|16406|16410|16411|16414|16252|16253|16254|16255|16256|16257|16258|16259|16260|16261|16262|16263|16264|16265|16266|16267|16268|16269|16270|16272|16273|16274|16275|16077|16042|16276|16277|16078|14749|14752|14753|14754|15986|16008|16065|16074|16076|16140|16141|16142|16143|16144|16148|16149|16155|16156|16157|16125|16127|16251|14714|14725|14724|14726|14738|14739|14740|14741|14742|14743|14747|14715|14716|14717|14719|14720|14713|14712|14748|14592|14601|14602|14604|14605|14606|14613|14595|14599|14620|14624|14625|14549|14690|14691|14701|14699|14709|14441|14444|14447|14455|14547|14544|14617|14616|14612|14607|14593|14591|14597|14598|14594|14600|14590|14317|14318|14497|14496|14495|14494|14493|14492|14491|14490|14489|14488|14486|14485|14484|14483|14481|14480|14479|14482|14487|14454|14460|14458|14459|14501|14440|14205|14253|14254|14256|14257|14258|14263|14270|14271|14272|14273|14274|6965|14329|14319|14331|14190|14187|14188|14191|14192|14193|14109|14004|14005|14007|14008|14010|14011|14019|13018|13988|13991|13994|13995|13997|13998|13999|14000|14001|14002|14003|13791|13790|13788|13776|13086|13817|13806|13780|13773|13885|13862|13856|13847|13840|13827|13825|13810|13800|13273|13270|13267|13263|13265|13716|13719|13720|13722|13725|13728|13733|13734|13735|13898|13895|13892|13889|13879|13871|13097|13094|13103|13107|13024|13022|13005|13062|13058|13049|13057|13046|13015|11732|11759|11758|11746|11720|11723|11736|11710|11715|11729|11730|11721|11728|12892|12977|12983|12999|13000|12994|6910|6908|6919|6920|6925|6921|6924|6922|6912|6923|6918|11757|11738|11751|11735|5774|6323|6358|6357|6329|6360|6366|6362|6091|6242|6261|6271|6270|6243|6272|6274|6296|6322|6344|6346|6359|6246|6244|6255|6254|6247|6259|6257|6090|6112|6084|6085|6089|6093|6094|6098|6099|6105|6106|6110|6241|6250|6248|6249|6253|6256|6251|6252|6086|6135|6119|6120|6087|6101|6219|6066|6214|6128|6209|6206|6104|6097|6103|6245|6020|6044|6062|6069|6058|6055|6068|6057|6054|6067|5903|5945|6064|6076|6075|6092|6108|6116|6100|6202|6210|6088|6095|6096|5968|5990|5992|5993|6050|6052|6032|6047|6051|6040|6042|6041|6018|6019|6028|6017|6031|6033|6022|6043|6025|6037|6036|6035|6034|6029|6026|5847|5851|5942|5941|5889|5883|5890|5997|5989|5972|5977|5962|5971|5970|5973|5959|5961|5991|5965|5963|5969|5976|5964|5999|5966|5770|5776|5786|5818|5857|5866|5865|5837|5834|5836|5824|5819|5850|5832|5849|5826|5839|5838|5845|5822|5784|5773|5833|5853|5852|5821|5820|5846|5825|5792|5791|5787|5780|5777|5769|5768|5785|5779|5694|5788|5695|5721|5732|5733|5739|5759|5761|5765|5775|5771|5783|5772|5795|5781|5644|5667|5690|5689|5702|5711|5712|5713|5709|5723|5724|5730|5729|5361|5365|5367|5536|5560|5596|5303|5307|5270|5271|5283|5264|5138|5158|5129|5075|5066|5013|4990|4947|4951|4697|4668|4684|4685|4688|4689|4691|4692|4660|4583|4589|4591|4598|4561|4562|4563|4566|4567|4524|4530|4534|4486|4457|4470|4412|4413|4425|4428|4444|4451|4360|4361|4363|4381|4384|4390|4404|4330|4331|4334|4335|4339|4347|4350|4351|4352|4354|4305|4309|4312|4314|4316|4317|4319|4320|4321|4322|4325|4327|4280|4287|4288|4289|4291|4292|4293|4294|4296|4241|4242|4243|4247|4248|4249|4250|4251|4253|4254|4256|4258|4259|4261|4262|4263|4264|4265|4208|4210|4211|4214|4215|4219|4220|4221|4223|4224|4230|4191|4194|4195|4196|4202|4205|4206|4207|4087|4090|4093|4094|4095|4097|4099|4146|4053|4055|4056|4058|4062|4064|4067|4072|4074|4075|4076|4078|4081|4082|3915|3916|3925|3749|3751|3723|3727|3619|3588|3601|3605|3555|3557|3558|3559|3565|3567|3568|3569|3570|3573|3515|3535|3538|3539|3540|3544|3545|3546|3547|3549|3550|3551|3552|3553|3554|3485|3486|3487|3488|3489|3490|3491|3492|3494|3496|3498|3499|3502|3504|3505|3506|3507|3508|3509|3510|3511|3512|3513|3514|3432|3448|3451|3460|3463|3475|3478|3483|3413|3414|3421|3428|3379|3386|3397|3352|3316|3318|3322|3326|3327|3328|3329|3330|3331|3333|3334|3335|3336|3338|3339|3340|3282|3284|3287|3288|3290|3295|3297|3298|3305|3307|3308|3212|3214|3215|3221|3236|3269|3273|3276|3279|3281|3178|3179|3180|3183|3186|3187|3189|3190|3191|3192|3193|3196|3197|3200|3203|3204|3205|3206|3069|3167|3168|3169|3170|3176|3036|3037|3039|3041|3042|3043|3044|3045|3046|3047|3048|3049|3051|3052|3053|3055|3056|3058|3006|3008|3009|3010|3011|3014|3015|3019|3020|3021|3022|3023|3024|3027|3028|3029|3030|3031|3033|3034|2978|2979|2980|2981|2982|2983|2984|2985|2986|2989|2990|2992|2994|2995|2997|2998|2999|3000|3001|3002|3003|3004|3005|2944|2945|2947|2948|2949|2950|2951|2952|2953|2959|2960|2961|2920|2921|2922|2925|2927|2928|2929|2930|2931|2933|2934|2936|2937|2938|2939|2940|2941|2942|2878|2892|2893|2894|2895|2902|2904|2905|2907|2908|2909|2874|2771|2772|2775|2777|2779|2780|2781|2782|2786|2787|2789|2792|2750|2751|2761|2770|2692|2653|2658|2676|2686|2585|2602|2526|2527|2503|2470|2472|2475|2478|2479|2481|2483|2484|2435|2455|2456|2457|2462|2463|2336|2337|2338|2339|2343|2345|2346|2348|2350|2351|2352|2306|2307|2309|2311|2312|2313|2318|2319|2327|2328|2329|2273|2274|2275|2277|2278|2279|2280|2281|2282|2283|2286|2287|2289|2290|2297|2250|2253|2259|2263|2265|2268|2270|2271|2215|2216|2217|2218|2219|2221|2223|2229|2231|2233|2234|2236|2207|2208|2209|2121|2127|2141|2115|2116|2118|2058|2059|2060|2062|2063|2087|2034|2050|2051|2053|2054|2014|1958|1966|1823|1824|1825|1826|1831|1832|1876|1890|1861|1862|1864|1865|1866|1867|1869|1872|1873|1874|1810|1815|1818|1773|1776|1833|1835|1841|1842|1847|1859|1860|1709|1711|1726|1739|1741|1742|1755|1761|1765|1767|1660|1671|1706|1606|1629|1570|1571|1551|1526|1450|1456|1459|1460|1461|1466|1432|1434|1435|1439|1440|1444|1388|1389|1405|1406|1407|1408|1409|1410|1356|1358|1363|1375|1378|1327|1328|1329|1115|1129|1107|4|7|1330|1331|1332|1334|1336|1354|1355|1323|1225|1103|1104|1108|1110|1118|1120|1121|1124|1125|1126|1127|1130|1131|1132|1082|1085|1086|1087|990|996|1068|1040|1041|1042|1043|1044|1045|1046|1049|1050|1051|1052|1053|1054|1055|1056|1057|1016|1017|1020|1028|1029|1030|1031|1032|1033|1034|1035|1036|1038|1039|907|910|916|917|921|924|1005|1008|848|846|883|880|878|872|871|894|896|898|834|835|842|845|869|867|866|865|864|863|862|860|858|852|851|850|849|720|765|786|576|575|574|573|572|571|570|568|566|564|563|562|561|560|559|558|557|556|595|594|600|632|674|711|540|539|538|523|584|583|581|580|579|578|507|522|517|515|510|553|547|471|469|468|467|488|485|483|482|481|480|479|477|476|475|503|497|489|424|423|422|421|420|419|418|417|414|413|440|438|434|431|457|456|448|445|472|386|385|384|377|376|374|373|372|404|402|401|398|397|428|426|341|340|338|337|336|335|344|353|351|349|347|345|358|357|356|370|368|366|364|363|361|388|387|333|332|330|328|325|324|334|295|298|296|241|111|110|109|108|107|106|105|104|103|102|101|100|82|86|120|118|117|116|115|114|113|112|16|18|56|15977|', 'd41d8cd98f00b204e9800998ecf8427e', '', 1300478884590);

-- --------------------------------------------------------

--
-- Структура таблицы `art_rating`
--

CREATE TABLE IF NOT EXISTS `art_rating` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `art_id` int(10) unsigned NOT NULL,
  `cookie` varchar(32) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cookie` (`art_id`,`cookie`),
  UNIQUE KEY `unique_ip` (`art_id`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3492 ;

--
-- Дамп данных таблицы `art_rating`
--

INSERT INTO `art_rating` (`id`, `art_id`, `cookie`, `ip`, `rating`) VALUES
(1046, 86, '1e013fd36933d526f8012da15a40a184', 1519785190, 1),
(1211, 14, '8fda0b2f82f93faa25a23f2914e3c1dc', 1598981001, 1),
(3491, 14, 'e9442fb83ba90f25955c012ad5683c37', 1572456059, 1);

--
-- Триггеры `art_rating`
--
DROP TRIGGER IF EXISTS `update_art`;
DELIMITER //
CREATE TRIGGER `update_art` AFTER INSERT ON `art_rating`
 FOR EACH ROW BEGIN
    UPDATE `art` SET `art`.`rating` = (select SUM(`rating`) from `art_rating` where `art_id` = NEW.`art_id`) WHERE `id` = NEW.`art_id`;
  END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `art_similar`
--

CREATE TABLE IF NOT EXISTS `art_similar` (
  `id` int(11) NOT NULL,
  `vector` varchar(2048) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `similar` varchar(4096) NOT NULL DEFAULT '|',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `art_similar`
--


-- --------------------------------------------------------

--
-- Структура таблицы `art_translation`
--

CREATE TABLE IF NOT EXISTS `art_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `art_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `art_id` (`art_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1180 ;

--
-- Дамп данных таблицы `art_translation`
--


-- --------------------------------------------------------

--
-- Структура таблицы `art_variation`
--

CREATE TABLE IF NOT EXISTS `art_variation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `art_id` int(10) unsigned NOT NULL,
  `md5` varchar(32) NOT NULL,
  `thumb` varchar(32) NOT NULL,
  `extension` varchar(4) NOT NULL,
  `resized` varchar(64) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `animated` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`art_id`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `art_variation`
--


-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=230 ;

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`id`, `alias`, `name`) VALUES
(1, 'alan', 'Алан'),
(10, 'Mervish', 'Mervish'),
(14, 'nameless', 'Безымянный'),
(24, 'raincat', 'RainCat'),
(35, 'ofen', 'Ofen'),
(45, 'w8m', 'w8m'),
(53, 'lbiss', 'LbISS'),
(54, 'pinkerton', 'Пинкертон'),
(65, '_', '&gt;_&lt;'),
(81, 'anonimus', 'Анонимус');

-- --------------------------------------------------------

--
-- Структура таблицы `board`
--

CREATE TABLE IF NOT EXISTS `board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('thread','post','old','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `thread` int(11) NOT NULL,
  `updated` bigint(16) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `trip` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `cookie` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `selector` (`type`,`sortdate`),
  KEY `child_posts` (`thread`,`type`,`sortdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `board`
--


-- --------------------------------------------------------

--
-- Структура таблицы `board_attachment`
--

CREATE TABLE IF NOT EXISTS `board_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `type` enum('image','video','flash','random') NOT NULL,
  `data` text NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`post_id`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `board_attachment`
--


-- --------------------------------------------------------

--
-- Структура таблицы `board_category`
--

CREATE TABLE IF NOT EXISTS `board_category` (
  `thread_id` int(10) unsigned NOT NULL,
  `category_id` smallint(5) unsigned NOT NULL,
  `actual` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`thread_id`,`category_id`),
  KEY `selector` (`thread_id`,`actual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `board_category`
--


-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=168 ;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `alias`, `name`, `area`) VALUES
(2, 'art', 'Арт', '|post|'),
(3, 'video', 'Видео', '|post|'),
(4, 'photo', 'Фото', '|post|art|'),
(1, 'none', 'Прочее', '|post|video|art|'),
(5, 'games', 'Игры', '|post|video|'),
(6, 'literature', 'Литература', '|post|'),
(7, 'manga', 'Манга', '|post|art|'),
(8, 'music', 'Музыка', '|post|video|'),
(9, 'soft', 'Программы', '|post|'),
(11, 'nsfw', 'Для взрослых', '|post|video|art|'),
(10, 'game_cg', 'Из игры', '|art|'),
(13, '3d', '3D графика', '|video|art|'),
(14, 'b', 'Общее', '|board|'),
(19, 'to', 'Тохо', '|board|'),
(20, 'sto', 'Склад', '|board|'),
(17, 'gm', 'Игры', '|board|'),
(18, 'mu', 'Музыка', '|board|'),
(15, 'd', 'Вопросы по сайту', '|board|'),
(21, 'r', 'Заказы', '|board|'),
(22, 'fl', 'Flash', '|board|'),
(16, 'a', 'Аниме и манга', '|board|'),
(167, 'dr', 'Рисование', '|board|');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rootparent` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `place` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `cookie` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `edit_date` varchar(256) CHARACTER SET utf8 NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `comment`
--


-- --------------------------------------------------------

--
-- Структура таблицы `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `function` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `period` varchar(10) CHARACTER SET utf8 NOT NULL,
  `last_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `function` (`function`),
  KEY `last_time` (`last_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `cron`
--

INSERT INTO `cron` (`id`, `function`, `period`, `last_time`) VALUES
(1, 'gouf_check', '1m', '2011-09-16 03:58:51'),
(2, 'gouf_refresh_links', '1d', '2011-09-16 14:41:53'),
(3, 'clean_tags', '1d', '2011-09-16 14:41:53'),
(4, 'send_mails', '1m', '2011-09-16 03:58:51'),
(5, 'close_orders', '1d', '2011-09-16 14:41:53'),
(6, 'clean_settings', '1h', '2011-09-16 04:47:49'),
(7, 'add_to_search', '1h', '2011-09-16 04:37:55'),
(8, 'update_search', '1m', '2011-09-16 03:58:53'),
(9, 'check_dropout_search', '10d', '2011-09-23 19:18:22'),
(10, 'search_balance_weights', '10d', '2011-09-23 19:18:24'),
(11, 'resize_arts', '1d', '2011-09-16 19:19:58'),
(12, 'track_similar_pictures', '1h', '2011-09-16 04:07:52'),
(13, 'get_logs', '2h', '2011-09-16 05:41:51'),
(14, 'check_wiki_tags', '1h', '2011-09-16 04:07:52'),
(15, 'delete_unneeded_variants', '1d', '2011-09-16 19:21:52'),
(16, 'create_pack_archive', '5m', '2011-09-16 03:58:52'),
(17, 'process_pack', '2m', '2011-09-16 03:59:54');

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Структура таблицы `head_menu`
--

CREATE TABLE IF NOT EXISTS `head_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Дамп данных таблицы `head_menu`
--

INSERT INTO `head_menu` (`id`, `name`, `url`, `parent`, `order`) VALUES
(2, 'Записи', '/post/', 0, 2),
(3, 'Видео', '/video/', 0, 3),
(4, 'На главную', '/', 0, 1),
(5, 'Арт', '/art/', 0, 4),
(6, 'Борда', '/board/', 0, 5),
(7, 'Основной раздел', '/post/', 2, 6),
(8, 'Мастерская', '/post/workshop/', 2, 7),
(9, 'Барахолка', '/post/flea_market/', 2, 8),
(10, 'Обновления', '/post/updates/', 2, 9),
(11, 'Битые ссылки', '/gouf/', 2, 10),
(12, 'Основной раздел', '/video/', 3, 11),
(13, 'Премодерация', '/video/workshop/', 3, 12),
(14, 'Барахолка', '/video/flea_market/', 3, 13),
(15, 'Основной раздел', '/art/', 5, 14),
(16, 'Премодерация', '/art/workshop/', 5, 15),
(17, 'Барахолка', '/art/flea_market/', 5, 16),
(18, 'Группы', '/art/pool/', 5, 17),
(19, 'CG паки', '/art/cg_packs/', 5, 18),
(20, 'Спрайты', '/art/sprites/', 5, 19),
(21, 'Вики сайта', 'http://wiki.4otaku.ru/', 29, 20),
(22, 'Рисовать онлайн', 'http://draw.4otaku.ru/', 29, 21),
(23, 'Кикаки', 'http://raincat.4otaku.ru/', 4, 22),
(24, 'Dreams of Dead', 'http://dod.4otaku.ru/', 4, 23),
(25, 'YukarinSubs', 'http://yukarinsubs.4otaku.ru/', 4, 24),
(26, 'ЧТТО', 'http://comics.4otaku.ru/', 4, 25),
(27, 'Randomness', 'http://alice.4otaku.ru/', 4, 26),
(28, 'VocaDome', 'http://voca.4otaku.ru/', 4, 27),
(29, 'Прочее', '#', 0, 35),
(30, 'Архив', '/archive/', 29, 29),
(31, 'Логи', '/logs/', 0, 28),
(32, 'Основной конференции', '/logs/', 31, 31),
(33, 'Конференции Hisouten', 'http://logs.4otaku.ru/hisouten', 31, 32),
(34, 'Конференции DoTS', 'http://logs.4otaku.ru/dots', 31, 33),
(35, 'Новости', '/news/', 29, 18),
(36, 'Лента комментариев', '/comments/', 29, 19),
(37, 'Заказы', '/order/', 0, 27),
(38, 'Общее', '/board/b/', 6, 36),
(39, 'Вопросы по сайту', '/board/d/', 6, 37),
(40, 'Аниме и манга', '/board/a/', 6, 38),
(41, 'Игры', '/board/gm/', 6, 39),
(42, 'Музыка', '/board/mu/', 6, 40),
(43, 'Тохо', '/board/to/', 6, 41),
(44, 'Склад', '/board/sto/', 6, 42),
(45, 'Заказы', '/board/r/', 6, 43),
(46, 'Flash', '/board/fl/', 6, 44),
(47, 'Рисование', '/board/dr/', 6, 45),
(48, 'Арт', '/order/category/art/', 37, 46),
(49, 'Видео', '/order/category/video/', 37, 47),
(50, 'Фото', '/order/category/photo/', 37, 48),
(51, 'Игры', '/order/category/games/', 37, 49),
(52, 'Литература', '/order/category/literature/', 37, 50),
(53, 'Манга', '/order/category/manga/', 37, 51),
(54, 'Музыка', '/order/category/music/', 37, 52),
(55, 'Программы', '/order/category/soft/', 37, 53),
(56, 'Для взрослых', '/order/category/nsfw/', 37, 54),
(57, 'Комментарии', '/comments/post/', 2, 55),
(58, 'Комментарии', '/comments/video/', 3, 56),
(59, 'Комментарии', '/comments/art/', 5, 57),
(60, 'Комментарии', '/comments/order/', 37, 58),
(62, 'Багтрекер', 'http://4otaku.ru/bugs', 29, 59);

-- --------------------------------------------------------

--
-- Структура таблицы `head_menu_user`
--

CREATE TABLE IF NOT EXISTS `head_menu_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cookie` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `selector` (`cookie`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `head_menu_user`
--


-- --------------------------------------------------------

--
-- Структура таблицы `input_filter`
--

CREATE TABLE IF NOT EXISTS `input_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `md5` (`md5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `input_filter`
--


-- --------------------------------------------------------

--
-- Структура таблицы `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `language`
--

INSERT INTO `language` (`id`, `alias`, `name`) VALUES
(1, 'none', 'Не указан'),
(2, 'japanese', 'Японский'),
(3, 'english', 'Английский'),
(4, 'russian', 'Русский'),
(5, 'korean', 'Корейский'),
(8, 'nolanguage', 'Не требуется'),
(6, 'chinese', 'Китайский'),
(7, 'spanish', 'Испанский');

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `month` (`month`),
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `logs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `misc`
--

CREATE TABLE IF NOT EXISTS `misc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data1` text COLLATE utf8_unicode_ci NOT NULL,
  `data2` text COLLATE utf8_unicode_ci NOT NULL,
  `data3` text COLLATE utf8_unicode_ci NOT NULL,
  `data4` text COLLATE utf8_unicode_ci NOT NULL,
  `data5` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11945 ;

--
-- Дамп данных таблицы `misc`
--

INSERT INTO `misc` (`id`, `type`, `data1`, `data2`, `data3`, `data4`, `data5`) VALUES
(1, 'logs_start', '2010', '2', '15', '', ''),
(20, 'site_alias', 'youtube.com', 'YouTube', '', '', ''),
(21, 'site_alias', 'imagehost.org', 'ImageHost', '', '', ''),
(22, 'site_alias', 'narod.ru', 'Яндекс.Диск', '', '', ''),
(23, 'site_alias', 'mediafire.com', 'MediaFire', '', '', ''),
(24, 'site_alias', 'megaupload.com', 'MegaUpload', '', '', ''),
(15, 'site_alias', 'rapidshare.com', 'Rapidshare', '', '', ''),
(16, 'site_alias', 'blame.ru', 'Blame!ru', '', '', ''),
(17, 'site_alias', 'depositfiles.com', 'DepositFiles', '', '', ''),
(18, 'site_alias', '4shared.com', '4shared', '', '', ''),
(19, 'site_alias', 'wikipedia.org', 'Wikipedia', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `morphy_cache`
--

CREATE TABLE IF NOT EXISTS `morphy_cache` (
  `word` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cache` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `morphy_cache`
--


-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=66 ;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `url`, `title`, `text`, `pretty_text`, `image`, `comment_count`, `last_comment`, `pretty_date`, `sortdate`, `area`) VALUES
(1, 'tech_support', 'Технические вопросы', '<p>Эта страница предназначена для любого рода технических вопросов по сайту в целом. \r\n</p><ul>\r\n<li>Вы хотели бы видеть какую-то категорию материалов продублированной на другой аплоадер? Спросите здесь.</li>\r\n<li>\r\nУ вас есть предложение или хорошая идея, что можно изменить в 4отаку? Озвучьте здесь.</li>\r\n<li>\r\nИспытываете сложности при навигации по сайту, не открываются страницы, не удобно находить искомое? Сообщите об этом здесь.</li>\r\n<li>\r\nУ вас есть желание поучаствовать в проекте и необходимые для этого навыки? Напишите об этом здесь. И не забудьте пожалуйста оставить средства связи.</li>\r\n</ul>\r\n<p><br />\r\nИ что угодно, не вошедшее в предыдущий список и при этом касающееся больше чем одной-двух записей. Для вопросов по конкретным записям есть комментарии в них самих. Если хотите следить за общим потоком комментариев к записям, всегда есть лента комментариев в правом сайдбаре и ее RSS в правом верхнем углу.</p>', '***', '125204356981.jpg', 34, 1256817005000, 'Март 16, 2009', 1237150800000, 'main'),
(2, 'kikaki', 'Объявление: Карбюраторы и приглашения ', '<b>Карбюраторы:</b> <br>\r\nОдин из наших постоянных авторов, <a href="http://4otaku.ru/author/raincat/">RainCat</a> занимается переводом додзинси на русский. И он завел себе блог переводчика, где будет выкладывать свои переведенные работы. <br>\r\nНаходится блог тут: <a href="http://raincat.4otaku.ru/">http://raincat.4otaku.ru</a>. <a href="http://raincat.4otaku.ru/feed" target="_blank">RSS</a>.<br><br>\r\n\r\n<b>Приглашения:</b> <br>\r\n\r\nКак вы можете заметить, его блог располагается на субдомене 4отаку. И это не случайно. Мы с радостью предоставим место и даже сделаем несложный блог или сайт для всех желающих, при одном условии. Ваш блог/сайт должны быть посвящены производству чего-то интересного или полезного для отаку. <br>\r\nДля примера, мы с радостью примем художников, переводчиков, косплееров с фотографиями, создателей игр, флеш-роликов или манги. <br>\r\nЕсли есть желание, то пишите на <a href="mailto:noname-nya@yandex.ru">noname-nya@yandex.ru</a>, обсудим. Можно еще на всякий случай, чтобы вас не потеряли, отписать в комментариях к этой записи.</p>', '***', '125266136514.jpg', 3, 1251478110000, 'Август 28, 2009', 1251403635000, 'main'),
(3, 'torrent', 'Объявление: торрент 4отаку ', 'Итак, приятный сюрприз догрузился и оказался большим <a href="http://4otaku.ru/4otaku.torrent">торрентом</a> со всеми материалами когда-либо выкладывавшимися на нашем сайте. 86 гигабайт с хвостиком. Надеюсь все умеют качать торренты по частям?<br>\r\nПо заказам пользователей – список всех файлов в торренте <a href="http://4otaku.ru/print.txt" rel="nofollow">http://4otaku.ru/print.txt</a>\r\n<p>Данный торрент можно и нужно распостранять где вам хочется в рунете – мы совершенно не против. (Еще раз подчеркиваю – в рунете, в японии например часть выложенных тут материалов – уже подсудное дело.)<br>\r\nЯ постарался проверить и рассортировать все архивы. Если несмотря на мои усилия вы найдете битые файлы или материалы оказавшиеся не в том разделе где они должны быть – пишите в комментариях к этой записи, надо же исправить к следующему торренту. <br>\r\nДа-да, торренты со всеми материалами сайта станут регулярной традицией. Приятного скачивания!</p>\r\n<p>Вторая новость – мы вынужденно отказываемся от бывшего раньше основным аплоадера MediaFire и хотим знать, какая замена была бы удобнее для всех. Предлагать и обсуждать варианты можно опять же в комментариях к этой записи.</p>\r\n<br><br>\r\n<b>Раздача торрента прекращена!</b>', '***', '125266477526.jpg', 24, 1257178380000, 'Июль 12, 2009', 1247342448000, 'main'),
(4, 'enlist', 'Объявление: общий сбор ', 'Чем отличается большой сайт от маленького бложика? Тем, что маленьким бложиком занимается в основном один человек. А над большим и уважающим себя сайтом трудятся многие. В любой роли – администратор ли, модератор, программист или дизайнер, и даже просто активные пользователи вносят свой вклад в развитие сайта. И разумеется выходит у них намного лучше, чем у одного-то человека.\r\n<p>4отаку никогда не задумывался как маленький бложик одного человека, мирно выкладывающего себе материалы. Но посмотрим состояние дел на сегодня?<br>\r\nЯ работаю над кодом сайта. Я модерирую комментарии и сообщество. Записей моих 2/3 от всех записей сайта. Разве что дизайном не занимаюсь. И еще есть несколько задач поменьше вроде там приятного сюрприза и обновлений старых записей – тоже я.</p>\r\n<p>Разумеется, с ростом сайта я просто перестану все успевать. И станет этот сайт чем-то большим чем он есть сейчас, чем-то известным и нужным, зависит от одной простой вещи – найдутся ли люди, тоже готовые над ним работать.</p>\r\n<p>Помочь сайту вы можете двумя способами:<br>\r\n<strong>Первый</strong> – это в роли активного пользователя, добавляя свои материалы по этому адресу <a href="http://4otaku.ru/add/">http://4otaku.ru/add</a>. Поверьте, это уже будет немало. Если есть какие-то вопросы на тему как эта штукенция работает – задавайте их в комментариях к этой записи.<br>\r\n<strong>Второй</strong> – вызвавшись добровольцем в команду тех кто над сайтом работает. Для этого вы можете как отписаться здесь, указав свой e-mail, так и отправить письмо мне на noonemail@mail.ru (но в этом случае все равно лучше на всякий отписаться здесь, чтоб вас не потеряли). Специальные навыки вроде фотошопа или веб-программирования не обязательны.</p>', '***', '125266531481.jpg', 18, 1246639605000, 'Июнь 28, 2009', 1246132876000, 'main'),
(5, 'board_open', 'Объявление: открылось сообщество сайта ', 'Скрипт имиджборды написан и готов к эксплуатации. Прошу всех к столу – <a href="/boards/">http://4otaku.ru/boards/</a>.<br>\r\nЧитать какие скрипт постигли обновления, задавать вопросы или запрашивать новый функционал можно в специальном разделе: <a href="/boards/f/">http://4otaku.ru/boards/f/</a>.\r\n', '***', '125266545416.jpg', 0, 0, 'Май 13, 2009', 1242158711000, 'main'),
(6, 'yumiko', 'Объявление: представляем вам Юмико-тян ', 'Здравствуйте! Меня зовут Юмико, и теперь я буду работать здесь. Приятно познакомиться! Надеюсь, мы с вами подружимся.\r\n<p>Начальник-доно в качестве первого задания выдал мне анкету и велел поговорить со всеми нашими посетителями, попросить их заполнить бланки. Так. Не проходите мимо, пожалуйста! Чтобы ответить, достаточно нажать на ссылку “<a href="/895">комментировать</a>” в правом верхнем углу, если вы читаете нас со страниц сайта, или же перейти к записи, если вы читаете нас через RSS-ридер.</p>\r\n<p>Если вам не сложно, ответьте, пожалуйста, на небольшой ряд вопросов:<br>\r\n1) Какие материалы вы хотели бы видеть на нашем сайте почаще?<br>\r\n2) Нужен ли сайту 4отаку редизайн или просто какие-нибудь дополнения дизайна?<br>\r\n3) Какой дополнительный функционал вы хотели бы видеть?<br>\r\n4) Любые ваши пожелания и вопросы касательно сайта на свободную тему.<br>\r\n5) Что вы думаете о Юмико-тян как о работнице? Оставить или гнать взашей?</p>\r\n\r\n<p>Да, мм.. ничего себе пятый вопросик у меня тут в анкете…<br>\r\nТак или иначе, ответьте на все пункты, если вам не сложно. Большое спасибо!</p>', '***', '125266555944.jpg', 20, 1241724117000, 'Апрель 22, 2009', 1240344359000, 'main'),
(7, 'plans_and_problems', 'Объявление: Планы и проблемы ', '<strong>Планы</strong>\r\n<p>В ближайших планах на серьезные обновления функционала есть новая и весьма нужная область сайта. Ее рабочее название – “мастерская”.</p>\r\n<p>Сейчас добавить свои материалы можно зайдя по адресу <a href="http://4otaku.ru/add">http://4otaku.ru/add</a>, или нажав на кнопку “добавить материал” вверху страницы. При этом вам будет нужно заполнить множество полей, от описания до тегов. Плюс еще дождаться модератора, который одобрит добавленную вами запись.</p>\r\n<p>Согласитесь, не самая удобная схема. И если я хочу превратить сайт в, как изначально планировалось, место где посетители обмениваются редкими и интересными диковинками прояпонской направленности, то нужно эту процедуру упростить.</p>\r\n<p>Поэтому будет сделан новый раздел сайта, для неодобренных еще записей. Новую запись можно будет добавить прямо там, не уходя со страницы, воспользовавшись выезжающей формой. Причем необходимый минимум полей будет включать в себя только название записи и хотя бы одну ссылку/хотя бы один торрент-файл. Более того, там необязательно жестко соблюдать тематику сайта, достаточно избегать совсем чужеродных материалов и материалов запрещенных УК РФ.<br>\r\nЗачем нужны такие неполные записи, спросите вы? Дело в том что там же, в “мастерской”, любой желающий сможет отредактировать любую запись, добавить картинки/теги/описание/дополнительные ссылки и так далее. Как скажем, в Википедии. И так, дополняя содержащие знакомые материалы или понравившиеся вам записи, вы сможете довести их до состояния когда они вполне смогут попасть на главную.</p>\r\n<p>Это были ближайшие планы на развитие сайта. Планы хорошие, нужные, вполне реализуемые. Но – смотрите следующий пункт.</p>\r\n\r\n<p><strong>Проблемы</strong></p>\r\n<p>Беда в том, что для того чтобы сделать достойный и непростаивающий новый раздел сайта нужно две вещи: хороший функционал и хороший дизайн. Сайт до сих пор развивало (в техническом смысле) двое людей. Я – программист и администратор. И дизайнер. Но сейчас дизайнер из-за своих личных трудностей выбывает на несколько месяцев из работы. Нет дизайна – нет нового раздела, нет развития. Поэтому если никто не вызовется ему на временную замену, развитие сайта затормозится на несколько месяцев.</p>', '***', '125266581086.jpg', 2, 1244488213000, 'Июнь 7, 2009', 1246910452000, 'main'),
(8, 'imageboard', 'Обсуждение будущей имиджборды ', '<p>Итак, на нашем сайте со временем появится имиджборда. Технические требования необходимые для ее реализации несколько расходятся с текущими возможностями моего хостинга. И сначала придется разобраться с этим, а только потом уже ставить непосредственно борду. Из-за этого процесс несколько затянется, но думаю не больше чем до середины мая.\r\n<p>А тем временем, здесь можно обсуждать технические аспекты будущей имиджборды, запрашивать для нее какой-либо функционал и разделы. Здесь же можно будет читать новости о ее разработке или предложить свою помощь в чем-либо.</p>\r\n<p>Да, если вы еще не заметили: возможность писать анонимно я уже ввел – просто не меняйте имя и е-мейл поставленные в полях комментария по умолчанию.</p>\r\n<p><b>Update: 02-05-2009.</b><br>\r\nПосле изучения скрипта Вакабы, я решил написать свой собственный скрипт. С блэкджеком и известно кем. <br>Не то, чтобы меня не устраивал функционал или компоновка Вакабы, они выше всяких похвал. Просто неуютно работать с ”несвоей” структурой кода. Да и PHP мне куда ближе, чем Perl. Да и новые навороты на собственном движке делать намного легче, чем на чужом.<br>\r\nТо есть будет практически все то же самое, отличие будет лишь в “кишках” скрипта, невидимых пользователю.<br>\r\nВот и сижу теперь, воспроизвожу функционал Вакабы с нуля. Процентов на 70 уже готово. Сроки не меняются – все также середина мая. Ведь надо еще причесать внешний вид и структуру HTML.</p>', '***', '125312038654.jpg', 10, 1244740935000, '18 Апрель, 2009', 1239998569000, 'main'),
(9, 'new_4otaku', 'Объявление: новый 4отаку, на 15% удобней, на 20% кавайней', '<p>Сделать сайт на wordpress-е было отличным и быстрым началом, но со временем в нем стало просто тесно. Индивидуально собранный движок всегда подойдет лучше, и если уж сайт приглянулся людям, то можно и потратить время на его написание.<br />\r\nПоскольку сайт полностью не тестировался, вы можете наткнуться на недоловленный баг или просто недочет верстки. Если это произойдет, отпишитесь пожалуйста здесь в комментариях. Чем раньше все глюки переловим и передавим, тем лучше.<br />\r\nРазвитие сайта на этом ни в коем случае не останавливается, еще больше обновлений и новых модулей ждут в недалеком будущем.<br />\r\nДо обещанного обновления <a href="/boards/" target="_blank">имиджборды</a> тоже обязательно доберусь.</p>\r\n<p>Якумо Юкари из тохо стала нашим маскотом, просим любить и жаловать. Она будет красоваться на всех официальных объявлениях, в шапке, и в самых неожиданных местах на сайте.</p>\r\n<p>Пожалуй главным обновлением стала <a href="/workshop/" target="_blank">мастерская</a>. Если у вас есть проверенная ссылка на скачку материалов интересных отаку - вы легко можете ее опубликовать. <br />\r\nОна попадет без всякой премодерации в мастерскую. И что самое главное - материалы находящиеся в мастерской может дорабатывать любой желающий, доводя до состояния полноценной записи 4отаку. <br />\r\nСамое вкусное из мастерской будет переноситься модераторами на главную страницу.</p>', '***', 'newcms.jpg', 13, 1253345386000, 'Сентябрь 16, 2009', 1253045256000, 'main'),
(10, 'download', 'Скачивание больших файлов', '<p>Время от времени появляется необходимость скачать действительно большой файл. В этом случае возрастает опасность, что связь прервется в процессе скачивания и все придется начать сначала. Решение в этом случае простое: это специальные программы, которые позволяют увеличить скорость скачивания и восстановить прерванную закачку. Называются они даунлодерами (от английского download – скачивать/сгружать). Даже если вы совершенно уверены в качестве своей связи, мы рекомендуем вам все-таки поставить одну из этих программ. Они совершенно бесплатны и обладают, например, такой полезной функцией, как поставить скачивание файла на паузу с последующим безболезненным восстановлением.</p>\r\n\r\n<p>Выбор программы зависит, в основном, от вашей операционной системы и любимого браузера. Для пользователей Windows, предпочитающих Mozilla Firefox, мы советуем поставить flashgot, который является встраиваемым плагином и не требует сложной установки. Последнюю версию можно всегда достать на официальном сайте <a href="http://www.flashgot.net">www.flashgot.net</a>. Если вы захотите расширить его базовую функциональность, вы можете дополнительно установить программу, которая будет работать с ним в паре. Из возможных вариантов есть <a href="http://www.westbyte.com/dm/">Download Master</a>, <a href="http://www.bitcomet.com/">BitComet</a> и <a href="http://flashget.izcity.com/">FlashGet</a>. Обращаем ваше внимание на то, что все вышеперечисленные сайты и их программы на английском языке.</p>\r\n<p>Для пользователей Windows, не любящих Firefox, а пользующихся другими браузерами, подходящим решением будет <a href="http://www.westbyte.com/dm/">Download Master</a>, он отлично работает и без интеграции в браузер. Он, в отличии от приведенных выше программ, русифицирован.</p>\r\n<p>Если вы пользуетесь не Windows, а Unix-системой, тогда мы можем порекомендовать вам downloader for x. Вы можете скачать проверенную на вирусы и всегда доступную версию у нас <a href="http://www.mediafire.com/?gqfntmyzrm2">по этой ссылке</a> или поискать в интернете свежую версию самостоятельно, нажав <a href="http://www.google.ru/search?hl=ru&amp;q=downloader+for+x">сюда</a>.</p>', '***', '', 3, 1255850366000, 'Ноябрь 18, 2008', 1000, 'main');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `spam` tinyint(4) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(4096) COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=712 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `title`, `username`, `email`, `spam`, `text`, `pretty_text`, `link`, `category`, `comment_count`, `last_comment`, `pretty_date`, `sortdate`, `area`) VALUES
(1, 'Все ранобе Kara no kyoukai на английском', 'msd', '0init0@gmail.com', 0, 'Сам смог найти только первую главу.', '***', '', '|none|', 2, 1253682540000, 'Март 19, 2009', 1237412696000, 'flea_market'),
(2, 'Манга Plastic Little', 'моНЯ', 'samael013@mail.ru', 0, 'Уже писал в коментах, но отпишу и здесь)<br />\r\nМангу Plastic Little хочу =))) Есть ли её русский перевод в электронном виде вообще? Всё-таки она была официально переведена и выпущена на российский рынок… Впринципе, и английский перевод тоже хорошо бы.) Если найду быстрее, то выложу.', '***', '/post/151', '|none|', 2, 1252819500000, 'Март 20, 2009', 1237499024000, 'main'),
(3, 'файтинг Glove on Fight', 'Гудвин', 'kotik_narkotik_2@bk.ru', 0, 'Здравствуйте. Никак не могу найти файтинг Glove on Figh, как буд-то его вообще нет. Есть только маленький ролик показывающий геймплей, выцарапаный из старого номера “Страны игр”.', '***', '/post/163', '|none|', 2, 1252819680000, 'Март 25, 2009', 1237930513000, 'main'),
(4, 'манга Eureka 7', 'osakades', 'freemen18@ya.ru', 0, 'товарищи…есть ли полностью переведёная манга Eureka 7. достанье пожалуйста даже если она не переведена )', '***', '', '|none|', 1, 1252819920000, 'Март 30, 2009', 1238361661000, 'flea_market'),
(5, 'Guilty Gear, часть XX c переведенным на англ. конфигом', 'Deeo', 'yourillusion@mail.ru', 0, 'Здравствуйте, администрация. Выражаю свою благодарность за создание этого ресурса. Очень симпатично, юзабельно, и просто по-человечески.<br />\r\nХочется увидеть на ресурсе игры из серии Guilty Gear, часть XX c переведенным на англ. конфигом, но оригинальным екзешником я качал с бухты.<br />\r\nТакже, на вашем ресурсе есть новелла FSN, но Tsukihime (первая работа Тайпнуна) нету. Не порядок.<br />\r\nСсылку, к сожалению, потерял.', '***', '/post/29', '|none|', 4, 1252820280000, 'Март 30, 2009', 1238361312000, 'main'),
(6, 'артбуки по Guilty Gear', 'Гудвин', 'kotik_narkotik_2@bk.ru', 0, 'А артбуки по Guilty Gear существуют?', '***', '/post/mixed/tag=guilty_gear+артбук', '|none|', 1, 1252820460000, 'Апрель 2, 2009', 1238618317000, 'main'),
(7, '23 и 24 главы манги Melty Blood', 'Fatum', 'xao_asakyra@mail.ru', 0, 'Здравствуйте, не знаете где можно найти 23 и 24 главы (а если можно то побольше)) манги Melty Blood (которая яв-ся продолжением Tsukihime)? Не обязательно на русском (да ее и нету наверняка), а хотя б на английском.', '***', '', '|none|', 2, 1252820640000, 'Апрель 3, 2009', 1238703902000, 'flea_market'),
(8, 'артбук по игре Tales of Destiny 2', 'Гудвин', 'kotik_narkotik_2@bk.ru', 0, 'А как на счёт артбука по игре Tales of Destiny 2 (Tales of Eternia)?', '***', '/post/184', '|none|', 0, 0, 'Апрель 4, 2009', 1238792650000, 'main'),
(9, 'mp3 русской версии опенинга Харухи', 'Kiguri', 'mzolotovag12@mail.ru', 0, 'Здравствуйте!Очень бы хотелось скачать русский опенинг из аниме меланхолия харухи судзумии,но не клип,а в формате mp3', '***', '', '|none|', 2, 1266248616000, 'Апрель 15, 2009', 1239739959000, 'flea_market'),
(10, '3-D файтинг наподобии Crucis Fatal Fake', 'МиоН', 'mion@yandex.ru', 0, 'Доброго времени суток!<br />\r\nХотелось бы увидеть 3-D файтинг наподобии Crucis Fatal Fake))', '***', '/post/215', '|none|', 3, 1252821300000, 'Апрель 22, 2009', 1240347062000, 'main'),
(11, 'работы Carnelian', 'Анонимус', 'default@avatar.mail', 0, 'Доброго времени суток.<br />\r\nКак насчет работ Carnelian?', '***', '/post/tag/carnelian', '|none|', 5, 1252821660000, 'Апрель 22, 2009', 1240344516000, 'main'),
(12, 'игрушко Words Worth', 'Baklan', 'Mikheland@yandex.ru', 0, 'Ищу игрушко Words Worth, по техническим причинам не могу скачать её с торрентов((( Залейте кто нибудь пжалста.', '***', '', '|none|', 4, 1253358660000, 'Апрель 23, 2009', 1240434190000, 'flea_market'),
(13, 'вертикальные скроллер-шутеры', 'Анонимус', 'kometaf@yandex.ru', 0, 'Хотелось бы побольше вертикальных скроллер-шутеров, особенно по Тохо, есть же фанатские игры', '***', '/post/tag/shoot_em_up', '|none|', 3, 1252822200000, 'Апрель 24, 2009', 1240518728000, 'main'),
(14, 'Seihou Banshiryuu extra stage и(или) Diadre Empty', 'Bushbasher', 'default@avatar.mail', 0, 'Очень хочеться поиграть, заранее спасибо ^_^', '***', '/post/236', '|none|', 6, 1252822800000, 'Май 3, 2009', 1241297574000, 'main'),
(15, 'оригинальные игры Хигураш', 'Mervish', 'mercvish@samtel.ru', 0, 'Кто-то говорил про оригинальные игры. Посему реквестирую Хигураш, и побольше.', '***', '/post/635', '|none|', 8, 1258469656000, 'Май 12, 2009', 1242072475000, 'main');

-- --------------------------------------------------------

--
-- Структура таблицы `plugin`
--

CREATE TABLE IF NOT EXISTS `plugin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(64) NOT NULL,
  `thread` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `author` text COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `language` text COLLATE utf8_unicode_ci NOT NULL,
  `tag` text COLLATE utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `update_count` int(11) NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1639 ;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `title`, `text`, `pretty_text`, `author`, `category`, `language`, `tag`, `comment_count`, `last_comment`, `update_count`, `pretty_date`, `sortdate`, `area`) VALUES
(1, 'Flash от студии Maru Production', 'Студия Maro Production производит игры, арт и флеш-видео. Стиль рисования этой студии многим напоминает стиль, использованный в аниме Last Exile студией Gonzo, а если точнее, то одного из художников, рисовавших Last Exile, Range Murata. Легкая и позитивная атмосфера делает флешки приятными для просмотра даже несмотря на то, что для понимания большей части происходящего нужно знание японского языка. Почти все видео связаны регулярными персонажами, которые редко пропускают возможность поучаствовать в новой флешке.', 'Студия Maro Production производит игры, арт и флеш-видео. Стиль рисования этой студии многим напоминает стиль, использованный в аниме Last Exile студией Gonzo, а если точнее, то одного из художников, рисовавших Last Exile, Range Murata. Легкая и позитивная атмосфера делает флешки приятными для просмотра даже несмотря на то, что для понимания большей части происходящего нужно знание японского языка. Почти все видео связаны регулярными персонажами, которые редко пропускают возможность поучаствовать в новой флешке.', '|nameless|', '|video|', '|japanese|', '|flash|', 2, 1302780349410, 0, 'Октябрь 18, 2008', 1224273601000, 'main'),
(2, 'Little Fighter 2 – 2.0', 'Little Fighter 2 – свободно распространяемая файтинг игра от Marti и Starsky Wong, вышедшая в 1999 году и продолжающая обновляться по сей день.<br />\nОтличительная особенность файтинга – масштабные битвы с участием большого количества персонажей на экране.<br />\n<br />\nЗа одним компьютером могут сражаться сразу 4 игрока. В сетевой игре максимальное количество активных игроков увеличивается до восьми.<br />\nПомимо обычных сражений друг с другом присутствуют такие режимы, как: игра на прохождение, массовые баталии 2-х команд с участием NPC и битва на выживание.', 'Little Fighter 2 – свободно распространяемая файтинг игра от Marti и Starsky Wong, вышедшая в 1999 году и продолжающая обновляться по сей день.\nОтличительная особенность файтинга – масштабные битвы с участием большого количества персонажей на экране.\n\nЗа одним компьютером могут сражаться сразу 4 игрока. В сетевой игре максимальное количество активных игроков увеличивается до восьми.\nПомимо обычных сражений друг с другом присутствуют такие режимы, как: игра на прохождение, массовые баталии 2-х команд с участием NPC и битва на выживание.', '|alan|', '|games|', '|english|', '|little_fighter|lan_game|fighting|', 1, 1254642695000, 0, 'Октябрь 19, 2008', 1224360002000, 'main'),
(3, 'Альбом Riot Girl от Hirano Aya', 'Riot Girl – дебютный альбом от J-Pop певицы и сейю Hirano Aya, релиз которого состоялся 16 июля 2008 года.<br />\n<br />\nАльбом содержит 14 песен, семь из которых совершенно новые, а остальные 7 уже можно было услышать в синглах певицы ранее.<br />\n<br />\nЕё популярность возросла после роли Haruhi в аниме The Melancholy of Haruhi Suzumiya. Она же исполнила opening сериала, и, совместно с Minori Chihara и Yuko Goto спела ending.', 'Riot Girl – дебютный альбом от J-Pop певицы и сейю Hirano Aya, релиз которого состоялся 16 июля 2008 года.\n\nАльбом содержит 14 песен, семь из которых совершенно новые, а остальные 7 уже можно было услышать в синглах певицы ранее.\n\nЕё популярность возросла после роли Haruhi в аниме The Melancholy of Haruhi Suzumiya. Она же исполнила opening сериала, и, совместно с Minori Chihara и Yuko Goto спела ending.', '|alan|', '|music|', '|japanese|', '|j_pop|album|hirano_aya|', 0, 0, 0, 'Октябрь 20, 2008', 1224446403000, 'main'),
(4, 'Трейлеры аниме фестивалей', 'Организаторы многих из аниме-фестивалей России создали рекламные ролики для своих фестивалей. Для этого берутся видео и фото, отснятые на фестивале и монтируются под специально подобранную музыку. Получается весьма интересное и увлекательное зрелище. Если вы размышляете – ехать или не ехать в другой город на фестиваль, трейлер может помочь вам определиться.<br />\n<br />\nВ данном архиве представлены фестивали Московского, Питерского, Чебоксарского, Казанского, Минского, Кировского, Ростовского и Воронежского фестиваля.', 'Организаторы многих из аниме-фестивалей России создали рекламные ролики для своих фестивалей. Для этого берутся видео и фото, отснятые на фестивале и монтируются под специально подобранную музыку. Получается весьма интересное и увлекательное зрелище. Если вы размышляете – ехать или не ехать в другой город на фестиваль, трейлер может помочь вам определиться.\n\nВ данном архиве представлены фестивали Московского, Питерского, Чебоксарского, Казанского, Минского, Кировского, Ростовского и Воронежского фестиваля.', '|nameless|', '|video|', '|russian|', '|mikan_no_yuki|акибан|анимацури|трейлер|феникс|фестиваль|', 0, 0, 0, 'Октябрь 21, 2008', 1224532804000, 'main'),
(5, 'Фотографии с Акибана-2008', 'Сборник фотографий с Ижевского фестиваля Акибан-2008. Фотографии собраны с нескольких фотографов и рассортированы с указанием никнейма и города, чтобы не возникло путаницы.', 'Сборник фотографий с Ижевского фестиваля Акибан-2008. Фотографии собраны с нескольких фотографов и рассортированы с указанием никнейма и города, чтобы не возникло путаницы.', '|nameless|', '|photo|', '|nolanguage|', '|акибан|фестиваль|cosplay|фотоотчет|', 3, 0, 0, 'Октябрь 28, 2008', 1225141205000, 'main'),
(6, 'Ника от Богдана', 'Ника от Богдана – один из немногих отечественных комиксов, нарисованных в аниме-стилистике.<br />\n<br />\nДанное произведение, если понравилось, можно приобрести в сети магазинов anime-point.<br />\n<br />\nПерсонажи данного комикса также присутствуют в качестве NPC в книге правил русской настольной ролевой игры “Эра Водолея”. Сюжет рассказывать не буду, чтобы не испортить впечатление от прочтения, намекну только на крайне интересных и харизматических персонажей.', 'Ника от Богдана – один из немногих отечественных комиксов, нарисованных в аниме-стилистике.\n\nДанное произведение, если понравилось, можно приобрести в сети магазинов anime-point.\n\nПерсонажи данного комикса также присутствуют в качестве NPC в книге правил русской настольной ролевой игры “Эра Водолея”. Сюжет рассказывать не буду, чтобы не испортить впечатление от прочтения, намекну только на крайне интересных и харизматических персонажей.', '|nameless|', '|manga|', '|russian|', '|богдан|', 11, 1300846267360, 0, 'Октябрь 28, 2008', 1225141206000, 'main'),
(7, 'Игра Touhou 7.5 ~ Immaterial and Missing Power', 'Англофицированный файтинг из серии Тохо. Если у вас не запускается config_e.exe, попробуйте поставить в свойствах приложения совместимость с Windows 2000.', 'Англофицированный файтинг из серии Тохо. Если у вас не запускается config_e.exe, попробуйте поставить в свойствах приложения совместимость с Windows 2000.', '|nameless|', '|games|', '|english|', '|fighting|team_shanghai_alice|touhou|', 17, 1313771725200, 0, 'Ноябрь 5, 2008', 1225832407000, 'main'),
(8, 'Переозвучка от Сатори', 'Аниме-переозвучка заключается в накладывании своей звуковой дорожки поверх или вместо имеющейся. Данная переозвучка была представлена на аниме-фестивале Черный Дракон 2008 от команды “ГТО и ЦК Сатори”. В качестве отрезка из аниме фигурирует отрезок из “Чобитов”.', 'Аниме-переозвучка заключается в накладывании своей звуковой дорожки поверх или вместо имеющейся. Данная переозвучка была представлена на аниме-фестивале Черный Дракон 2008 от команды “ГТО и ЦК Сатори”. В качестве отрезка из аниме фигурирует отрезок из “Чобитов”.', '|nameless|', '|video|', '|russian|', '|фестиваль|черный_дракон|переозвучка|', 0, 0, 0, 'Ноябрь 5, 2008', 1225832408000, 'main'),
(9, 'Додзинси Suzumiya Haruoh', 'Жгучая пародия на Меланхолию Харухи Суздзумии от неизвестного художника. Доступна пока только на английском языке. Суть пародии заключается в исполнении событий, имевших место быть в сериале в стиле и с персонажами из Fist of the North Star. При этом надо понимать что стиль Меланхолии и стиль FotNS практически диаметрально противоположны, не говоря уже о разнице в персонажах.<br />\n<br />\nОценить юмор можно и не зная одного из источников.<br />\n<br />\nПеревод на русский выполнил Мангани', 'Жгучая пародия на Меланхолию Харухи Суздзумии от неизвестного художника. Доступна пока только на английском языке. Суть пародии заключается в исполнении событий, имевших место быть в сериале в стиле и с персонажами из Fist of the North Star. При этом надо понимать что стиль Меланхолии и стиль FotNS практически диаметрально противоположны, не говоря уже о разнице в персонажах.\n\nОценить юмор можно и не зная одного из источников.\n\nПеревод на русский выполнил Мангани', '|nameless|', '|manga|', '|english|', '|parody|suzumiya_haruhi_no_yuuutsu|doujinshi|', 2, 1301201884800, 1, 'Ноябрь 5, 2008', 1225832409000, 'main');

-- --------------------------------------------------------


--
-- Структура таблицы `post_extra`
--

CREATE TABLE IF NOT EXISTS `post_extra` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`post_id`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_file`
--

CREATE TABLE IF NOT EXISTS `post_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `size` decimal(10,4) unsigned NOT NULL,
  `sizetype` tinyint(3) unsigned NOT NULL COMMENT '0 - килобайты, 1 - мегабайты, 2- гигабайты',
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`post_id`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_image`
--

CREATE TABLE IF NOT EXISTS `post_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `file` varchar(255) NOT NULL,
  `extension` varchar(6) NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_link`
--

CREATE TABLE IF NOT EXISTS `post_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` decimal(10,4) unsigned NOT NULL,
  `sizetype` tinyint(3) unsigned NOT NULL COMMENT '0 - килобайты, 1 - мегабайты, 2- гигабайты',
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`post_id`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_link_url`
--

CREATE TABLE IF NOT EXISTS `post_link_url` (
  `url_id` int(10) unsigned NOT NULL,
  `link_id` int(10) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`url_id`,`link_id`),
  UNIQUE KEY `selector` (`url_id`,`link_id`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `post_status` (
  `id` int(10) unsigned NOT NULL,
  `overall` float unsigned NOT NULL,
  `total` smallint(5) unsigned NOT NULL,
  `broken` smallint(5) unsigned NOT NULL,
  `partially_broken` smallint(5) unsigned NOT NULL,
  `unmirorred` smallint(5) unsigned NOT NULL,
  `unknown` smallint(5) unsigned NOT NULL,
  `uncheked` smallint(5) unsigned NOT NULL,
  `lastcheck` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Структура таблицы `post_torrent`
--

CREATE TABLE IF NOT EXISTS `post_torrent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `size` decimal(10,4) unsigned NOT NULL,
  `sizetype` tinyint(3) unsigned NOT NULL COMMENT '0 - килобайты, 1 - мегабайты, 2- гигабайты',
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`post_id`,`order`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- Структура таблицы `post_update`
--

CREATE TABLE IF NOT EXISTS `post_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `username` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'main',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`sortdate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_update_link`
--

CREATE TABLE IF NOT EXISTS `post_update_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `update_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` decimal(10,4) unsigned NOT NULL,
  `sizetype` tinyint(3) unsigned NOT NULL COMMENT '0 - килобайты, 1 - мегабайты, 2- гигабайты',
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`update_id`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_update_link_url`
--

CREATE TABLE IF NOT EXISTS `post_update_link_url` (
  `url_id` int(10) unsigned NOT NULL,
  `link_id` int(10) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`url_id`,`link_id`),
  UNIQUE KEY `selector` (`url_id`,`link_id`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `post_update_status` (
  `id` int(10) unsigned NOT NULL,
  `overall` float unsigned NOT NULL,
  `total` smallint(5) unsigned NOT NULL,
  `broken` smallint(5) unsigned NOT NULL,
  `partially_broken` smallint(5) unsigned NOT NULL,
  `unmirorred` smallint(5) unsigned NOT NULL,
  `unknown` smallint(5) unsigned NOT NULL,
  `uncheked` smallint(5) unsigned NOT NULL,
  `lastcheck` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `post_url`
--

CREATE TABLE IF NOT EXISTS `post_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `status` tinyint(3) unsigned NOT NULL COMMENT '0 - не проверено, 1 - работает, 2 - не удалось понять, 3 - сломано',
  `lastcheck` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`(255)),
  KEY `lastcheck` (`lastcheck`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Структура таблицы `post_url_rule`
--
CREATE TABLE IF NOT EXISTS `post_url_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `type` varchar(16) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Дамп данных таблицы `post_url_rule`
--

INSERT INTO `post_url_rule` (`id`, `domain`, `type`, `value`) VALUES
(1, 'narod.ru', 'alias', 'narod.yandex.ru'),
(2, 'narod2.yandex.ru', 'alias', 'narod.yandex.ru'),
(3, 'narod3.yandex.ru', 'alias', 'narod.yandex.ru'),
(4, 'narod4.yandex.ru', 'alias', 'narod.yandex.ru'),
(5, 'narod.yandex.ru', 'works', '/<b>Скачать<\\/b>/u'),
(6, 'megaupload.com', 'works', '/<[Tt][Dd]\\s+align="center">[Pp]lease\\s+wait<\\/[Tt][Dd]>/u'),
(7, 'megaupload.com', 'works', '/<center>The\\s+file\\s+you\\s+are\\s+trying\\s+to\\s+access\\s+is\\s+temporarily\\s+unavailable\\./u'),
(8, 'megaupload.com', 'works', '/<center>Файл,\\s+который\\s+Вы\\s+пытаетесь\\s+открыть,\\s+временно\\s+недоступен/u'),
(9, 'mediafire.com', 'works', '/Preparing download\\.{3}/u'),
(10, 'mediafire.com', 'works', '/Data is loading from the server\\.{2}/u'),
(11, '4shared.com', 'works', '/<font>Скачать<\\/font>/u'),
(12, '4shared.com', 'works', '/<font>Скачать\\s+сейчас<\\/font>/u'),
(13, 'megashares.com', 'works', '/<td\\s+[^>]*>Choose\\s+download\\s+service:<\\/td>/u'),
(14, 'megashares.com', 'works', '/<dd\\s+class="red">All\\s+download\\s+slots\\s+for\\s+this\\s+link\\s+are\\s+currently\\s+filled./u'),
(15, 'depositfiles.com', 'works', '/depositfiles\\.com\\/images\\/speed_small\\.gif"[^>]*>/u'),
(16, 'ifolder.ru', 'works', '/<label\\s+for="dw1">[\\s\\\r\\\n]*Скачать\\s+бесплатно\\s+просмотрев\\s+рекламу[\\s\\\r\\\n]*<\\/label>/u'),
(17, 'letitbit.net', 'works', '/<form\\s+id="ifree_form"\\s+action="\\/download/u'),
(18, 'rghost.ru', 'works', '/<a[^>]+class="download_link"[^>]*>Скачать<\\/a>/u'),
(19, 'files.desu.ru', 'works', '/Всего:\\s+\\d+\\s+файлов,\\s+общий\\s+размер:\\s+[\\d,]+\\s+Мб/u'),
(20, 'dump.ru', 'works', '/<form\\s+[^>]*id="file_download"[^>]*name="file_download"[^>]*>/u'),
(21, 'sites.google.com', 'works', '/Cкачать\\s+<a\\s+href="http:\\/\\/sites\\.google\\.com/u'),
(22, 'ifile.it', 'works', '/<input\\s+type="button"\\s+id="req_btn2"\\s+value="\\s*request\\s+download\\s+ticket\\s*"/u'),
(23, 'zshare.net', 'works', '/<h2>Download:\\s+/u'),
(24, 'animeotaku.ru', 'works', '/<div\\s+id="listing">\\s*<div><a\\s+href="/u'),
(25, 'upload.com.ua', 'works', '/\\stitle="Нажмите\\s+чтобы\\s+скачать\\s+файл\\s[^"]+">[^<]+<\\/a><\\/div>/u'),
(26, 'fileserve.com', 'works', '/<a\\s+id="regularBtn"\\s+href="#"\\s+class="slower_download_btn">Slower\\s+Download<\\/a>/u'),
(27, 'narod.yandex.ru', 'broken', '/Закончился\\s+срок\\s+хранения\\s+файла\\.\\s*Файл\\s+удален/u'),
(28, 'rghost.ru', 'broken', '/<div\\s+[^>]*>[\\s\\\r\\\n]*Файл\\s+удален\\.[\\s\\\r\\\n]*<\\/div>/u'),
(29, 'megaupload.com', 'broken', '/Unfortunately,\\s+the\\s+link\\s+you\\s+have\\s+clicked\\s+is\\s+not\\s+available\\./u'),
(30, 'dump.ru', 'broken', '/<li>[\\s\\\r\\\n]*Запрошенный\\s+файл\\s+удален[\\s\\\r\\\n]*<\\/li>/u'),
(31, 'hotfile.com', 'broken', '/<td>Diese\\s+Datei\\s+ist\\s+entweder\\s+aufgrund\\s+des\\s+Copyright-Rechtes/u'),
(32, 'raincat.4otaku.ru', 'broken', '/<h2>Страница\\s+не\\s+найдена\\.\\s+=(<\\/h2>/u'),
(33, 'mediafire.com', 'works', 'id="authorize_dl_btn"><span></span>Authorize Download</a>'),
(34, 'megaupload.com', 'works', '<a class="download_regular_disabled"  id="dlbuttondisabled"></a>'),
(35, 'rghost.ru', 'broken', 'Файл удален.'),
(36, 'narod.yandex.ru', 'works', '<input type="hidden" name="action" value="sendcapcha'),
(37, 'narod.yandex.ru', 'broken', 'http://narod.yandex.ru/404u.yhtml'),
(38, 'mediafire.com', 'broken', '/error.php?errno=320'),
(39, '4shared.com', 'works', '<span><span><font>Скачать сейчас</font>'),
(40, 'narod.yandex.ru', 'broken', 'Закончился срок хранения файла. Файл удален с сервиса.'),
(41, 'megaupload.com', 'works', '<div class="access_na_text"></div>'),
(42, '4shared.com', 'broken', 'Ссылка на запрашиваемый файл недействительна.'),
(43, 'ifolder.ru', 'broken', '</b> удален !!!</p>');

--
-- Структура таблицы `raw_logs`
--

CREATE TABLE IF NOT EXISTS `raw_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `raw_logs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `place` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `index` longtext COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `lastupdate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`place`,`item_id`),
  KEY `sortdate` (`sortdate`),
  FULLTEXT KEY `index` (`index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2528038 ;

--
-- Дамп данных таблицы `search`
--

INSERT INTO `search` (`id`, `place`, `item_id`, `index`, `area`, `sortdate`, `lastupdate`) VALUES
(2457848, 'orders', 11, '|РАБОТА=3|CARNELIAN=3|ДОБРЫЙ=1|ВРЕМЯ=1|СУТКИ=1|КАК=1|НАСТАТЬ=1|РАБОТА=1|CARNELIAN=1|NONE=2|ПРОЧИЙ=2|', 'main', 1240344516000, 1316040486),
(2477039, 'post', 14, '|SAVE=3|THE=3|GATE=3|КОРОТКИЙ=1|ДОДЗИНСИК=1|ТОХИЙ=1|ПРО=1|ПЕРСОНАЖ=1|ПО=1|ИМЯ=1|HONG=1|MEIL=1|РАБОТАТЬ=1|БЕССМЕННЫЙ=1|ДВЕРНОЙ=1|СТОРОЖ=1|В=1|ПОМЕСТЬЕ=1|SCARLET=1|ПОВЕСТВОВАНИЕ=1|ОПИСЫВАТЬ=1|ДЕНЬ=1|ИЗА=1|ЕЕ=1|ОБЫЧНЫЙ=1|ТРУДОВОЙ=1|БУДНИЙ=1|РИСОВАТЬ=1|MATSUKI=1|UGATSU=1|ИСТОРИЯ=1|ПИСАТЬ=1|ЧЕЛОВЕК=1|С=1|НИКТО=1|216=1|СКАЧАТЬ=1|HTTP=1|WWW=1|COM=1|UYJHDNNRTFY=1|MEDIAFIRE=1|ЛЮБИТЕЛЬСКИЙ=2|МАНГ=2|ДОДЗИНСЬ=2|同人誌=2|ДОДЖИНША=2|DOUJINSHI=2|MATSUKI=2|UGATSU=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|АСАЦУКИЙ=2|ДО=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|MANGA=2|МАНГ=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'main', 1226264414000, 1316065449),
(2454273, 'art', 29, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316035926),
(2454274, 'art', 2, '|ANIMAL=2|EAR=2|獣耳=2|けものみみ=2|KEMONOMIMUS=2|ГОЛУБОЙ=2|СИНИЙ=2|BLUE=2|EYE=2|GREEN=2|EYE=2|ЗЕЛЕНОЕ=2|ЗЕЛЁНЫЙ=2|ГЛАЗ=2|RIBBON=2|RIBBON=2|WEAPON=2|ОРУЖИЕ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274813489000, 1316035926),
(2454759, 'art', 58, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316036529),
(2454760, 'art', 83, '|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|GLASS=2|ОЧКО=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275221014000, 1316036529),
(2454767, 'art', 32, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316036644),
(2454815, 'art', 49, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316036645),
(2457858, 'orders', 7, '|23=3|И=3|24=3|ГЛАВА=3|МАНГ=3|MELTY=3|BLOOD=3|ЗДРАВСТВОВАТЬ=1|ЗНАТЬ=1|ГДЕ=1|НАЙТИ=1|23=1|24=1|ГЛАВА=1|ЕСЛИ=1|МОЖНО=1|ТОТ=1|БОЛЬШОЙ=1|МАНГ=1|MELTY=1|BLOOD=1|КОТОРЫЙ=1|ЯВ_СЯ=1|ПРОДОЛЖЕНИЕ=1|TSUKIHIME=1|НЕ=1|ОБЯЗАТЕЛЬНЫЙ=1|РУССКИЙ=1|ДА=1|ЕЕ=1|И=1|НЕТУ=1|НАВЕРНЯКА=1|А=1|ХОТЕТЬ=1|Б=1|НА=1|АНГЛИЙСКИЙ=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1238703902000, 1316040486),
(2477893, 'art', 88, '|DEVILMEN=2|ДЭВИЛМЭН=2|MAZINGER=2|МАЗИНГЕР=2|МЕХ=2|MERVISH=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275223420000, 1316066646),
(2457867, 'orders', 5, '|GUILTY=3|GEAR=3|ЧАСТЬ=3|XX=3|C=3|ПЕРЕВЕСТЬ=3|НА=3|АНГЛЫЙ=3|КОНФИГ=3|ЗДРАВСТВОВАТЬ=1|АДМИНИСТРАЦИЯ=1|ВЫРАЖАТЬ=1|СВОЙ=1|БЛАГОДАРНОСТЬ=1|ЗА=1|СОЗДАНИЕ=1|ЭТОТ=1|РЕСУРС=1|ОЧЕНЬ=1|СИМПАТИЧНЫЙ=1|ЮЗАБЕЛЬНО=1|И=1|ПРОСТОЙ=1|ПО_ЧЕЛОВЕЧЕСКИ=1|ХОТЕТЬСЯ=1|УВИДЕТЬ=1|ИГРА=1|ИЗА=1|СЕРИЯ=1|GUILTY=1|GEAR=1|ЧАСТЬ=1|XX=1|C=1|ПЕРЕВЕСТЬ=1|АНГЛЫЙ=1|КОНФИГ=1|ОРИГИНАЛЬНЫЙ=1|ЕКЗЕШНИКТО=1|Я=1|КАЧАТЬ=1|С=1|БУХТА=1|ТАКЖЕ=1|НА=1|ВАШ=1|РЕСУРС=1|ЕСТЬ=1|НОВЕЛЛА=1|FSN=1|НО=1|TSUKIHIME=1|ПЕРВЫЙ=1|РАБОТА=1|ТАЙПНУН=1|НЕТУ=1|НЕ=1|ПОРЯДОК=1|ССЫЛКА=1|К=1|СОЖАЛЕНИЕ=1|ПОТЕРЯТЬ=1|NONE=2|ПРОЧИЙ=2|', 'main', 1238361312000, 1316040606),
(2453624, 'art', 84, '|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275221337000, 1316035098),
(2482507, 'video', 13, '|ТОХИЙ=3|06=3|ЭСТРЫЙ=3|УРОВЕНЬ=3|УВЕЛИЧИТЬ=3|СЛОЖНОСТЬ=3|СОМНЕВАТЬСЯ=1|ЧТО=1|КТО_ТО=1|ИЗА=1|ЕВРОПЕЕЦ=1|СПОСОБНЫЙ=1|НА=1|ТАКОЙ=1|GAME=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|RAINCAT=1|GAME=2|ИГРА=2|', 'main', 1267451212000, 1316072526),
(2493702, 'video', 7, '|ЗАСТАВКА=3|К=3|ИГРА=3|11EYES=3|ЗАСТАВКА=1|К=1|11EYES=1|ОТ=1|СТУДИЯ=1|LASS=1|ИЛЛЮСТРАЦИЯ=1|В=1|ИГРА=1|ВЫПОЛНИТЬ=1|ТАКОЙ=1|ХУДОЖНИК=1|КАК=1|HAGIWARA=1|ONSEN=1|CHIKOTAM=1|OZAWA=1|KENGOU=1|NARUMUS=1|YUU=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|LUNATIC=1|TEAR=1|И=1|ИСПОЛНИТЬ=1|ПЕВИЦА=1|AYANE=1|11EYES=2|11ГЛАЗЫЙ=2|ELEVEN=2|EYE=2|イレブンアイズ=2|ОДИННАДЦАТЬ=2|ГЛАЗ=2|AYANE=2|彩音=2|LASS=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|OFEN=1|GAME=2|ИГРА=2|', 'flea_market', 1283692151830, 1316087050),
(2463826, 'orders', 8, '|АРТБУК=3|ПО=3|ИГРА=3|TALE=3|OF=3|DESTINY=3|2=3|А=1|СТАТЬ=1|КАК=1|НА=1|СЧЁТ=1|АРТБУК=1|ПО=1|ИГРА=1|DESTINY=1|2=1|TALE=1|OF=1|ETERNIA=1|NONE=2|ПРОЧИЙ=2|', 'main', 1238792650000, 1316048286),
(2481100, 'art', 85, '|GLASS=2|ОЧКО=2|MONOCHROME=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275221425000, 1316070727),
(2484514, 'news', 3, '|ОБЪЯВЛЕНИЕ=3|ТОРРЕНТ=3|4ОТАК=3|НАЙТИ=1|ИТАК=1|ПРИЯТНЫЙ=1|СЮРПРИЗ=1|ДОГРУЗИТЬСЯ=1|ОКАЗАТЬСЯ=1|БОЛЬШОЙ=1|ТОРРЕНТ=1|КОГДА_ЛИБО=1|ВЫКЛАДЫВАТЬСЯ=1|НАШ=1|САЙТ=1|86=1|ГИГАБАЙТ=1|С=1|ХВОСТИК=1|НАДЕЯТЬСЯ=1|УМЕТЬ=1|КАЧАТЬ=1|ЧАСТЬ=1|ПО=1|ЗАКАЗ=1|ПОЛЬЗОВАТЕЛЬ=1|СПИСОК=1|ФАЙЛ=1|ТОРРЕНТА=1|HTTP=1|4OTAKU=1|RU=1|PRINT=1|TXT=1|ДАННЫЙ=1|ТОРРЕНТ=1|НУЖНО=1|РАСПОСТРАНЯТЬ=1|ВЫ=1|ХОТЕТЬСЯ=1|СОВЕРШЕННЫЙ=1|ПРОТИВ=1|ЕЩЕ=1|РАЗ=1|ПОДЧЕРКИВАТЬ=1|РУНЕТ=1|ЯПОНИЯ=1|НАПРИМЕР=1|ЧАСТЬ=1|ВЫЛОЖИТЬ=1|ТУТ=1|МАТЕРИАЛ=1|УЗКИЙ=1|ПОДСУДНЫЙ=1|ДЕЛО=1|Я=1|ПОСТАРАТЬСЯ=1|ПРОВЕРИТЬ=1|РАССОРТИРОВАТЬ=1|ВСЕ=1|АРХИВ=1|ЕСЛИ=1|НЕСМОТРЯ=1|НА=1|МОЙ=1|УСИЛИЕ=1|ВЫ=1|НАЙХАТЬ=1|БИТЬ=1|ФАЙЛ=1|ИЛИ=1|МАТЕРИАЛ=1|ОКАЗАТЬСЯ=1|НЕ=1|ТОТ=1|РАЗДЕЛ=1|ГДЕ=1|ОНИ=1|ДОЛЖЕН=1|БЫТЬ=1|ПИСАТЬ=1|НАДО=1|ИСПРАВИТЬ=1|СЛЕДОВАТЬ=1|ТОРРЕНТА=1|ДА_ДА=1|ТОРРЕНТА=1|СО=1|ВСЕ=1|МАТЕРИАЛ=1|САЙТ=1|СТАТЬ=1|РЕГУЛЯРНЫЙ=1|ТРАДИЦИЯ=1|ПРИЯТНЫЙ=1|СКАЧИВАНИЕ=1|ВТОРОЙ=1|НОВОСТЬ=1|МЫ=1|ВЫНУЖДЕННЫЙ=1|ОТКАЗЫВАТЬСЯ=1|ОТ=1|БЫТЬ=1|РАННИЙ=1|ОСНОВНОЕ=1|АПЛОАДЕР=1|MEDIAFIRE=1|ХОТЕТЬ=1|ЗНАТЬ=1|КАКОЙ=1|ЗАМЕНА=1|БЫТЬ=1|БЫ=1|УДОБНЫЙ=1|ДЛИТЬ=1|ВСЕ=1|ПРЕДЛАГАТЬ=1|И=1|ОБСУЖДАТЬ=1|ВАРИАНТА=1|МОЖНО=1|ОПЯТЬ=1|ЖЕ=1|В=1|КОММЕНТАРИЙ=1|К=1|ЭТОТ=1|ЗАПИСЬ=1|РАЗДАЧА=1|ТОРРЕНТА=1|ПРЕКРАТИТЬ=1|', 'main', 1247342448000, 1316075168),
(2454960, 'art', 54, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316036884),
(2461449, 'video', 10, '|ДЕМО_ВИДЕО=3|К=3|ИГРА=3|CHAOS=3|HEAD=3|LOVE=3|CHU=3|ДЛИТЬ=3|XBOX360=3|ДЕМО_ВИДЕО=1|К=1|ИГРА=1|CHAOS=1|HEAD=1|LOVE=1|CHU=1|ДЛИТЬ=1|XBOX360=1|ОТ=1|СТУДИЯ=1|NITROPLUS=1|NITRO=1|ИЛЛЮСТРАЦИЯ=1|ВЫПОЛНИТЬ=1|ХУДОЖНИК=1|SASAKI=1|MUTSUMUS=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|SYNCHRO=1|SHIYOUYO=1|И=1|ИСПОЛНИТЬ=1|ПЕВИЦА=1|NAO=1|5PB=2|CHAOS=2|カオスヘッド=2|ХАОС=2|ВЕРШИНА=2|CHäO=2|HEAD=2|NAO=2|NITROPLUS=2|NITRO=2|ニトロプラス=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|ВИЗУАЛЬНЫЙ=2|VISUAL=2|NOVEL=2|ВИЗУАЛЬНЫЙ=2|ГРАФИЧЕСКИЙ=2|НОВЕЛЛА=2|ГРАФИЧЕСКИЙ=2|РОМАНА=2|ビジュアルノベル=2|VN=2|OFEN=1|GAME=2|ИГРА=2|', 'main', 1267287783000, 1316045164),
(2478035, 'art', 65, '|TOSHIAKI=2|TAKAYAMA=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275153513000, 1316066768),
(2454975, 'art', 26, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316036884),
(2464223, 'orders', 10, '|3_D=3|ФАЙТИНГ=3|НАПОДОБИЕ=3|CRUCIS=3|FATAL=3|FAKE=3|ДОБРЫЙ=1|ВРЕМЯ=1|СУТКИ=1|ХОТЕТЬСЯ=1|БЫ=1|УВИДЕТЬ=1|3_D=1|ФАЙТИНГ=1|НАПОДОБИЕ=1|CRUCIS=1|FATAL=1|FAKE=1|NONE=2|ПРОЧИЙ=2|', 'main', 1240347062000, 1316048770),
(2484515, 'news', 2, '|ОБЪЯВЛЕНИЕ=3|КАРБЮРАТОР=3|И=3|ПРИГЛАШЕНИЕ=3|КАРБЮРАТОР=1|ОДИН=1|ИЗА=1|НАШ=1|ПОСТОЯННЫЙ=1|АВТОР=1|ЗАНИМАТЬСЯ=1|ПЕРЕВОД=1|ДОДЗИНСЬ=1|РУССКИЙ=1|ОН=1|ЗАВЕТЬ=1|СЕБЯ=1|ПЕРЕВОДЧИК=1|ГДЕ=1|БЫТЬ=1|ВЫКЛАДЫВАТЬ=1|СВОЙ=1|ПЕРЕВЕСТЬ=1|РАБОТА=1|НАХОДИТЬСЯ=1|ТУТ=1|HTTP=1|RAINCAT=1|4OTAKU=1|RSS=1|ПРИГЛАШЕНИЕ=1|КАК=1|ВЫ=1|МОЧЬ=1|ЗАМЕТИТЬ=1|ОНО=1|РАСПОЛАГАТЬСЯ=1|СУБДОМЕН=1|4ОТАК=1|ЭТОТ=1|СЛУЧАЙНЫЙ=1|ПРЕДОСТАВИТЬ=1|МЕСТО=1|И=1|ДАЖЕ=1|СДЕЛАТЬ=1|НЕСЛОЖНЫЙ=1|ВСЕ=1|ЖЕЛАТЬ=1|ПРЯ=1|ОДИН=1|УСЛОВИЕ=1|ВАШ=1|БЛОГ=1|САЙТ=1|ДОЛЖЕН=1|БЫТЬ=1|ПОСВЯТИТЬ=1|ПРОИЗВОДСТВО=1|ЧТО_ТО=1|ИНТЕРЕСНЫЙ=1|ПОЛЕЗНЫЙ=1|ОТАК=1|ДЛИТЬ=1|ПРИМЕР=1|МЫ=1|РАДОСТЬ=1|ПРИНЯТЬ=1|ХУДОЖНИК=1|ПЕРЕВОДЧИК=1|КОСПЛЕЕР=1|С=1|ФОТОГРАФИЯ=1|СОЗДАТЕЛЬ=1|ИГРА=1|ФЛЕШ_РОЛИК=1|ИЛИ=1|МАНГ=1|ЕСЛИ=1|ЕСТЬ=1|ЖЕЛАНИЕ=1|ТОТ=1|ПИСАТЬ=1|NONAME_NYA=1|YANDEX=1|RU=1|ОБСУДИТЬ=1|МОЖНО=1|ЕЩЕ=1|НА=1|ВСЯКИЙ=1|СЛУЧАТЬ=1|ЧТОБЫ=1|ВЫ=1|НЕ=1|ПОТЕРЯТЬ=1|ОТПИСАТЬ=1|В=1|КОММЕНТАРИЙ=1|К=1|ЭТОТ=1|ЗАПИСЬ=1|', 'main', 1251403635000, 1316075168),
(2481254, 'art', 80, '|BOOK=2|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275220308000, 1316070965),
(2455019, 'art', 27, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316036885),
(2489843, 'video', 14, '|ВТОРОЙ=3|ЗАСТАВКА=3|К=3|ИГРА=3|MURAMASA=3|ВТОРОЙ=1|ЗАСТАВКА=1|К=1|ИГРА=1|ОТ=1|СТУДИЯ=1|NITROPLUS=1|NITRO=1|ИЛЛЮСТРАЦИЯ=1|ВЫПОЛНИТЬ=1|ХУДОЖНИК=1|NAMANIKU=1|ATK=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|MURAMASA=1|И=1|ИСПОЛНИТЬ=1|ПЕВЕЦ=1|ONO=1|MASATOSHI=1|NITROPLUS=2|NITRO=2|ニトロプラス=2|ONO=2|MASATOSHI=2|小野正利=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|ВИЗУАЛЬНЫЙ=2|VISUAL=2|NOVEL=2|ВИЗУАЛЬНЫЙ=2|ГРАФИЧЕСКИЙ=2|НОВЕЛЛА=2|ГРАФИЧЕСКИЙ=2|РОМАНА=2|ビジュアルノベル=2|VN=2|OFEN=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1283692205580, 1316082128),
(2489849, 'video', 9, '|ПРОМО_ВИДЕО=3|К=3|ИГРА=3|11EYES=3|CROSSOVER=3|ДЛИТЬ=3|PSP=3|ЗАСТАВКА=1|К=1|11EYES=1|ДЛИТЬ=1|PSP=1|ОТ=1|СТУДИЯ=1|LASS=1|ИЛЛЮСТРАЦИЯ=1|В=1|ИГРА=1|ВЫПОЛНИТЬ=1|ТАКОЙ=1|ХУДОЖНИК=1|КАК=1|HAGIWARA=1|ONSEN=1|CHIKOTAM=1|YOU=1|OZAWA=1|NARUMUS=1|YUU=1|KENGOU=1|ZEN=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|SHINJITSU=1|HE=1|NO=1|CHINKONKA=1|И=1|ИСПОЛНИТЬ=1|ПЕВИЦА=1|AYANE=1|11EYES=2|11ГЛАЗЫЙ=2|ELEVEN=2|EYE=2|イレブンアイズ=2|ОДИННАДЦАТЬ=2|ГЛАЗ=2|5PB=2|AYANE=2|彩音=2|LASS=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|ПРОМО_ВИДЕО=2|PV=2|PROMO=2|PROMOTION=2|VIDEO=2|プロモーションビデオ=2|OFEN=1|GAME=2|ИГРА=2|', 'main', 1267286520000, 1316082128),
(2462658, 'orders', 1, '|ВСЕ=3|РАНОБА=3|KARA=3|NO=3|KYOUKAI=3|НА=3|АНГЛИЙСКИЙ=3|САМ=1|СМОЧЬ=1|НАЙТИ=1|ТОЛЬКО=1|ПЕРВЫЙ=1|ГЛАВА=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1237412696000, 1316046735),
(2481720, 'art', 64, '|TOSHIAKI=2|TAKAYAMA=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275153368000, 1316071568),
(2464231, 'orders', 9, '|MP3=3|РУССКАЯ=3|ВЕРСИЯ=3|ОПЕНИНГ=3|ХАРУХ=3|ЗДРАВСТВОВАТЬ=1|ОЧЕНЬ=1|БЫ=1|ХОТЕТЬСЯ=1|СКАЧАТЬ=1|РУССКИЙ=1|ОПЕНИНГ=1|ИЗА=1|АНИМ=1|МЕЛАНХОЛИЯ=1|ХАРУХ=1|СУДЗУМИЕ=1|НО=1|НЕ=1|КЛИП=1|А=1|В=1|ФОРМАТ=1|MP3=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1239739959000, 1316048890),
(2454610, 'art', 43, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274980936000, 1316036407),
(2485923, 'video', 6, '|КЛИП=3|К=3|ПЕСНЯ=3|HIIRO=3|GEKKA=3|KURUIZAKI=3|NO=3|ZETSU=3|1ST=3|ANNIVERSARY=3|REMIX=3|С=3|РУССКАЯ=3|СУБТИТР=3|КЛИП=1|К=1|ПЕСНЯ=1|HIIRO=1|GEKKA=1|KURUIZAKI=1|NO=1|ZETSU=1|1ST=1|ANNIVERSARY=1|REMIX=1|ОТ=1|ЛЮБИТЕЛЬСКИЙ=1|КРУГ=1|EASTNEWSOUND=1|РУССКАЯ=1|СУБТИТР=1|ВЫПОЛНИТЬ=1|RAINCAT=1|EASTNEWSOUND=2|J=2|ЯПОНСКИЙ=2|МУЗЫКА=2|ДЖЕЙ_ПОП=2|ДЖЕЙ=2|ПОПА=2|ジェイポップ=2|JPOP=2|JAPANESE=2|POP=2|MUSIC=2|J_POP=2|ポップ音楽=2|ポップ=2|ミュージック=2|ПРОМО_ВИДЕО=2|PV=2|PROMO=2|PROMOTION=2|VIDEO=2|プロモーションビデオ=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|OFEN=1|NONE=2|ПРОЧИЙ=2|', 'main', 1267277762000, 1316077088),
(2455432, 'art', 52, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316037489),
(2456077, 'art', 55, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316038207),
(2454633, 'art', 17, '|YUKIRIN=2|W8M=1|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'main', 1274956439000, 1316036407),
(2462673, 'orders', 15, '|ОРИГИНАЛЬНЫЙ=3|ИГРА=3|ХИГУРАШ=3|КТО_ТО=1|ГОВОРИТЬ=1|ПРО=1|ОРИГИНАЛЬНЫЙ=1|ИГРА=1|ПОСЕМУ=1|РЕКВЕСТИРОВАТЬ=1|ХИГУРАШ=1|И=1|БОЛЬШОЙ=1|NONE=2|ПРОЧИЙ=2|', 'main', 1242072475000, 1316046844),
(2455506, 'art', 42, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274980936000, 1316037608),
(2455510, 'art', 37, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316037608),
(2485545, 'news', 10, '|СКАЧИВАНИЕ=3|БОЛЬШОЙ=3|ФАЙЛ=3|ВСЁ_ТАКИ=1|ВРЕМЯ=1|ВРЕМЯ=1|ПОЯВЛЯТЬСЯ=1|НЕОБХОДИМОСТЬ=1|ДЕЙСТВИТЕЛЬНЫЙ=1|БОЛЬШОЙ=1|ФАЙЛ=1|ВОЗРАСТАТЬ=1|ОПАСНОСТЬ=1|СВЯЗЬ=1|ПРЕРВЕТСЯ=1|ПРОЦЕСС=1|ПРИСТИСЬ=1|НАЧАТЬ=1|СНАЧАЛА=1|РЕШЕНИЕ=1|ЭТОТ=1|СЛУЧАЙ=1|ПРОСТОЙ=1|ЭТОТ=1|СПЕЦИАЛЬНЫЙ=1|КОТОРЫЙ=1|ПОЗВОЛЯТЬ=1|УВЕЛИЧИТЬ=1|СКОРОСТЬ=1|СКАЧИВАНИЕ=1|ВОССТАНОВИТЬ=1|ПРЕРВАТЬ=1|ЗАКАЧКА=1|НАЗЫВАТЬСЯ=1|ДАУНЛОДЕР=1|АНГЛИЙСКИЙ=1|СКАЧИВАТЬ=1|СГРУЖАТЬ=1|ДАЖЕ=1|УВЕРЕННЫЙ=1|КАЧЕСТВО=1|СВОЙ=1|СВЯЗЬ=1|РЕКОМЕНДОВАТЬ=1|ВСЕ_ТАКИ=1|ОДИН=1|ЭТОТ=1|ОНИ=1|СОВЕРШЕННЫЙ=1|БЕСПЛАТНЫЙ=1|ОБЛАДАТЬ=1|НАПРИМЕР=1|ТАКОЙ=1|ПОЛЕЗНЫЙ=1|ФУНКЦИЯ=1|КАК=1|СКАЧИВАНИЕ=1|ФАЙЛ=1|ПАУЗА=1|ПОСЛЕДУЮЩИЙ=1|БЕЗБОЛЕЗНЕННЫЙ=1|ВОССТАНОВЛЕНИЕ=1|ВЫБОР=1|ЗАВИСЕТЬ=1|ОСНОВНОЕ=1|ВАШ=1|ОПЕРАЦИОННЫЙ=1|СИСТЕМА=1|ЛЮБИТЬ=1|БРАУЗЕР=1|ПРЕДПОЧИТАТЬ=1|MOZILLA=1|СОВЕТОВАТЬ=1|ПОСТАВИТЬ=1|КОТОРЫЙ=1|ЯВЛЯТЬСЯ=1|ВСТРАИВАТЬ=1|ПЛАГИН=1|ТРЕБОВАТЬ=1|СЛОЖНЫЙ=1|УСТАНОВКА=1|ПОСЛЕДНИЙ=1|МОЖНО=1|ДОСТАТЬ=1|ОФИЦИАЛЬНЫЙ=1|САЙТ=1|WWW=1|FLASHGOT=1|NET=1|ЗАХОТЕТЬ=1|РАСШИРИТЬ=1|ОНО=1|БАЗОВЫЙ=1|ФУНКЦИОНАЛЬНОСТЬ=1|ДОПОЛНИТЕЛЬНЫЙ=1|УСТАНОВИТЬ=1|ПРОГРАММА=1|КОТОРЫЙ=1|РАБОТАТЬ=1|С=1|ОНО=1|ПАР=1|ИЗА=1|ВОЗМОЖНЫЙ=1|ВАРИАНТ=1|ЕСТЬ=1|BITCOMET=1|FLASHGET=1|ОБРАЩАТЬ=1|ВАШ=1|ВНИМАНИЕ=1|ТОТ=1|ЧТО=1|ВСЕ=1|ВЫШЕПЕРЕЧИСЛЕННЫЙ=1|САЙТ=1|ОНИ=1|ПРОГРАММА=1|АНГЛИЙСКИЙ=1|ЯЗЫК=1|ДЛИТЬ=1|ПОЛЬЗОВАТЕЛЬ=1|ЛЮБИТЬ=1|FIREFOX=1|ПОЛЬЗОВАТЬСЯ=1|ДРУГОЙ=1|БРАУЗЕР=1|ПОДХОДИТЬ=1|РЕШЕНИЕ=1|БЫТЬ=1|DOWNLOAD=1|MASTER=1|ОТЛИЧНЫЙ=1|РАБОТАТЬ=1|БЕЗ=1|ИНТЕГРАЦИЯ=1|БРАУЗЕР=1|ОН=1|ОТЛИЧИЕ=1|ОТ=1|ПРИВЕСТЬ=1|ВЫСОКИЙ=1|ПРОГРАММА=1|РУСИФИЦИРОВАТЬ=1|ЕСЛИ=1|ПОЛЬЗОВАТЬСЯ=1|НЕ=1|WINDOW=1|А=1|UNIX_СИСТЕМА=1|ТОГДА=1|МЫ=1|МОЧЬ=1|ПОРЕКОМЕНДОВАТЬ=1|ВЫ=1|DOWNLOADER=1|FOR=1|X=1|ВЫ=1|МОЧЬ=1|СКАЧАТЬ=1|ПРОВЕРИТЬ=1|НА=1|ВИРУС=1|И=1|ВСЕГДА=1|ДОСТУПНЫЙ=1|У=1|МЫ=1|ПО=1|ЭТОТ=1|ССЫЛКА=1|ИЛИ=1|ПОИСКАТЬ=1|В=1|ИНТЕРНЕТ=1|СВЕЖИЙ=1|ВЕРСИЯ=1|САМОСТОЯТЕЛЬНЫЙ=1|НАЖАТЬ=1|СЮДА=1|', 'main', 1000, 1316076490),
(2484519, 'news', 7, '|ОБЪЯВЛЕНИЕ=3|ПЛАН=3|И=3|ПРОБЛЕМА=3|БЛИЗКИЙ=1|ПЛАН=1|СЕРЬЕЗНЫЙ=1|ОБНОВЛЕНИЕ=1|ФУНКЦИОНАЛ=1|ЕСТЬ=1|НОВЫЙ=1|ВЕСЬМА=1|НУЖНЫЙ=1|ОБЛАСТЬ=1|ЕЕ=1|РАБОЧИЙ=1|МАСТЕРСКОЙ=1|СВОЙ=1|ЗАЙТИ=1|ПО=1|АДРЕС=1|HTTP=1|4OTAKU=1|RU=1|ADD=1|НАЖАТЬ=1|КНОПКА=1|МАТЕРИАЛ=1|ВВЕРХУ=1|ПРЯ=1|ЭТОТ=1|ЗАПОЛНИТЬ=1|МНОЖЕСТВО=1|ОТ=1|ОПИСАНИЕ=1|ТЕГ=1|ПЛЮС=1|ДОЖДАТЬСЯ=1|МОДЕРАТОР=1|КОТОРЫЙ=1|ОДОБРИТЬ=1|ДОБАВИТЬ=1|ВЫ=1|СОГЛАСИТЬСЯ=1|САМЫЙ=1|УДОБНЫЙ=1|СХЕМА=1|ХОТЕТЬ=1|ПРЕВРАТИТЬ=1|ИЗНАЧАЛЬНЫЙ=1|ПЛАНИРОВАТЬСЯ=1|МЕСТО=1|ГДЕ=1|ПОСЕТИТЕЛЬ=1|ОБМЕНИВАТЬСЯ=1|РЕДКИЙ=1|ИНТЕРЕСНЫЙ=1|ДИКОВИНКА=1|ПРОЯПОНСКИЙ=1|НАПРАВЛЕННОСТЬ=1|ТОТ=1|ЭТОТ=1|ПРОЦЕДУРА=1|УПРОСТИТЬ=1|СДЕЛАТЬ=1|НЕОДОБРИТЬ=1|ЕЩЕ=1|ЗАПИСЬ=1|НОВЫЙ=1|МОЖНО=1|ПРЯМОЙ=1|УХОДИТЬ=1|СО=1|СТРАНИЦА=1|ВОСПОЛЬЗОВАТЬСЯ=1|ВЫЕЗЖАТЬ=1|ФОРМА=1|ПРИЧ=1|НЕОБХОДИМЫЙ=1|МИНИМУМ=1|ПОЛИТЬ=1|БЫТЬ=1|ВКЛЮЧАТЬ=1|СЕБЯ=1|ТОЛЬКО=1|НАЗВАНИЕ=1|ОДИН=1|ССЫЛКА=1|ХОТЕТЬ=1|БЫ=1|ОДИН=1|ТОРРЕНТ_ФАЙЛ=1|МНОГО=1|НЕОБЯЗАТЕЛЬНЫЙ=1|ЖЕСТКО=1|СОБЛЮДАТЬ=1|ТЕМАТИКА=1|ДОСТАТОЧНО=1|ИЗБЕГАТЬ=1|СОВСЕМ=1|ЧУЖЕРОДНЫЙ=1|МАТЕРИАЛ=1|ЗАПРЕЩЕННАЯ=1|УК=1|РФ=1|ЗАЧЕМ=1|НУЖНЫЙ=1|ТАКОЙ=1|НЕПОЛНЫЙ=1|СПРОСИТЬ=1|ДЕЛО=1|ТАМ=1|ЖЕ=1|МАСТЕРСКОЙ=1|ЛЮБОЙ=1|ЖЕЛАТЬ=1|СМОЧЬ=1|ОТРЕДАКТИРОВАТЬ=1|ЛЮБАЯ=1|ЗАПИСЬ=1|ДОБАВИТЬ=1|КАРТИНКА=1|ТЕГ=1|ОПИСАНИЕ=1|ДОПОЛНИТЕЛЬНЫЙ=1|ССЫЛКА=1|ДАЛЕЕ=1|КАК=1|СКАЗАТЬ=1|ВИКИПЕДИЯ=1|ТАК=1|ДОПОЛНЯТЬ=1|СОДЕРЖАТЬ=1|ЗНАКОМАЯ=1|МАТЕРИАЛ=1|ИЛИ=1|ПОНРАВИТЬСЯ=1|ВЫ=1|ЗАПИСЬ=1|ВЫ=1|СМОЧЬ=1|ДОВЕСТИ=1|ОНИ=1|СОСТОЯНИЕ=1|КОГДА=1|ОНИ=1|СМОЧЬ=1|ПОПАСТЬ=1|ГЛАВНЫЙ=1|ЭТОТ=1|БЫТЬ=1|БЛИЗКИЙ=1|ПЛАН=1|ХОРОШИЙ=1|НУЖНЫЙ=1|ВПОЛНЕ=1|РЕАЛИЗОВАТЬ=1|СМОТРЕТЬ=1|СЛЕДОВАТЬ=1|ПУНКТ=1|ПРОБЛЕМА=1|БЕДА=1|ТОТ=1|ЧТО=1|ДЛИТЬ=1|ТОТ=1|ЧТОБЫ=1|СДЕЛАТЬ=1|ДОСТОЙНЫЙ=1|НЕПРОСТАИВАТЬ=1|НОВЫЙ=1|РАЗДЕТЬ=1|НУЖНО=1|ДВА=1|ВЕЩЬ=1|ФУНКЦИОНАЛ=1|ХОРОШИЙ=1|ДИЗАЙН=1|САЙТ=1|ДО=1|СЕЙ=1|ПОРА=1|РАЗВИВАТЬ=1|В=1|ТЕХНИЧЕСКИЙ=1|СМЫСЛ=1|ДВОЕ=1|ЧЕЛОВЕК=1|Я=1|ПРОГРАММИСТ=1|АДМИНИСТРАТОР=1|И=1|НО=1|СЕЙЧАС=1|ДИЗАЙНЕР=1|ИЗ_ЗА=1|СВОЙ=1|ЛИЧНОЙ=1|ТРУДНОСТЬ=1|ВЫБЫВАТЬ=1|ИЗА=1|РАБОТА=1|ДИЗАЙН=1|НОВОЕ=1|РАЗДЕТЬ=1|НЕТ=1|РАЗВИТИЕ=1|ПОЭТОМУ=1|ЕСЛИ=1|НИКТО=1|НЕ=1|ВЫЗВАТЬСЯ=1|ОНО=1|ВРЕМЕННОЙ=1|ЗАМЕНА=1|РАЗВИТИЕ=1|САЙТ=1|ЗАТОРМОЗИТЬСЯ=1|НА=1|НЕСКОЛЬКО=1|МЕСЯЦ=1|', 'main', 1246910452000, 1316075169),
(2480082, 'art', 77, '|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275219583000, 1316069527),
(2454695, 'art', 53, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316036528),
(2484522, 'news', 9, '|ОБЪЯВЛЕНИЕ=3|НОВЫЙ=3|4ОТАК=3|15=3|УДОБНЫЙ=3|НА=3|20=3|КАВАЙНЯ=3|СДЕЛАТЬ=1|ПОДОЙТИ=1|WORDPRESS_Е=1|БЫТЬ=1|ОТЛИЧНЫЙ=1|БЫСТРЫЙ=1|НАЧАЛО=1|НО=1|СО=1|ВРЕМЯ=1|НЕМОЙ=1|СТАТЬ=1|ТЕСНЫЙ=1|ИНДИВИДУАЛЬНЫЙ=1|СОБРАТЬ=1|ДВИЖОК=1|ВСЕГДА=1|ПОДОЙДЕТ=1|УЖ=1|ПРИГЛЯНУТЬСЯ=1|ЧЕЛОВЕК=1|ТОТ=1|МОЖНО=1|ПОТРАТИТЬ=1|ВРЕМЯ=1|ОНО=1|НАПИСАНИЕ=1|ПОСКОЛЬКУ=1|САЙТ=1|ПОЛНОСТЬЮ=1|ТЕСТИРОВАТЬСЯ=1|НАТКНУТЬСЯ=1|НЕДОЛОВИТЬ=1|БАГ=1|ИЛИ=1|ПРОСТОЙ=1|НЕДОТАТЬ=1|ВЕРСТКА=1|ЭТОТ=1|ПРОИЗОЙДЕТ=1|ОТПИСАТЬСЯ=1|ПОЖАЛУЙСТА=1|ЗДЕСЬ=1|КОММЕНТАРИЙ=1|ЧТО=1|РАННИЙ=1|ВСЕ=1|ГЛЮК=1|ПЕРЕЛОВИТЬ=1|ПЕРЕДАВИТЬ=1|ТОТ=1|ХОРОШО=1|РАЗВИТИЕ=1|САЙТ=1|ЭТОТ=1|НИ=1|КОЙ=1|СЛУЧАЙ=1|НЕ=1|ОСТАНАВЛИВАТЬСЯ=1|ЕЩЕ=1|МНОГО=1|ОБНОВЛЕНИЕ=1|НОВОЕ=1|МОДУЛЬ=1|ЖДАТЬ=1|НЕДАЛЕК=1|БУДУЩЕЕ=1|ОБЕЩАТЬ=1|ОБНОВЛЕНИЕ=1|ИМИДЖБОРД=1|ТОЖЕ=1|ОБЯЗАТЕЛЬНЫЙ=1|ДОБРАТЬСЯ=1|ЯКУМО=1|ЮКАРЬ=1|ТОХИЙ=1|НАШ=1|МАСКОТ=1|ПРОСИТЬ=1|ЛЮБИТЬ=1|ЖАЛОВАТЬ=1|КРАСОВАТЬСЯ=1|ВСЕ=1|ОФИЦИАЛЬНЫЙ=1|ОБЪЯВЛЕНИЕ=1|ШАПКА=1|САМЫЙ=1|НЕОЖИДАННЫЙ=1|МЕСТО=1|САЙТ=1|ПОЖАЛОВАТЬ=1|ГЛАВНОЕ=1|ОБНОВЛЕНИЕ=1|СТАТЬ=1|МАСТЕРСКОЙ=1|ЕСЛИ=1|У=1|ВЫ=1|ЕСТЬ=1|ПРОВЕРИТЬ=1|ССЫЛКА=1|СКАЧОК=1|МАТЕРИАЛ=1|ИНТЕРЕСНЫЙ=1|ОТАК=1|ВЫ=1|ЛЁГКИЙ=1|МОЧЬ=1|ЕЕ=1|ОПУБЛИКОВАТЬ=1|ОНА=1|ПОПАДЕТ=1|БЕЗ=1|ВСЯКИЙ=1|ПРЕМОДЕРАЦИЯ=1|МАСТЕРСКОЙ=1|И=1|ЧТО=1|ГЛАВНОЕ=1|МАТЕРИАЛ=1|НАХОДИТЬСЯ=1|В=1|МОЧЬ=1|ДОРАБАТЫВАТЬ=1|ЛЮБОЙ=1|ЖЕЛАТЬ=1|ДОВОДИТЬ=1|ДО=1|СОСТОЯНИЕ=1|ПОЛНОЦЕННЫЙ=1|ЗАПИСЬ=1|4ОТАК=1|САМЫЙ=1|ВКУСНЫЙ=1|ИЗА=1|МАСТЕРСКОЙ=1|БЫТЬ=1|ПЕРЕНОСИТЬСЯ=1|МОДЕРАТОР=1|НА=1|ГЛАВНЫЙ=1|СТРАНИЦА=1|', 'main', 1253045256000, 1316075169),
(2481108, 'art', 78, '|BOOK=2|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275220308000, 1316070727),
(2452416, 'video', 5, '|КЛИП=3|К=3|ПЕСНЯ=3|BID=3|APPLE=3|С=3|РУССКАЯ=3|СУБТИТР=3|КЛИП=1|К=1|ПЕСНЯ=1|BID=1|APPLE=1|ОТ=1|ЛЮБИТЕЛЬСКИЙ=1|КРУГ=1|ALSTROEMER=1|RECORD=1|РУССКАЯ=1|СУБТИТР=1|ВЫПОЛНИТЬ=1|RAINCAT=1|ALSTROEMER=2|RECORD=2|BID=2|APPLE=2|J=2|ЯПОНСКИЙ=2|МУЗЫКА=2|ДЖЕЙ_ПОП=2|ДЖЕЙ=2|ПОПА=2|ジェイポップ=2|JPOP=2|JAPANESE=2|POP=2|MUSIC=2|J_POP=2|ポップ音楽=2|ポップ=2|ミュージック=2|ПРОМО_ВИДЕО=2|PV=2|PROMO=2|PROMOTION=2|VIDEO=2|プロモーションビデオ=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|OFEN=1|NONE=2|ПРОЧИЙ=2|', 'main', 1267277283000, 1316033528),
(2476596, 'post', 12, '|M=3|D_КЛИП=3|LUNAFLOWER=3|ПО=3|TOUHOU=3|КРАСИВЫЙ=1|ПОСВЯТИТЬ=1|M=1|D=1|ОТ=1|LIGHTPI=1|ПОСВЯЩЕННЫЙ=1|ВСЕЛЕННАЯ=1|ТОХИЙ=1|ОНА=1|ПЕРСОНАЖ=1|БОЛЬШОЙ=1|ЧАСТЬ=1|ИЗА=1|ТОТ=1|МОЖНО=1|ВИДЕТЬ=1|В=1|ЭТОТ=1|ВИДЕО=1|НАРИСОВАТЬ=1|САМ=1|АВТОР=1|ЧТО=1|НЕ=1|ДЕЛАТЬ=1|ОНО=1|МАЛО=1|КРАСИВЫЙ=1|И=1|АТМОСФЕРНЫЙ=1|СКАЧАТЬ=1|LUNAFLOWER=1|HTTP=1|WWW=1|COM=1|ZXM2ETYZZOE=1|MEDIAFIRE=1|LIGHTPI=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|АМВ=2|АМV=2|AMV=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|VIDEO=2|ВИДЕО=2|JAPANESE=2|ЯПОНСКИЙ=2|', 'main', 1226178012000, 1316064850),
(2471199, 'orders', 6, '|АРТБУК=3|ПО=3|GUILTY=3|GEAR=3|А=1|АРТБУК=1|ПО=1|GUILTY=1|GEAR=1|СУЩЕСТВОВАТЬ=1|NONE=2|ПРОЧИЙ=2|', 'main', 1238618317000, 1316057890),
(2487655, 'art', 61, '|ЖЁЛТЫЙ=2|ARMOR=2|ДОСПЕХ=2|БРОНЯ=2|ДОСПЕХ=2|CLARE=2|CLAYMORE=2|HIGHRE=2|HIRE=2|HI=2|RES=2|HI_RE=2|SWORD=2|SWORD=2|МЕЧ=2|МЕТАТЬ=2|ОБОИ=2|WALLPAPER=2|WP=2|壁紙=2|WALLPAPER=2|ウォールペーパー=2|カベカミ=2|WEAPON=2|ОРУЖИЕ=2|WHITE=2|HAIR=2|БЕЛЫЙ=2|ВОЛОС=2|YELLOW=2|EYE=2|ЖЕЛТЫЙ=2|ГЛАЗ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275045122000, 1316079287),
(2462685, 'orders', 12, '|ИГРУШКО=3|WORD=3|WORTH=3|ИСКАТЬ=1|ЕЕ=1|ИГРУШКО=1|WORD=1|WORTH=1|ПО=1|ТЕХНИЧЕСКИЙ=1|ПРИЧИНА=1|НЕ=1|МОЧЬ=1|СКАЧАТЬ=1|ОНА=1|С=1|ТОРРЕНТ=1|ЗАЛИТЬ=1|КТО=1|НИБУДЬ=1|ПЖАЛСТЫЙ=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1240434190000, 1316046844),
(2482202, 'art', 60, '|TOSHIAKI=2|TAKAYAMA=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275044911000, 1316072164),
(2455544, 'art', 25, '|AMANO=2|AI=2|W8M=1|PHOTO=2|ФОТО=2|', 'main', 1274957116000, 1316037609),
(2463055, 'art', 4, '|3GIRL=2|AYANAMUS=2|REUS=2|АЯНА=2|РЭЙ=2|BLUE=2|СИНИЙ=2|ГОЛУБОЙ=2|GLASS=2|ОЧКО=2|MARI=2|ILLUSTRIOUS=2|MAKINAMUS=2|NEON=2|GENESIS=2|EVANGELION=2|ЕВАНГЕЛИОНА=2|新世紀エヴァンゲリオン=2|NGE=2|EYE=2|ГЛАЗ=2|EYE=2|RED=2|HAIR=2|КРАСНЫЙ=2|ВОЛОС=2|SOURYUU=2|СОРЬЕ=2|ЛЭНГЛИ=2|АСК=2|SORYU=2|SHIKINAMUS=2|ASUKA=2|LANGLEY=2|SWIMSUIT=2|КУПАЛЬНИК=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274815337000, 1316047326),
(2462686, 'orders', 4, '|МАНГ=3|EUREKA=3|7=3|ТОВАРИЩ=1|ЕСТЬ=1|ЛИ=1|ПОЛНОСТЬЮ=1|ПЕРЕВЕДЁНЫЙ=1|МАНГ=1|EUREKA=1|7=1|ДОСТАНЬЕ=1|ПОЖАЛУЙСТА=1|ДАЖЕ=1|ЕСЛИ=1|ОНА=1|НЕ=1|ПЕРЕВЕСТИ=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1238361661000, 1316046844),
(2481547, 'art', 73, '|ЖЁЛТЫЙ=2|2GIRL=2|AHOGE=2|BLONDE=2|СВЕТЛЫЙ=2|カナン=2|ХАНААН=2|CANAAN=2|CHARACTER=2|OOSAWA=2|大沢マリア=2|OSAWA=2|MARIA=2|ОСАВА=2|МАРИЯ=2|WHITE=2|HAIR=2|БЕЛЫЙ=2|ВОЛОС=2|YELLOW=2|EYE=2|ЖЕЛТЫЙ=2|ГЛАЗ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275161963000, 1316071326),
(2455552, 'art', 59, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316037609),
(2459266, 'orders', 2, '|МАНГ=3|PLASTIC=3|LITTLE=3|УЗКИЙ=1|ЕЕ=1|ПИСАТЬ=1|КОМЕНТ=1|НО=1|ОТПИСАТЬ=1|ЗДЕСЬ=1|МАНГ=1|PLASTIC=1|LITTLE=1|ХОТЕТЬ=1|ЕСТЬ=1|ЛИ=1|ОНА=1|РУССКИЙ=1|В=1|ЭЛЕКТРОННЫЙ=1|ВИД=1|ВООБЩЕ=1|ВСЁ_ТАКИ=1|ОНА=1|БЫТЬ=1|ОФИЦИАЛЬНЫЙ=1|ПЕРЕВЕСТИ=1|ВЫПУСТИТЬ=1|НА=1|РОССИЙСКИЙ=1|РЫНОК=1|ВПРИНЦИП=1|И=1|АНГЛИЙСКИЙ=1|ПЕРЕВОД=1|ТОЖЕ=1|ХОРОШО=1|БЫ=1|ЕСЛИ=1|НАЙТИ=1|БЫСТРЫЙ=1|ТОТ=1|ВЫЛОЖИТЬ=1|NONE=2|ПРОЧИЙ=2|', 'main', 1237499024000, 1316042285),
(2478438, 'art', 71, '|BLUE=2|HAIR=2|СИНИЙ=2|ГОЛУБОЙ=2|ВОЛОС=2|フェイトステイナイト=2|FATE=2|STAY=2|NIGHT=2|ФЭЙТ=2|СТЭЙ=2|НАЙТ=2|FSN=2|СУДЬБА=2|НОЧЬ=2|СХВАТКИ=2|LANCER=2|ランサー=2|ЛАНСЕР=2|TYPE=2|MOON=2|TYPE_MOON=2|タイプムーン=2|TYPEMOON=2|WOLF=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275161243000, 1316067368),
(2484524, 'news', 6, '|ОБЪЯВЛЕНИЕ=3|ПРЕДСТАВЛЯТЬ=3|ВЫ=3|ЮМИКО_ТЯНИН=3|ЗДРАВСТВОВАТЬ=1|ЗВАТЬ=1|ЮМИКИЙ=1|ТЕПЕРЬ=1|Я=1|БЫТЬ=1|РАБОТАТЬ=1|ЗДЕСЬ=1|ПРИЯТНО=1|ПОЗНАКОМИТЬСЯ=1|НАДЕЯТЬСЯ=1|МЫ=1|С=1|ВЫ=1|ПОДРУЖИТЬСЯ=1|НАЧАЛЬНИК_ДОНО=1|КАЧЕСТВО=1|ПЕРВОЕ=1|ЗАДАНИЕ=1|ВЫДАТЬ=1|Я=1|АНКЕТА=1|ВЕЛЕТЬ=1|ПОГОВОРИТЬ=1|ВСЕ=1|НАШ=1|ПОСЕТИТЕЛЬ=1|ПОПРОСИТЬ=1|ОНИ=1|ЗАПОЛНИТЬ=1|БЛАНК=1|ПРОХОДИТЬ=1|МИМО=1|ЧТОБЫ=1|ОТВЕТИТЬ=1|ДОСТАТОЧНО=1|НАЖАТЬ=1|ССЫЛКА=1|КОММЕНТИРОВАТЬ=1|ПРАВЫЙ=1|ВЕРХНИЙ=1|УГОЛ=1|СО=1|СТРАНИЦА=1|ЖЕ=1|ПЕРЕЙТИ=1|К=1|ЗАПИСЬ=1|ЧИТАТЬ=1|МЫ=1|ЧЕРЕЗ=1|RSS_РИДЕР=1|ПОЖАЛУЙСТА=1|НЕБОЛЬШОЙ=1|РЯД=1|ВОПРОС=1|1=1|КАКОЙ=1|МАТЕРИАЛ=1|НАШ=1|САЙТ=1|ЧАСТЫЙ=1|2=1|НУЖНЫЙ=1|ЛИ=1|САЙТ=1|4ОТАК=1|РЕДИЗАЙН=1|ПРОСТОЙ=1|КАКОЙ_НИБУДЬ=1|ДОПОЛНЕНИЕ=1|ДИЗАЙН=1|3=1|КАКОЙ=1|ДОПОЛНИТЕЛЬНЫЙ=1|ФУНКЦИОНАЛ=1|ХОТЕТЬ=1|БЫ=1|ВИДЕТЬ=1|4=1|ЛЮБОЙ=1|ВАШ=1|ПОЖЕЛАНИЕ=1|И=1|ВОПРОС=1|КАСАТЕЛЬНО=1|САЙТ=1|СВОБОДНЫЙ=1|ТЕМА=1|5=1|ЧТО=1|ВЫ=1|ДУМАТЬ=1|ЮМИКО_ТЯНИН=1|КАК=1|О=1|РАБОТНИЦА=1|ОСТАВИТЬ=1|ГНАТЬ=1|ВЗАШЕЙ=1|ДА=1|МИЛЛИМЕТР=1|НИЧТО=1|СЕБЯ=1|ПЯТЫЙ=1|ВОПРОСИК=1|У=1|Я=1|ТУТ=1|В=1|АНКЕТА=1|ТАК=1|ИЛИ=1|ИНАЧЕ=1|ОТВЕТИТЬ=1|НА=1|ВСЕ=1|ПУНКТ=1|ЕСЛИ=1|ВЫ=1|НЕ=1|СЛОЖНО=1|БОЛЬШОЙ=1|СПАСИБО=1|', 'main', 1240344359000, 1316075169),
(2454780, 'art', 34, '|BROWN=2|HAIR=2|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316036644),
(2455055, 'art', 51, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316037007),
(2484701, 'video', 15, '|ЗАСТАВКА=3|К=3|ИГРА=3|STEIN=3|GATE=3|ДЛИТЬ=3|XBOX360=3|ЗАСТАВКА=1|К=1|STEIN=1|GATE=1|ДЛИТЬ=1|XBOX360=1|ОТ=1|СТУДИЯ=1|NITROPLUS=1|NITRO=1|ИЛЛЮСТРАЦИЯ=1|В=1|ИГРА=1|ВЫПОЛНИТЬ=1|ХУДОЖНИК=1|HUKE=1|SH=1|RP=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|SKY=1|CLOTHE=1|NO=1|KANSOKUSHA=1|И=1|ИСПОЛНИТЬ=1|ПЕВИЦА=1|ITOU=1|KANAKO=1|ITOU=2|KANAKO=2|いとうかなこ=2|威闘華鳴乎=2|NITROPLUS=2|NITRO=2|ニトロプラス=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|ВИЗУАЛЬНЫЙ=2|VISUAL=2|NOVEL=2|ВИЗУАЛЬНЫЙ=2|ГРАФИЧЕСКИЙ=2|НОВЕЛЛА=2|ГРАФИЧЕСКИЙ=2|РОМАНА=2|ビジュアルノベル=2|VN=2|OFEN=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1283692230400, 1316075409),
(2451880, 'art', 89, '|BLONDE=2|HAIR=2|СВЕТЛЫЙ=2|ВОЛОС=2|フェイトステイナイト=2|STAY=2|NIGHT=2|ФЭЙТ=2|СТЭЙ=2|НАЙТ=2|FSN=2|СУДЬБА=2|НОЧЬ=2|СХВАТКИ=2|フェイト=2|アンリミテッドコード=2|FATE=2|UNLIMITED=2|CODE=2|GREEN=2|EYE=2|ЗЕЛЕНОЕ=2|ЗЕЛЁНЫЙ=2|ГЛАЗ=2|セイバー=2|СЕЙБЫЙ=2|SABER=2|LILY=2|СЭЙБЕР=2|СЕЙБЕР=2|ЛИТЬ=2|STAR=2|SKY=2|SWORD=2|SWORD=2|МЕЧ=2|МЕТАТЬ=2|TYPE=2|MOON=2|TYPE_MOON=2|タイプムーン=2|TYPEMOON=2|ОБОИ=2|WALLPAPER=2|WP=2|壁紙=2|WALLPAPER=2|ウォールペーパー=2|カベカミ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275224457000, 1316032809),
(2482601, 'art', 56, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316072648),
(2477096, 'post', 8, '|ПЕРЕОЗВУЧКА=3|ОТ=3|САТОРИТЬ=3|АНИМЕ_ПЕРЕОЗВУЧКА=1|ЗАКЛЮЧАТЬСЯ=1|НАКЛАДЫВАНИЕ=1|СВОЙ=1|ЗВУКОВОЙ=1|ДОРОЖКА=1|ПОВЕРХ=1|ИЛИ=1|ВМЕСТО=1|ИМЕТЬСЯ=1|ДАННЫЙ=1|ПЕРЕОЗВУЧКА=1|БЫТЬ=1|ПРЕДСТАВИТЬ=1|НА=1|АНИМЕ_ФЕСТИВАЛЬ=1|ЧЕРНЫЙ=1|ДРАКОН=1|2008=1|ОТ=1|КОМАНДА=1|ГТО=1|И=1|ЦК=1|САТОРИТЬ=1|В=1|КАЧЕСТВО=1|ОТРЕЗОК=1|АНИМ=1|ФИГУРИРОВАТЬ=1|ОТРЕЗОК=1|ИЗА=1|ЧОБИТ=1|СКАЧАТЬ=1|ПЕРЕОЗВУЧИТЬ=1|ОТРЕЗОК=1|HTTP=1|WWW=1|COM=1|ZWZLW5QWMWW=1|MEDIAFIRE=1|=1|ﾟ=1|ПЕРЕОЗВУЧКА=2|ФЕСТИВАЛЬ=2|ЧЕРНЫЙ=2|ДРАКОН=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|VIDEO=2|ВИДЕО=2|RUSSIAN=2|РУССКИЙ=2|', 'main', 1225832408000, 1316065571),
(2455647, 'art', 62, '|AHOGE=2|ARMOR=2|ДОСПЕХ=2|БРОНЯ=2|ДОСПЕХ=2|BLONDE=2|HAIR=2|СВЕТЛЫЙ=2|ВОЛОС=2|フェイトステイナイト=2|FATE=2|STAY=2|NIGHT=2|ФЭЙТ=2|СТЭЙ=2|НАЙТ=2|FSN=2|СУДЬБА=2|НОЧЬ=2|СХВАТКИ=2|FIRE=2|SABER=2|СЭЙБЕР=2|セイバー=2|СЕЙБЕР=2|СЕЙБЫЙ=2|SWORD=2|SWORD=2|МЕЧ=2|МЕТАТЬ=2|TYPE=2|MOON=2|TYPE_MOON=2|タイプムーン=2|TYPEMOON=2|ОБОИ=2|WALLPAPER=2|WP=2|壁紙=2|WALLPAPER=2|ウォールペーパー=2|カベカミ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275048544000, 1316037614),
(2455029, 'art', 21, '|AYANAMUS=2|REUS=2|АЯНА=2|РЭЙ=2|BLUE=2|HAIR=2|СИНИЙ=2|ГОЛУБОЙ=2|ВОЛОС=2|LINUX=2|ЛИНУПСА=2|ЛИНУКС=2|ЛИНУХА=2|NEON=2|GENESIS=2|EVANGELION=2|ЕВАНГЕЛИОНА=2|新世紀エヴァンゲリオン=2|NGE=2|PENGUIN=2|EYE=2|КРАСНЫЙ=2|ГЛАЗ=2|RED=2|EYE=2|SCARF=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274956992000, 1316036886),
(2455676, 'art', 30, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316037728),
(2483390, 'art', 18, '|EYE=2|PURPLE=2|HAIR=2|ПУРПУРНЫЙ=2|ВОЛОС=2|YUKIRIN=2|W8M=1|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'main', 1274956649000, 1316073731),
(2477413, 'post', 15, '|ИГРА=3|TOUHOU=3|7=3|PERFECT=3|CHERRY=3|BLOSSOM=3|ФАКТИЧЕСКИ=1|САМЫЙ=1|ИЗВЕСТНЫЙ=1|ИГРА=1|ИЗА=1|СЕРИЯ=1|ТОХИЙ=1|PERFECT=1|CHERRY=1|BLOSSOM=1|МОЧЬ=1|БЫТЬ=1|ПЕРЕВЕСТИ=1|РУССКИЙ=1|КАК=1|ИДЕАЛЬНЫЙ=1|ЦВЕТЕНИЕ=1|САКУР=1|СЮЖЕТ=1|ЗАВЯЗАТЬ=1|ТОТ=1|ЧТО=1|В=1|GENSOKYO=1|УЗКИЙ=1|НАЧАТЬСЯ=1|МАЙ=1|А=1|СНЕГ=1|ТАК=1|НЕ=1|СОБИРАТЬСЯ=1|ТАЯТЬ=1|И=1|ВОТ=1|ВЫБРАТЬ=1|ИГРОК=1|ПЕРСОНАЖ=1|ОТПРАВЛЯТЬСЯ=1|НА=1|ПОИСК=1|УКРАСТЬ=1|ВЕСНА=1|HTTP=1|NAROD=1|RU=1|DISK=1|6972218000=1|PERFECT=1|CHERRY=1|BLOSSOM=1|RAR=1|HTML=1|ЯНДЕКС=1|ДИСК=1|СКАЧАТЬ=1|MEDIAFIRE=1|ДЛИТЬ=1|ЗАПУСК=1|ИГРА=1|НА=1|WINDOW=1|МОЧЬ=1|ПОНАДОБИТЬСЯ=1|D3DX9=1|33=1|DLL=1|SHOOT=2|EM=2|UP=2|SHMUP=2|SHOOT=2|GAME=2|ШМАП=2|ШУМПА=2|TEAM=2|SHANGHAI=2|ALICE=2|ZUN=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|GAME=2|ИГРА=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'main', 1226264415000, 1316065931),
(2480401, 'art', 81, '|BOOK=2|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275220308000, 1316069885),
(2478085, 'post', 3, '|АЛЬБОМ=3|RIOT=3|GIRL=3|ОТ=3|HIRANO=3|AYA=3|RIOT=1|ЕЕ=1|GIRL=1|ДЕБЮТНЫЙ=1|ОТ=1|J_POP=1|СЕЙЮ=1|HIRANO=1|AYA=1|РЕЛИЗ=1|КОТОРЫЙ=1|СОСТОЯТЬСЯ=1|16=1|ИЮЛЬ=1|2008=1|ГОД=1|АЛЬБОМ=1|СОДЕРЖАТЬ=1|14=1|ПЕСНЯ=1|СЕМЬ=1|ИЗА=1|КОТОРЫЙ=1|СОВЕРШЕННЫЙ=1|НОВОЕ=1|А=1|ОСТАЛЬНОЕ=1|7=1|УЗКИЙ=1|МОЖНО=1|БЫТЬ=1|УСЛЫШАТЬ=1|СИНГЛ=1|ПЕВИЦА=1|РАНЕЕ=1|ОНА=1|ПОПУЛЯРНОСТЬ=1|ВОЗРАСТИ=1|ПОСОЛ=1|РОЛЬ=1|В=1|АНИМ=1|THE=1|MELANCHOLY=1|OF=1|HARUHI=1|SUZUMIYA=1|ОНА=1|ЖЕ=1|ИСПОЛНИТЬ=1|OPEN=1|СЕРИАЛ=1|СОВМЕСТНО=1|С=1|MINORUS=1|CHIHARA=1|И=1|YUKO=1|GOTO=1|СПЕЛЫЙ=1|END=1|HTTP=1|NAROD=1|RU=1|DISK=1|13480010000=1|5BAYA=1|20HIRANO=1|5D=1|RIOT=1|20GIRL=1|RAR=1|HTML=1|ЯНДЕКС=1|ДИСК=1|ССЫЛКА=1|ДЛИТЬ=1|СКАЧИВАНИЕ=1|=1|ﾟ=1|АЛЬБОМ=2|ALBUM=2|アルバム=2|HIRANO=2|AYA=2|平野綾=2|J=2|ЯПОНСКИЙ=2|МУЗЫКА=2|ДЖЕЙ_ПОП=2|ДЖЕЙ=2|ПОПА=2|ジェイポップ=2|JPOP=2|JAPANESE=2|POP=2|MUSIC=2|J_POP=2|ポップ音楽=2|ポップ=2|ミュージック=2|ALAN=1|АЛАН=1|MUSIC=2|МУЗЫКА=2|JAPANESE=2|ЯПОНСКИЙ=2|', 'main', 1224446403000, 1316066885),
(2489541, 'video', 8, '|КЛИП=3|К=3|ПЕСНЯ=3|SUPERLUMINAL=3|Ж=3|AKIBA_POP=3|КЛИП=1|К=1|ПЕСНЯ=1|SUPERLUMINAL=1|Ж=1|AKIBA_POP=1|ОТ=1|ГРУППА=1|MOSAIC=1|WAV=1|J=2|ЯПОНСКИЙ=2|МУЗЫКА=2|ДЖЕЙ_ПОП=2|ДЖЕЙ=2|ПОПА=2|ジェイポップ=2|JPOP=2|JAPANESE=2|POP=2|MUSIC=2|J_POP=2|ポップ音楽=2|ポップ=2|ミュージック=2|MOSAIC=2|WAV=2|ПРОМО_ВИДЕО=2|PV=2|PROMO=2|PROMOTION=2|VIDEO=2|プロモーションビデオ=2|OFEN=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1283692138710, 1316081766),
(2484526, 'news', 4, '|ОБЪЯВЛЕНИЕ=3|ОБЩИЙ=3|СБОР=3|ОТЛИЧАТЬСЯ=1|БОЛЬШОЙ=1|МАЛЕНЬКИЙ=1|БЛОЖИКА=1|ТОТ=1|МАЛЕНЬКИЙ=1|БЛОЖИК=1|ЗАНИМАТЬСЯ=1|ОСНОВНОЕ=1|ОДИН=1|ЧЕЛОВЕК=1|А=1|УВАЖАТЬ=1|СЕБЯ=1|ТРУДИТЬСЯ=1|МНОГИЕ=1|ЛЮБОЙ=1|АДМИНИСТРАТОР=1|МОДЕРАТОР=1|ПРОГРАММИСТ=1|ДИЗАЙНЕР=1|ДАЖЕ=1|АКТИВНЫЙ=1|ПОЛЬЗОВАТЕЛЬ=1|ВНОСИТЬ=1|ВКЛАД=1|РАЗВИТИЕ=1|ВЫХОДИТЬ=1|ОНИ=1|НАМНОГО=1|У=1|ОДНОГО_ТО=1|4ОТАК=1|НИКОГДА=1|ЗАДУМЫВАТЬСЯ=1|МАЛЕНЬКИЙ=1|БЛОЖИК=1|ОДИН=1|ЧЕЛОВЕК=1|МИРНЫЙ=1|ВЫКЛАДЫВАТЬ=1|СЕБЯ=1|ПОСМОТРЕТЬ=1|СОСТОЯНИЕ=1|ДЕЛО=1|СЕГОДНЯ=1|РАБОТАТЬ=1|КОД=1|МОДЕРИРОВАТЬ=1|КОММЕНТАРИЙ=1|СООБЩЕСТВО=1|МОЙ=1|2=1|3=1|ВСЕ=1|РАЗВЕ=1|ЧТО=1|ДИЗАЙН=1|ЗАНИМАТЬСЯ=1|ЕЩЕ=1|НЕСКОЛЬКО=1|ЗАДАЧА=1|МАЛЕНЬКИЙ=1|ТАМ=1|ПРИЯТНЫЙ=1|СЮРПРИЗ=1|ОБНОВЛЕНИЕ=1|СТАРЫЙ=1|ЗАПИСЬ=1|РАЗУМЕТЬСЯ=1|С=1|РОСТ=1|САЙТ=1|Я=1|ПРОСТОЙ=1|ПЕРЕСТАТЬ=1|УСПЕВАТЬ=1|СТАТЬ=1|ЭТОТ=1|САЙТ=1|БОЛЬШОЙ=1|ЧТО=1|ОН=1|СЕЙЧАС=1|ЧТО_ТО=1|ИЗВЕСТНЫЙ=1|НУЖНЫЙ=1|ЗАВИСЕТЬ=1|ОТ=1|ОДИН=1|ПРОСТОЙ=1|ВЕЩЬ=1|НАЙТИСЬ=1|ЛИ=1|ЧЕЛОВЕК=1|ТОЖЕ=1|ГОТОВЫЙ=1|ОНО=1|РАБОТАТЬ=1|ПОМОЧЬ=1|САЙТ=1|ДВА=1|СПОСОБ=1|ПЕРВЫЙ=1|РОЛЬ=1|АКТИВНЫЙ=1|ПОЛЬЗОВАТЕЛЬ=1|ДОБАВЛЯТЬ=1|СВОЙ=1|МАТЕРИАЛ=1|ПО=1|ЭТОТ=1|АДРЕС=1|HTTP=1|4OTAKU=1|ADD=1|ПОВЕРИТЬ=1|ЭТОТ=1|УЗКИЙ=1|БЫТЬ=1|НЕМАЛО=1|ЕСЛИ=1|ЕСТЬ=1|КАКОЙ_ТО=1|ВОПРОС=1|ТЕМА=1|ЭТОТ=1|ШТУКЕНЦИЯ=1|ЗАДАВАТЬ=1|ОНИ=1|КОММЕНТАРИЙ=1|К=1|ЭТОТ=1|ЗАПИСЬ=1|ВТОРОЙ=1|ВЫЗВАТЬСЯ=1|ДОБРОВОЛЕЦ=1|КОМАНДА=1|ТОТ=1|КТО=1|НАД=1|САЙТ=1|РАБОТАТЬ=1|ДЛИТЬ=1|ЭТОТ=1|ВЫ=1|МОЧЬ=1|КАК=1|УКАЗАТЬ=1|СВОЙ=1|E_MAIL=1|ТАК=1|И=1|ОТПРАВИТЬ=1|ПИСЬМО=1|Я=1|NOONEMAIL=1|MAIL=1|RU=1|НО=1|В=1|ЭТОТ=1|СЛУЧАЙ=1|ВСЕ=1|РАВНЫЙ=1|ХОРОШО=1|НА=1|ВСЯКИЙ=1|ОТПИСАТЬСЯ=1|ЗДЕСЬ=1|ЧТОБ=1|ВЫ=1|ПОТЕРЯТЬ=1|СПЕЦИАЛЬНЫЙ=1|НАВЫК=1|ВРОДЕ=1|ФОТОШОП=1|ИЛИ=1|ВЕБ_ПРОГРАММИРОВАНИЕ=1|НЕ=1|ОБЯЗАТЕЛЬНЫЙ=1|', 'main', 1246132876000, 1316075170),
(2484527, 'news', 1, '|ТЕХНИЧЕСКИЙ=3|ВОПРОС=3|ЭТОТ=1|СТРАНИЦА=1|ПРЕДНАЗНАЧИТЬ=1|ЛЮБОЙ=1|РОД=1|ТЕХНИЧЕСКИЙ=1|ЦЕЛОЕ=1|ВЫ=1|ХОТЕТЬ=1|БЫ=1|ВИДЕТЬ=1|КАКОЙ_ТО=1|КАТЕГОРИЯ=1|МАТЕРИАЛ=1|ПРОДУБЛИРОВАТЬ=1|НА=1|ДРУГОЙ=1|АПЛОАДЕР=1|СПРОСИТЬ=1|ПРЕДЛОЖЕНИЕ=1|ИЛИ=1|ХОРОШИЙ=1|ИДЕЯ=1|МОЖНО=1|ИЗМЕНИТЬ=1|4ОТАК=1|ОЗВУЧИТЬ=1|ИСПЫТЫВАТЬ=1|СЛОЖНОСТЬ=1|НАВИГАЦИЯ=1|САЙТ=1|ОТКРЫВАТЬСЯ=1|СТРАНИЦА=1|УДОБНО=1|НАХОДИТЬ=1|ИСКОМОЕ=1|СООБЩИТЬ=1|У=1|ВЫ=1|ЖЕЛАНИЕ=1|ПОУЧАСТВОВАТЬ=1|ПРОЕКТ=1|НЕОБХОДИМЫЙ=1|ЭТОТ=1|НАВЫК=1|НАПИСАТЬ=1|ОБ=1|ЗДЕСЬ=1|ЗАБЫТЬ=1|ПОЖАЛУЙСТА=1|ОСТАВИТЬ=1|СРЕДСТВО=1|СВЯЗЬ=1|ЧТО=1|УГОДНО=1|НЕ=1|ВОЙТИ=1|ПРЕДЫДУЩИЙ=1|СПИСОК=1|ПРЯ=1|ЭТОТ=1|КАСАТЬСЯ=1|МНОГО=1|ЧТО=1|ОДНОЙ_ДВУХ=1|ЗАПИСЬ=1|ДЛИТЬ=1|ВОПРОС=1|ПО=1|КОНКРЕТНЫЙ=1|КОММЕНТАРИЙ=1|ОНИ=1|САМ=1|ЕСЛИ=1|ХОТЕТЬ=1|СЛЕДИТЬ=1|ЗА=1|ОБЩИЙ=1|ПОТОК=1|К=1|ЗАПИСЬ=1|ВСЕГДА=1|ЕСТЬ=1|ЛЕНТА=1|КОММЕНТАРИЙ=1|САЙДБАР=1|И=1|ЕЕ=1|RSS=1|В=1|ПРАВЫЙ=1|ВЕРХНИЙ=1|УГОЛ=1|', 'main', 1237150800000, 1316075170),
(2478763, 'art', 87, '|MAZINGER=2|МАЗИНГЕР=2|МЕХ=2|MERVISH=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275223164000, 1316067728),
(2478764, 'art', 38, '|SABER=2|СЭЙБЕР=2|セイバー=2|СЕЙБЕР=2|СЕЙБЫЙ=2|SWORD=2|SWORD=2|МЕЧ=2|МЕТАТЬ=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274958701000, 1316067728),
(2455711, 'art', 20, '|YUKIRIN=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274956649000, 1316037729),
(2481413, 'art', 79, '|BOOK=2|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275220308000, 1316071086),
(2490325, 'orders', 3, '|ФАЙТИНГ=3|GLOVE=3|ON=3|FIGHT=3|ЗДРАВСТВОВАТЬ=1|НИКАК=1|НЕ=1|МОЧЬ=1|НАЙТИ=1|ФАЙТИНГ=1|GLOVE=1|ON=1|FIGH=1|КАК=1|БУД_ТО=1|ОНО=1|ВООБЩЕ=1|НЕТ=1|ЕСТЬ=1|ТОЛЬКО=1|МАЛЕНЬКИЙ=1|РОЛИК=1|ПОКАЗЫВАТЬ=1|ГЕЙМПЛЫЙ=1|ВЫЦАРАПАНЫЙ=1|ИЗА=1|СТАРЫЙ=1|НОМЕР=1|СТРАНА=1|ИГРА=1|NONE=2|ПРОЧИЙ=2|', 'main', 1237930513000, 1316082728),
(2478775, 'art', 68, '|TOSHIAKI=2|TAKAYAMA=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275157005000, 1316067729),
(2480269, 'art', 72, '|BLUE=2|HAIR=2|СИНИЙ=2|ГОЛУБОЙ=2|ВОЛОС=2|DELETION=2|REQUEST=2|К=2|УДАЛЕНИЕ=2|フェイトステイナイト=2|FATE=2|STAY=2|NIGHT=2|ФЭЙТ=2|СТЭЙ=2|НАЙТ=2|FSN=2|СУДЬБА=2|НОЧЬ=2|СХВАТКИ=2|LANCER=2|ランサー=2|ЛАНСЕР=2|EYE=2|КРАСНЫЙ=2|ГЛАЗ=2|RED=2|EYE=2|TYPE=2|MOON=2|TYPE_MOON=2|タイプムーン=2|TYPEMOON=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275161891000, 1316069767),
(2478788, 'art', 63, '|TOSHIAKI=2|TAKAYAMA=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275152700000, 1316067853),
(2454911, 'art', 50, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316036767),
(2454912, 'art', 44, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316036767),
(2490606, 'video', 4, '|ЗАСТАВКА=3|К=3|ИГРА=3|11EYES=3|CROSSOVER=3|ДЛИТЬ=3|XBOX360=3|ЗАСТАВКА=1|К=1|11EYES=1|ДЛИТЬ=1|XBOX360=1|ОТ=1|СТУДИЯ=1|LASS=1|ИЛЛЮСТРАЦИЯ=1|В=1|ИГРА=1|ВЫПОЛНИТЬ=1|ТАКОЙ=1|ХУДОЖНИК=1|КАК=1|HAGIWARA=1|ONSEN=1|CHIKOTAM=1|YOU=1|OZAWA=1|NARUMUS=1|YUU=1|KENGOU=1|ZEN=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|ENDLESS=1|TEAR=1|И=1|ИСПОЛНИТЬ=1|ПЕВИЦА=1|AYANE=1|11EYES=2|11ГЛАЗЫЙ=2|ELEVEN=2|EYE=2|イレブンアイズ=2|ОДИННАДЦАТЬ=2|ГЛАЗ=2|5PB=2|AYANE=2|彩音=2|LASS=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|ВИЗУАЛЬНЫЙ=2|VISUAL=2|NOVEL=2|ВИЗУАЛЬНЫЙ=2|ГРАФИЧЕСКИЙ=2|НОВЕЛЛА=2|ГРАФИЧЕСКИЙ=2|РОМАНА=2|ビジュアルノベル=2|VN=2|OFEN=1|GAME=2|ИГРА=2|', 'flea_market', 1283692002920, 1316083209),
(2456381, 'art', 45, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316038577),
(2454876, 'art', 28, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316036767),
(2463250, 'orders', 14, '|SEIHOU=3|BANSHIRYUU=3|EXTRA=3|STAGE=3|И=3|ИЛИ=3|DIADRE=3|EMPTY=3|ОЧЕНЬ=1|ХОЧЕТЬСЯ=1|ПОИГРАТЬ=1|ЗАРАНЕЕ=1|СПАСИБО=1|NONE=2|ПРОЧИЙ=2|', 'main', 1241297574000, 1316047568),
(2454804, 'art', 40, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274980936000, 1316036645),
(2482703, 'art', 82, '|ASAHINA=2|MIKURU=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|СУЗУМИЕ=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275220491000, 1316072885);
INSERT INTO `search` (`id`, `place`, `item_id`, `index`, `area`, `sortdate`, `lastupdate`) VALUES
(2484521, 'news', 8, '|ОБСУЖДЕНИЕ=3|БУДУЩИЙ=3|ИМИДЖБОРД=3|ИТАК=1|НАШ=1|САЙТ=1|СО=1|ПОЯВИТЬСЯ=1|ИМИДЖБОРД=1|ТРЕБОВАНИЕ=1|НЕОБХОДИМЫЙ=1|РЕАЛИЗАЦИЯ=1|РАСХОДИТЬСЯ=1|ТЕЧЬ=1|ВОЗМОЖНОСТЬ=1|МОЙ=1|ХОСТИНГ=1|СНАЧАЛА=1|ПРИСТИСЬ=1|РАЗОБРАТЬСЯ=1|ЭТОТ=1|ТОЛЬКО=1|ПОТ=1|СТАВИТЬ=1|НЕПОСРЕДСТВЕННЫЙ=1|БОРД=1|ИЗ_ЗА=1|ЭТОТ=1|ПРОЦЕСС=1|НЕСКОЛЬКО=1|ЗАТЯНУТЬСЯ=1|НО=1|ДУМАТЬ=1|МНОГО=1|ДО=1|СЕРЕДИНА=1|А=1|ТОТ=1|ВРЕМЯ=1|ОБСУЖДАТЬ=1|ТЕХНИЧЕСКИЙ=1|АСПЕКТ=1|БУДУЩИЙ=1|ИМИДЖБОРД=1|ЗАПРАШИВАТЬ=1|ДЛИТЬ=1|НЕЯ=1|КАКОЙ_ЛИБО=1|РАЗДЕЛ=1|ЗДЕСЬ=1|МОЖНО=1|ЧИТАТЬ=1|НОВОСТЬ=1|О=1|ЕЕ=1|РАЗРАБОТКА=1|ПРЕДЛОЖИТЬ=1|СВОЙ=1|ПОМОЩЬ=1|ЧТО_ЛИБО=1|ЕСЛИ=1|ВЫ=1|ЗАМЕТИТЬ=1|ВОЗМОЖНОСТЬ=1|ПИСАТЬ=1|АНОНИМНЫЙ=1|ВВЕТЬ=1|МЕНЯТЬ=1|ИМЯ=1|Е_МЕЙЛ=1|ПОСТАВИТЬ=1|ПОЛЕ=1|КОММЕНТАРИЙ=1|ПО=1|УМОЛЧАНИЕ=1|UPDATE=1|02_05_2009=1|ПОСОЛ=1|ИЗУЧЕНИЕ=1|Я=1|РЕШИТЬ=1|НАПИСАТЬ=1|СВОЙ=1|СОБСТВЕННЫЙ=1|СКРИПТ=1|БЛЭКДЖЕК=1|ИЗВЕСТНО=1|КТО=1|ЧТОБЫ=1|Я=1|УСТРАИВАТЬ=1|ИЛИ=1|КОМПОНОВКА=1|ОНИ=1|ВЫСОКИЙ=1|ВСЯКИЙ=1|ПОХВАЛА=1|ПРОСТОЙ=1|НЕУЮТНО=1|РАБОТАТЬ=1|НЕСВОЙ=1|СТРУКТУРА=1|КОДА=1|PHP=1|Я=1|КУДА=1|БЛИЗКИЙ=1|PERL=1|ДА=1|НОВОЕ=1|НАВОРОТ=1|СОБСТВЕННЫЙ=1|ДВИЖОК=1|ДЕЛАТЬ=1|НАМНОГО=1|ЛЕГЧЕ=1|ЧТО=1|ЧУЖОЙ=1|ЕСТЬ=1|ПРАКТИЧЕСКИ=1|ТОТ=1|ЖЕ=1|САМЫЙ=1|ОТЛИЧИЕ=1|БЫТЬ=1|ЛИШЬ=1|В=1|КИШКА=1|СКРИПТ=1|НЕВИДИМЫЙ=1|ПОЛЬЗОВАТЕЛЬ=1|ВОТ=1|СИДЕТЬ=1|ТЕПЕРЬ=1|ВОСПРОИЗВОДИТЬ=1|ФУНКЦИОНАЛ=1|ВАКАБА=1|С=1|НУЛЬ=1|ПРОЦЕНТ=1|НА=1|70=1|УЗКИЙ=1|ГОТОВЫЙ=1|СРОК=1|НЕ=1|МЕНЯТЬСЯ=1|ВСЕ=1|ТАКЖЕ=1|СЕРЕДИНА=1|МАЙ=1|ВЕДЬ=1|НАДО=1|ЕЩЕ=1|ПРИЧЕСАТЬ=1|ВНЕШНИЙ=1|ВИД=1|И=1|СТРУКТУРА=1|HTML=1|', 'main', 1239998569000, 1316075169),
(2475821, 'post', 1, '|FLASH=3|ОТ=3|СТУДИЯ=3|MARU=3|PRODUCTION=3|СТУДИЯ=1|MARO=1|PRODUCTION=1|ПРОИЗВОДИТЬ=1|ИГРА=1|АРТА=1|ФЛЕШ_ВИДЕО=1|РИСОВАНИЕ=1|ЭТОТ=1|СТУДИЯ=1|МНОГО=1|НАПОМИНАТЬ=1|СТИЛЬ=1|ИСПОЛЬЗОВАТЬ=1|АНИМ=1|СТУДИЯ=1|GONZO=1|А=1|ЕСЛИ=1|ТОЧНЫЙ=1|ОДИН=1|ИЗА=1|ХУДОЖНИК=1|РИСОВАТЬ=1|LATE=1|EXILE=1|RANGE=1|MURATUM=1|ЛЕГКАЯ=1|И=1|ПОЗИТИВНЫЙ=1|АТМОСФЕРА=1|ДЕЛАТЬ=1|ФЛЕШКА=1|ПРИЯТНЫЙ=1|ПРОСМОТР=1|ДАЖЕ=1|НЕСМОТРЯ=1|НА=1|ТОТ=1|ЧТО=1|ДЛИТЬ=1|ПОНИМАНИЕ=1|БОЛЬШИЙ=1|ЧАСТЬ=1|ПРОИСХОДИТЬ=1|НУЖНО=1|ЗНАНИЕ=1|ЯПОНСКИЙ=1|ЯЗЫК=1|ПОЧТИТЬ=1|ВСЕ=1|ВИДЕО=1|СВЯЗАННЫЙ=1|РЕГУЛЯРНЫЙ=1|ПЕРСОНАЖ=1|КОТОРЫЙ=1|РЕДКИЙ=1|ПРОПУСКАТЬ=1|ВОЗМОЖНОСТЬ=1|ПОУЧАСТВОВАТЬ=1|В=1|НОВЫЙ=1|ФЛЕШКА=1|HTTP=1|WWW=1|COM=1|KQEDFNMKF04=1|MEDIAFIRE=1|ССЫЛКА=1|ДЛИТЬ=1|СКАЧИВАНИЕ=1|САЙТ=1|MARU=1|PRODUCTION=1|MARUPRODUCTION=1|COM=1|FLASH=2|ФЛЕШ=2|ФЛЭШ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|VIDEO=2|ВИДЕО=2|JAPANESE=2|ЯПОНСКИЙ=2|', 'main', 1224273601000, 1316063890),
(2478460, 'art', 70, '|TOSHIAKI=2|TAKAYAMA=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275158284000, 1316067368),
(2477052, 'post', 2, '|LITTLE=3|FIGHTER=3|2=3|0=3|LITTLE=1|FIGHTER=1|2=1|СВОБОДНЫЙ=1|РАСПРОСТРАНЯТЬ=1|ФАЙТИНГ=1|ОТ=1|MARTUS=1|STARSKY=1|WONG=1|ВЫЙТИ=1|1999=1|ГОД=1|ПРОДОЛЖАТЬ=1|ОБНОВЛЯТЬСЯ=1|ПО=1|СЕЙ=1|ДЕНЬ=1|ОТЛИЧИТЕЛЬНЫЙ=1|ОСОБЕННОСТЬ=1|ФАЙТИНГ=1|МАСШТАБНЫЙ=1|БИТВА=1|БОЛЬШОЙ=1|КОЛИЧЕСТВО=1|ПЕРСОНАЖ=1|ЭКРАН=1|ЗА=1|ОДИН=1|КОМПЬЮТЕР=1|МОЧЬ=1|СРАЖАТЬСЯ=1|СРАЗУ=1|4=1|ИГРОК=1|В=1|СЕТЕВОЙ=1|ИГРА=1|МАКСИМАЛЬНЫЙ=1|КОЛИЧЕСТВО=1|АКТИВНЫЙ=1|ИГРОК=1|УВЕЛИЧИВАТЬСЯ=1|ДО=1|ВОСЕМЬ=1|ПОМИМО=1|ОБЫЧНЫЙ=1|СРАЖЕНЬЕ=1|ДРУГ=1|ДРУГОЙ=1|ПРИСУТСТВОВАТЬ=1|ТАКОЙ=1|РЕЖИМ=1|КАК=1|ИГРА=1|ПРОХОЖДЕНИЕ=1|МАССОВЫЙ=1|БАТАЛИЯ=1|2_Х=1|КОМАНДА=1|С=1|УЧАСТИЕ=1|NPC=1|И=1|БИТВА=1|НА=1|ВЫЖИВАНИЕ=1|HTTP=1|WWW=1|COM=1|IWTFWMYWTUF=1|MEDIAFIRE=1|ССЫЛКА=1|ДЛИТЬ=1|СКАЧИВАНИЕ=1|ОФИЦИАЛЬНЫЙ=1|САЙТ=1|ИГРА=1|LF2=1|NET=1|ФАЙТИНГ=2|FIGHT=2|ファイティング=2|ИГРА=2|ПО=2|СЕТЬ=2|LAN=2|GAME=2|LAN=2|LITTLE=2|FIGHTER=2|ALAN=1|АЛАН=1|GAME=2|ИГРА=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'main', 1224360002000, 1316065450),
(2484953, 'news', 5, '|ОБЪЯВЛЕНИЕ=3|ОТКРЫТЬСЯ=3|СООБЩЕСТВО=3|САЙТ=3|ИМИДЖБОРД=1|НАПИСАТЬ=1|И=1|ГОТ=1|ЭКСПЛУАТАЦИЯ=1|ПРОСИТЬ=1|ВСЕ=1|К=1|СТОЛ=1|ЧИТАТЬ=1|КАКОЙ=1|СКРИПТ=1|ПОСТИЧЬ=1|ОБНОВЛЕНИЕ=1|ЗАДАВАТЬ=1|ВОПРОС=1|ИЛИ=1|ЗАПРАШИВАТЬ=1|НОВЫЙ=1|ФУНКЦИОНАЛ=1|МОЖНО=1|В=1|СПЕЦИАЛЬНЫЙ=1|РАЗДЕЛ=1|HTTP=1|4OTAKU=1|RU=1|BOARD=1|F=1|', 'main', 1242158711000, 1316075770),
(2455060, 'art', 75, '|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|PURPLE=2|HAIR=2|ПУРПУРНЫЙ=2|ВОЛОС=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275219203000, 1316037007),
(2457490, 'post', 6, '|НИКА=3|ОТ=3|БОГДАНА=3|НИКА=1|БОГДАНА=1|ОДИН=1|ИЗА=1|НЕМНОГО=1|ОТЕЧЕСТВЕННЫЙ=1|КОМИКС=1|НАРИСОВАТЬ=1|АНИМЕ_СТИЛИСТИКА=1|ДАННЫЙ=1|ПРОИЗВЕДЕНИЕ=1|ЕСЛИ=1|ПОНРАВИТЬСЯ=1|МОЖНО=1|ПРИОБРЕСТИ=1|СЕТЬ=1|МАГАЗИН=1|ANIME_POINT=1|ПЕРСОНАЖ=1|ДАННЫЙ=1|КОМИКС=1|ТАКЖЕ=1|ПРИСУТСТВОВАТЬ=1|КАЧЕСТВО=1|NPC=1|В=1|КНИГА=1|ПРАВИЛО=1|РУССКАЯ=1|НАСТОЛЬНЫЙ=1|РОЛЕВОЙ=1|ИГРА=1|ЭРА=1|ВОДОЛЕЙ=1|СЮЖЕТ=1|РАССКАЗЫВАТЬ=1|БЫТЬ=1|ЧТОБЫ=1|НЕ=1|ИСПОРТИТЬ=1|ВПЕЧАТЛЕНИЕ=1|ОТ=1|ПРОЧТЕНИЕ=1|НАМЕКНУТЬ=1|ТОЛЬКО=1|НА=1|КРАЙНИЙ=1|ИНТЕРЕСНЫЙ=1|И=1|ХАРИЗМАТИЧЕСКИЙ=1|ПЕРСОНАЖ=1|СКАЧАТЬ=1|WWW=1|COM=1|TFYZKYTZAZM=1|LT=1|MEDIAFIRE=1|GT=1|HTTP=1|=1|ﾟ=1|БОГДАНА=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|MANGA=2|МАНГ=2|RUSSIAN=2|РУССКИЙ=2|', 'main', 1225141206000, 1316040012),
(2482704, 'art', 16, '|YUKIRIN=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274956439000, 1316072885),
(2455062, 'art', 48, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316037007),
(2455064, 'art', 19, '|YUKIRIN=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274956649000, 1316037007),
(2455072, 'art', 24, '|AMANO=2|AI=2|W8M=1|PHOTO=2|ФОТО=2|', 'main', 1274957012000, 1316037007),
(2455073, 'art', 35, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316037007),
(2455269, 'art', 31, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316037246),
(2479588, 'art', 15, '|ОНО=2|YOUMU=2|KONPAKU=2|YOMU=2|ЁМ=2|ЁМ=2|ЁМ=2|КОМПАК=2|КОНПАК=2|ЙЫЙ=2|魂魄=2|妖夢=2|こんぱく=2|ようむ=2|MYON=2|МИТЬ=2|みょん=2|YUYUKO=2|SAIGYOUJI=2|САЙГЁДЗЬ=2|ЮЮКИЙ=2|SAYORUS=2|さより=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274895912000, 1316068809),
(2455272, 'art', 22, '|AMANO=2|AI=2|W8M=1|PHOTO=2|ФОТО=2|', 'main', 1274957012000, 1316037246),
(2455274, 'art', 41, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274980936000, 1316037246),
(2455277, 'art', 33, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316037246),
(2455278, 'art', 23, '|AMANO=2|AI=2|W8M=1|PHOTO=2|ФОТО=2|', 'main', 1274957012000, 1316037246),
(2455591, 'art', 69, '|TOSHIAKI=2|TAKAYAMA=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275158020000, 1316037612),
(2475827, 'post', 9, '|ДОДЗИНСЬ=3|SUZUMIYA=3|HARUOH=3|ЖГУЧИЙ=1|ПАРОДИЯ=1|МЕЛАНХОЛИЯ=1|ХАРУХ=1|СУЗДЗУМИЕ=1|ОТ=1|НЕИЗВЕСТНЫЙ=1|ХУДОЖНИК=1|ДОСТУПНЫЙ=1|ПОКА=1|ТОЛЬКО=1|АНГЛИЙСКИЙ=1|ЯЗЫК=1|БЫТЬ=1|ПАРОДИЯ=1|ЗАКЛЮЧАТЬСЯ=1|ИСПОЛНЕНИЕ=1|СОБЫТИЕ=1|ИМЕТЬ=1|МЕСТО=1|БЫТЬ=1|СЕРИАЛ=1|СТИЛЬ=1|С=1|ПЕРСОНАЖ=1|FIST=1|OF=1|THE=1|NORTH=1|STAR=1|ПРЯ=1|ЭТОТ=1|НАДО=1|ПОНИМАТЬ=1|ЧТО=1|МЕЛАНХОЛИЯ=1|СТИЛЬ=1|FOTN=1|ПРАКТИЧЕСКИ=1|ДИАМЕТРАЛЬНЫЙ=1|ПРОТИВОПОЛОЖНЫЙ=1|ГОВОРИТЬ=1|УЗКИЙ=1|О=1|РАЗНИЦА=1|В=1|ПЕРСОНАЖ=1|ОЦЕНИТЬ=1|ЮМОР=1|МОЖНО=1|И=1|НЕ=1|ЗНАТЬ=1|ОДИН=1|ИЗА=1|ИСТОЧНИК=1|ПЕРЕВОД=1|НА=1|РУССКИЙ=1|ВЫПОЛНИТЬ=1|МАНГАНУТЬ=1|АНГЛИЙСКИЙ=1|WWW=1|COM=1|HYTZYYGXMMY=1|LT=1|MEDIAFIRE=1|GT=1|СКАЧАТЬ=1|НА=1|РУССКИЙ=1|HTTP=1|NAROD=1|YANDEX=1|RU=1|DISK=1|8510754001=1|SUZUMIA=1|KOVAL=1|RAR=1|ЯНДЕКС=1|ДИСК=1|ССЫЛКА=1|НА=1|САЙТ=1|ПЕРЕВОДЧИК=1|LT=1|MANGANY=1|UCOZ=1|RU=1|GT=1|HTTP=1|ЛЮБИТЕЛЬСКИЙ=2|МАНГ=2|ДОДЗИНСЬ=2|同人誌=2|ДОДЖИНША=2|DOUJINSHI=2|PARODY=2|ПАРОДИЯ=2|パロディ=2|パロ=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|MANGA=2|МАНГ=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'main', 1225832409000, 1316063890),
(2475878, 'post', 13, '|CARD=3|CAPTOR=3|SAKURA=3|ОБОИ=3|ПОДБОРКА=1|ОБОИ=1|НА=1|РАБОЧИЙ=1|СТОЛ=1|ПО=1|АНИМ=1|CARD=1|CAPTOR=1|SAKURA=1|WWW=1|COM=1|EE1FYNVZ3NJ=1|ПЕРВЫЙ=1|MEDIAFIRE=1|13497567000=1|CARDCAPTORSAKURA2=1|ВТОРОЙ=1|HTTP=1|NAROD=1|RU=1|DISK=1|13497722000=1|CARDCAPTORSAKURA3=1|RAR=1|HTML=1|СКАЧАТЬ=1|ТРЕТИЙ=1|ЧАСТЬ=1|ПОДБОРКА=1|ЯНДЕКС=1|ДИСК=1|=1|ﾟ=1|CARD=2|CAPTOR=2|SAKURA=2|ОБОИ=2|WALLPAPER=2|WP=2|壁紙=2|WALLPAPER=2|ウォールペーパー=2|カベカミ=2|PINKERTON=1|ПИНКЕРТОН=1|ART=2|АРТА=2|NOLANGUAGE=2|НЕ=2|ТРЕБОВАТЬСЯ=2|', 'main', 1226178013000, 1316064007),
(2477103, 'post', 7, '|ИГРА=3|TOUHOU=3|7=3|5=3|IMMATERIAL=3|AND=3|MISSING=3|POWER=3|АНГЛОФИЦИРОВАННЫЙ=1|ФАЙТИНГ=1|ИЗА=1|СЕРИЯ=1|ТОХИЙ=1|ЕСЛИ=1|У=1|ВЫ=1|НЕ=1|ЗАПУСКАТЬСЯ=1|CONFIG=1|E=1|EXE=1|ПОПРОБОВАТЬ=1|ПОСТАВИТЬ=1|В=1|СВОЙСТВО=1|ПРИЛОЖЕНИЕ=1|СОВМЕСТИМОСТЬ=1|С=1|WINDOW=1|2000=1|СКАЧАТЬ=1|ИГРА=1|HTTP=1|NAROD=1|RU=1|DISK=1|6106793001=1|IMMATERIAL=1|AND=1|MISSING=1|POWER=1|RAR=1|HTML=1|ЯНДЕКС=1|ДИСК=1|ФАЙТИНГ=2|FIGHT=2|ファイティング=2|TEAM=2|SHANGHAI=2|ALICE=2|ZUN=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|GAME=2|ИГРА=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'main', 1225832407000, 1316065571),
(2475891, 'post', 4, '|ТРЕЙЛЕР=3|АНИМ=3|ФЕСТИВАЛЬ=3|ОРГАНИЗАТОР=1|МНОГО=1|ИЗА=1|АНИМЕ_ФЕСТИВАЛЬ=1|РОССИЯ=1|СОЗДАТЬ=1|РЕКЛАМНЫЙ=1|РОЛИК=1|СВОЙ=1|ФЕСТИВАЛЬ=1|ДЛИТЬ=1|ЭТОТ=1|БРАТЬСЯ=1|ВИДЕО=1|ФОТО=1|ОТСНЯТЬ=1|ФЕСТИВАЛЬ=1|МОНТИРОВАТЬСЯ=1|ПОД=1|СПЕЦИАЛЬНЫЙ=1|ПОДОБРАТЬ=1|МУЗЫКА=1|ПОЛУЧАТЬСЯ=1|ВЕСЬМА=1|ИНТЕРЕСНЫЙ=1|УВЛЕКАТЕЛЬНЫЙ=1|ЗРЕЛИЩЕ=1|ЕСЛИ=1|ВЫ=1|РАЗМЫШЛЯТЬ=1|ИЛИ=1|НЕ=1|ЕХАТЬ=1|ДРУГОЙ=1|ГОРОД=1|НА=1|ФЕСТИВАЛЬ=1|ТРЕЙЛЕР=1|МОЧЬ=1|ПОМОЧЬ=1|ВЫ=1|ОПРЕДЕЛИТЬСЯ=1|В=1|ДАННЫЙ=1|АРХИВ=1|ПРЕДСТАВИТЬ=1|ФЕСТИВАЛЬ=1|МОСКОВСКИЙ=1|ПИТЕРСКИЙ=1|ЧЕБОКСАРСКИЙ=1|КАЗАНСКИЙ=1|МИНСКИЙ=1|КИРОВСКИЙ=1|РОСТОВСКИЙ=1|И=1|ВОРОНЕЖСКИЙ=1|ФЕСТИВАЛЬ=1|13480736000=1|9C=1|20ANIMATRIX=1|МОСКОВСКИЙ=1|АНИМАТРИКС=1|13481773000=1|A1=1|9F=1|83=1|B3=1|20ANIMATSURUS=1|ПИТЕРСКИЙ=1|АНИМАЦУРЬ=1|13483230000=1|A7=1|B1=1|8B=1|20CHEBICON=1|ЧЕБОКСАРСКИЙ=1|ЧЕБИКОНА=1|13480343000=1|B7=1|20FENIX=1|КАЗАНСКИЙ=1|ФЕНИКС=1|ДАННЫЙ=1|ЕДИНСТВЕННЫЙ=1|СДЕЛАТЬ=1|НЕ=1|МАТЕРИАЛ=1|СНЯТЬ=1|НА=1|ФЕСТИВАЛЬ=1|А=1|НАРЕЗАТЬ=1|ИЗА=1|АНИМ=1|GENSHIKEN=1|13480512000=1|20HIGAN=1|КИЕВСКИЙ=1|ХИГАТЬ=1|13480650000=1|9A=1|20MIKAN=1|20NO=1|20YUKI=1|ТРЕЙЛЕР=1|КОВРОВСКИЙ=1|МИКАН_НО_ЮК=1|13480986000=1|A0=1|94=1|20TANIBA=1|РОСТОВСКИЙ=1|ТАНИБАТ=1|HTTP=1|NAROD=1|RU=1|DISK=1|13480250000=1|92=1|80=1|BE=1|BD=1|B6=1|BA=1|B9=1|20=1|84=1|B5=1|81=1|82=1|B8=1|B2=1|B0=1|D0=1|BB=1|D1=1|8C=1|RAR=1|HTML=1|ЯНДЕКС=1|ДИСК=1|ТРЕЙЛЕР=1|ВОРОНЕЖСКИЙ=1|АНИМЕ_ФЕСТИВАЛЬ=1|=1|ﾟ=1|MIKAN=2|NO=2|YUKI=2|АКИБАТЬ=2|АНИМАЦУРЬ=2|ТРЕЙЛЕР=2|ФЕНИКС=2|ФЕСТИВАЛЬ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|VIDEO=2|ВИДЕО=2|RUSSIAN=2|РУССКИЙ=2|', 'main', 1224532804000, 1316064008),
(2475924, 'post', 5, '|ФОТОГРАФИЯ=3|С=3|АКИБАНА_2008=3|СБОРНИК=1|ФОТОГРАФИЯ=1|ИЖЕВСКИЙ=1|ФЕСТИВАЛЬ=1|АКИБАН_2008=1|ФОТОГРАФИЯ=1|СОБРАТЬ=1|НЕСКОЛЬКО=1|ФОТОГРАФ=1|РАССОРТИРОВАТЬ=1|С=1|УКАЗАНИЕ=1|НИКНЕЙМ=1|И=1|ГОРОД=1|ЧТОБЫ=1|НЕ=1|ВОЗНИКНУТЬ=1|ПУТАНИЦА=1|ZMLZQHMJJI5=1|ФРОСЯ=1|223=1|WTMZNTJNHZ4=1|DANTE=1|75=1|1ZJGCKKVY4Y=1|GHOST=1|259=1|Q0HNY2ZZMM2=1|LUKA=1|НАБЕРЕЖНАЯ=1|ЧЁЛН=1|149=1|JX0HKMOHZIZ=1|YUFFY=1|НЕФТЕКАМСК=1|476=1|UZGVMMLHYJJ=1|ZOI=1|НИЖНЕКАМСК=1|116=1|OT4U3N1VYDG=1|АЛЕКСЕЙ=1|ЕМЕЛЬЯН=1|ЧЕБОКСАРЫ=1|174=1|JW5ZWTMYO2Z=1|АНДРЕЙ=1|139=1|NAROD=1|RU=1|DISK=1|13480704000=1|VLADIMIR=1|RAR=1|HTML=1|ВЛАДИМИР=1|ИЖЕВСК=1|542=1|ФОТОГРАФИЯ=1|ЯНДЕКС=1|ДИСК=1|OTB4QIZMZQW=1|МС_СЕРГЕЙ=1|МОЖГА=1|227=1|211DGIMINDK=1|МУТ=1|КОВЁР=1|330=1|HTTP=1|WWW=1|COM=1|NIWJLJLYQJX=1|РЕЛЕНА=1|ЙОШКАР_ОЛА=1|85=1|ФОТОГРАФИЯ=1|MEDIAFIRE=1|=1|ﾟ=1|COSPLAY=2|КОСПЛЕЙ=2|コスプレ=2|COSTUME=2|PLAY=2|АКИБАТЬ=2|ФЕСТИВАЛЬ=2|ФОТООТТАТЬ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|PHOTO=2|ФОТО=2|NOLANGUAGE=2|НЕ=2|ТРЕБОВАТЬСЯ=2|', 'main', 1225141205000, 1316064011),
(2483720, 'post', 11, '|AMV=3|ПО=3|YAMUS=3|NO=3|MATSUEI=3|СЕРЬЕЗНЫЙ=1|ПРЕДСТАВЛЯТЬ=1|ВЫ=1|ДВА=1|КЛИП=1|ПО=1|YAMUS=1|NO=1|MATSUEI=1|ОБА=1|ПОСВЯТИТЬ=1|СЛОЖНЫЙ=1|ОТНОШЕНИЕ=1|ДОКТОР=1|МУРАК=1|И=1|ОРИЙ=1|ПРЯ=1|ЧТО=1|ВЫПОЛНИТЬ=1|КЛИП=1|РАЗНЫЙ=1|СТИЛЬ=1|СЕРЬЁЗНЫЙ=1|ОДИН=1|В=1|ЮМОРИСТИЧЕСКИЙ=1|СЕРЬЁЗНЫЙ=1|КЛИП=1|СДЕЛАТЬ=1|МУЗЫКА=1|GARY=1|BARLOW=1|LIE=1|TO=1|I=1|ВЕСЁЛЫЙ=1|СООТВЕТСТВЕННЫЙ=1|НА=1|КАБАРЕ_ДУЭТА=1|АКАДЕМИЯ=1|ПЕСНЯ=1|Я=1|ОБИДЕТЬСЯ=1|СЕРЬЕЗНЫЙ=1|СЕРЬЁЗНЫЙ=1|CJWKDDZGEJJ=1|СКАЧАТЬ=1|ВЕСЁЛЫЙ=1|КЛИП=1|HTTP=1|WWW=1|COM=1|NMZWZNZM3MO=1|MEDIAFIRE=1|=1|ﾟ=1|AMV=2|YAOI=2|ЯОИТЬ=2|ЯОИТЬ=2|やおい=2|BL=2|BOY=2|LOVE=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|VIDEO=2|ВИДЕО=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'main', 1226178011000, 1316074090),
(2479675, 'art', 90, '|GREEN=2|ЗЕЛЕНОЕ=2|ЗЕЛЁНЫЙ=2|HATSUNE=2|初音ミク=2|ХАЦУНА=2|MIKU=2|ХАЦУНЭ=2|МИК=2|LONG=2|HAIR=2|ДЛИННЫЙ=2|ВОЛОС=2|TWINTAIL=2|TWIN=2|TAIL=2|VOCALOID=2|ボカロイド=2|ボーカロイド=2|ВОКАЛОИДА=2|ОБОИ=2|WALLPAPER=2|WP=2|壁紙=2|WALLPAPER=2|ウォールペーパー=2|カベカミ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275229561000, 1316068930),
(2463338, 'art', 7, '|BLONDE=2|HAIR=2|СВЕТЛЫЙ=2|ВОЛОС=2|GREEN=2|EYE=2|ЗЕЛЕНОЕ=2|ЗЕЛЁНЫЙ=2|ГЛАЗ=2|UEDA=2|RYO=2|植田亮=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274891376000, 1316047688),
(2454771, 'art', 76, '|BROWN=2|EYE=2|КОРИЧНЕВЫЙ=2|ГЛАЗ=2|NAGATO=2|YUKI=2|НАГАТЫЙ=2|ЮК=2|SERAFUKU=2|NO=2|YUUUTSU=2|MELANCHOLY=2|OF=2|HARUHI=2|SUZUMIYA=2|СУДЗУМИЕ=2|МЕЛАНХОЛИЯ=2|ХАРУХ=2|СУЗУМИЕ=2|涼宮ハルヒの憂鬱=2|ANONIMUS=1|АНОНИМУС=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275219406000, 1316036644),
(2481299, 'art', 6, '|COMIC=2|HATSUNE=2|初音ミク=2|ХАЦУНА=2|MIKU=2|ХАЦУНЭ=2|МИК=2|LONG=2|HAIR=2|ДЛИННЫЙ=2|ВОЛОС=2|MONOCHROME=2|TWINTAIL=2|TWIN=2|TAIL=2|VOCALOID=2|ボカロイド=2|ボーカロイド=2|ВОКАЛОИДА=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1285083275180, 1316070967),
(2448452, 'video', 1, '|HATSUNE=3|MIKU=3|V=3|NICO=3|DOUGA=3|НАРИСОВАТЬ=1|БОЙ=1|МИК=1|ПРОТИВ=1|МНОГО=1|ИЗВЕСТНЫЙ=1|МЕМОВ_ПЕРСОНАЖ=1|С=1|НИКОНИКИЙ=1|ANIMATSIYA=2|АНИМАЦИЯ=2|HATSUNE=2|初音ミク=2|ХАЦУНА=2|MIKU=2|ХАЦУНЭ=2|МИК=2|VOCALOID=2|ボカロイド=2|ボーカロイド=2|ВОКАЛОИДА=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|NONE=2|ПРОЧИЙ=2|', 'main', 1267275602000, 1316028373),
(2467149, 'art', 5, '|AKI=2|MORGUE=2|CHAIR=2|CREEPY=2|SCARY=2|GLASS=2|ОЧКО=2|HATSUNE=2|初音ミク=2|ХАЦУНА=2|MIKU=2|ХАЦУНЭ=2|МИК=2|MACHINE=2|MICROFON=2|МИКРОФОН=2|MICROPHONE=2|STAND=2|MONOCHROME=2|MORBID=2|MUSTACHE=2|NECKTIE=2|TIE=2|ГАЛСТУК=2|OLD=2|MAN=2|PANTYHOSE=2|SALIVA=2|SEPIA=2|SIT=2|SOUL=2|CRUSHINGLY=2|DEPRESS=2|TWINTAIL=2|TWIN=2|TAIL=2|VERY=2|LONG=2|HAIR=2|ОЧЕНЬ=2|ДЛИННЫЙ=2|ВОЛОС=2|VOCALOID=2|ボカロイド=2|ボーカロイド=2|ВОКАЛОИДА=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274816374000, 1316052606),
(2470810, 'orders', 13, '|ВЕРТИКАЛЬНЫЙ=3|СКРОЛЛЕР_ШУТЕР=3|ХОТЕТЬСЯ=1|БЫ=1|БОЛЬШОЙ=1|ВЕРТИКАЛЬНЫЙ=1|СКРОЛЛЕР_ШУТЕР=1|ОСОБЕННЫЙ=1|ПО=1|ТОХИЙ=1|ЕСТЬ=1|ЖЕ=1|ФАНАТСКАЯ=1|ИГРА=1|NONE=2|ПРОЧИЙ=2|', 'main', 1240518728000, 1316057409),
(2455271, 'art', 47, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316037246),
(2455275, 'art', 36, '|SISTER=2|PRINCESS=2|W8M=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274957792000, 1316037246),
(2455282, 'art', 57, '|GAME=2|CG=2|MAP=2|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274981068000, 1316037246),
(2481234, 'art', 74, '|AHOGE=2|ALSHUA=2|アルファルド=2|ALPHARD=2|АЛЬФАРД=2|ANTENNA=2|АНТЕННА=2|ANTENNA=2|BRUNESTUD=2|アルクェイド=2|ブリュ=2|ンスタッド=2|ARCUEY=2|АРКВЕЙД=2|БРЮНСТУДЫЙ=2|ARMOR=2|ДОСПЕХ=2|БРОНЯ=2|ДОСПЕХ=2|BLACK=2|ЧЕРНАЯ=2|BLONDE=2|СВЕТЛЫЙ=2|BOW=2|КОРИЧНЕВЫЙ=2|BROWN=2|カナン=2|ХАНААН=2|CANAAN=2|CHARACTER=2|CHIBI=2|CROSSOVER=2|フェイトステイナイト=2|STAY=2|NIGHT=2|ФЭЙТ=2|СТЭЙ=2|НАЙТ=2|FSN=2|СУДЬБА=2|НОЧЬ=2|СХВАТКИ=2|フェイト=2|アンリミテッドコード=2|FATE=2|UNLIMITED=2|CODE=2|GLOVE=2|GREEN=2|ЗЕЛЕНОЕ=2|ЗЕЛЁНЫЙ=2|GREY=2|СЕРЫЙ=2|JAPANESE=2|CLOTHE=2|KARA=2|NO=2|KYOUKAI=2|KIMONO=2|KIO=2|SAYUKI=2|LIANG=2|QI=2|リャン=2|チー=2|LONG=2|ДЛИННЫЙ=2|EYE=2|КРАСНЫЙ=2|ГЛАЗ=2|RED=2|EYE=2|RYOUGI=2|SHIKI=2|両儀織=2|両儀式=2|セイバー=2|СЕЙБЫЙ=2|SABER=2|LILY=2|СЭЙБЕР=2|СЕЙБЕР=2|ЛИТЬ=2|SHORT=2|КОРОТКИЙ=2|SMOKE=2|TAIL=2|月姫=2|ТСУКИХИМ=2|SHINGETSUTAN=2|TSUKIHIME=2|TYPE=2|MOON=2|TYPE_MOON=2|タイプムーン=2|TYPEMOON=2|WHITE=2|HAIR=2|БЕЛЫЙ=2|ВОЛОС=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275162319000, 1316070851),
(2495375, 'art', 91, '|CUM=2|EDITIO=2|PERFECTA=2|LOLUS=2|ORAL=2|OYARI=2|ASHITO=2|大槍葦人=2|大槍蘆人=2|SEX=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'cg', 1285241084730, 1316089328),
(2495376, 'art', 92, '|EDITIO=2|PERFECTA=2|LOLUS=2|ORAL=2|OYARI=2|ASHITO=2|大槍葦人=2|大槍蘆人=2|SEX=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'cg', 1285241101070, 1316089328),
(2495377, 'art', 94, '|CUM=2|EDITIO=2|PERFECTA=2|LOLUS=2|ORAL=2|OYARI=2|ASHITO=2|大槍葦人=2|大槍蘆人=2|SEX=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'cg', 1285241140320, 1316089328),
(2495378, 'art', 95, '|CUM=2|EDITIO=2|PERFECTA=2|LOLUS=2|OYARI=2|ASHITO=2|大槍葦人=2|大槍蘆人=2|SEX=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'cg', 1285241144550, 1316089328),
(2495379, 'art', 96, '|EDITIO=2|PERFECTA=2|LOLUS=2|ORAL=2|OYARI=2|ASHITO=2|大槍葦人=2|大槍蘆人=2|SEX=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'cg', 1285241163600, 1316089328),
(2495380, 'art', 99, '|EDITIO=2|PERFECTA=2|GAME=2|CG=2|MAP=2|LOLUS=2|OYARI=2|ASHITO=2|大槍葦人=2|大槍蘆人=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'cg', 1285241184840, 1316089328),
(2483365, 'art', 100, '|GADGET=2|TRIAL=2|ガジェットトライアル=2|GAME=2|CG=2|MAP=2|MECHA=2|MUSUME=2|MECHAGIRL=2|MECHAMUSUME=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1275239441000, 1316073729),
(2455270, 'art', 39, '|KARA=2|NO=2|SHOUJO=2|殻ノ少女=2|W8M=1|GAME=2|CG=2|ИЗА=2|ИГРА=2|', 'main', 1274980936000, 1316037246),
(2445035, 'post', 10, '|ДОДЗИНСЬ=3|ПО=3|TOUHOU=3|ОТ=3|AFTER=3|DEATH=3|ТРИ=1|КОРОТКИЙ=1|ДОДЗИНСЬ=1|ОТ=1|ЯПОНСКИЙ=1|ХУДОЖНИК=1|AFTER=1|DEATH=1|ПЕРЕВЕСТИ=1|АНГЛИЙСКИЙ=1|И=1|ИМЕТЬ=1|НА=1|РЕДКОСТЬ=1|КРАСИВЫЙ=1|СТИЛЬ=1|РИСОВАНИЕ=1|ВСЕ=1|ТРИ=1|В=1|ОДИН=1|АРХИВ=1|MTW5YZO4DFW=1|MURDEROUS=1|LOVE=1|NMKU5NTHMOD=1|POLAROID=1|CIRCUS=1|GNJOBCIYEK2=1|СКАЧАТЬ=1|ДОДЗИНСЬ=1|SAKURA=1|KOTOBA=1|HTTP=1|WWW=1|COM=1|BBM4ITITMIH=1|MEDIAFIRE=1|СЁДЗЕ=2|AFTER=2|DEATH=2|ЛЮБИТЕЛЬСКИЙ=2|МАНГ=2|ДОДЗИНСЬ=2|同人誌=2|ДОДЖИНША=2|DOUJINSHI=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|YURUS=2|СЕДЗЁ_АЙ=2|СЕДЗЁ_АЙ=2|СЕДЗЁ=2|СЕДЗЁ=2|ЮРИ=2|SHOUJO_AI=2|SHOUJO=2|AI=2|少女愛=2|ШОДЖО_АЙ=2|ШОДЖО_АЙ=2|АЙЯ=2|ШОДЖО=2|АЯ=2|NAMELESS=1|БЕЗЫМЯННЫЙ=1|MANGA=2|МАНГ=2|ENGLISH=2|АНГЛИЙСКИЙ=2|', 'flea_market', 1315654081770, 1316024052),
(2478435, 'art', 67, '|TOSHIAKI=2|TAKAYAMA=2|МЕХ=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1275156990000, 1316067368),
(2480935, 'art', 14, '|2GIRL=2|BLUE=2|СИНИЙ=2|ГОЛУБОЙ=2|CIRNO=2|СЫРНЫЙ=2|ЦЫРНЫЙ=2|CHIRNO=2|ЧИРНЫЙ=2|GREEN=2|HAIR=2|ЗЕЛЕНОЕ=2|ЗЕЛЁНЫЙ=2|ВОЛОС=2|SAYORUS=2|さより=2|TOUHOU=2|TOHO=2|ТОХИЙ=2|東方=2|TOHOU=2|東方アレンジ=2|WATER=2|ВОДА=2|WING=2|КРЫЛО=2|LBISS=1|NONE=2|ПРОЧИЙ=2|', 'main', 1274895781000, 1316070484),
(2481182, 'art', 1, '|СЁДЗЕ=2|BLUE=2|СИНИЙ=2|ГОЛУБОЙ=2|EYE=2|ГЛАЗ=2|EYE=2|RED=2|HAIR=2|КРАСНЫЙ=2|ВОЛОС=2|SWIMSUIT=2|КУПАЛЬНИК=2|WATER=2|ВОДА=2|YURUS=2|СЕДЗЁ_АЙ=2|СЕДЗЁ_АЙ=2|СЕДЗЁ=2|СЕДЗЁ=2|ЮРИ=2|SHOUJO_AI=2|SHOUJO=2|AI=2|少女愛=2|ШОДЖО_АЙ=2|ШОДЖО_АЙ=2|АЙЯ=2|ШОДЖО=2|АЯ=2|GT=1|LT=1|NSFW=2|ДЛИТЬ=2|ВЗРОСЛЫЙ=2|', 'main', 1274812602000, 1316070849),
(2494073, 'video', 3, '|ВТОРОЕ=3|ДЕМО_ВИДЕО=3|К=3|ИГРА=3|ANGELO=3|ARMAS=3|ВТОРОЕ=1|ДЕМО_ВИДЕО=1|К=1|ИГРА=1|ANGELO=1|ARMAS=1|ОТ=1|СТУДИЯ=1|NITROPLUS=1|NITRO=1|ИЛЛЮСТРАЦИЯ=1|ВЫПОЛНИТЬ=1|ХУДОЖНИК=1|CHUUOU=1|HIGASHIGUCHI=1|МУЗЫКАЛЬНЫЙ=1|ТРЕК=1|НАЗЫВАТЬСЯ=1|KESSHOU=1|И=1|ИСПОЛНИТЬ=1|ПЕВИЦА=1|ITOU=1|KANAKO=1|ITOU=2|KANAKO=2|いとうかなこ=2|威闘華鳴乎=2|NITROPLUS=2|NITRO=2|ニトロプラス=2|ЗАСТАВКА=2|OPEN=2|OP=2|オープ二ング=2|ВИЗУАЛЬНЫЙ=2|VISUAL=2|NOVEL=2|ВИЗУАЛЬНЫЙ=2|ГРАФИЧЕСКИЙ=2|НОВЕЛЛА=2|ГРАФИЧЕСКИЙ=2|РОМАНА=2|ビジュアルノベル=2|VN=2|OFEN=1|NONE=2|ПРОЧИЙ=2|', 'flea_market', 1283692012810, 1316087530);

-- --------------------------------------------------------

--
-- Структура таблицы `search_queries`
--

CREATE TABLE IF NOT EXISTS `search_queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `length` int(11) NOT NULL,
  `post` int(11) NOT NULL DEFAULT '0',
  `video` int(11) NOT NULL DEFAULT '0',
  `art` int(11) NOT NULL DEFAULT '0',
  `comment` int(11) NOT NULL DEFAULT '0',
  `news` int(11) NOT NULL DEFAULT '0',
  `orders` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `query` (`query`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `search_queries`
--


-- --------------------------------------------------------

--
-- Структура таблицы `search_weights`
--

CREATE TABLE IF NOT EXISTS `search_weights` (
  `place` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `weight` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`place`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `search_weights`
--

INSERT INTO `search_weights` (`place`, `weight`) VALUES
('post', 164.08),
('video', 53.32),
('art', 31.08),
('news', 135.683),
('orders', 33.44),
('comment', 10.95);

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `lastchange` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie` (`cookie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `cookie`, `data`, `lastchange`) VALUES
(1, 'c0b757aaf25e4de346a2fd8c735e9451', 'YTowOnt9', 1316175919),
(2, '901e0758c9c9ef4b6db6609d07cd3175', 'YTowOnt9', 1316176176),
(3, '50e40b6569b653d1c32ee657d04f549f', 'YTowOnt9', 1316176776),
(4, 'e820e2974566c2241360d8ae7d1cd491', 'YTowOnt9', 1316177378);

-- --------------------------------------------------------

--
-- Структура таблицы `soku`
--

CREATE TABLE IF NOT EXISTS `soku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `character` set('Hakurei Reimu','Kirisame Marisa','Alice Margatroid','Shameimaru Aya','Izayoi Sakuya','Konpaku Youmu','Patchouli Knowledge','Saigyouji Yuyuko','Remilia Scarlet','Yakumo Yukari','Ibuki Suika','Reisen Udongein Inaba','Onozuka Komachi','Nagae Iku','Hinanai Tenshi','Kochiya Sanae','Cirno','Hong Meiling','Moriya Suwako','Reiuji Utsuho') COLLATE utf8_unicode_ci NOT NULL,
  `second_character` set('Hakurei Reimu','Kirisame Marisa','Alice Margatroid','Shameimaru Aya','Izayoi Sakuya','Konpaku Youmu','Patchouli Knowledge','Saigyouji Yuyuko','Remilia Scarlet','Yakumo Yukari','Ibuki Suika','Reisen Udongein Inaba','Onozuka Komachi','Nagae Iku','Hinanai Tenshi','Kochiya Sanae','Cirno','Hong Meiling','Moriya Suwako','Reiuji Utsuho') COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `soku`
--


-- --------------------------------------------------------

--
-- Структура таблицы `subscription`
--

CREATE TABLE IF NOT EXISTS `subscription` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cookie` varchar(32) NOT NULL,
  `email` varchar(512) NOT NULL,
  `area` varchar(16) NOT NULL,
  `rule` text,
  `item_id` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `check_email_rights` (`cookie`,`email`(255)),
  KEY `subscription_rule` (`area`,`rule`(255)),
  KEY `subscription_id` (`area`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `subscription`
--


-- --------------------------------------------------------

--
-- Структура таблицы `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `variants` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `post_main` int(11) NOT NULL,
  `post_flea_market` int(11) NOT NULL,
  `video_main` int(11) NOT NULL,
  `video_flea_market` int(11) NOT NULL,
  `art_main` int(11) NOT NULL,
  `art_sprites` int(11) NOT NULL,
  `art_flea_market` int(11) NOT NULL,
  `have_description` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `check` (`alias`,`name`,`variants`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1832 ;

--
-- Дамп данных таблицы `tag`
--

INSERT INTO `tag` (`id`, `alias`, `name`, `variants`, `color`, `post_main`, `post_flea_market`, `video_main`, `video_flea_market`, `art_main`, `art_sprites`, `art_flea_market`, `have_description`) VALUES
(1, 'flash', 'Flash', '|флеш|флэш|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(2, 'little_fighter', 'Little_Fighter', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(3, 'lan_game', 'Игра_по_сети', '|LAN_game|LANでゲーム|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(4, 'fighting', 'Файтинг', '|Fighting|ファイティング|', '', 2, 0, 0, 0, 0, 0, 0, 0),
(5, 'j_pop', 'Японская_поп_музыка', '|Джей-поп|Джей_поп|ジェイポップ|Jpop|Japanese_pop_music|J-pop|ポップ音楽|ポップ_ミュージック|', '', 1, 0, 2, 1, 0, 0, 0, 0),
(6, 'album', 'Альбом', '|Album|アルバム|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(7, 'mikan_no_yuki', 'Mikan_no_Yuki', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(8, 'акибан', 'Акибан', '|', '', 2, 0, 0, 0, 0, 0, 0, 0),
(9, 'анимацури', 'Анимацури', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(10, 'трейлер', 'Трейлер', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(11, 'феникс', 'Феникс', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(12, 'фестиваль', 'Фестиваль', '|', '', 3, 0, 0, 0, 0, 0, 0, 0),
(13, 'cosplay', 'Косплей', '|コスプレ|Costume_play|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(14, 'фотоотчет', 'Фотоотчет', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(16, 'touhou', 'Touhou', '|Toho|Тохо|東方|Tohou|東方アレンジ|', 'AA00AA', 4, 1, 3, 0, 2, 0, 0, 0),
(19, 'vocaloid', 'Vocaloid', '|ボカロイド|ボーカロイド|Вокалоид|', 'AA00AA', 0, 0, 1, 0, 2, 0, 1, 0),
(26, 'hatsune_miku', 'Hatsune_Miku', '|初音ミク|Хацуне_Мику|Miku|Хацунэ_Мику|', '00AA00', 0, 0, 1, 0, 2, 0, 1, 0),
(35, 'doujinshi', 'Любительская_манга', '|Додзинси|同人誌|Доджинши|Doujinshi|', '', 2, 1, 0, 0, 0, 0, 0, 0),
(37, 'амв', 'АМV', '|amv|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(44, 'wallpapers', 'Обои', '|Wallpapers|wp|壁紙|Wallpaper|ウォールペーパー|カベカミ|', '', 1, 0, 0, 0, 4, 0, 0, 0),
(45, 'visual_novel', 'Визуальная_новелла', '|Visual_novel|Визуальный_роман|Графическая_новелла|Графический_роман|ビジュアルノベル|VN|', '', 0, 0, 1, 4, 0, 0, 0, 0),
(46, 'shoot_em_up', 'Shoot_em_up', '|Shmup|Shooting|Games|Шмап|Шумп|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(47, 'tsukihime', 'Tsukihime', '|月姫|Тсукихиме|Shingetsutan_Tsukihime|', 'AA00AA', 0, 0, 0, 0, 1, 0, 0, 0),
(48, 'type_moon', 'Type-Moon', '|タイプムーン|Typemoon|', 'AA00AA', 0, 0, 0, 0, 5, 0, 0, 0),
(61, 'parody', 'Пародия', '|パロディ|パロ|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(68, 'черный_дракон', 'Черный_Дракон', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(86, 'suzumiya_haruhi_no_yuuutsu', 'Suzumiya_Haruhi_no_Yuuutsu', '|Меланхолия_Судзумии_Харухи|Меланхолия_Сузумии_Харухи|Melancholy_of_Haruhi_Suzumiya|Меланхолия_Харухи_Судзумии|Меланхолия_Харухи_Сузумии|涼宮ハルヒの憂鬱|', 'AA00AA', 1, 0, 0, 0, 11, 0, 0, 0),
(102, 'меха', 'Меха', '|', '', 0, 0, 0, 0, 9, 0, 0, 0),
(105, 'fate_stay_night', 'Fate/Stay_Night', '|フェイトステイナイト|Fate_Stay_Night|Фэйт/Стэй_Найт|FSN|Судьба/Ночь_Схватки|', 'AA00AA', 0, 0, 0, 0, 5, 0, 0, 0),
(117, 'переозвучка', 'Переозвучка', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(153, 'nitroplus', 'Nitroplus', '|Nitro+|ニトロプラス|', 'AA0000', 0, 0, 1, 3, 0, 0, 0, 0),
(200, 'card_captor_sakura', 'Card_Captor_Sakura', '|', 'AA00AA', 1, 0, 0, 0, 0, 0, 0, 0),
(214, 'богдан', 'Богдан', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(215, 'асацуки_до', 'Асацуки_До', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(217, 'lass', 'Lass', '|', '', 0, 0, 1, 2, 0, 0, 0, 0),
(218, '11eyes', '11eyes', '|11глаз|Eleven_Eyes|イレブンアイズ|Одиннадцать_глаз|', '', 0, 0, 1, 2, 0, 0, 0, 0),
(264, 'alstroemeria_records', 'Alstroemeria_Records', '|', '', 0, 0, 1, 0, 0, 0, 0, 0),
(292, 'itou_kanako', 'Itou_Kanako', '|いとうかなこ|威闘華鳴乎|', '', 0, 0, 0, 2, 0, 0, 0, 0),
(308, 'hirano_aya', 'Hirano_Aya', '|平野綾|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(342, 'after_death', 'After_Death', '|', '', 0, 1, 0, 0, 0, 0, 0, 0),
(343, 'lightpi', 'Lightpi', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(344, 'matsuki_ugatsu', 'Matsuki_Ugatsu', '|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(446, 'ono_masatoshi', 'Ono_Masatoshi', '|小野正利|', '', 0, 0, 0, 1, 0, 0, 0, 0),
(480, 'nao', 'Nao', '|', '00AA00', 0, 0, 1, 0, 0, 0, 0, 0),
(505, 'promo_video', 'Промо-видео', '|PV|Promo_video|Promotion_video|プロモーションビデオ|', '', 0, 0, 3, 1, 0, 0, 0, 0),
(528, 'eastnewsound', 'EastNewSound', '|', '', 0, 0, 1, 0, 0, 0, 0, 0),
(591, 'mosaic_wav', 'MOSAIC.WAV', '|', '', 0, 0, 0, 1, 0, 0, 0, 0),
(645, 'ayane', 'Ayane', '|彩音|', '00AA00', 0, 0, 1, 2, 0, 0, 0, 0),
(649, '5pb', '5pb.', '|', '', 0, 0, 2, 1, 0, 0, 0, 0),
(650, 'chaos_head', 'Chaos;Head', '|カオスヘッド|Хаос_Вершина|ChäoS;HEAd|', 'AA00AA', 0, 0, 1, 0, 0, 0, 0, 0),
(693, 'animatsiya', 'Анимация', '|', '', 0, 0, 1, 0, 0, 0, 0, 0),
(728, 'claymore', 'Claymore', '|', 'AA00AA', 0, 0, 0, 0, 1, 0, 0, 0),
(756, 'kara_no_shoujo', 'Kara_no_Shoujo', '|殻ノ少女|', 'AA00AA', 0, 0, 0, 0, 20, 0, 0, 0),
(794, 'yaoi', 'Yaoi', '|Яой|Яои|やおい|BL|Boys_Love|', '', 1, 0, 0, 0, 0, 0, 0, 0),
(842, 'gadget_trial', 'Gadget_Trial', '|ガジェットトライアル|', 'AA00AA', 0, 0, 0, 0, 1, 0, 0, 0),
(858, 'ueda_ryo', 'Ueda_Ryo', '|植田亮|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(974, 'yuri', 'Yuri', '|Седзё-ай|Седзё-аи|Седзё_аи|Седзё_ай|Юри|Shoujo-ai|Shoujo_ai|少女愛|Шоджо-ай|Шоджо-аи|Шоджо_ай|Шоджо_аи|', '', 0, 1, 0, 0, 1, 0, 0, 0),
(983, 'game', 'game', '|', '', 0, 0, 1, 0, 0, 0, 0, 0),
(985, 'amano_ai', 'amano_ai', '|', '00AA00', 0, 0, 0, 0, 4, 0, 0, 0),
(986, 'yukirin', 'yukirin', '|', 'AA0000', 0, 0, 0, 0, 5, 0, 0, 0),
(987, 'sister_princess', 'sister_princess', '|', 'AA00AA', 0, 0, 0, 0, 12, 0, 0, 0),
(988, 'saber', 'Saber', '|Сэйбер|セイバー|Сейбер|Сейба|', '00AA00', 0, 0, 0, 0, 4, 0, 0, 0),
(998, 'toshiaki_takayama', 'Toshiaki_Takayama', '|', '', 0, 0, 0, 0, 8, 0, 0, 0),
(999, 'lancer', 'Lancer', '|ランサー|Лансер|', '00AA00', 0, 0, 0, 0, 2, 0, 0, 0),
(1000, 'canaan', 'Canaan', '|カナン|Ханаан|', 'AA00AA', 0, 0, 0, 0, 2, 0, 0, 0),
(1001, 'alphard', 'Alphard_Alshua', '|アルファルド|Alphard|Альфард|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1002, 'arcueid_brunestud', 'Arcueid_Brunestud', '|アルクェイド・ブリュ|ンスタッド|Arcueid|Арквейд|Арквейд_Брюнстуд|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1003, 'armor', 'armor', '|доспехи|броня|доспех|', '', 0, 0, 0, 0, 3, 0, 0, 0),
(1004, 'black_hair', 'black_hair', '|черные_волосы|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1005, 'blonde_hair', 'blonde_hair', '|светлые_волосы|', '', 0, 0, 0, 0, 5, 0, 0, 0),
(1006, 'brown_eyes', 'brown_eyes', '|коричневые_глаза|', '', 0, 0, 0, 0, 9, 0, 0, 0),
(1007, 'brown_hair', 'brown_hair', '|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1008, 'crossover', 'crossover', '||', 'AA0000', 0, 0, 0, 0, 1, 0, 0, 0),
(1009, 'fate_unlimited_codes', 'Fate/Unlimited_Codes', '|フェイト/アンリミテッドコード|Fate_Unlimited_Codes|', 'AA00AA', 0, 0, 0, 0, 2, 0, 0, 0),
(1010, 'gloves', 'gloves', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1011, 'green_eyes', 'green_eyes', '|зеленые_глаза|зелёные_глаза|', '', 0, 0, 0, 0, 4, 0, 0, 0),
(1012, 'grey_eyes', 'grey_eyes', '|серые_глаза|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1013, 'japanese_clothes', 'japanese_clothes', '|kimono|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1014, 'kara_no_kyoukai', 'Kara_no_Kyoukai', '|', 'AA00AA', 0, 0, 0, 0, 1, 0, 0, 0),
(1015, 'kimono', 'kimono', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1016, 'kio_sayuki', 'kio_sayuki', '|', 'AA0000', 0, 0, 0, 0, 1, 0, 0, 0),
(1017, 'liang_qi', 'Liang_Qi', '|リャン・チー|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1018, 'red_eyes', 'red_eyes', '|красные_глаза|red_eye|', '', 0, 0, 0, 0, 5, 0, 0, 0),
(1019, 'ryougi_shiki', 'Ryougi_Shiki', '|両儀織|両儀式|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1020, 'saber_lily', 'Saber_Lily', '|Сэйбер_Лили|Сейбер_Лили|', '00AA00', 0, 0, 0, 0, 2, 0, 0, 0),
(1021, 'white_hair', 'white_hair', '|белые_волосы|', '', 0, 0, 0, 0, 3, 0, 0, 0),
(1024, 'nagato_yuki', 'Nagato_Yuki', '|Нагато_Юки|', '00AA00', 0, 0, 0, 0, 11, 0, 0, 0),
(1026, 'serafuku', 'serafuku', '|', '', 0, 0, 0, 0, 7, 0, 0, 0),
(1027, 'neon_genesis_evangelion', 'Neon_Genesis_Evangelion', '|Evangelion|Евангелион|新世紀エヴァンゲリオン|NGE|', 'AA00AA', 0, 0, 0, 0, 2, 0, 0, 0),
(1028, '3girls', '3girls', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1029, 'souryuu_asuka_langley', 'Souryuu_Asuka_Langley', '|Сорью_Аска_Лэнгли|Аска|Asuka|Soryu_Asuka_Langley|asuka_langley_soryu|Asuka_Langley|shikinami_asuka_langley|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1030, 'ayanami_rei', 'Ayanami_Rei', '|Аянами_Рэй|', '00AA00', 0, 0, 0, 0, 2, 0, 0, 0),
(1031, 'makinami_mari_illustrious', 'Makinami_Mari_Illustrious', '|Mari_Illustrious_Makinami|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1032, 'linux', 'Linux', '|Линупс|Линукс|Линух|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1033, 'yellow_eyes', 'yellow_eyes', '|желтые_глаза|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1034, 'white_eyes', 'white_eyes', '|белые_глаза|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1035, '2girls', '2girls', '|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1036, 'blue_hair', 'blue_hair', '|синие_волосы|голубые_волосы|', '', 0, 0, 0, 0, 6, 0, 0, 0),
(1037, 'clare', 'Clare', '|clare_(claymore)|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1038, 'purple_hair', 'purple_hair', '|пурпурные_волосы|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1039, 'purple_eyes', 'purple_eyes', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1040, 'book', 'book', '|', '', 0, 0, 0, 0, 4, 0, 0, 0),
(1041, 'suzumiya_haruhi', 'Suzumiya_Haruhi', '|Сузумия_Харухи|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1042, 'asahina_mikuru', 'asahina_mikuru', '|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1043, 'glasses', 'glasses', '|очки|', '', 0, 0, 0, 0, 4, 0, 0, 0),
(1044, 'monochrome', 'monochrome', '|', '', 0, 0, 0, 0, 2, 0, 1, 0),
(1045, 'highres', 'highres', '|hires|hi_res|hi-res|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1046, 'mazinger', 'Мазингер', '|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1047, 'devilmen', 'Дэвилмэн', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1050, 'red_hair', 'red_hair', '|красные_волосы|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1051, 'swimsuit', 'swimsuit', '|купальник|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1052, 'water', 'water', '|вода|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1053, 'weapon', 'weapon', '|Оружие|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1054, 'blue_eyes', 'blue_eyes', '|голубые_глаза|синие_глаза|blue_eye|', '', 0, 0, 0, 0, 3, 0, 0, 0),
(1056, 'animal_ears', 'animal_ears', '|獣耳|けものみみ|kemonomimi|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1057, 'creepy', 'creepy', '|scary|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1058, 'comic', 'comic', '|', '', 0, 0, 0, 0, 0, 0, 1, 0),
(1063, 'cirno', 'Cirno', '|Сырно|Цырно|chirno|чирно|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1065, 'green_hair', 'green_hair', '|зеленые_волосы|зелёные_волосы|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1066, 'wings', 'Wings', '|Крылья|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1123, 'moon', 'moon', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1137, 'tail', 'tail', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1169, 'mecha_musume', 'mecha_musume', '|mechagirl|mechamusume|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1179, 'short_hair', 'short_hair', '|короткие_волосы|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1202, 'sitting', 'sitting', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1211, 'machine', 'machine', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1221, 'long_hair', 'long_hair', '|длинные_волосы|', '', 0, 0, 0, 0, 3, 0, 1, 0),
(1250, 'sword', 'sword', '|swords|меч|мечи|', '', 0, 0, 0, 0, 4, 0, 0, 0),
(1261, 'antennae', 'antennae', '|волосы_антенна|antenna_hair|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1328, 'ahoge', 'ahoge', '|', '', 0, 0, 0, 0, 3, 0, 0, 0),
(1383, 'soul_crushingly_depressing', 'Soul_Crushingly_Depressing', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1479, 'myon', 'Myon', '|Мён|みょん|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1480, 'game_cg_map', 'game_cg_map', '|', '', 0, 0, 0, 0, 2, 0, 0, 0),
(1484, 'chibi', 'Chibi', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1499, 'microfon', 'Microfon', '|Микрофон|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1529, 'twintails', 'twintails', '|twin_tails|', '', 0, 0, 0, 0, 2, 0, 1, 0),
(1535, 'very_long_hair', 'very_long_hair', '|очень_длинные_волосы|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1590, 'konpaku_youmu', 'Konpaku_Youmu', '|Youmu_Konpaku|Konpaku_Yomu|Ёму|Компаку_Ёму|Конпаку_Ёму|Йому|Компаку_Йому|Конпаку_Йому|魂魄_妖夢|こんぱく_ようむ|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1591, 'saigyouji_yuyuko', 'Saigyouji_Yuyuko', '|Yuyuko_Saigyouji|Yuyuko_Saigyouji.|сайгёдзи_ююко|', '00AA00', 0, 0, 0, 0, 1, 0, 0, 0),
(1681, 'old_man', 'old_man', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1688, 'bow', 'bow', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1711, 'scarf', 'scarf', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1802, 'fire', 'fire', '|', '', 0, 0, 0, 0, 1, 0, 0, 0),
(1831, 'star_sky', 'star_sky', '|', '', 0, 0, 0, 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `username` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'main',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`sortdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=254 ;

--
-- Дамп данных таблицы `updates`
--

INSERT INTO `updates` (`id`, `post_id`, `username`, `text`, `pretty_text`, `link`, `pretty_date`, `sortdate`, `area`) VALUES
(170, 9, 'Безымянный', 'Добавлен перевод на русский.<br />\nПеревод выполнен <a href="/go?http://mangany.ucoz.ru/load/">Мангани</a>.', 'Добавлен перевод на русский.\r\nПеревод выполнен [url=http://mangany.ucoz.ru/load/]Мангани[/url].', 'a:1:{i:0;a:7:{s:4:"name";s:34:"Скачать на русском";s:4:"link";s:87:"&lt;Яндекс.Диск&gt;http://narod.yandex.ru/disk/8510754001/Suzumia_-_Koval.rar";s:4:"size";s:3:"6,2";s:8:"sizetype";s:4:"мб";s:3:"url";a:2:{i:0;s:58:"http://narod.yandex.ru/disk/8510754001/Suzumia_-_Koval.rar";i:1;s:58:"http://www.4shared.com/file/YL847wWM/Suzumia_-_Koval.html ";}s:5:"alias";a:2:{i:0;s:21:"Яндекс.Диск";i:1;s:7:"4shared";}s:6:"search";s:33:"&lt;Яндекс.Диск&gt;http";}}', 'Март 27, 2011', 1301201682310, 'main');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `cookie` varchar(32) CHARACTER SET utf8 NOT NULL,
  `rights` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;


--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `pass`, `email`, `cookie`, `rights`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'example@admin', '', 2),
(2, 'moderator', '0408f3c997f309c03b08bf3a4bc7b730', 'example@moder', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `versions`
--

CREATE TABLE IF NOT EXISTS `versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `time` bigint(16) NOT NULL,
  `author` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`item_id`,`time`,`author`(255),`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `versions`
--


-- --------------------------------------------------------

--
-- Структура таблицы `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(4096) COLLATE utf8_unicode_ci NOT NULL,
  `object` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `author` text COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `tag` text COLLATE utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1091 ;

--
-- Дамп данных таблицы `video`
--

INSERT INTO `video` (`id`, `title`, `link`, `object`, `text`, `pretty_text`, `author`, `category`, `tag`, `comment_count`, `last_comment`, `pretty_date`, `sortdate`, `area`) VALUES
(1, 'Hatsune Miku vs. Nico Nico Douga', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/zXsrRLT2ijs&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/zXsrRLT2ijs&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Нарисованный бой Мику против многих известных мемов-персонажей с НикоНико', 'Нарисованный бой Мику против многих известных мемов-персонажей с НикоНико', '|nameless|', '|none|', '|vocaloid|hatsune_miku|niconico|animatsiya|', 4, 1307119588750, 'Февраль 27, 2010', 1267275602000, 'main'),
(2, 'Первое промо видео к игре  Tenshi_no_Nichou_Kenjuu', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/m2to-OrIymM&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/m2to-OrIymM&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Первое демо-видео к игре Angelos Armas от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Chuuou Higashiguchi. Музыкальный трек называется Samenai Netsu и исполнен певцом Watanabe Kazuhiro.', 'Первое демо-видео к игре Angelos Armas от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Chuuou Higashiguchi. Музыкальный трек называется Samenai Netsu и исполнен певцом Watanabe Kazuhiro.', '|ofen|', '|none|', '|visual_novel|opening|nitroplus|watanabe_kazuhiro|tenshi_no_nichou_kenjuu|', 2, 1315645309510, 'Сентябрь 10, 2011', 1315646985070, 'deleted'),
(3, 'Второе демо-видео к игре Angelos Armas', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/uWCga7RpEfY&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/uWCga7RpEfY&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Второе демо-видео к игре Angelos Armas от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Chuuou Higashiguchi. Музыкальный трек называется Kesshou и исполнен певицей Itou Kanako.', 'Второе демо-видео к игре Angelos Armas от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Chuuou Higashiguchi. Музыкальный трек называется Kesshou и исполнен певицей Itou Kanako.', '|ofen|', '|none|', '|nitroplus|visual_novel|itou_kanako|opening|', 0, 0, 'Сентябрь 5, 2010', 1283692012810, 'flea_market'),
(4, 'Заставка к игре 11eyes CrossOver для XBox360', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/FtrdjafueJg&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/FtrdjafueJg&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Заставка к игре 11eyes для XBox360 от студии Lass. Иллюстрации в игре выполнили такие художники, как: Hagiwara Onsen, Chikotam, Youta, Ozawa Yuu, Narumi Yuu, KENGOU и ZEN. Музыкальный трек называется Endless Tears... и исполнен певицей Ayane.', 'Заставка к игре 11eyes для XBox360 от студии Lass. Иллюстрации в игре выполнили такие художники, как: Hagiwara Onsen, Chikotam, Youta, Ozawa Yuu, Narumi Yuu, KENGOU и ZEN. Музыкальный трек называется Endless Tears... и исполнен певицей Ayane.', '|ofen|', '|games|', '|lass|visual_novel|ayane|opening|11eyes|5pb|', 0, 0, 'Сентябрь 5, 2010', 1283692002920, 'flea_market'),
(5, 'Клип к песне Bad Apple с русскими субтитрами', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/Hiqn1Ur32AE&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/Hiqn1Ur32AE&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Клип к песне Bad Apple от любительского круга Alstroemeria Records. Русские субтитры выполнил <a href="http://raincat.4otaku.ru/">RainCat</a>.', 'Клип к песне Bad Apple от любительского круга Alstroemeria Records. Русские субтитры выполнил [url=http://raincat.4otaku.ru/]RainCat[/url].', '|ofen|', '|none|', '|alstroemeria_records|promo_video|touhou|j_pop|bad_apple|', 0, 0, 'Февраль 27, 2010', 1267277283000, 'main'),
(6, 'Клип к песне Hiiro Gekka, Kuruizaki no Zetsu -1st Anniversary Remix- с русскими субтитрами', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/FYyGsofAQF8&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/FYyGsofAQF8&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Клип к песне Hiiro Gekka, Kuruizaki no Zetsu -1st Anniversary Remix- от любительского круга EastNewSound. Русские субтитры выполнил <a href="http://raincat.4otaku.ru/">RainCat</a>.', 'Клип к песне Hiiro Gekka, Kuruizaki no Zetsu -1st Anniversary Remix- от любительского круга EastNewSound. Русские субтитры выполнил [url=http://raincat.4otaku.ru/]RainCat[/url].', '|ofen|', '|none|', '|eastnewsound|promo_video|touhou|j_pop|', 1, 1282892807900, 'Февраль 27, 2010', 1267277762000, 'main'),
(7, 'Заставка к игре 11eyes', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/G2pFeLXCuIs&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/G2pFeLXCuIs&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Заставка к игре 11eyes от студии Lass. Иллюстрации в игре выполнили такие художники, как: Hagiwara Onsen, Chikotam, Ozawa Yuu, KENGOU и Narumi Yuu. Музыкальный трек называется Lunatic Tears... и исполнен певицей Ayane.', 'Заставка к игре 11eyes от студии Lass. Иллюстрации в игре выполнили такие художники, как: Hagiwara Onsen, Chikotam, Ozawa Yuu, KENGOU и Narumi Yuu. Музыкальный трек называется Lunatic Tears... и исполнен певицей Ayane.', '|ofen|', '|games|', '|lass|ayane|opening|11eyes|', 0, 0, 'Сентябрь 5, 2010', 1283692151830, 'flea_market'),
(8, 'Клип к песне Superluminal Ж Akiba-Pop', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/VIh-wAEo4Mo&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/VIh-wAEo4Mo&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Клип к песне Superluminal Ж AKIBA-POP от группы MOSAIC.WAV.', 'Клип к песне Superluminal Ж AKIBA-POP от группы MOSAIC.WAV.', '|ofen|', '|none|', '|mosaic_wav|promo_video|j_pop|', 0, 0, 'Сентябрь 5, 2010', 1283692138710, 'flea_market'),
(9, 'Промо-видео к игре 11eyes CrossOver для PSP', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/Ho7h3WEJkKs&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/Ho7h3WEJkKs&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Заставка к игре 11eyes для PSP от студии Lass. Иллюстрации в игре выполнили такие художники, как: Hagiwara Onsen, Chikotam, Youta, Ozawa Yuu, Narumi Yuu, KENGOU и ZEN. Музыкальный трек называется Shinjitsu he no Chinkonka и исполнен певицей Ayane.', 'Заставка к игре 11eyes для PSP от студии Lass. Иллюстрации в игре выполнили такие художники, как: Hagiwara Onsen, Chikotam, Youta, Ozawa Yuu, Narumi Yuu, KENGOU и ZEN. Музыкальный трек называется Shinjitsu he no Chinkonka и исполнен певицей Ayane.', '|ofen|', '|games|', '|ayane|opening|promo_video|11eyes|lass|5pb|', 0, 0, 'Февраль 27, 2010', 1267286520000, 'main'),
(10, 'Демо-видео к игре Chaos;Head Love Chu☆Chu! для XBox360', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/-dMHXUJyISU&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/-dMHXUJyISU&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Демо-видео к игре Chaos;Head Love Chu☆Chu! для XBox360 от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Sasaki Mutsumi. Музыкальный трек называется Synchro Shiyouyo и исполнен певицей NAO.', 'Демо-видео к игре Chaos;Head Love Chu☆Chu! для XBox360 от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Sasaki Mutsumi. Музыкальный трек называется Synchro Shiyouyo и исполнен певицей NAO.', '|ofen|', '|games|', '|nao|nitroplus|visual_novel|opening|chaos_head|5pb|', 0, 0, 'Февраль 27, 2010', 1267287783000, 'main'),
(14, 'Вторая заставка к игре Muramasa', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/e_3xjr4s1wg&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/e_3xjr4s1wg&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Вторая заставка к игре Muramasa от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Namaniku ATK. Музыкальный трек называется Muramasa и исполнен певцом Ono Masatoshi.', 'Вторая заставка к игре Muramasa от студии Nitroplus (Nitro+). Иллюстрации выполнены художником Namaniku ATK. Музыкальный трек называется Muramasa и исполнен певцом Ono Masatoshi.', '|ofen|', '|none|', '|nitroplus|visual_novel|ono_masatoshi|opening|', 0, 0, 'Сентябрь 5, 2010', 1283692205580, 'flea_market'),
(13, 'Тохо 06 - Эстра уровень увеличенной сложности', '***', '<script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/sm8508923"></script>', 'Сомневаюсь что кто-то из европейцев способен на такое.', 'Сомневаюсь что кто-то из европейцев способен на такое.', '|raincat|', '|games|', '|touhou|game|', 4, 1267469968000, 'Март 1, 2010', 1267451212000, 'main'),
(15, 'Заставка к игре Steins;Gate для XBox360', '***', '<object width="%video_width%" height="%video_height%"><param name="movie" value="http://www.youtube.com/v/fFe130aZGss&hl=ru_RU&fs=1&border=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/fFe130aZGss&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed></object>', 'Заставка к игре Steins;Gate для XBox360 от студии Nitroplus (Nitro+). Иллюстрации в игре выполнены художники Huke и Sh@rp. Музыкальный трек называется Sky Clad no Kansokusha и исполнен певицей Itou Kanako.', 'Заставка к игре Steins;Gate для XBox360 от студии Nitroplus (Nitro+). Иллюстрации в игре выполнены художники Huke и Sh@rp. Музыкальный трек называется Sky Clad no Kansokusha и исполнен певицей Itou Kanako.', '|ofen|', '|none|', '|itou_kanako|nitroplus|visual_novel|opening|', 2, 1267470535000, 'Сентябрь 5, 2010', 1283692230400, 'flea_market');

-- --------------------------------------------------------
