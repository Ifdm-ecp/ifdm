<script type="text/javascript">
    function showFrontendErrors(messageList) {
        $("#modal_error_frontend_messages").empty();

        for (var i = 0; i < messageList.length; i++) {
            $("#modal_error_frontend_messages").append("<li>" + messageList[i] + "</li>");
        }

        $("#modal_error_frontend").modal("show");
    }
</script>