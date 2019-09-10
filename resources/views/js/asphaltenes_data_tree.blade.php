<script type="text/javascript">
    var TreeAD = [];
    var getAsphaltenesDataTreeAD;


    getAsphaltenesDataTreeAD = $.get("{{url('getAsphaltenesData')}}", function (data) {
        TreeAD = data;
    });

    $(document).ready(function () {
        getAsphaltenesDataTreeAD.done(function () {
            $('#treeAsphaltenesData').tree(TreeAD);
        });
    });


    $(document).on('click', 'a[href="#link_asplatenes_data"]', function () {

        var sel_asphaltene_data = TreeAD;

        var pos = $(this).find('span').attr('id');
        var pos = pos.split("_");

        for (var i = 1; i <= pos.length - 1; i++) {
            sel_asphaltene_data = sel_asphaltene_data[pos[i]];
            var k = i + 1;
            if (k <= pos.length - 1) {
                sel_asphaltene_data = sel_asphaltene_data['child'];
            }
        }

        var getValueField = $.get("{{url('get_asphaltenes_data')}}", {asphaltenes_data: sel_asphaltene_data['id']}, function (data) {
            $('#asphaltene_particle_diameter').val(data.asphaltene_particle_diameter);
            $('#asphaltene_molecular_weight').val(data.asphaltene_molecular_weight);
            $('#asphaltene_apparent_density').val(data.asphaltene_apparent_density);
        });
        $("#asphaltenesData").modal('hide');
    });
</script>
