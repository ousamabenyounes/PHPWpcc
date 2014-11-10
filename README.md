PhpWpcc - PHP Web Page Content Generator
==========================

This Php Script generate PHPUNIT tests files to help you check your web page content using guzzle client.

Install
=================


    $ composer.phar install



Sample
=================

In input you just have to fill in the main configuration array in <b>WebPageContentCheckerGenerator.php</b> containing services you want to check, accepted Configuration per service, and urls you want to validate:

```php
        $this->_webParsingConfig = array(
             'Jquery' => array(
                      'acceptedConfig' => array(
                              '1.0' => array(
                                         'http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'
                              ),
                              'supinfoJs' => array(
                                         '/js/jquery-1.7.2.min.js'
                              )
                      ),
                      'urls' => array(
                             'http://www.epitech.fr',
                             'http://www.supinfo.fr'
                      ),
             ),
             'Paypal' => array(
                      'acceptedConfig' => array(
                              'logo' => array(
                                         'logo_paiement_paypal.jpg'
                              ),
                              'module' => array(
                                         'modules/paypal'
                              )
                      ),
                      'urls' => array(
                             'http://www.foodistrib.com',
                             'http://www.shop2tout.com'
                      ),
             ),

       );
```

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
  
