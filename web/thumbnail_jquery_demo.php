<html>
<head>

    <link type="text/css" rel="stylesheet" href="css/lightSlider.css" />

    <style>
        .demo {
            width:450px;
        }
        ul {
            list-style: none outside none;
            padding-left: 0;
            margin-bottom:0;
        }
        li {
            display: block;
            float: left;
            margin-right: 6px;
            cursor:pointer;
        }
        img {
            display: block;
            height: auto;
            max-width: 100%;
        }
    </style>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/jquery.lightSlider.min.js"></script>

</head>
<body>
<div class="demo">
    <ul id="lightSlider">
        <li data-thumb="../cache/20141206_21h/screenshot/thumbnail/alsacefrance3fr600X800-mini.jpg">
            <img src="../cache/20141206_21h/screenshot/alsacefrance3fr600X800.jpg" />
        </li>
        <li data-thumb="../cache/20141206_21h/screenshot/thumbnail/animateursfrance2fr600X800-mini.jpg">
            <img src="../cache/20141206_21h/screenshot/animateursfrance2fr600X800.jpg" />
        </li>
    </ul>
</div>

<script>
    $('#lightSlider').lightSlider({
    gallery: true,
    item: 1,
    loop:true,
    slideMargin: 0,
    thumbItem: 9,
        adaptiveHeight:true
    });
</script>

</body>
</html>