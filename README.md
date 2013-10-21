killbill-demo
=============

Example of Spy Car Shop using Killbill.

# Notes on Developping and Debugging

## Basic Setup

1. You need to have a instance of Kill Bill server running somewhere-- see for instance [Kill Bill in five minutes](http://docs.kill-bill.org/userguide.html#five-minutes)
2. You need to clone both the [killbill-demo repo](git@github.com:killbill/killbill-demo.git) and the [killbill-client-php](git@github.com:killbill/killbill-client-php.git)  
Then, the easiest way is to add a symlinl from killbill-demo to the killbill-client-php
```  
 > cd killbill-demo  
 > ln -s <YOUR_DEMO_PATH>/killbill-client-php killbill-client-php
```

3. Also make sure the serverUrl as definied in the killbill-client-php points to the correct instnace of Kill Bill-- see [README](https://github.com/killbill/killbill-client-php)


## HTTP Server

There are multiple possibilities at this point to run the demo, here are a few i looked at:

### Start Apache Server

This is beyong that doc to explian how to do that; however if you want to run it on a macox laptop the easiest way it to use the 
~/Sites folder. [Here is a good explanation on how to do that](https://discussions.apple.com/docs/DOC-3083).


### Use php5.4 Embedded Server

Another (easier) alternative is to use the new embedded http server that ships with php starting version 5.4

You can start the server by specifying the virtualhost and port it should listen to, specify the DocRoot and also potentially the localtion of your php.ini if your are not satisfied with the default version

```
 > /usr/local/opt/php55/bin/php -S 0.0.0.0:8081 -t <YOUR_DEMO_PATH>/killbill-demo --php-ini <YOUR_PHP_INI_PATH>/php.ini 
```

## Debugging php

If you are using phpStorm there is a way to directly start an embedded server from phpStorm and debug from there. Unfortunately i could not make it work, but i was able to connect remotely to my running instance of php.

The steps are a bit convoluted, and sometimes counter intuitive; forst of all your debugger will not connect to the instnace of embedded-php server running, but this is thge opposite, your php server will connect to your debugger:

1. Tweak your php.ini to allow Xdebug
```
 ; allow remote debug
 xdebug.remote_enable=1
 ; wherer to find your debugger
 xdebug.remote_host=127.0.0.1
 ; debugging port
 xdebug.remote_port=9000
```
2. Then, this is unfortunately not enough, you need to also activate the server to it connects to your debugger. For that you can install a chrome extension [xdebug-helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc?hl=en)
3. Finally you need to tell phpStorm to listen for connections-- this is the icon in the main bar that looks like a telephone

You can find more details [here]( follow the steps [here](http://confluence.jetbrains.com/display/PhpStorm/Zero-configuration+Web+Application+Debugging+with+Xdebug+and+PhpStorm) and in particulat )
