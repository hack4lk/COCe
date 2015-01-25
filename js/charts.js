var Charts = function(){
    var that = {};
    
    that.chart = null;
    that.type = null;
    that.data = null;
    that.parsedData = [];
    that.selectedItem = null;
    
    that.formatChartData = function(chartType, passedData, passedParameter, passedParameter2){
        //reset parsed data in class
        that.parsedData = [];
        
        //assign class variables to this scope
        that.type = chartType;
        that.data = passedData;
        
        //call the proper method based on chart type
        switch(that.type){
            case 'warStatsAvgLine':
                return createWarStatsAvg('avg');
                break;
            case 'warStatsMaxLine':
                return createWarStatsAvg('max');
                break;
            case 'warStatsAvgCol':
                return createWarStatsColAvg('avg', passedParameter);
                break;
            case 'warStatsMaxCol':
                return createWarStatsColAvg('max', passedParameter);
                break;
            case 'warStatStackedCol':
                return createWarStatsColStacked(passedParameter);
                break;
            case 'warSingleWarCol':
                return createWarSingleCol(passedParameter, passedParameter2);
                break;
            default:
                //do nohting...
                break;
        }    
    };
    
    var createWarSingleCol = function(targetData, singleMetric){
        var tempArray = [];
        var dataNode = {};
        var prefix = "";
        
        if(targetData == 'avg') prefix = "Avg. ";   
        if(targetData == 'max') prefix = "Max ";
        
        console.log("prefix: " + targetData);
        
        if(targetData == "avg" || targetData == "max"){
            if(targetData == 'avg'){
                arrayRefKey = "__Averages__";
            }else if(targetData == 'max'){
                arrayRefKey = "__Maximums__";
                
            }
            
            tempArray.push(['Metric', prefix]);
            
            for(var key in that.data){
                tempArray.push([prefix+'Single', that.data[key][arrayRefKey]['Single']]);
                tempArray.push([prefix+'Single(w)', that.data[key][arrayRefKey]['wSingle']]);
                tempArray.push([prefix+'War', that.data[key][arrayRefKey]['War']]);
                tempArray.push([prefix+'War(w)',that.data[key][arrayRefKey]['wWar']]);
                tempArray.push([prefix+'War No Penalty', that.data[key][arrayRefKey]['War_NoP']]);
                tempArray.push([prefix+'War(w) No Penalty', that.data[key][arrayRefKey]['wWar_NoP']]);
            }
        } 
        
        if(targetData == "values"){
            tempArray.push(['Clan Member', 'values']);
            
            for(var key in that.data){
                var warData = that.data[key];
                
                for(var member in warData){
                    if(member != "__Averages__" && member != "__Maximums__"){
                        tempArray.push([member, warData[member][singleMetric]]);
                    }
                }
            }
        }
        
        var convertedData = new google.visualization.arrayToDataTable(tempArray);
        return convertedData;
    };
    
    var createWarStatsColStacked = function(targetDataNode){
        var prefix1 = "";
        var prefix2 = "";
        var dataNode1 = {};
        var dataNode2 = {};
        var tempArray = [];
        
        if(targetDataNode.avgmax1 == 'avg'){
            arrayRefKey1 = "__Averages__";
            prefix1 = "Avg.";
        }else if(targetDataNode.avgmax1 == 'max'){
            arrayRefKey1 = "__Maximums__";
            prefix1 = "Max";
        }
        
        if(targetDataNode.avgmax2 == 'avg'){
            arrayRefKey2 = "__Averages__";
            prefix2 = "Avg.";
        }else if(targetDataNode.avgmax2 == 'max'){
            arrayRefKey2 = "__Maximums__";
            prefix2 = "Max";
        }
        
        dataNode1 = setObjTitleParam(targetDataNode.p1, prefix1);
        dataNode2 = setObjTitleParam(targetDataNode.p2, prefix2);
        
        tempArray.push(['Date', dataNode1.title, dataNode2.title]);
        
        for(var key in that.data){
            tempArray.push([
                key, 
                that.data[key][arrayRefKey1][dataNode1.param],
                that.data[key][arrayRefKey2][dataNode2.param]
            ]);
        }
        var convertedData = new google.visualization.arrayToDataTable(tempArray);
        return convertedData;
    };
    
    var createWarStatsColAvg = function(AvgMax, targetDataNode){
        var tempArray = [];
        var dataNode = {};
        var prefix = "";
        
        if(AvgMax == 'avg') prefix = "Avg.";   
        if(AvgMax == 'max') prefix = "Max";
        
        dataNode = setObjTitleParam(targetDataNode, prefix);
        
        if(AvgMax == 'avg'){
            arrayRefKey = "__Averages__";
        }else if(AvgMax == 'max'){
            arrayRefKey = "__Maximums__";
        }
        tempArray.push(['Date', dataNode.title]);
        
        for(var key in that.data){
            tempArray.push([
                key, 
                that.data[key][arrayRefKey][dataNode.param],
            ]);
        }
        var convertedData = new google.visualization.arrayToDataTable(tempArray);
        return convertedData;
        
    };
    
    var createWarStatsAvg = function(AvgMax){
        var tempArray = [];
        var arrayRefKey = "";
        
        if(AvgMax == 'avg'){
            arrayRefKey = "__Averages__";
            tempArray.push(['Date', 'Avg. Single', 'Avg. Single(w)', 'Avg. War', 'Avg. War(w)', 'Avg. War No Penalty', "Avg. War(w) No Penalty"]);    
        }else if(AvgMax == 'max'){
            arrayRefKey = "__Maximums__";
            tempArray.push(['Date', 'Max Single', 'Max Single(w)', 'Max War', 'Max War(w)', 'Max War No Penalty', "Max War(w) No Penalty"]);
        }
        

        for(var key in that.data){
            tempArray.push([
                key, 
                that.data[key][arrayRefKey]['Single'],
                that.data[key][arrayRefKey]['wSingle'],
                that.data[key][arrayRefKey]['War'],
                that.data[key][arrayRefKey]['wWar'],
                that.data[key][arrayRefKey]['War_NoP'],
                that.data[key][arrayRefKey]['wWar_NoP'],
            ]);
        }
        var convertedData = new google.visualization.arrayToDataTable(tempArray);
        return convertedData;
    };
    
    //helper function to help output chart header and titles
    var setObjTitleParam = function(param, prefix){
        tempObj = {};
        switch(param){
            case 'single':
                tempObj.title = prefix + ' Single';
                tempObj.param = 'Single';
                break;
           case 'singleW':
                tempObj.title = prefix + ' Single(w)';
                tempObj.param = 'wSingle';
                break;
           case 'war':
                tempObj.title = prefix + ' War';
                tempObj.param = 'War';
                break;
           case 'warW':
                tempObj.title = prefix + ' War(w)';
                tempObj.param = 'wWar';
                break;
           case 'warNoP':
                tempObj.title = prefix + ' War No Penalty';
                tempObj.param = 'War_NoP';
                break;
           case 'warWNoP':
                tempObj.title = prefix + ' War(w) No Penalty';
                tempObj.param = 'wWar_NoP';
                break;
           default:
                //do nothing....
                break;
        }
        
        return tempObj;
    };
    
    /*
     * Helper method to get the selected item in the chart
     */
    var selectChartItem = function(){
        if(that.chart.getSelection()[0] != undefined){
             var selectedItem = that.chart.getSelection()[0];
            var value = that.data.getValue(selectedItem.row, 0);
            that.selectedItem = value;     
        }
    };
    
    /*
     * This method uses the built in Google Chart options to render the graph
     */
    that.renderLineChart = function(data, targetDiv, options, disableSelection){
        that.data = data;
        // Instantiate and draw our chart, passing in some options.
        that.chart = new google.visualization.LineChart(document.getElementById(targetDiv));
        
        if(disableSelection != false){
            google.visualization.events.addListener(that.chart, 'select', selectChartItem);
            
            that.chart.setAction({
               id: 'warDate',
               text: 'Full War Details',
               action: function(){
                   console.log(that.selectedItem);
               } 
            }); 
        }
        
        that.chart.draw(that.data, options);
    };
    
    that.renderColumnChart = function(data, targetDiv, options, disableSelection){
        that.data = data;
        // Instantiate and draw our chart, passing in some options.
        that.chart = new google.visualization.ColumnChart(document.getElementById(targetDiv));
        
        if(disableSelection != false){
            google.visualization.events.addListener(that.chart, 'select', selectChartItem);
                 that.chart.setAction({
               id: 'warDate',
               text: 'Full War Details',
               action: function(){
                   console.log(that.selectedItem);
               } 
            });    
        }
        
        that.chart.draw(that.data, options);
    };
    
    
    return that;
};
