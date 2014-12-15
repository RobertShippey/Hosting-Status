#make sure you put a webook token in token.txt
token=$(cat webhostingCheck/token.txt)

#load old hash
hashFile="webhostingCheck/temp/FH-hash.txt"
oldHash=$(cat "$hashFile")

#cache html file
curl -s http://status.fasthosts.co.uk/ -o webhostingCheck/temp/FH-cache.html
#hash the cache
newHash=$(md5sum webhostingCheck/temp/FH-cache.html)

#work out the status [red, orange, green] in emoji
status=$(php webhostingCheck/status-fasthosts.php)

#save new hash to file
echo "$newHash" > "$hashFile"

#if hashes are different (page updated) then post in Slack
if [ "$newHash" != "$oldHash" ]; then
	curl -s -d "{\"icon_emoji\": \"$status\", \"channel\": \"#hosting\", \"text\": \"Fasthosts' <http://status.fasthosts.co.uk|hosting status> has been updated... \"}" \
	https://lingodesign.slack.com/services/hooks/incoming-webhook?token=$token
fi
