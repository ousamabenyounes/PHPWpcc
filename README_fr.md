PHP Web Page Content Checker
==========================

Vous avez un grand nombre de portails / sites web sous votre responsabilité.  
Vous devez gérer des services qui peuvent être mise à jour sur tous ces sites web.  
=> Jquery, Paypal, SSO, ...

Par exemple, vous devez retrouver la présence de ces contenus sur ces sites web:  
sso-janrain_v1.2 OU sso-janrain_v1.3  
Vous devez aussi garantir la non présence de version antérieur de votre service SSO sur vos sites web:  
sso-janrain-v1.1 ET sso-janrain-v1.0 

Comment faites vous pour atteindre un tel objectif aujourd'hui?  
- Vous vous rendez sur tous vos sites web, et faites une recherche manuel de tous ces contenus?...  
- Vous écrivez des tests pour surveiller si vos contenus sont correct?  
Bonne idée, mais cela reste très long à mettre en place car vous allez devoir écrire un grand nombre de ligne de code source..  
Et dés que vos services évoluent, vous allez devoir maintenir tous vos tests à chaque fois.  

PHPWpcc vous permet de faire tout ce travail via une interface web.  
Vous renseignez simplement la liste complète de vos sites web.  
Vous renseignez ensuite tous les services que vous souhaitez piloter.  
Enfin, vous n'avez plus qu'à lier vos services à vos sites web, et PHPWpcc va générer pour vous des tests unitaires.  
=> Cette dernière étape peux être généré automatiquement grâce au script d'installation de PHPWpcc.  

PHPWpcc vous permet aussi de générer des screenshot de vos sites web grâce à Pageres  
Vous pourrez ainsi être capable de savoir si votre site internet comporte un bug graphique, alors que les contenus sont pourtant ceux attendus.


Installation
=================

1 - Installez PHPWpcc sur votre serveur en clonant ce repository github  
lancez ensuite la commande: "composer install"  
  
2 - Une fois installé et votre VHOST configuré, rendez vous sur l'url suivante:  
=> http://VOTRE_VHOST/install.php  
 
3 - Configurez vos sites internets => cliquez sur le menu "portails"  
Vous pouvez renseigner la liste de vos portails, sites web.  
Pour ces sites web, lister toutes les pages que vous souhaitez surveiller.  
Ce sont ces pages internet, qui au final seront analysés par PHPWpcc  
  
4 - Service Configuration => cliquez sur le menu "services"  
Vous pouvez renseigner la liste des différents services que vous souhaitez piloter.  
Pour chaque service, vous devez indiquer des versions.  
Celles-ci seront chacunes composées par un ou plusieurs fichiers  
Ex: jqueryUI => jquery-ui.js + jquery-ui.css.  


Example:
```php

JqueryUi Service:
	 jquery-ui.min.css
	 jquery-ui.min.js

```

5 - Enfin, vous pouvez lancer le script PHPWpcc

<pre>   
   $  cd bin && ./phpwpcc.sh
</pre>

Ce script se charge de générer la cache de vos pages HTML, lie automatiquement les services aux pages concernées,
lance les tests phpunit générés, et enfin, envoi un email si un des tests est KO.
 
#Requirements

- composer 
- phpunit  
- guzzle  
  
#Credit

- Thanks to Gael Metai https://github.com/gmetais/YellowLabTools
- Thanks to Arnaud Huon
- dynatree http://wwwendt.de/tech/dynatree/doc/samples.html
- FullScreen https://github.com/codrops/FullscreenForm

