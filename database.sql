CREATE TABLE `abos` (
  `id` int(11) NOT NULL,
  `buyerName` varchar(155) NOT NULL,
  `city` varchar(25) DEFAULT NULL,
  `buyerEmail` varchar(255) NOT NULL,
  `Amount` varchar(5) NOT NULL,
  `TelegramUser` varchar(155) NOT NULL,
  `userid` bigint(10) DEFAULT NULL,
  `channels` varchar(55) DEFAULT NULL,
  `pass` varchar(16) NOT NULL,
  `TransID` varchar(25) DEFAULT NULL,
  `paydate` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `interaktion` datetime DEFAULT NULL,
  `info` int(1) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `name` varchar(155) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `url` varchar(155) NOT NULL,
  `chatid` bigint(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `channels`
--

INSERT INTO `channels` (`id`, `name`, `sort`, `url`, `chatid`) VALUES
(1, 'Kanal 1', NULL, 'https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ', NULL),
(2, 'Kanal 2', NULL, 'https://t.me/Kanal2', NULL),
(3, 'Kanal 3', NULL, 'https://t.me/joinchat/XXXXXXgy6i4Y6WxnEQQNqw', NULL),
(4, 'Kanal 4', NULL, 'https://t.me/Kanal4', NULL);

--
-- Indizes für die Tabelle `abos`
--
ALTER TABLE `abos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `TelegramUser` (`TelegramUser`);

--
-- AUTO_INCREMENT für Tabelle `abos`
--
ALTER TABLE `abos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

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

--
-- Indizes für die Tabelle `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für Tabelle `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;