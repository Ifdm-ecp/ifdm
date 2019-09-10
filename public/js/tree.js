function expand_collapse(call) {
    if ($(call).find('i').hasClass('glyphicon-chevron-up')) {
        $(call).find('i').addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-up');
    } else {
        $(call).find('i').addClass('glyphicon-chevron-up').removeClass('glyphicon-chevron-down');
    }
}

$.fn.extend({
    tree: function (jsonTree) {
        var id = this.attr('id');

        create = function (jsonTree, id) {
            if (!id) {
                id = 'tree_miss_id';
            }

            treeObj = $('#' + id);


            var treeDiv = $("<div>", {'class': "tree"}).appendTo(treeObj);
            var ini = 0;

            function treeElement(parent, branch, idt) {
                if (branch.length > 0) {
                    var ulist = $("<ul>");

                    ulist.attr('id', idt + '_children')
                    if (ini != 0) {
                        ulist.attr('class', 'collapse');
                    } else {
                        ini = 1;
                    }
                    //
                    var i = 0;

                    $.each(branch, function (ind, obj) {
                        var name = obj['name'];
                        var icon = obj['icon'];
                        var child = obj['child'];
                        var href = obj['href'];
                        var class_ = obj['class'];
                        var item;
                        var ilist = $("<li>");
                        var span = $("<span>");
                        var item_id = idt + '_' + i;
                        span.attr('id', item_id);
                        var img = $("<img>");
                        img.attr('class', 'tree-icon');
                        img.attr('src', icon);
                        span.append(img).append(' ' + name)

                        if (href != '') {
                            var ahref = $("<a class='"+class_+"'>");
                            ahref.attr('href', href);
                            item = ahref;
                            item.append(span);
                        } else {
                            item = span;
                        }

                        if (child.length > 0) {
                            var glyph = $("<i>");
                            ilist.addClass('parent_li');
                            glyph.attr('class', 'icon-movement glyphicon glyphicon-chevron-down');
                            span.append(glyph);
                            span.attr('data-toggle', 'collapse');
                            span.attr('data-target', '#' + item_id + '_children');
                            span.attr('onClick', 'expand_collapse(this)');
                        }

                        ilist.append(item);

                        if (child.length > 0) {
                            treeElement(ilist, child, item_id);
                        }

                        ulist.append(ilist);
                        i = i + 1;
                    });
                    parent.append(ulist);
                }
            }

            return treeElement(treeDiv, jsonTree, id);
        }

        return create(jsonTree, id);
    }
});




