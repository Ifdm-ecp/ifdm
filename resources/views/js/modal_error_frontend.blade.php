<script type="text/javascript">
    function showFrontendErrors(messageList) {
        $("#modal_error_frontend_messages").empty();
        $("#modal_error_frontend_title").html("Error");

        for (var i = 0; i < messageList.length; i++) {
            if (typeof messageList[i] === "object") {
                var tableMessageList = "<li class='modal_error_frontend_li_clickable' onclick='toggleModalErrorUl(" + i + ")'>" + messageList[i].message + "</li>";
                tableMessageList += "<ul id='modal_error_frontend_ul_" + i + "' class='modal_error_frontend_ul' style='display: none'>";

                for (var j = 0; j < messageList[i].errors.length; j++) {
                    tableMessageList += "<li>" + messageList[i].errors[j] + "</li>";
                }
                
                tableMessageList += "</ul>";
                $("#modal_error_frontend_messages").append(tableMessageList);
            } else if (typeof messageList[i] === "boolean") {
                $("#modal_error_frontend_title").html("Warning");
                $("#modal_error_frontend_messages").append("<p>This scenario is going to be saved with missing data. You need to complete the information required at a later time in order to run the results.</p>");
            } else {
                if (messageList[i].includes("Tab: ")) {
                    $("#modal_error_frontend_messages").append("<h4>" + messageList[i] + "</h4>");
                } else {
                    $("#modal_error_frontend_messages").append("<li>" + messageList[i] + "</li>");
                }
            }
        }

        if (typeof messageList[0] === "boolean") {
            $("#modal_error_frontend_button_ok").html("Stay and fill the form");
            $("#modal_error_frontend_button_continue").show();
        } else {
            $("#modal_error_frontend_button_ok").html("Ok");
            $("#modal_error_frontend_button_continue").hide();
        }

        $("#modal_error_frontend").modal("show");
    }

    function showFrontendErrorsBasic(message) {
        $("#modal_error_frontend_messages").empty();
        $("#modal_error_frontend_messages").append(message);
        $("#modal_error_frontend").modal("show");
    }

    function toggleModalErrorUl(index) {
        $("#modal_error_frontend_ul_" + index).toggle("fast");
    }
</script>