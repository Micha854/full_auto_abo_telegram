# full_auto_abo_telegram
Ein voll Automatisiertes Abo- System für einen Telegram Kanal

Support On <a href="https://discord.gg/jsvX9pz">Discord</a>

### Install

lade folgendes in den admin/ ordner
https://github.com/danog/MadelineProto

### Verbindung zum Madline Client aufbauen

in admin/_auth_client.php passe folgende Zeilen an:

* $InputChannel = 'https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ';	// YOUR Telegram Chanel
* $InputUser = '@username'; // Username der dem Kanal hinzugefügt werden soll

Rufe dann im Browser YOURURL.COM/admin/_auth_client.php auf

Gib deine Rufnummer an, du bekommst dann einen Code mit dem du dich verifizieren musst. Als nächstes logge dich als User mit deinem Telegram Username ein (Wichtig!! Es muss ein Admin des Kanals sein, der User hinzufügen darf). Hierzu bekommst du auch nochmal einen Code. Fertig! Unter Telegram Einstellungen / Sicherheit / Aktive Sitzungen sollte nun deine neue Sitzung angezeigt werden ;) Dieser Schritt ist wichtig, damit das Script in Zukunft User dem Kanal hinzufügen und löschen kann.

### PayPal API einrichten
Logge dich in deinen PayPal Account ein! Danach öffne im selben Browser-Tab folgende URL:

https://www.paypal.com/businessmanage/credentials/apiAccess

Wähle die Option "NVP/SOAP API integration" und erstelle API Username, Password & Signature (diese Daten dann in die config.php)

### Config
folgende Dateien müssen angepasst werden:

* config_example.php		--> config.php
* ggf. noch den admin/ per .htaccess schützen !

Erstelle einen stündlichen Cronjob für YOURURL.COM/admin/_cron.php (hierbei werden abgelaufene Abos-User aus dem Kanal und der Datenbank entfernt)

### Zugriff auf Rocketmap via .htpasswd

Wenn User Zugriff auf die Rocketmap haben dürfen muss folgendes (in Ubuntu) konfiguriert werden. 
erstelle eine neue Datei "rocketmap.conf" in /etc/apache2/sites-available/

```
ServerName YOURDOMAIN.de
ProxyPass /go/ http://YOURIP:46516/
ProxyPassReverse /go/ http://127.0.0.1:46516/

    <Proxy *>
        Order deny,allow
        Allow from all
        Authtype Basic
        Authname "Password Required"
        AuthUserFile /var/www/vhosts/YOURDOMAIN.de/httpdocs/.htpasswd
        Require valid-user
    </Proxy>

RewriteCond %{HTTP_HOST} !^YOURDOMAIN\.de/go/$ [NC]
RewriteRule ^/go/$ http://%{HTTP_HOST}/go/ [L,R=301]
```

eingebunden wird die configuration in 000-default.conf mit der zeile "Include sites-available/rocketmap.conf
"


### SQL Telegram Chanel
Name der Tabelle muss in --> config_example.php angepasst werden!!


```
CREATE TABLE `abos` (
  `id` int(11) NOT NULL,
  `buyerName` varchar(155) NOT NULL,
  `buyerEmail` varchar(255) NOT NULL,
  `Amount` varchar(5) NOT NULL,
  `TelegramUser` varchar(155) NOT NULL,
  `channels` varchar(55) NOT NULL,
  `pass` varchar(8) NOT NULL,
  `paydate` datetime NOT NULL,
  `endtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
```
### SQL products (Produkte müssen angepasst werden)

```
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
### SQL channels

```
CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `name` varchar(155) NOT NULL,
  `url` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `channels`
--

INSERT INTO `channels` (`id`, `name`, `url`) VALUES
(1, 'Kanal 1', 'https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ'),
(2, 'Kanal 2', 'https://t.me/Kanal2'),
(3, 'Kanal 3', 'https://t.me/joinchat/XXXXXXgy6i4Y6WxnEQQNqw'),
(4, 'Kanal 4', 'https://t.me/Kanal4');

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
```
### PMSF Datenbank anpassen

```
ALTER TABLE `users`
  ADD UNIQUE KEY `user` (`user`);
```
