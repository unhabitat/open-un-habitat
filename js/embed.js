document.write('<iframe id="oipa-embed" frameborder="0" allowfullscreen></iframe>');

function OipaEmbed(){
    this.options = function (url, width, height){
        var oipa_iframe = document.getElementById("oipa-embed");
        oipa_iframe.src = url;
        oipa_iframe.width = width;
        oipa_iframe.height = height;
    }
}

oipa_embed = new OipaEmbed();