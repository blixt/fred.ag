<?php
// Get the ISO 8601 week year, number and day. The year may differ from the
// Gregorian calendar year since ISO 8601 years only consist of whole weeks,
// which may be a bit confusing.
$year = date('o');
$week = date('W');
$day = date('N');

$request = $_SERVER['REQUEST_URI'];
if (preg_match('#^/(\\d{4})/(\\d{2})$#', $request, $match)) {
    // Invalid values and Fridays that haven't happened yet will return 404.
    if (($match[1] < 1970 || $match[1] > $year) ||
        ($match[2] < 1 || $match[2] > 53) ||
        ($match[1] == $year && $match[2] > ($day >= 5 ? $week : $week - 1)))
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }

    // Change values according to requested year and week.
    $year = $match[1];
    $week = $match[2];
    $day = 5;
} else if ($request != '/') {
    header('HTTP/1.0 404 Not Found');
    exit;
}

// Get a timestamp for the Friday of the specified date.
$time = strtotime("$year-W$week-5");

// Generate a path for current year/week and make sure the browser is on that
// path.
$path = ($day == 5) ? date('/o/W', $time) : '/';
if ($request != $path) {
    header('Location: http://fred.ag' . $path, true, 302);
    exit;
}

$months = array(
    'January' => 'januari',
    'February' => 'februari',
    'March' => 'mars',
    'April' => 'april',
    'May' => 'maj',
    'June' => 'juni',
    'July' => 'juli',
    'August' => 'augusti',
    'September' => 'september',
    'October' => 'oktober',
    'November' => 'november',
    'December' => 'december'
);

include('settings.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
<?php if ($day == 5): ?>
    <title>fredag</title>
    <meta property="og:title" content="fredag">
    <meta property="og:type" content="activity">
    <meta property="og:url" content="http://fred.ag<?php echo $path; ?>">
    <meta property="og:site_name" content="fred.ag">
    <meta property="og:description" content="<?php echo 'Fredagen den ' . strtr(date('j:\\e F, Y (\\v\\e\\c\\k\\a W)', $time), $months); ?>">
    <meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID ?>">
<?php else: ?>
    <title>inte fredag</title>
<?php endif; ?>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        a {
            color: #fff;
        }

        body {
            color: #fff;
            font: 15px/1.3 sans-serif;
        }

        h1 {
            font-family: serif;
            font-size: 140px;
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
            color: #0a0;
            text-shadow: 0 0 70px #0a0;
        }

        html {
            background: #151515;
        }

        p {
            margin-bottom: .3em;
        }

        #like {
            background: #000;
            float: right;
            padding: 20px;
        }

        #open-source {
            background: #000;
            bottom: 0;
            font-size: 11px;
            left: 0;
            margin: 0;
            padding: 5px;
            position: absolute;
        }
    </style>
    <script>
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
<?php if ($day == 5): ?>
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function () {
            FB.init({appId: '<?php echo FACEBOOK_APP_ID ?>', status: true, cookie: true, xfbml: true});
        };

        (function () {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
        })();
    </script>
    <h1 class="yes">fredag!</h1>
    <div id="like">
        <p>It's party time!!! Alla gillar fredagar, eller?</p>
        <p><fb:like colorscheme="dark"></fb:like></p>
    </div>
<?php else: ?>
    <h1 class="no">:(</h1>
<?php endif; ?>
    <p id="open-source">Är du en nörd? Bidra! <a href="http://github.com/blixt/fred.ag">fred.ag @ GitHub</a></p>
</body>
</html>
