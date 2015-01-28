var App = function(){
    /*
     * Set all the default value for chart
     */
    var that = {};
    var chartWidth = '100%';
    var chartHeight = 300;
    
    that.chartData = null;
    that.singleChartData = null;
    that.charts = null;
    that.loadTarget = "all";
    that.defaultChartTitle = 'Clan Effectiveness for War';
    that.latestWar = {date:0, dateStr:""};
    
    that.defaultOptions = {
        'title':     that.defaultChartTitle,
        'width':     chartWidth,
        'height':    chartHeight,
        'curveType': 'function',
        'legend':    {position:'right'}
    };
    
    that.init = function(){
        that.charts = new Charts();
        //on initialization, load the libraries and grab general war stats
        google.load('visualization', '1.0', {'packages':['corechart', 'table']});
        google.setOnLoadCallback(that.getMasterData);
    };
    
    //this method gets called when the app first gets loaded and after initial war overview
    //data is loaded into application
    var prepareCanvas = function(){
        //this will change when UI is developed
        $("#controls").show('slow');
        if(that.chartData === false){
            alert("no data loaded yet!");
        }
        determineLatestWar();
    };
    
    //this method gets called when single war data is loaded
    var showSingleWarOptions = function(){
        console.log('single war file loaded...');
    };
    
    that.createSingleWarColChartStats = function(dateOfWar, statsType, singleMetric){
        var dataArray = [];
        dataArray = that.charts.formatChartData('warSingleWarCol', that.singleChartData, statsType, singleMetric);
         // Set chart options
        var options = that.defaultOptions;
        options.title =  that.defaultChartTitle + ' on ' + dateOfWar;
        options.curveType = 'function';
        
        that.charts.renderColumnChart(dataArray, 'chartOutput', options, false);
    };
    
    that.createWarLineChart = function(dataType){
        var dataArray = [];
        dataArray = that.charts.formatChartData(dataType, that.chartData);
        
        var options = that.defaultOptions;
        options.tooltip = {trigger: 'selection'};
        
        that.charts.renderLineChart(dataArray, 'chartOutput', options);
    };
    
    that.createWarColumnChart = function(dataType, parameter){
        var dataArray = [];
        dataArray = that.charts.formatChartData(dataType, that.chartData, parameter);
        
        var options = that.defaultOptions;
        options.tooltip = {trigger: 'selection'};
        
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
        
        var options = that.defaultOptions;
        options.tooltip = {trigger: 'selection'};
        options.isStacked = doStack;
        
        that.charts.renderColumnChart(dataArray, 'chartOutput', options);
    };
    
    that.createAllWarTableData = function(AvgMax){
        var dataArray = [];
        var parameters = AvgMax;
        dataArray = that.charts.formatChartData('warStatsTable', that.chartData, parameters); 
        
        // Set chart options
        var options = that.defaultOptions;
        options.title = that.defaultChartTitle + ' - ' + AvgMax;
        
        //render function of the charts clas to output chart to specified div
        that.charts.renderTableChart(dataArray, 'chartOutputTable', options); 
    };
    
    that.createSingleWarTableData = function(dateOfWar, statsType){
        var dataArray = [];
        dataArray = that.charts.formatChartData('warStatsSingleTable', that.singleChartData, statsType);
        
        var options = that.defaultOptions;
        
        that.charts.renderTableChart(dataArray, 'chartOutputTable', options); 
    };
    
    that.createSingleWarTableDataNoStats = function(dateOfWar){
        var dataArray = [];
        dataArray = that.charts.formatChartData('warStatsSingleTableNoStats', that.singleChartData);
        
        var options = that.defaultOptions;
        
        that.charts.renderTableChart(dataArray, 'chartOutputTable', options); 
    };
    
    var determineLatestWar = function(){
        var tempKey = 0;
        for(key in that.chartData){
            tempKey = key.replace(new RegExp('-', 'g'), "");
            if(tempKey > that.latestWar.date){
                that.latestWar.date = tempKey;
                that.latestWar.dateStr = key;
            }
        };
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
    
    $app = that;
    return that;
};
