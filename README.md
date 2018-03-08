# PI-CONDITION #

## OPIS ##
	
Biblioteka skryptów do kontroli stanu domowego serwera.

## SKRYPTY ##
	
temp_cpu_ram_disk.py - Skrypt sprawdzający parametry w RS Pi i zapisuje je w bazie MySQL
```
temperatura procesora
zurzycie chwilowe procesora
zurzycie chwilowe ramu
zajętość dysku
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
Indexes for table `temp_cpu_ram_disk`
```
ALTER TABLE `temp_cpu_ram_disk`
  ADD PRIMARY KEY (`czas`);
```
## MASZYNA ##

The Raspberry Pi 3 is the third-generation Raspberry Pi. It replaced the Raspberry Pi 2 Model B in February 2016.
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
Niestandardowe ustawienia:
```
dtparam=audio=on
gpu_mem=256
```
