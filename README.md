# full_auto_abo_telegram
Ein voll Automatisiertes Abo- System für einen/mehrere Telegram Kanal/Kanäle

Support On <a href="https://discord.gg/jsvX9pz">Discord</a>

### TelegramApiServer

`git clone https://github.com/xtrime-ru/TelegramApiServer.git`

wechsel in das Verzeichniss und führe `composer install` aus, der TelegramAPIServer wird nun installiert.

anschließend die Datei `.env.example` in `.env` umbenennen und den `HOST` eintragen

starten wir den Server mit `php server.php`

#### TelegramApiServer in den Autostart migrieren

Damit der ApiServer 24/7 läuft installieren wir diesen als Systemdienst. Hierzu können wir zb. Supervisor verwenden

Falls Supervisor noch nicht installiert ist (in Ubuntu) `apt-get install supervisor`

damit Supervisor startet führen wir noch einmal `service supervisor restart` aus

Um nun die API als Systemdienst zu migrieren gehen wir in das Verzeichniss `/etc/supervisor/conf.d/` und erstellen hier eine neue Datei `telegram_client.conf` mit folgendem Inhalt (den path zur api bitte anpassen!)
```
[program:telegram_client]
command=/usr/bin/php /home/USER/TelegramApiServer/server.php
numprocs=1
directory=/home/USER/TelegramApiServer/
autostart=true
autorestart=true
stdout_logfile=none
redirect_stderr=true
```

Nun müssen wir mit `supervisorctl reread` Supervisor über unser neues Programm informieren und ihm anschließend mit `supervisorctl update` mitteilen, dass er unser neues Programm aufnimmt. Damit sollte die API 24/7 laufen und im Falle eines Fehlers sowie beim reboot neugestartet werden

### PayPal API einrichten
Logge dich in deinen PayPal Account ein! Danach öffne im selben Browser-Tab folgende URL:

https://www.paypal.com/businessmanage/credentials/apiAccess

Wähle die Option "NVP/SOAP API integration" und erstelle API Username, Password & Signature (diese Daten dann in die config.php)

### Config
folgende Dateien müssen angepasst werden:

* config_example.php        --> config.php
* admin/msg_example.php     --> /admin/msg.php
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

### bei Verwendung von PMSF als MAP

Es müssen manualdb (PMSF) und unsere 3 Tabellen (siehe unten) zusammengeführt werden!
Folgende Anpassung muss außerdem die Spalte "users" bekommen

```
ALTER TABLE `users`
  ADD UNIQUE KEY `user` (`user`);
```

Wenn du alle vorhanden User aus der PMSF tabelle `users` in die tabelle `abos` kopieren möchtest kannst du folgenden SQL Befehl benutzten:

```
INSERT INTO abos (id, buyerName, buyerEmail, Amount, TelegramUser, userid, channels, pass, TransID, paydate, endtime, info)  
SELECT id, '', user, '', user, NULL, '1', '', NULL, now(), FROM_UNIXTIME(expire_timestamp), NULL
FROM users;
```

im Anschluß musst du die spalte `TelegramUsername` natürlich anpassen ;)

### SQL Telegram Chanel
Name der Tabelle muss in `config.php` angepasst werden!!


```
CREATE TABLE `abos` (
  `id` int(11) NOT NULL,
  `buyerName` varchar(155) NOT NULL,
  `buyerEmail` varchar(255) NOT NULL,
  `Amount` varchar(5) NOT NULL,
  `TelegramUser` varchar(155) NOT NULL,
  `userid` bigint(10) DEFAULT NULL,
  `channels` varchar(55) NOT NULL,
  `pass` varchar(8) NOT NULL,
  `TransID` varchar(25) DEFAULT NULL,
  `paydate` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `info` int(1) 
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
  `url` varchar(155) NOT NULL,
  `chatid` bigint(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `channels`
--

INSERT INTO `channels` (`id`, `name`, `url`, `chatid`) VALUES
(1, 'Kanal 1', 'https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ', NULL),
(2, 'Kanal 2', 'https://t.me/Kanal2', NULL),
(3, 'Kanal 3', 'https://t.me/joinchat/XXXXXXgy6i4Y6WxnEQQNqw', NULL),
(4, 'Kanal 4', 'https://t.me/Kanal4', NULL);

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
