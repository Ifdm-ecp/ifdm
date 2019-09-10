<script type="text/javascript">
    function validate_table(table_data,table_name,column_types){
        $("#div_error").empty();

        number_tables = table_data.length;

        var flag_message = false;

        var message_empty = "";
        var message_numeric = "";
        var message_date = "";
        var message_value_empty = "";

        var final_message = "";

        for (var k = 0; k < number_tables; k++){
            var flag_numeric = false;
            var flag_date = false;
            var flag_empty = false;
            var flag_value_empty = false;

            var actual_table = table_data[k]; 
            var table = actual_table.filter(value => Object.keys(value).length !== 0);
            var name = table_name[k];
            var types = column_types[k];
            var number_rows = table.length;

            if(number_rows>0){
                var number_columns = table[0].length;  

                for (var i = 0; i < number_columns; i++){
                    for (var j = 0; j < number_rows; j++){
                        if(types[i] == "numeric"){
                            if (typeof table[j][i] !== "undefined" && table[j][i] != null && table[j][i] !== "") {
                                if(!$.isNumeric(table[j][i])){
                                    message_numeric = "<ul><li>" + "Some data for "+name+" must be numeric. Please check your data." + "</li></ul>";
                                    
                                    flag_numeric = true;
                                    flag_message = true;
                                }
                            }
                        }

                        if(table[j][i] == null || table[j][i] === "" || typeof table[j][i] === "undefined"){
                            console.log(table[j][i]);
                            message_value_empty = "<ul><li>" + "There's missing information for "+name+". Please check your data." + "</li></ul>";
                            
                            flag_value_empty = true;
                            flag_message = true;
                        }
                    }
                }
            }else{
                message_empty = "<ul><li>" + name.capitalize() +" is empty. Please check your data." + "</li></ul>";
                $("#div_error").append(message_empty);
                flag_empty = true;
                flag_message = true;
            }

            if(flag_numeric){
                $("#div_error").append(message_numeric);
            }

            if(flag_value_empty){
                $("#div_error").append(message_value_empty);
            }                      
            
        }

        if(flag_message){
            var evt = window.event || arguments.callee.caller.arguments[0];
            evt.preventDefault();
            $("#loading_icon").hide();
            $("#modal_table").modal("show");
        }
    }

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
</script>