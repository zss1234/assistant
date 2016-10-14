$(function(){
    var titleName = $("#menus li");
    var tabContent = $("#content div");
    if(titleName.length != tabContent.length){
        return;
    }
    for(var i = 0;i<titleName.length;i++){
        titleName[i].id = i;
        titleName[i].onclick = function(){
            for(var j = 0;j<titleName.length;j++){
                titleName[j].className = "";
                tabContent[j].style.display = "none"
            }
            this.className = "select";
            tabContent[this.id].style.display = "block";
        }
    }
});