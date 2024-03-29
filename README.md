# full_auto_abo_telegram
Ein voll Automatisiertes Abo- System für einen/mehrere Telegram Kanal/Kanäle

Support On <a href="https://discord.gg/jsvX9pz">Discord</a>

# Requirements
- TelegramApiServer
- mindestens PHP 7.4
- Apache/Nginx
- Mysql

# 1. Einrichtung

Nachdem mein Repo geclont wurde wechsel in das Verzeichnis 

```
full_auto_abo_telegram/admin/
```

Nun installieren wir den TelegramApiServer
```
git clone https://github.com/xtrime-ru/TelegramApiServer.git
```
Der TelegramAPIServer sollte sich nun hier befinden
```
full_auto_abo_telegram/admin/TelegramAPIServer
```

wechsel nun in dieses Verzeichniss und führe `composer install -o --no-dev` aus, der TelegramAPIServer wird nun installiert.

anschließend erstellen wir uns aus der .env.example eine `.env` 
```
cp .env.example .env
```

Öffne nun die Datei mit zb. nano `nano .env`

### Example
```
# ENV file version
# Check for outdated .env files
VERSION=1

SERVER_ADDRESS=domain.com
SERVER_PORT=9503

MEMORY_LIMIT=256M
TIMEZONE=UTC
EXIT_ON_FATAL_EXCEPTION=true

# List of allowed clients. Separate with comma.
# Leave blanc, to allow requests from all IP (dangerous!)
IP_WHITELIST=127.0.0.1,YOUR_SERVER_IP

# TELEGRAM CLIENT
TELEGRAM_API_ID=
TELEGRAM_API_HASH=

# FATAL_ERROR = 0; ERROR = 1; WARNING = 2; const NOTICE = 3; VERBOSE = 4; ULTRA_VERBOSE = 5;
LOGGER_LEVEL=2

# TELEGRAM SOCKS5 PROXY (optional)
TELEGRAM_PROXY_ADDRESS=
TELEGRAM_PROXY_PORT=
TELEGRAM_PROXY_USERNAME=
TELEGRAM_PROXY_PASSWORD=
```

Hier wird nun der Host eingetragen und die Server_IP Adresse

`app_id` and `app_hash` bekommst du hier https://my.telegram.org/ oder lasse es einfach leer. Beim ersten start werden diese dann automatisch generiert.

starten wir den Server mit `php server.php --session=YOUR_SESSION_NAME` 

```ersetze "YOUR_SESSION_NAME" durch einen belibigen Namen```

## 1.1 TelegramApiServer in den Autostart migrieren

Damit der ApiServer 24/7 läuft installieren wir diesen als Systemdienst. Hierzu können wir zb. Supervisor oder pm2 verwenden

### - Supervisor:
Falls Supervisor noch nicht installiert ist (in Ubuntu) `apt-get install supervisor`

damit Supervisor startet führen wir noch einmal `service supervisor restart` aus

Um nun die API als Systemdienst zu migrieren gehen wir in das Verzeichniss `/etc/supervisor/conf.d/` und erstellen hier eine neue Datei `telegram_client.conf` mit folgendem Inhalt (den path zur api bitte anpassen!)
```
[program:telegram_client]
command=/usr/bin/php /home/USER/TelegramApiServer/server.php --session=YOUR_SESSION_NAME
numprocs=1
directory=/home/USER/TelegramApiServer/
autostart=true
autorestart=true
stdout_logfile=none
redirect_stderr=true
```

Nun müssen wir mit `supervisorctl reread` Supervisor über unser neues Programm informieren und ihm anschließend mit `supervisorctl update` mitteilen, dass er unser neues Programm aufnimmt. Damit sollte die API 24/7 laufen und im Falle eines Fehlers sowie beim reboot neugestartet werden

### - pm2:
```
pm2 start --name TelegramAboBotApi server.php --interpreter /usr/bin/php -- --session=YOUR_SESSION_NAME
```

## 1.2 BotFather erstellen

Starte einen Chat mit dem https://t.me/BotFather
Mit `/newbot` wird ein neuer Bot erstellt. Anschließend müssen im Dialog der Botname und der Benutzername angelegt werden.

Ist der bot erstellt notiere dir den bot Token, den brauchen wir aber erst später (config.php)

Erstelle nun einen bot Befehl mit `/setcommands`:
```
abo - Zeige Infos zum Abo an
```

Füge noch eine Beschreibung hinzu mit `/setdescription`:
```
Hi, Willkommen im Abo Kanal von ..... Hier erfährst du alle Informationen zu deinem Abo!
```

Webhook setzen (einmalig):
```
https://api.telegram.org/bot123456789:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/setWebhook?url=https://www.YOURURL.de/full_auto_abo_telegram/abo_bot.php
```

# 2. PayPal API einrichten
Logge dich in deinen PayPal Account ein! Danach öffne im selben Browser-Tab folgende URL:

https://www.paypal.com/businessmanage/credentials/apiAccess

Wähle die Option "NVP/SOAP API integration" und erstelle API Username, Password & Signature (diese Daten dann in die config.php)

# 3. Config
folgende Dateien müssen angepasst werden:

* config_example.php        --> config.php
* admin/msg_example.php     --> /admin/msg.php
* ggf. noch den admin/ per .htaccess schützen ! (Nginx: https://willy-tech.de/htaccess-in-nginx-einrichten/)

Erstelle einen stündlichen Cronjob für YOURURL.COM/admin/_cron.php (hierbei werden abgelaufene Abos-User aus dem Kanal und der Datenbank entfernt)

## 3.1 Zugriff auf Rocketmap via .htpasswd

Wenn User Zugriff auf die Rocketmap haben dürfen muss folgendes (in Ubuntu) konfiguriert werden.

### - Apache:

erstelle eine neue Datei "rocketmap.conf" in /etc/apache2/sites-available/

```
ServerName YOURDOMAIN.de

    <Location /go/>
        ProxyPass http://YOURIP:46516/go/
        ProxyPassReverse http://127.0.0.1:46516/go/
        ProxyAddHeaders On
        ProxyPreserveHost On
        RequestHeader append SCRIPT_NAME /go
    </Location>

    <Proxy *>
        Order deny,allow
        Allow from all
        Authtype Basic
        Authname "Password Required"
        AuthUserFile /var/www/vhosts/YOURDOMAIN.de/httpdocs/.htpasswd
        Require valid-user
    </Proxy>
```

eingebunden wird die configuration in 000-default.conf mit der zeile "Include sites-available/rocketmap.conf"

### - Nginx:

```
Weiß ich nicht...
```

## 3.2 bei Verwendung von PMSF als MAP

Es müssen manualdb (PMSF) und unsere 3 Tabellen (siehe unten) zusammengeführt werden!
Folgende Anpassung muss außerdem die Spalte "users" bekommen

```
ALTER TABLE `users`
  ADD UNIQUE KEY `user` (`user`);
```

Wenn du alle vorhanden User aus der PMSF tabelle `users` in die tabelle `abos` kopieren möchtest kannst du folgenden SQL Befehl benutzten:

```
INSERT INTO abos (id, buyerName, buyerEmail, Amount, TelegramUser, userid, channels, pass, TransID, paydate, endtime)  
SELECT id, '', user, '', user, NULL, '1', '', NULL, now(), FROM_UNIXTIME(expire_timestamp)
FROM users;
```

im Anschluß musst du die spalte `TelegramUsername` natürlich anpassen ;)

# 4. SQL Telegram Chanel

## 4.1 SQL abos


```
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
## 4.2 SQL products (Produkte müssen angepasst werden)

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
## 4.3 SQL channels

```
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
