// This is a simple file to read XML and then return some data, which is then processed through 
// another function and turned to HTML that can be included in the file.

function blog_load(entry) {
    // Returns an entry and it's data.
    var load;
    if (window.XMLHttpRequest) {
        load = new XMLHttpRequest();
    } else {
        load = new ActiveXObject("Microsoft.XMLHTTP");
    }
    load.open("GET", "entries/" + entry + ".xml", false);
    load.send();

    var response = load.responseXML;

    return response;
}