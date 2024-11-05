function loadMap(){
    var sol = {lat:14.825524 , lng:120.865265 };
    var map = new google.maps.Map(document.getElementById('map'),{
        zoom: 20,
        center:sol
    });
}