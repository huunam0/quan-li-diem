function makeLink(latex_code) {
        var str = latex_code;
        //Clean code
        str.replace(/<br>/gi,"");
        str.replace(/<br \/>/gi,"");
        //Create img tag
        latex_img = " <img src=\"http://latex.codecogs.com/gif.latex?"+ str +"\" title=\""+ str +"\" alt=\""+ str +"\" align=\"absmiddle\" border=\"0\" /> ";

        return latex_img;
}

function renderTeX(tag) {
        var eqn = window.document.getElementsByTagName(tag);
        for (var i=0; i<eqn.length; i++) {
                if (eqn[i].getAttribute("lang") == "latex" || eqn[i].getAttribute("xml:lang") == "latex") { 
                        if ( !eqn[i].innerHTML.match(/<img.*?>/i) )
                                eqn[i].innerHTML = makeLink(eqn[i].innerHTML);
                } 
        }
}

//Run
renderTeX("pre");
renderTeX("code");
