#!/bin/sh
#Skrypt sprawdzający wolne miejsce i wysyłający maila

. "/home/pi/admin_skrypt/pass_pi_temp.sh"

mail_odbiorcy=$mail_tomek
poziom_alarmu=70

#-----------------wartość w procentach zajętości dyku bez znaku %
VAR1=`df /home | awk '{ print $5 }' | tail -n 1 | sed 's/%//'`

echo "---Dane do maila----------------------------------"
echo "temat:\t\t" $temat
echo "tresc:\t\t" $tresc
echo "mail_odbiorcy:\t" $mail_odbiorcy
echo "--------------------------------------------------"

#echo "---------------------"
#df -h | grep dost.
#df -h | grep /dev/root
#echo "---------------------"
#sudo du -a / | sort -n -r | head -n 20
#echo "---------------------"

if [ $VAR1 -ge $poziom_alarmu ]
then
	temat="!!!!!Za malo miejsca!!!!! $VAR1 %"


	tresc="`df -h`\r\n"
	tresc="$tresc------------------------------------------\r\n"
	tresc="$tresc`sudo du -a / --exclude=/media --exclude=/proc | sort -n -r | head -n 20`\r\n"

	
	echo $temat
	echo "------------------------------------------"
	echo $tresc
	#-----------------wyslanie maila
	echo "$tresc" | mail -a "Content-Type: text/plain; charset=UTF-8" -s "$temat" $mail_odbiorcy
else
	echo "W pyte miejsca" $VAR1 "%"
fi


echo "end"