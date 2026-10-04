-- Partner başvurularına kaynak, dil, ofis ve kampanya (UTM) bilgilerini ekler.
-- phpMyAdmin > SQL sekmesinde bir kez çalıştırın. Çalıştırılmadan önce de form
-- çalışır: yeni kolonlar yoksa kayıt eski şemaya yapılır ve e-posta yine gönderilir.

ALTER TABLE `partnership_applications`
  ADD COLUMN `source` varchar(100) DEFAULT NULL AFTER `url`,
  ADD COLUMN `lang` varchar(10) DEFAULT NULL AFTER `source`,
  ADD COLUMN `office` varchar(100) DEFAULT NULL AFTER `lang`,
  ADD COLUMN `utm_source` varchar(255) DEFAULT NULL AFTER `office`,
  ADD COLUMN `utm_medium` varchar(255) DEFAULT NULL AFTER `utm_source`,
  ADD COLUMN `utm_campaign` varchar(255) DEFAULT NULL AFTER `utm_medium`,
  ADD COLUMN `utm_content` varchar(255) DEFAULT NULL AFTER `utm_campaign`,
  ADD COLUMN `utm_term` varchar(255) DEFAULT NULL AFTER `utm_content`,
  ADD COLUMN `gclid` varchar(255) DEFAULT NULL AFTER `utm_term`,
  ADD COLUMN `referrer` text DEFAULT NULL AFTER `gclid`;
