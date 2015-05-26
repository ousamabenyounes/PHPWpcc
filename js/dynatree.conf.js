$(document).ready(function(){
    $("#tree3").dynatree({
        checkbox: true,
        selectMode: 3,
        children: treeData,
        onSelect: function(select, node) {
            // Get a list of all selected nodes, and convert to a key array:
            var selKeys = $.map(node.tree.getSelectedNodes(), function(node){
                return node.data.key;
            });
            $("#choosenUrl").val(selKeys.join(", "));

            // Get a list of all selected TOP nodes
            var selRootNodes = node.tree.getSelectedNodes(true);
            // ... and convert to a key array:
            var selRootKeys = $.map(selRootNodes, function(node){
                return node.data.key;
            });
         //   $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
         //   $("#echoSelectionRoots3").text(selRootNodes.join(", "));
        },
        onDblClick: function(node, event) {
            node.toggleSelect();
        },
        onKeydown: function(node, event) {
            if( event.which == 32 ) {
                node.toggleSelect();
                return false;
            }
        },
        // The following options are only required, if we have more than one tree on one page:
//        initId: "treeData",
        cookieId: "dynatree-Cb3",
        idPrefix: "dynatree-Cb3-"


    });



    $.map($("#tree3").dynatree("getTree").getSelectedNodes(),
        function(dtnode){
            var selKeys = $.map(dtnode.tree.getSelectedNodes(), function(dtnode){
                return dtnode.data.key;
            });
            $("#choosenUrl").val(selKeys.join(", "));
        });
}




);
