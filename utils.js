function getVideo(videoName) {
        const video = document.querySelector("#uploaded");

        fetch(videoName, {
                method: 'GET',
        })
        .then(response => response.blob())
        .then(result => {
                        const reader = new FileReader();

                        reader.addEventListener("load", function () {
                                        // convert image file to base64 string
                                        video.src = reader.result;
                                        video.load();
                        });
                        if (result) {
                                reader.readAsDataURL(result);
                        }
                console.log('Success: video loaded');
        })
        .catch(error => {
                console.log('Error:', error);
        });
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {
  var user = getCookie("username");
  if (user != "") {
    alert("Welcome again " + user);
  } else {
    user = prompt("Please enter your name:", "");
    if (user != "" && user != null) {
      setCookie("username", user, 365);
    }
  }
}

function tempCookie() {
        var name = document.getElementById("uname");
        if(name){
                setCookie("username", name.value, 365);
        }
}

function setUserName(){
        var show_uname = document.getElementById("show_uname");
        var user = getCookie("username");
        if(user != "") {
                show_uname.innerHTML = user;
        }
}
