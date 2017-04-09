window.print = function(str){
    var div = document.createElement("div");
    div.innerHTML = str;
    document.body.appendChild(div);
};

function CArray(numElements){
    this.dataStore = [];
    this.pos = 0;
    this.numElements = numElements;
    this.insert = insert;
    this.toString = toString;
    this.clear = clear;
    this.setData = setData;
    this.clear = clear;
    this.setData = setData;
    this.swap = swap;
    this.bubbleSort = bubbleSort;
    this.selectionSort = selectionSort;
    this.insertionSort = insertionSort;

    for ( var i =0; i<numElements;i++)
    {
        this.dataStore[i] = i;
    }
}

function setData(){
    for ( var  i = 0; i < this.numElements; ++i){
        this.dataStore[i] = Math.floor(Math.random()*100);
    }
}

function clear(){
    for( var i = 0;i < this.dataStore.length;i++){
        this.dataStore[i] = 0;
    }
}

function insert(element){
    this.dataStore[this.pos++] = element;
}

function toString(){
    var retstr = "";
    for ( var i =0; i < this.dataStore.length; ++i){
        retstr += this.dataStore[i] + " ";
        if (i > 0 & i % 10 ==0){
            retstr += "<br>";
        }
    }
    return retstr;
}

function swap(arr, index1, index2){
    var temp = arr[index1];
    arr[index1] = arr[index2];
    arr[index2] = temp;
}

function bubbleSort(log_tip) {
    var numElements = this.dataStore.length;
    var temp;
    for ( var outer = numElements; outer >= 2; --outer) {
        log_tip && print("µÚ" + parseInt(numElements - outer + 1) +"ÂÖ")
        for ( var inner = 0; inner <= outer - 1; ++inner ) {
            if (this.dataStore[inner] > this.dataStore[inner + 1]) {
                swap(this.dataStore, inner, inner + 1);
                log_tip && window.print(this.dataStore.toString());
            }
        }

    }
}

function selectionSort(log_tip) {
    var min, temp;

    for (var outer = 0; outer <= this.dataStore.length-2; ++outer) {
        min = outer;
        log_tip && print("µÚ" + parseInt(outer+1) +"ÂÖ")
        for (var inner = outer + 1;
             inner <= this.dataStore.length-1; ++inner) {
            if (this.dataStore[inner] < this.dataStore[min]) {
                min = inner;
            }

        }
        swap(this.dataStore, outer, min);
        log_tip && window.print(this.dataStore.toString());
    }
}

function insertionSort(log_tip) {
    var temp, inner;
    for (var outer = 1; outer <= this.dataStore.length - 1; ++outer) {
        log_tip && print("µÚ" + parseInt(outer+1) +"ÂÖ")
        temp = this.dataStore[outer];
        inner = outer;
        while (inner > 0 && (this.dataStore[inner - 1] >= temp)) {
            this.dataStore[inner] = this.dataStore[inner - 1];
            --inner;
        }
        this.dataStore[inner] = temp;
        log_tip && window.print(this.dataStore.toString());
    }
}