#                       MADE BY: TOMASZ KULINOWSKI                      #
#                              PI-CONDITION                             #

## OPIS ##
	
	Biblioteka skryptów do kontroli stanu domowego serwera.

## SKRYPTY ##
	
	temp_cpu_ram_disk.py - Skrypt sprawdzający parametry w RS Pi i zapisuje je w bazie MySQL
		temperatura procesora
		zurzycie chwilowe procesora
		zurzycie chwilowe ramu
		zajętość dysku
		
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
	niestandardowe ustawienia:
	```
	dtparam=audio=on
	gpu_mem=256
	```
	