var refeshPageTimer = null;

function RefeshPage(){
    if(typeof(isPost) == 'undefined'){
        window.location.reload();
    }
}

refeshPageTimer = setTimeout("RefeshPage()", 300000);