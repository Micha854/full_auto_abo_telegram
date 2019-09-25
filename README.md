# full_auto_abo_telegram
Ein voll Automatisiertes Abo- System für einen Telegram Kanal

### Config
folgende dateien müssen angepasst werden:

* config_example.php		--> config.php
* ggf. noch den admin/ per .htaccess schützen !

### install

lade folgendes in den admin/ ordner
https://github.com/danog/MadelineProto


### SQL Telegram Chanel
name der tabelle muss in --> config_example.php angepasst werden !!


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
### SQL products

```
--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `months` varchar(2) NOT NULL,
  `item_number` varchar(6) NOT NULL,
  `item_price` varchar(5) NOT NULL,
  `abo_days` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`id`, `months`, `item_number`, `item_price`, `abo_days`) VALUES
(1, '1', '10000', '0.87', '30'),
(2, '3', '30000', '1.90', '90'),
(3, '6', '60000', '3.44', '180');

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
```
