<!doctype html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      
        <title>Multi-Column-Select Demo</title>
	      
        <link rel="stylesheet" href="css/multiselect.css">
        <link href='http://fonts.googleapis.com/css?family=Ubuntu+Condensed|Ubuntu' rel='stylesheet' type='text/css'>

        <style>
            .clear{
                margin-bottom: 10px;
            }
        </style>
</head>

<body>

    <div class='wrapper'>
    
        <header>
            <h1>PhpWPCC</h1>    
        </header>

            <h2>PHPWebPageContentChecker Configuration</h2>

        
        <form action="test.php" method="GET">


         
	        <? foreach ($groupUrl  as  $portail => $urls)
		    {

		?>
		<div id="cnilService<? echo $portail ?>" >

		<select name="cnil<? echo $portail ?>" id="cnil" multiple>
		  <?
		      foreach ($urls as $ind => $url)
		      {

		      ?>
		       <option value="<? echo $ind ?>"><? echo $url ?></option>
		      <?
		      }		    
		?>
		</select>
		</div>
		<?
		  }
		?>
        <div class="clear"></div>        
        <div class="clear"></div>
	<input type="submit" value="Submit" class="btn" />
    </form>
    
    </div>
        
    <!--[if lt IE 9]>  
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <![endif]-->      
    <!--[if gte IE 9]><!-->  
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.1/jquery.min.js"></script> 
    <!--<![endif]-->  
    
    <script src="js/multiselect.js"></script>    
    <script>
     $(document).ready(function(){
      
     function mutliSelectActive(portail) 
     {
          $('#cnilService' + portail).MultiColumnSelect({
            multiple: true,
            menuclass : 'mcs', 
            openmenuClass : 'mcs-open',
            openmenuText : 'CNIL service configuration on portail ' + portail + ': ',
            containerClass : 'mcs-container',
            itemClass : 'mcs-item',
             idprefix : 'yourOwnID-',
            openclass : 'open',
            duration : 200,
            //hideselect : false,               
        });
     }
     <?
	foreach ($groupUrl  as $portail => $urls)
        {
	?>
           mutliSelectActive('<? echo $portail ?>');
        <?
	  }
	  ?>
     });  
    </script>    
</body>
</html>
