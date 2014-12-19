<html>
<head>
<style>
ul#loadbar {
    list-style: none;
    width: 800px;
    margin: 0 auto;
    padding-top: 50px;
    padding-bottom: 75px;
}

ul#loadbar li {
    float: left;
    position: relative;
    width: 11px;
    height: 26px;
    margin-left: 1px;
    border-left: 1px solid #111;
    border-top: 1px solid #111;
    border-right: 1px solid #333;
    border-bottom: 1px solid #333;
    background: #000;
}

ul#loadbar li:first-child {
    margin-left: 0;
}

.bar {
    background-color: #2187e7;
    background-image: -moz-linear-gradient(45deg, #2187e7 25%, #a0eaff);
    background-image: -webkit-linear-gradient(45deg, #2187e7 25%, #a0eaff);
    width: 11px;
    height: 26px;
    opacity: 0;
    -webkit-animation: fill .5s linear forwards;
    -moz-animation: fill .5s linear forwards;
}

#layerFill1 {
    -moz-animation-delay: 0.5s;
    -webkit-animation-delay: 0.5s;
}


}

</style>
</head>
<body>

<ul id="loadbar">
    <? for ($i=0; $i<400; $i++)
    {
        ?>
    <li>
    <div class="bar"></div> <!-- Control Layer + Bar  -->
    </li>
    <?
    }
    ?>


</ul>

</body>
</html>
