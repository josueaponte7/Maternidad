<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Refresh"  content="1; login.php">
        <script type="text/javascript">
            var cSpeed = 9;
            var cWidth = 128;
            var cHeight = 128;
            var cTotalFrames = 18;
            var cFrameWidth = 128;
            var cImageSrc = 'imagenes/sprites.gif';

            var cImageTimeout = false;
            var cIndex = 0;
            var cXpos = 0;
            var cPreloaderTimeout = false;
            var SECONDS_BETWEEN_FRAMES = 0;

            function startAnimation() {

                document.getElementById('loaderImage').style.backgroundImage = 'url(' + cImageSrc + ')';
                document.getElementById('loaderImage').style.width = cWidth + 'px';
                document.getElementById('loaderImage').style.height = cHeight + 'px';

                //FPS = Math.round(100/(maxSpeed+2-speed));
                FPS = Math.round(100 / cSpeed);
                SECONDS_BETWEEN_FRAMES = 1 / FPS;

                cPreloaderTimeout = setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES / 1000);

            }

            function continueAnimation() {

                cXpos += cFrameWidth;
                //increase the index so we know which frame of our animation we are currently on
                cIndex += 1;

                //if our cIndex is higher than our total number of frames, we're at the end and should restart
                if (cIndex >= cTotalFrames) {
                    cXpos = 0;
                    cIndex = 0;
                }

                if (document.getElementById('loaderImage'))
                    document.getElementById('loaderImage').style.backgroundPosition = (-cXpos) + 'px 0';

                cPreloaderTimeout = setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES * 1000);
            }

            function stopAnimation() {//stops animation
                clearTimeout(cPreloaderTimeout);
                cPreloaderTimeout = false;
            }

            function imageLoader(s, fun)//Pre-loads the sprites image
            {
                clearTimeout(cImageTimeout);
                cImageTimeout = 0;
                genImage = new Image();
                genImage.onload = function() {
                    cImageTimeout = setTimeout(fun, 0)
                };
                genImage.onerror = new Function('alert(\'Could not load the image\')');
                genImage.src = s;
            }

            //The following code starts the animation
            new imageLoader(cImageSrc, 'startAnimation()');
        </script>
        
        <style type="text/css">
            #loaderImage{
                width:455px;
                height:285px;
                position:absolute;
                left:55%;
                top:50%;
                margin:-75px 0 0 -135px;
            }
        </style>
    </head>
    <body>
        <div id="loaderImage" style=""></div>
    </body>
</html>