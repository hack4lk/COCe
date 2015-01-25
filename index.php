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
        <script src="js/charts.js"></script>
        <script>
            var app = new App();
            app.init();
           
           
            //test js code below
            //method to call sub-methods of the app class
            var runMethod = function(methodName, targetDropdown1, targetDropdown2, targetDropdown3, targetDropdown4, targetDropdown5){
                 if(targetDropdown5 != undefined){
                    dropDownValue1 = $("#" + targetDropdown1 + " option:selected").val();
                    dropDownValue2 = $("#" + targetDropdown2 + " option:selected").val();
                    dropDownValue3 = $("#" + targetDropdown3 + " option:selected").val();
                    dropDownValue4 = $("#" + targetDropdown4 + " option:selected").val();
                    dropDownValue5 = $("#" + targetDropdown5 + " option:selected").val();
                    eval('app.' + methodName + "('" + dropDownValue1 +"', '" + dropDownValue2 +"', '" + dropDownValue3 + "', '" + dropDownValue4 +"', '" + dropDownValue5 +"');");
                }else if(targetDropdown4 != undefined){
                    dropDownValue1 = $("#" + targetDropdown1 + " option:selected").val();
                    dropDownValue2 = $("#" + targetDropdown2 + " option:selected").val();
                    dropDownValue3 = $("#" + targetDropdown3 + " option:selected").val();
                    dropDownValue4 = $("#" + targetDropdown4 + " option:selected").val();
                    eval('app.' + methodName + "('" + dropDownValue1 +"', '" + dropDownValue2 +"', '" + dropDownValue3 + "', '" + dropDownValue4 +"');");
                }else if(targetDropdown3 != undefined){
                    dropDownValue1 = $("#" + targetDropdown1 + " option:selected").val();
                    dropDownValue2 = $("#" + targetDropdown2 + " option:selected").val();
                    dropDownValue3 = $("#" + targetDropdown3 + " option:selected").val();
                    eval('app.' + methodName + "('" + dropDownValue1 +"', '" + dropDownValue2 +"', '" + dropDownValue3 + "');");
                }else if(targetDropdown2 != undefined){
                    dropDownValue1 = $("#" + targetDropdown1 + " option:selected").val();
                    dropDownValue2 = $("#" + targetDropdown2 + " option:selected").val();
                    eval('app.' + methodName + "('" + dropDownValue1 +"', '" + dropDownValue2 +"');");
                }else{
                    dropDownValue1 = $("#" + targetDropdown1 + " option:selected").val();
                    eval('app.' + methodName + "('" + dropDownValue1 +"');" );
                }
                
            }
        </script>
    </head>
    
    <body>
        <div class="container">
            <div id="controls" style="display:none;">
                <div class="row">
                    <div class="col-md-3">
                        <select id="war1">
                            <option value="warStatsAvgLine">War Average</option>
                            <option value="warStatsMaxLine">War Max</option>
                        </select>
                        <a class="btn btn-info" onclick="runMethod('createWarLineChart', 'war1');">Create Line Chart</a>
                     </div>
                     
                    <div class="col-md-5">
                        <select id="warColAvgMax">
                            <option value="warStatsAvgCol">Average</option>
                            <option value="warStatsMaxCol">Max</option>
                        </select>
                        <select id="warColMetric">
                            <option value="single">single</option>
                            <option value="singleW">single weighted</option>
                            <option value="war">war</option>
                            <option value="warW">war weighted</option>
                            <option value="warNoP">war no penalty</option>
                            <option value="warWNoP">war no penalty weighted</option>
                        </select>
                        <a class="btn btn-info" onclick="runMethod('createWarColumnChart', 'warColAvgMax', 'warColMetric');">Create Column Chart</a>
                    </div>
                    
                    <div class="row"><div class="col-md-12"></div></div>
                    
                    <div class="col-md-12">
                        <select id="warStackColMetric1">
                            <option value="single">single</option>
                            <option value="singleW">single weighted</option>
                            <option value="war">war</option>
                            <option value="warW">war weighted</option>
                            <option value="warNoP">war no penalty</option>
                            <option value="warWNoP">war no penalty weighted</option>
                        </select>
                        <select id="warStackColAvgMax1">
                            <option value="avg">Average</option>
                            <option value="max">Max</option>
                        </select>
                        <select id="warStackColMetric2">
                            <option value="single">single</option>
                            <option value="singleW">single weighted</option>
                            <option value="war">war</option>
                            <option value="warW">war weighted</option>
                            <option value="warNoP">war no penalty</option>
                            <option value="warWNoP">war no penalty weighted</option>
                        </select>
                        <select id="warStackColAvgMax2">
                            <option value="avg">Average</option>
                            <option value="max">Max</option>
                        </select>
                        <select id="warDoStack">
                            <option value="true">Stack</option>
                            <option value="false">No Stack</option>
                        </select>
                        <a class="btn btn-info" onclick="runMethod('createWarStackedChart', 'warStackColMetric1', 'warStackColMetric2', 'warStackColAvgMax1', 'warStackColAvgMax2', 'warDoStack');">Create Stacked Column Chart</a>
                     </div>
                     
                     
                </div>
                
                <div class="row">
                    <div class="col-md-12" style="border-top: 1px solid gray; margin:25px 0; padding-top:20px">
                        <a class="btn btn-default" onclick="app.getSingleWarFile('1-20-2015', 'true')">load war (1-20-2015)</a>&nbsp;&nbsp;
                        <a class="btn btn-default" onclick="app.getSingleWarFile('1-20-2015')">load war (1-20-2015) (no stats)</a>&nbsp;&nbsp;
                        <a class="btn btn-default" onclick="app.getSingleWarFile('1-24-2015', 'true')">load war (1-24-2015)</a>&nbsp;&nbsp;
                        <a class="btn btn-default" onclick="app.getSingleWarFile('1-28-2015', 'true')">load war (1-28-2015)</a>&nbsp;&nbsp;
                        <a class="btn btn-default" onclick="app.getSingleWarFile('1-31-2015', 'true')">load war (1-31-2015)</a>&nbsp;&nbsp;
                        <a class="btn btn-default" onclick="app.getSingleWarFile('2-3-2015', 'true')">load war (2-3-2015)</a>&nbsp;&nbsp;
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <select id="warDate">
                            <option value="1-20-2015">1-20-2015</option>
                            <option value="1-24-2015">1-24-2015</option>
                        </select>
                         <select id="singleWarDataMetric">
                            <option value="max">Maximums</option>
                            <option value="avg">Averages</option>
                            <option value="values">Individual Clan Members</option>
                        </select>
                        <select id="singleWarDataMetric2">
                            <option value="E1">first attack efficiency</option>
                            <option value="E2">second attack efficiciency</option>
                            <option value="wE1">first attack weighted efficiency</option>
                            <option value="wE2">second attack weighted efficiciency</option>
                            <option value="WarE">war efficiency</option>
                            <option value="wWarE">war weighted efficiency</option>
                            <option value="WarE_NoP">war efficiency - no penalty</option>
                            <option value="wWarE_NoP">war weighted efficiency - no penalty</option>
                        </select>
                        <a class="btn btn-info" onclick="runMethod('createSingleWarColChartStats', 'warDate', 'singleWarDataMetric', 'singleWarDataMetric2');">Create Column Chart with stats</a>
                     </div>
                </div>
                
            </div>
            <div id="chartOutput"></div>
        <form action="app/?task=singlewar&method=saveWarData" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="warDataFile" id="warDataFile">
            <input type="submit" value="Upload Data" name="submit">
        </form>
        </div>
    </body>
    
</html>