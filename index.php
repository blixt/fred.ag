<?php
switch (date('w')) {
    case 0:
        $day = 'söndag';
        break;
    case 1:
        $day = 'måndag';
        break;
    case 2:
        $day = 'tisdag';
        break;
    case 3:
        $day = 'onsdag';
        break;
    case 4:
        $day = 'torsdag';
        break;
    case 5:
        $day = 'fredag';
        break;
    case 6:
        $day = 'lördag';
        break;
}

$path = '/' . ($day == 'fredag' ? '' : $day);
if ($_SERVER['REQUEST_URI'] != $path) {
    header('Location: http://fred.ag' . $path, true, 302);
    exit;
}

$title = ucfirst($day);

include('settings.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:type" content="activity">
    <meta property="og:url" content="http://fred.ag<?php echo $path; ?>">
    <meta property="og:site_name" content="fred.ag">
    <meta property="og:description" content="Det är <?php echo $day ?>!">
    <meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID ?>">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            color: #fff;
            font: 15px/1.3 sans-serif;
        }

        h1 {
            font-family: serif;
            font-size: 120px;
            line-height: 1;
            margin-top: -60px;
            position: absolute;
            text-align: center;
            top: 50%;
            width: 100%;
        }

        h1.no {
            color: #c00;
            text-shadow: 0 0 70px #c00;
        }

        h1.yes {
            -webkit-animation-name: party;
            -webkit-animation-duration: 1s;
            -webkit-animation-iteration-count: infinite;
            -webkit-animation-timing-function: linear;
            color: #0a0;
            text-shadow: 0 0 70px #0a0;
        }

        html {
            background: #111;
        }

        p {
            margin-bottom: .3em;
        }

        #like {
            background: #000;
            float: right;
            padding: 20px;
        }
    </style>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo ANALYTICS_ID; ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>
<body>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function () {
        FB.init({appId: '<?php echo FACEBOOK_APP_ID ?>', status: true, cookie: true, xfbml: true});
    };

    (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
</script>
<?php if ($day == 'fredag'): ?>
    <h1 class="yes">fredag!</h1>
    <div id="like">
        <p>It's party time!!! Alla gillar fredagar, eller?</p>
        <p><fb:like colorscheme="dark"></fb:like></p>
    </div>
<?php else: ?>
    <h1 class="no">:(</h1>
    <div id="like">
        <p>Tyvärr, idag är en <?php echo $day; ?>. Du kanske gillar <?php echo $day; ?>ar?</p>
        <p><fb:like colorscheme="dark"></fb:like></p>
    </div>
<?php endif; ?>
</body>
</html>
