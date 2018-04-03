# Nimiq Miner Status

Nimiq Miner Status allows you to view the status of any nodes you have running
by parsing the log file of the [Nimiq NodeJS Miner](https://github.com/nimiq-network/core).

![alt text](https://github.com/Patchesoft/Nimiq-Miner-Status/blob/master/miners.png "NIMIQ Miner Example")

Each time you refresh the page, the logfile is parsed. The current data extracted from the logfile is:

* Wallet Address
* Hashrate
* Last Block Mined
* Timestamp

## Setup Nimiq Miner Status

To check the status of a server, add your log file to the array of servers. You should also add a name for each server in index.php:

```php
$servers = array(
	array("http://www.example.com/logfile.log", "Pikachu1"),
	array("http://www.example2.com/logfile.log", "Bulbasaur1"),
	array("http://www.example3.com/logfile.log", "Squirtle1"),
	array("http://www.example4.com/logfile.log", "Charmander1"),
);
```

## Styling of Nimiq Miner Status

Each server output is wrapped in a div with the class `bubble`. You can then add your styling as your like:

```css
<style type="text/css">
.bubble { display: inline-block; width: 400px; margin: 10px; }
</style>
```

## Add Last-Modified Header

If you want to know the last time your file was updated to make sure the miner is running correctly, make sure your server adds the Last-Modified header to page requests. You can do this for Apache by installing mod_expires.

## Pipe To Logfile

When running the Nimiq NodeJS Miner, pipe the output to a log file that is accessible via URL. Make sure to include the --statistics 30 line.

```
node index.js --host example.com --port 8080 --key /etc/letsencrypt/live/example.com/privkey.pem --cert /etc/letsencrypt/live/example.com/fullchain.pem --miner=2 --statistics 30 >> /var/www/sites/example.com/logfile.log &
```

The & will keep the miner running in the background.

Because the log file will get bigger over time, it's best to setup a cron to delete the file once a day or so. 

```
0 * * * * * rm /var/www/sites/example.com/logfile.log
```

## Resources

* [Nimiq](https://nimiq.com)
* [How To Buy Nimiq](https://www.patchesoft.com/buy-nimiq-net)
* [Best Ethereum Wallet](https://www.patchesoft.com/best-ethereum-wallet)