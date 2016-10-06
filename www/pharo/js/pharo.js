
$(document).ready(function () {
    $(".newtab").click(function () {
        var productLink = $(this).attr("href");
        $(this).attr("target", "_blank");
        window.open(productLink);
        return false;
    });





});


function isValidEmail(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}


function tinyMCEcleaner(node) {
    var prev;
    for (var child = node.lastChild; child; child = prev) {
        prev = child.previousSibling;

        if (node.nodeType != 1)
        {
            continue;
        }//if

        if (child.nodeType == 8)
        {
            node.removeChild(child);
            continue;
        }//if

        if (child.nodeName == 'SCRIPT')
        {
            node.removeChild(child);
            continue;
        }//if

        if (child.nodeName == 'FONT')
        {
            node.removeChild(child);
            continue;
        }//if

        if (child.attributes)
        {
            for (var i = child.attributes.length; i-- > 0; )
            {
                if (
                        child.attributes[i].name == 'src'
                        ||
                        child.attributes[i].name == 'alt'
                        ||
                        child.attributes[i].name == 'width'
                        ||
                        child.attributes[i].name == 'height'
                        ||
                        child.attributes[i].name == 'align'
                        ||
                        child.attributes[i].name == 'valign'
                        ||
                        child.attributes[i].name == 'border'
                        ||
                        child.attributes[i].name == 'href'
                        ||
                        child.attributes[i].name == 'title'
                        )
                {
                    continue;
                }
                child.removeAttributeNode(child.attributes[i]);
            }//for
        }//if

        tinyMCEcleaner(child);
    }//for
}
