var App = function(){
    
    var chartData = null;
    
    this.init = function(){
        //on initialization, load the libraries and grab general war stats
        google.load('visualization', '1.0', {'packages':['corechart']});
        google.setOnLoadCallback(getMasterData);
    };
    
    var getMasterData = function(returnData){
       getFile("app/?task=allwars", "createMasterChart", "json");
    };
    
    var createMasterChart = function(){
        var dataArray = [];
        
        dataArray.push(['Date', 'Avg. Single', 'Avg. Single(w)', 'Avg. War', 'Avg. War(w)', 'Avg. War No Penalty', "Avg. War(w) No Penalty"]);
        for(var key in chartData){
            dataArray.push([
                key, 
                chartData[key]['__Averages__']['Single'],
                chartData[key]['__Averages__']['wSingle'],
                chartData[key]['__Averages__']['War'],
                chartData[key]['__Averages__']['wWar'],
                chartData[key]['__Averages__']['War_NoP'],
                chartData[key]['__Averages__']['wWar_NoP'],
            ]);
        }
        console.log(dataArray);
        var data = new google.visualization.arrayToDataTable(dataArray);

        // Set chart options
        var options = {
                       'title':     'Clan Effectiveness (E) per War',
                       'width':     1200,
                       'height':    500,
                       'curveType': 'function',
                       'legend':    {position:'right'}
                      };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    };
    
    
    //helper function to get data files from backend API via AJAX
    var getFile = function(fullFilePath, callback, conversion){
        var tempData = null;
        
        $.get(fullFilePath, function(result){
            if(conversion != ""){
                switch(conversion){
                    case 'json':
                        tempData = JSON.parse(result);
                        break;
                    default:
                        tempData = result;
                        break;
                }
                chartData = tempData;
            }else{
                chartData = result;
            }
            if(callback != ""){
                eval(callback+"()");
            }
        });
    };
    
    return this;
};
