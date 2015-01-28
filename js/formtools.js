var FormTools = function(){
    //create and set initial vars for the application
    var that = {};
    that.tableHolder = "";
    that.warTable = "";
    that.warFormID = "";
    that.tableHeader = "War Data Upload Form";
    that.datePickerID = "wardate";
    that.autofillData = null;
    
    that.warTableRef = {
        indexId: 0,
        items: {}
    };
    
    that.tableRowData = {
        member: "Clan member name",
        homebase: "Home clan position",
        opponent1base: "Opponent 1 position",
        opponent2base: "Opponent 2 position",
        attack1stars: "# Stars on 1st attack",
        attack2stars: "# Stars on 2nd attack"
    };

    that.init = function(tableHolder, formDiv){
        that.tableHolder = $("#"+tableHolder);        
        that.warTable = $("#"+formDiv);
        that.warFormID = formDiv;
        that.warTableRef.indexId = 0;
        
        //add necessary elemements...
        createMandatoryFormElements();
        
        //add handlers to necessary form buttons
        $("#addRowBtn").on("click", $ft.addRow);
        $("#submitWarTable").on("click", $ft.onWarFormSubmit);
        
        //add initial and required elements...
        that.setWarTableHeader();
        that.addDatePicker(that.datePickerID);
    };
    
    //method to add table header row
    that.setWarTableHeader = function(message){
        var headerContent = '<h3>'+that.tableHeader+'</h3>';
        var autoUploadBtn = '<a class="btn btn-default" onclick="$ft.autofillForm()">Autofill members from last war</a>';
        
        if(message != undefined && message != ""){
            $("#formHeader").append('<h3>' + message + '</h3>' + autoUploadBtn);
        }else{
            $("#formHeader").append(headerContent + autoUploadBtn);
        }
    };
    
    //method to add date picker to specific div
    that.addDatePicker = function(targetDiv){
        $("#"+targetDiv).datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: "m/d/yy"
        }); 
    };
    
    var createMandatoryFormElements = function(){
                
        that.warTable.append('Application Key <input type="text" id="appkey" name="appkey" />');
        that.warTable.append('War Date <input type="text" id="wardate" name="wardate" placeholder="Click icon for date ->" />');
        that.tableHolder.append('<a class="btn btn-info" id="addRowBtn">Add Row</a>');
        that.tableHolder.append('&nbsp;<button name="submitWarTable" id="submitWarTable">Submit Form</button>');
    };
    
    that.addRow = function(targetMember){
       
        var rowContent = '<div id="tableRow_' + that.warTableRef.indexId + '">';
        for(key in that.tableRowData){
            if(key == 'member' && typeof targetMember != "object"){
                value = targetMember;
            }else{
                value = "";
            }
            rowContent += '<input type="text" value="' + value + '" name="' + key + '_'+that.warTableRef.indexId+'" placeholder="' + that.tableRowData[key] +'" />&nbsp;';
        }
        
        rowContent += '<a onclick="$ft.removeRowClickBtn('+that.warTableRef.indexId+')" class="btn btn-warning">Remove</a>';
        rowContent += '</div>';
        
        that.warTable.append(rowContent);
        that.warTableRef.items[that.warTableRef.indexId] = true;
        that.warTableRef.indexId++;
    };
    
    that.removeRowClickBtn = function(targetIndex){
        delete that.warTableRef.items[targetIndex];
        $("#tableRow_"+targetIndex).remove();
        console.log(that.warTableRef.items);
    };
    
    that.onWarFormSubmit = function(){
        console.log(that.warTableRef.items);
    };
    
    that.autofillForm = function(dataSet){
        if(dataSet){
            for(key in that.autofillData){
                if(key != '__War::Date__'){
                    that.addRow(key); 
                }
            }
        }else{
            var url = 'app/?task=singlewar&date=' + $app.latestWar.dateStr;
            $.get(url, function(result){
               that.autofillData = JSON.parse(result);
               $ft.autofillForm(true);
            });
        }
    };
    
    $ft = that;
    return that;
};
