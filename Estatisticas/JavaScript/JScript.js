//Sys.Application.add_init(sender);

function AppInit(sender) {
    Sys.WebForms.PageRequestManager.getInstance().add_beginRequest(BeginRequestHandler);
}

function beginRequestHandler(sender, args) {
    var prm = Sys.WebForms.PageRequestManager.getInstance();
    if (args.get_postBackElement().id.indexOf('sdlEdicaoSumario') != -1) {
        hideActiveToolTip();
    }
}

function GetRadWindow() {
    var oWindow = null;
    if (window.radWindow)
        oWindow = window.radWindow;
    else if (window.frameElement.radWindow)
        oWindow = window.frameElement.radWindow;
    return oWindow;
}

function GetUrlParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}