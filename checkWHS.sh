#make sure you put a webook token in token.txt
token=$(cat webhostingCheck/token.txt)

#load old hash
hashFile="/home/pi/webhostingCheck/temp/WHS-hash.txt"
oldHash=$(cat "$hashFile")

#cache html file
curl -s http://www.webhostingstatus.com/ -o webhostingCheck/temp/WHS-cache.html
#hash the cache
newHash=$(md5sum temp/WHS-cache.html)

#work out the status [red, orange, green] in emoji
status=$(php /home/pi/webhostingCheck/status-heart.php)

#save new hash to file
echo "$newHash" > "$hashFile"

if [ "$newHash" != "$oldHash" ]; then
	curl -s -d "{\"icon_emoji\": \"$status\", \"channel\": \"#hosting\", \"text\": \"Heart Internet's <http://www.webhostingstatus.com|hosting status> has been updated...\"}" \
	https://lingodesign.slack.com/services/hooks/incoming-webhook?token=$token
fi
