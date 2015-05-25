PHPWpcc - PHP Web Page Content Generator
==========================

You have a great number of portail & websites. 
You need to manage services that may be updated on all those websites.
jquery, paypal, sso, ...

So for exemple, I need to find these content on my website:
sso-janrain_v1.2 or sso-janrain_v1.3
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

1 - Install PHPWPCC on your webserver
  
2 - First launch of your PHPWPCC instance 
=> Go to this url: http://YOUR_HOST_NAME/install.php 
 
3 - Configure your websites => click on "portails" menu 
Here you can List all your portails, websites, and webpages urls 
Notice that only the webpages urls wich will be parsed by PHPWPCC.
  
4 - Service Configuration => click on "services" menu  
Set up the services listing that you need to monitor
You can fill in different versions by service
And a service version can be defined by one or more files.

sample:
```php

JqueryUi Service:
	 jquery-ui.min.css
	 jquery-ui.min.js

```

5 - Launch PHPWpcc crawler
Go to command line and launch: 
   
   $  cd bin && phpwpccInit.sh


TODO:
faciliter la cartographie des pages web à partir du site
chmod 777 des fichiers générés pour faciliter la suppression / maj
ok => mail
versionning des results
gruntify
insight


This configuration checks if Jquery library is present in epitech and supinfo websites and allows two versions.One configuration using google version of jquery and in an another one the website hosts jquery directly.  

It also checks if Paypal service is present in two prestashop stores, foodistrib.com & shop2tout.com. The first configuration search for paypal logo on the website, and the second, search for the prestashop module path.
  
Just launch this command:  

    $ php WebPageContentCheckerGenerator.php

You'll get 2 PHPUNIT test files in your tests directory  

<b>DemoCheckJqueryTest.php File: </b>  

```php
 public function testIfEpitechfrContainsJquery() {
        $html = $this->getHtmlContent("http://www.epitech.fr");
        $this->assertTrue($this->DemoCheckJquery($html), "testIfEpitechfrContainsJqueryKO");
    }

  public function testIfSupinfofrContainsJquery() {
        $html = $this->getHtmlContent("http://www.supinfo.fr");
        $this->assertTrue($this->DemoCheckJquery($html), "testIfSupinfofrContainsJqueryKO");
    }
```

<b>DemoCheckPaypalTest.php File:</b>  
 
```php
public function testIfFoodistribcomContainsPaypal() {
        $html = $this->getHtmlContent("http://www.foodistrib.com");
        $this->assertTrue($this->DemoCheckPaypal($html), "testIfFoodistribcomContainsPaypalKO");
    }

  public function testIfShop2toutcomContainsPaypal() {
        $html = $this->getHtmlContent("http://www.shop2tout.com");
        $this->assertTrue($this->DemoCheckPaypal($html), "testIfShop2toutcomContainsPaypalKO");
    }

```
 
Just launch your tests now :  

    $ cd tests/ && phpunit DemoCheckJqueryTest.php

<pre>
PHPUnit 4.0.20 by Sebastian Bergmann.

Configuration read from project/ous/WebPageContentChecker/tests/phpunit.xml

..

Time: 2.38 seconds, Memory: 3.25Mb

OK (2 tests, 2 assertions)
</pre>



    $ phpunit DemoCheckPaypalTest.php

<pre>
PHPUnit 4.0.20 by Sebastian Bergmann.

Configuration read from project/ous/WebPageContentChecker/tests/phpunit.xml

..

Time: 4.38 seconds, Memory: 3.50Mb

OK (2 tests, 2 assertions)
</pre>
 
 
#Requirements

- composer 
- phpunit  
- guzzle  
  
#Credit

- Thanks to Gael Metai https://github.com/gmetais/YellowLabTools
- Thanks to Arnaud Huon
- dynatree http://wwwendt.de/tech/dynatree/doc/samples.html
- FullScreen https://github.com/codrops/FullscreenForm
- phpunit
- guzzle



#Todo

- bug when saving services configuration by portail
- use var_export to simplify
- sensiolab insight free php checking (code style, security, web perf, best practices...!
TODO:
mail
versionning des results
gruntify
insight
