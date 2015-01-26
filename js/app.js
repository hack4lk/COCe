var App = function(){
    var that = {};
    var chartWidth = '100%';
    var chartHeight = 300;
    
    that.chartData = null;
    that.singleChartData = null;
    that.charts = null;
    that.loadTarget = "all";
    
    that.init = function(){
        that.charts = new Charts();
        //on initialization, load the libraries and grab general war stats
        google.load('visualization', '1.0', {'packages':['corechart', 'table']});
        google.setOnLoadCallback(that.getMasterData);
    };
    
    var prepareCanvas = function(){
        //this will change when UI is developed
        $("#controls").show('slow');
        if(that.chartData === false){
            alert("no data loaded yet!");
        }
    };
    
    var showSingleWarOptions = function(){
        console.log('single war file loaded...');
    };
    
    that.createSingleWarColChartStats = function(dateOfWar, statsType, singleMetric){
        var dataArray = [];
        dataArray = that.charts.formatChartData('warSingleWarCol', that.singleChartData, statsType, singleMetric);
         // Set chart options
        var options = {
           'title':     'Clan effectiveness for war on ' + dateOfWar,
           'width':     chartWidth,
           'height':    chartHeight,
           'curveType': 'function',
           'legend':    {position:'right'},
           animation:{
              duration: 3000,
              easing:   'out',
              startup:  true
           }
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderColumnChart(dataArray, 'chartOutput', options, false);
    };
    
    that.createWarLineChart = function(dataType){
        var dataArray = [];
        dataArray = that.charts.formatChartData(dataType, that.chartData);
        
        // Set chart options
        var options = {
           'title':     'Clan Effectiveness (E) per War',
           'width':     chartWidth,
           'height':    chartHeight,
           'curveType': 'function',
           'legend':    {position:'right'},
           'tooltip':   {trigger: 'selection'},
           animation:{
              duration: 3000,
              easing:   'out',
              startup:  true
           } 
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderLineChart(dataArray, 'chartOutput', options);
    };
    
    that.createWarColumnChart = function(dataType, parameter){
        var dataArray = [];
        dataArray = that.charts.formatChartData(dataType, that.chartData, parameter);
        
        // Set chart options
        var options = {
           'title':     'Clan Effectiveness (E) per War',
           'width':     chartWidth,
           'height':    chartHeight,
           'tooltip':   {trigger: 'selection'}
           //'curveType': 'function',
           //'legend':    {position:'right'}
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderColumnChart(dataArray, 'chartOutput', options);
    };
    
    that.createWarStackedChart = function(param1, param2, avgmax1, avgmax2, stack){
        var dataArray = [];
        var doStack = true;
        var parameters ={
          'p1': param1, 
          'p2': param2, 
          'avgmax1': avgmax1, 
          'avgmax2': avgmax2  
        };
        
        if(stack == 'false') doStack = false;
                
        dataArray = that.charts.formatChartData('warStatStackedCol', that.chartData, parameters);
        
        // Set chart options
        var options = {
           'title':     'Clan Effectiveness (E) per War',
           'subtitle':  param1 + ' and ' + param2 + '(' + avgmax1 + '/' + avgmax2 + ')',
           'width':     chartWidth,
           'height':    chartHeight,
           'bar':       { groupWidth: '75%' },
           'isStacked':  doStack,
           'tooltip':   {trigger: 'selection'}
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderColumnChart(dataArray, 'chartOutput', options);
    };
    
    that.createAllWarTableData = function(AvgMax){
        var dataArray = [];
        var parameters = AvgMax;
        dataArray = that.charts.formatChartData('warStatsTable', that.chartData, parameters); 
        
        // Set chart options
        var options = {
           'title':     'Clan Effectiveness (E) per War - ' + AvgMax,
           'width':     chartWidth,
           'height':    chartHeight,
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderTableChart(dataArray, 'chartOutputTable', options); 
    };
    
    that.createSingleWarTableData = function(dateOfWar, statsType){
        var dataArray = [];
        dataArray = that.charts.formatChartData('warStatsSingleTable', that.singleChartData, statsType);
        
        // Set chart options
        var options = {
           'title':     'Clan Effectiveness (E) for War',
           'width':     chartWidth,
           'height':    chartHeight,
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderTableChart(dataArray, 'chartOutputTable', options); 
    };
    
    that.createSingleWarTableDataNoStats = function(dateOfWar){
        var dataArray = [];
        dataArray = that.charts.formatChartData('warStatsSingleTableNoStats', that.singleChartData);
        
        // Set chart options
        var options = {
           'title':     'Clan Effectiveness (E) for War',
           'width':     chartWidth,
           'height':    chartHeight,
        };
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderTableChart(dataArray, 'chartOutputTable', options); 
    };
    
    /*
     * ----------------------------------------------------------------
     * These following methods load the necessary data into the app
     * ----------------------------------------------------------------
     */
    that.getMasterData = function(returnData){
       that.loadTarget = "all";
       getFile("app/?task=allwars", "prepareCanvas", "json");
    };
    
    that.getSingleWarFile = function(warDate, showStats){
        that.loadTarget = "single";
        if(showStats == 'true'){
            var url = 'app/?task=singlewar&date=' + warDate + '& showstats=true';
        }else{
            var url = 'app/?task=singlewar&date=' + warDate;
        }
        
        getFile(url, 'showSingleWarOptions', 'json');
    };
    
    
    //helper function to get data files from backend API via AJAX
    var getFile = function(fullFilePath, callback, conversion){
        var tempData = null;
        
        $.get(fullFilePath, function(result){
            result = result.trim();
            if(result == "war file not found or is empty"){
                that.chartData = false;
                that.singleChartData = false;
            }else{
                if(conversion != ""){
                    switch(conversion){
                        case 'json':
                            tempData = JSON.parse(result);
                            break;
                        default:
                            tempData = result;
                            break;
                    }
                    if(that.loadTarget == "all"){
                        that.chartData = tempData;
                    }else if(that.loadTarget == "single"){
                        that.singleChartData = tempData;
                    }
                }else{
                    if(that.loadTarget == "all"){
                        that.chartData = tempData;
                    }else if(that.loadTarget == "single"){
                        that.singleChartData = tempData;
                    }
                    
                }
            }
            
            if(callback != ""){
                eval(callback+"()");
            }
        });
    };
    
    return that;
};
