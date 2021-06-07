let dataFromCSV;

function getVideo() {
        const video = document.querySelector("#uploaded");

        //get video name from file addEventListener
        if(selection != -1){

                var vid = document.getElementById(selection);

                let videoName = "uploads/" + vid.innerHTML + ".mp4";

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
                                                setData(selection.substring(10));
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
}

function setData(videoId){

        let data;

        const formData = new FormData();
        formData.append('ofVideo', videoId);

        fetch("php/getData.php", {
                method: 'POST',
                body : formData
        })
        .then(response => response.text())
        .then(result => {
                console.log('Success:', result);
                getDataFromCSV(result)
                .then(response => {
                        dataFromCSV = response;
                        generateChartsReport();
                });
        })
        .catch(error => {
                console.log('Error:', error);
        });

}

async function getDataFromCSV(fileName){
        const response = await fetch("uploads/"+fileName+".csv");
        const file = await response.text();
        const table = file.split('\n');
        let d = [];
        table.forEach(row => {
                const columns = row.split(',');

                for(let i = 0; i < columns.length; i++) {
                        if(d.length <= i) {
                                //create new dataSlot
                                d.push([]);
                                //push all previous elements as zero
                                for(let j = 0; j < d[0].length-1; j++ ) d[d.length-1].push(0);
                                //set current columns value
                                d[d.length-1].push(columns[i]);
                        } else {
                                //add data to column
                                d[i].push(columns[i]);
                        }
                }
        });

        return d;
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
  var user = getCookie("userName");
  if (user != "") {
    alert("Welcome again " + user);
  } else {
    user = prompt("Please enter your name:", "");
    if (user != "" && user != null) {
      setCookie("userName", user, 365);
    }
  }
}

function tempCookie() {
        var name = document.getElementById("uname");
        if(name){
                setCookie("userName", name.value, 365);
        }
}

function setUserName(){
        var show_uname = document.getElementById("show_uname");
        var user = getCookie("userName");
        if(user != "") {
                show_uname.innerHTML = user;
        }
}

function setup() {
        var login = getCookie('userId');
        if(login) {

        } else {

        }
        ///....
}
