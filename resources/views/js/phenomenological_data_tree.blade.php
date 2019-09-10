<script type="text/javascript">
    var TreeAD=[];
    var getPhenomenologicalDataTreeAD;


    getPhenomenologicalDataTreeAD = $.get("{{url('getPhenomenologicalData')}}", function (data) {
        TreeAD = data;
    });

    $(document).ready(function(){
        getPhenomenologicalDataTreeAD.done(function () {
            $('#treePhenomenologicalData').tree(TreeAD);
        });
    });


    $(document).on('click','a[href="#link_phenomenological_data"]',function(){
        var table = $phenomenological_constants_table.handsontable('getInstance');
        var sel_asphaltene_data = TreeAD;
        
        var pos=$(this).find('span').attr('id');
        var pos=pos.split("_");

        for (var i=1;i<=pos.length-1;i++){
            sel_asphaltene_data=sel_asphaltene_data[pos[i]];
            var k=i+1;
            if (k<=pos.length-1){
                sel_asphaltene_data=sel_asphaltene_data['child'];
            }
        }

        var getValueField = $.get("{{url('get_phenomenological_data')}}", { phenomenological_data: sel_asphaltene_data['id']}, function (data) {
            table.updateSettings({
                data: JSON.parse(data.value),
                stretchH: 'all'
            });
            table.render();
        });
        $("#phenomenologicalData").modal('hide');
    });
</script>
