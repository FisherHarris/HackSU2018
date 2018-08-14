var token; //The User's google ID Token
var profile;
    
function onSignIn(googleUser) {
    profile = googleUser.getBasicProfile();
    token = googleUser.getAuthResponse().id_token;
}

function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    console.log('User signed out.'); });
    timeout();
}

function getURLVar(name)
{
    var url = window.location.search.substring(1);
    var vars = url.split("&");
    for(var i=0; i<vars.length; i++) {
        var pair = vars[i].split("=");
        if(pair[0] == name)
            return pair[1];
    }
    return false;
}