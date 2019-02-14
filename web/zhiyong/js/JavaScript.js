function overDis(obj, id) {
    var offId = document.getElementById(id);
    offId.style.display = "block";
    var offset = obj.offsetLeft;
    if (offId.offsetParent != null) {
        offset = offset - offId.offsetParent.offsetLeft;
    }
    if (id == "dis1") {
        offset = offset - 184;
    } else if (id == "dis2") {
        offset = offset - 280;
    }
    document.getElementById(id).style.left = offset + "px";

}
function outDis(id) {
    document.getElementById(id).style.display = "none";
}
function overDis1(obj, id) {
    obj.style.display = "block";
    document.getElementById(id).className = "hh";
}
function outDis1(obj, id) {
    obj.style.display = "none";
    document.getElementById(id).className = "";
}