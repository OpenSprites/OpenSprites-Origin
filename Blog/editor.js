function setup() {
    $("#editor-contents").on("keyup", function() {
        var content_elem = document.getElementById("editor-contents");
        var content = marked(content_elem.innerHTML);
        var header = document.getElementById("editor-title").innerHTML;
        var output = ""
          + "<h1 class='entry-header'>" + header + "</h1>"
          + "<p class='entry-contents'>" + content + "</p>";
        document.getElementById("preview").innerHTML = "";
        document.getElementById("preview").innerHTML = output;
    });
};
