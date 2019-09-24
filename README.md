# full_auto_abo_telegram
Ein voll Automatisiertes Abo- System für einen Telegram Kanal

### Config
folgende dateien müssen angepasst werden:

* config_example.php		--> config.php
* index_example.php		--> index.php
* items_example.php		--> items.php

* admin/_config_example.php	--> admin/_config.php

* ggf. noch den admin/ per .htaccess schützen !

### install

lade folgendes in den admin/ ordner
https://github.com/danog/MadelineProto


### SQL
name der tabelle muss in --> admin/_config_example.php angepasst werden !!


```
CREATE TABLE `AAAAAEOzl0uIG6rC2xuqjQ` (
  `id` int(11) NOT NULL,
  `buyerName` varchar(155) NOT NULL,
  `buyerEmail` varchar(255) NOT NULL,
  `Amount` varchar(5) NOT NULL,
  `TelegramUser` varchar(155) NOT NULL,
  `pass` varchar(8) NOT NULL,
  `paydate` datetime NOT NULL,
  `endtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes für die Tabelle `AAAAAEOzl0uIG6rC2xuqjQ`
--
ALTER TABLE `AAAAAEOzl0uIG6rC2xuqjQ`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `TelegramUser` (`TelegramUser`);

--
-- AUTO_INCREMENT für Tabelle `AAAAAEOzl0uIG6rC2xuqjQ`
--
ALTER TABLE `AAAAAEOzl0uIG6rC2xuqjQ`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```
