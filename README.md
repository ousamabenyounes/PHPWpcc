PHP Web Page Content Checker
==========================

You have a great number of portail & websites.  
You need to manage services that may be updated on all those websites.  
jquery, paypal, sso, ...  

So for exemple, I need to find these content on my website:  
sso-janrain_v1.2 OR sso-janrain_v1.3  
And I also need to be sure that these old version of SSO aren't visible on my websites:  
sso-janrain-v1.1 AND sso-janrain-v1.0

How do you manage to reach theses goals?
- You open all your website, and manually check your contents? ...  
- You write tests to check if your contents are fine?  
Great, but this may take a very long time because you'll have to write a lot of source code.  
And when your services are updated, you need to update all your tests everytime.  

PHPWpcc allows you to easily manage your website services delivery by writing / updating for you those line of source code.  
You fill in your website listing (portails, webistes, pages)  
You fill in all services that you need to check on your websites  
(for exemple: jquery, paypal, sso, ...)  
Finally, just by configuring which service must be enabled in which websites, you can generate hundred of phpunit source code lines.  
=> Note that this step can be made automaticaly via PHPWpcc install Script.  

PHPWPCC can also generate website screenshot (thanks to pageres),  
so you'll be able to know if your website has a graphical issue.  


Install
=================

1 - Installez PHPWpcc on your server by cloning this github repository  
Then you can launch: "composer install"

2 - Give permission to PHPWpcc to write your tests / configurations


sudo chmod -R 777  phpunitTests config cache
sudo chown -R www-data:www-data phpunitTests config cache


3 - First launch of your PHPWPCC instance  
=> Go to this url: http://YOUR_HOST_NAME/install.php  
 
4 - Configure your websites => click on "portails" menu  
Here you can List all your portails, websites, and webpages urls  
Notice that only the webpages urls wich will be parsed by PHPWPCC.  
  
5 - Service Configuration => click on "services" menu  
Set up the services listing that you need to monitor  
You can fill in different versions by service  
And a service version can be defined by one or more files. See this sample:  
```php

JqueryUi Service:
	 jquery-ui.min.css
	 jquery-ui.min.js

```

6 - Launch PHPWpcc crawler  
Go to command line and launch:  

<pre>   
   $  cd bin && ./phpwpcc.sh
</pre>

This shell script will generate PHPWpcc cache, automatically initialize your service configuration,
launch all generated tests, and finally send you email if one test was KO.


#Requirements

- composer 
- phpunit  
- guzzle  
  
#Credit

- Thanks to Gael Metai https://github.com/gmetais/YellowLabTools
- Thanks to Arnaud Huon
- dynatree http://wwwendt.de/tech/dynatree/doc/samples.html
- FullScreen https://github.com/codrops/FullscreenForm

#Todo

- sensiolab insight free php checking (code style, security, web perf, best practices...!
- Result versionning 
- gruntify

