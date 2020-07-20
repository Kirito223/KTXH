function TreeViewServices(prefix) {
    let rightCarets = document.getElementsByClassName(prefix + "-right-caret");
    let downCarets = document.getElementsByClassName(prefix + "-down-caret");
    for(let i=0; i < rightCarets.length; i++){
        rightCarets[i].addEventListener('click', function() {
        let idArr = this.id.split('-');
        let id = idArr[idArr.length - 1 ];
        let downCaretsWithSameId = document.getElementById(prefix + "-down-caret-" + id);
        downCaretsWithSameId.classList.toggle('hidden-item');
        openChildsNode(id)
        this.classList.toggle('hidden-item');
    });
    }
    
    for(let i = 0; i < downCarets.length; i++) {
        downCarets[i].addEventListener('click', function() {
        let idArr = this.id.split('-');
        let id = idArr[idArr.length - 1 ];
        let rightCaretsWithSameId = document.getElementById(prefix + "-right-caret-" + id);
        rightCaretsWithSameId.classList.toggle('hidden-item');
        closeAllChildsNode(id);
        this.classList.toggle('hidden-item');
    });
    }
    
    function openChildsNode(id) {
        let childsNode = document.getElementsByClassName(prefix + "-children-row-" + id);
        for(let i = 0; i < childsNode.length; i++) {
            childsNode[i].classList.remove('hidden-item');
        }
    }
    
    function closeAllChildsNode(id) {
        let childNodesIdArr = getAllChildsNodeId(id);
        for(let i = 0; i < childNodesIdArr.length; i++){
        let childsNode = document.getElementById(prefix + "-self-row-" + childNodesIdArr[i]);
        childsNode.classList.add('hidden-item');
        let downCaret = document.getElementById(prefix + "-down-caret-" + childNodesIdArr[i]);
        if(downCaret != null){
        downCaret.classList.add('hidden-item');
        }
        let rightCaret = document.getElementById(prefix + "-right-caret-" + childNodesIdArr[i]);
        if(rightCaret != null){
        rightCaret.classList.remove('hidden-item');
        }
         
        }
    }
    
    function getAllChildsNodeId(...nodesId) {
        if(nodesId.length == 0) {
            return [];
        }
        let nodesIdArr = [];
        let currentChildsNodeIdArr = []
        for(let i = 0; i < nodesId.length; i++){
            let childsNode = document.getElementsByClassName(prefix + "-children-row-" + nodesId[i]);
            childsNode = [...childsNode];
            currentChildsNodeIdArr = [...currentChildsNodeIdArr, ...childsNode.map(function(item) {
                return item.id.split("-")[item.id.split("-").length - 1]
            })];
        }
        return [...currentChildsNodeIdArr,...getAllChildsNodeId(...currentChildsNodeIdArr)]; 
     }
}