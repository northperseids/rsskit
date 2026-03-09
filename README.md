# RSSKit | PluralKit Fronters RSS Feed Generator

So friends can use RSS feeds to see who's fronting!

*Transparently, I don't have a huge personal infrastructure and I am one\* person! You're welcome to try to host your own version, though. You'll need a MySQL database and a web server capable of hosting PHP files, and the (very simple) database structure is included in `data/rsskit.sql`.*

You will need to submit your token for authorization to create a feed, but tokens are not saved.
Tokens are only used to make sure the actual owner of the PluralKit system is the only one who can register an RSS feed for that system. The only data saved is an automatically-generated database entry ID, your 5-6 letter PK ID, and the day you created the feed.

## Usage
Input your 5- or 6-letter system ID and your token (run pk;token in Discord if you don't have it),
then click Create. You will be directed to an XML RSS feed. Copy the URL and use whatever RSS feed reader you like!

If you want to remove your RSS feed, enter your system ID and token, and then click Remove instead.

## URL
If you lose your feed URL, it's always going to be `https://rsskit.neartsua.me/feeds/feed.php?=[SYSTEMID]` where [SYSTEMID] is your 5- or 6-letter PluralKit ID. You can also resubmit with your ID and token if you want.