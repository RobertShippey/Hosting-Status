#make sure you put a webook token in token.txt
token=$(cat /home/pi/webhostingCheck/token.txt)

#load old hash
hashFile="/home/pi/webhostingCheck/temp/HeartStatus-hash.txt"
oldHash=$(cat "$hashFile")

#cache html file
curl -s http://www.heartstatus.uk/index.json -o /home/pi/webhostingCheck/temp/HeartStatus-cache.json
# http://heartstatus.uk

#hash the cache
newHash=$(md5sum /home/pi/webhostingCheck/temp/HeartStatus-cache.json)

#work out the status [red, orange, green] in emoji
#status=$(php webhostingCheck/status-heart.php)

#save new hash to file
echo "$newHash" > "$hashFile"

if [ "$newHash" != "$oldHash" ]; then
        php webhostingCheck/status-HeartNew.php
fi
