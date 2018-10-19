#!/bin/sh

#https://bitcointalk.org/index.php?topic=2799605.msg

cp /config/bmminer.conf /config/bmminer.conf.backup
cp /config/network.conf /config/network.conf.backup

rm /config/bmminer.conf
rm /config/network.conf

touch /config/bmminer.conf
touch /config/network.conf

echo "{"                                                            >> /config/bmminer.conf
echo "\"pools\" : ["                                                            >> /config/bmminer.conf
echo "{"                                                            >> /config/bmminer.conf
echo "\"url\" : \"stratum.antpool.com:3333\","                                                            >> /config/bmminer.conf
echo "\"user\" : \"test.$1\","                                                            >> /config/bmminer.conf
echo "\"pass\" : \"123\""                                                            >> /config/bmminer.conf
echo "},"                                                            >> /config/bmminer.conf
echo "{"                                                            >> /config/bmminer.conf
echo "\"url\" : \"stratum.antpool.com:3333\","                                                            >> /config/bmminer.conf
echo "\"user\" : \"test.$2\","                                                            >> /config/bmminer.conf
echo "\"pass\" : \"123\""                                                            >> /config/bmminer.conf
echo "},"                                                            >> /config/bmminer.conf
echo "{"                                                            >> /config/bmminer.conf
echo "\"url\" : \"stratum.antpool.com:3333\","                                                            >> /config/bmminer.conf
echo "\"user\" : \"test.$3\","                                                            >> /config/bmminer.conf
echo "\"pass\" : \"123\""                                                            >> /config/bmminer.conf
echo "}"                                                            >> /config/bmminer.conf
echo "]"                                                            >> /config/bmminer.conf
echo ","                                                            >> /config/bmminer.conf
echo "\"api-listen\" : "true","                                                            >> /config/bmminer.conf
echo "\"api-network\" : "true","                                                            >> /config/bmminer.conf
echo "\"api-groups\" : \"A:stats:pools:devs:summary:version\","                                                            >> /config/bmminer.conf
echo "\"api-allow\" : \"A:0/0,W:*\","                                                            >> /config/bmminer.conf
echo "\"bitmain-use-vil\" : "true","                                                            >> /config/bmminer.conf
echo "\"bitmain-freq\" : \"550\","                                                            >> /config/bmminer.conf
echo "\"bitmain-voltage\" : \"0706\","                                                            >> /config/bmminer.conf
echo "\"multi-version\" : \"1\""                                                            >> /config/bmminer.conf
echo "}"                                                            >> /config/bmminer.conf


chmod 400 /config/bmminer.conf

echo "hostname=miner$4"                    >>  /config/network.conf
echo "ipaddress=10.0.0.$5"                  >> /config/network.conf
echo "netmask=255.255.255.0"                              >> /config/network.conf
echo "gateway=10.0.0.138"                              >> /config/network.conf
echo "dnsservers=\"8.8.8.8"\"    >> /config/network.conf

chmod 400 /config/network.conf

#/etc/init.d/bmminer.sh restart >/dev/null 2>&1
#/etc/init.d/network.sh
#/etc/init.d/avahi restart > /dev/null
#echo "root:antMiner Configuration:23c2a2d78c0d20ec069097d7d20c4392" >> /config/lighttpd-htdigest.user

