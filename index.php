<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>COCe</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jsapi.js"></script>
        <script src="js/app.js"></script>
        <script>
            var app = new App();
            app.init();
        </script>
    </head>
    
    <body>
        <div id="chart_div"></div>
        <form action="app/?task=singlewar&method=saveWarData" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="warDataFile" id="warDataFile">
            <input type="submit" value="Upload Data" name="submit">
        </form>
    </body>
    
</html>