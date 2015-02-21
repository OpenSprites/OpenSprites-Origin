// This is a simple file to read XML and then return some data, which is then processed through
// another function and turned to HTML that can be included in the file.

function blog_load(entry) {
    // Returns an entry's document contents.
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

function blog_load_html(entry) {
    // Returns the HTML that would compose an entry.
    var response = blog_load(entry);
    var header = $(response).find("blogheader").text();
    var $contents = $(response).find("blogcontents");
    var contents = "";
    contents = get_contents($contents);
    return ""
        +"<div class='entry'>"
        +"<h1 class='entry-header'><a href='viewpost.php?post="+entry+"'>"+header+"</a></h1>"
        +"<p class='entry-contents'>"+contents+"</p>"
        +"</div>";
}

function get_contents(xml) {
    // Remove any tags from the XML inputted and turn in to a markdown string.
    var output = "";
    for (var i = 0; i < xml.length; i++) {
        if (xml[i].nodeValue !== null) {
            output += xml[i].nodeValue;
        } else {
            output += get_contents(xml[i].childNodes);
        }
    }

    output = marked(output);
    return output;
}
