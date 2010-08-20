<?php
include('settings.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>fredag?</title>
        <style type="text/css">
            @-webkit-keyframes party {
                0% {
                    -webkit-transform: rotate(0);
                }
                25% {
                    font-size: 150px;
                    margin-top: -75px;
                    -webkit-transform: rotate(-15deg);
                }
                50% {
                    font-size: 120px;
                    margin-top: -60px;
                    -webkit-transform: rotate(0);
                }
                75% {
                    font-size: 150px;
                    margin-top: -75px;
                    -webkit-transform: rotate(15deg);
                }
            }

            * {
                margin: 0;
                padding: 0;
            }

            h1 {
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
<?php if (date('w') == 5): ?>
        <h1 class="yes">fredag!</h1>
<?php else: ?>
        <h1 class="no">:(</h1>
<?php endif; ?>
    </body>
</html>
