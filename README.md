PHPWebPageCheckerGenerator
==========================

This Php Script generate PHPUNIT tests files to help you check your web page content using guzzle client.

Install
=================


    $ composer.phar install



Sample
=================

In input you just have to fill in the main configuration array in <b>WebPageContentCheckerGenerator.php</b>:

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
                      'urls' =>  array(
                              'http://www.epitech.fr',
                              'http://www.supinfo.fr'
                      )
             )

       );

```

 
This configuration checks if Jquery library is present in epitech and supinfo websites and allows two versions.  
One configuration using google version of jquery and in an another one the website hosts jquery directly.  
  
Just launch this command:  

    $ php WebPageContentCheckerGenerator.php

You'll get PHPUNit test files in your tests directory  
Here is a Sample of one of PHPunit test file content:  

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
 
Just launch your tests now:  

    $ cd tests/ && phpunit DemoCheckJqueryTest.php

<pre>
PHPUnit 4.0.20 by Sebastian Bergmann.

Configuration read from /home/ousama/project/ous/WebPageContentChecker/tests/phpunit.xml

..

Time: 2.38 seconds, Memory: 3.25Mb

OK (2 tests, 2 assertions)
</pre>

 
 
#Requirements

- composer 
  
