# PI-CONDITION #

## OPIS ##
	
Biblioteka skryptów do kontroli stanu domowego serwera.

## SKRYPTY ##
	
temp_cpu_ram_disk.py - Skrypt sprawdzający parametry w RS Pi i zapisuje je w bazie MySQL
```
temperatura procesora [C]
zurzycie chwilowe procesora
zurzycie chwilowe ramu
zajętość dysku
```

miejsce.sh - Skrypt sprawdzający wolne miejsce i wysyłający maila - test zużycia i porówanie z predefiniowaną wielkością i wysłanie maila, gdy zostanie przekroczona w mailu znajduje się:
```
[df -h] struktura partycji i ich wykorzystanie (zajmowane miejsce) w systemie
[du -a...] spis 20 największych folderów
```

speedtest_net.py
```
Ping [ms]
Download [Mbit/s]
Upload [Mbit/s]
```

pi-condition-view - folder zawierający pliki php z podglądem danych zawartych w bazie
```
tabela.php - przedstawia dane w tabeli
wykres.php - ###W PROJEKTOWANIU### przedstawia dane na wykresach 
```

## WYKONANIE SKRYPTU ##

Skrypty dodane do crontab:
```
0 * * * * /usr/bin/python /home/pi/admin_skrypt/pi-condition/temp_cpu_ram_disk.py >> /var/log/admin_skrypt/cron_temp_cpu_ram_disk.log 2>&1
5 * * * * /usr/bin/python /home/pi/admin_skrypt/pi-condition/miejsce.sh >> /var/log/admin_skrypt/cron_miejsce.log 2>&1
10 * * * * /usr/bin/python /home/pi/admin_skrypt/pi-condition/speedtest_net.py >> /var/log/admin_skrypt/cron_speedtest_net.log 2>&1
```

## STRUKTURA SQL ##

Dane przechowuje w bazie MySQL - MariaDB

Struktura tabeli `temp_cpu_ram_disk`
```
CREATE TABLE `temp_cpu_ram_disk` (
  `czas` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CPU_temp` text NOT NULL,
  `CPU_usage` text NOT NULL,
  `RAM_total` text NOT NULL,
  `RAM_used` text NOT NULL,
  `RAM_free` text NOT NULL,
  `DISK_total` text NOT NULL,
  `DISK_free` text NOT NULL,
  `DISK_perc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
Ideksy tabeli `temp_cpu_ram_disk`
```
ALTER TABLE `temp_cpu_ram_disk`
  ADD PRIMARY KEY (`czas`);
```
## APACHE ##

Ustawienie aliasu do podglądu plików php w /etc/apache2/sites-available/moje.conf
```
Alias "/system" "###/pi-condition-view/"
<Directory ""###/pi-condition-view">
  AllowOverride none
  #dopuszczenie polaczenia z wewnetrznej sieci
    Require ip 192.168.1.1/255.255.255.0
  #dopuszcze polaczenia przez VPN
    Require ip ###.###.###.###/255.255.255.0
</Directory>
```

## BIBLIOTEKI ##

(Biblioteki nie są brane do repozytorium)

* [Flot](https://www.flotcharts.org/) - biblioteka do rysowania wykresów (JavaScript - jQuery)

## MASZYNA ##

Raspberry Pi 3 B
Specyfikacja:
```
A 1.2GHz 64-bit quad-core ARMv8 CPU
802.11n Wireless LAN
Bluetooth 4.1
Bluetooth Low Energy (BLE)
1GB RAM
4 USB ports
40 GPIO pins
Full HDMI port
Ethernet port
Combined 3.5mm audio jack and composite video
Camera interface (CSI)
Display interface (DSI)
Micro SD card slot (now push-pull rather than push-push)
VideoCore IV 3D graphics core
```
Niestandardowe ustawienia (nieistotne dla tego projektu):
```
dtparam=audio=on
gpu_mem=256
```
Zinstalowane:
```
Raspbian 4.14.30-v7+
Apache/2.4.25
PHP 7.0.27-0+deb9u1
mysql Ver 15.1 Distrib 10.1.23-MariaDB
```