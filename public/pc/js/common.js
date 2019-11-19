function StringBuilder() {
    this.__strings__ = new Array();
}
StringBuilder.prototype.append = function (str) {
    this.__strings__.push(str);
    return this;    //方便链式操作
}
StringBuilder.prototype.toString = function () {
    return this.__strings__.join("");
}