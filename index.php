<!DOCTYPE html>
<html>
<head>
  <title>Place searches</title>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <style>
  html, body, #map-canvas {
    height: 80%;
    margin: 0px;
    padding: 0px
  }
  </style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places,geometry"></script>

  <script>
  var map;
  var infowindow = new google.maps.InfoWindow({
            content: ""
        });
  var pos;
  var current_lat;
  var current_lng;

  function initialize() {

// Try HTML5 geolocation
if(navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
   pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
   current_lat = position.coords.latitude;
   current_lng = position.coords.longitude;

   var pyrmont = new google.maps.LatLng(current_lat, current_lng);
   console.log("lat and lng: " + current_lng + " " +current_lat);

   map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: pos,
    zoom: 15
  });

   var request = {
    location: pyrmont,
    radius: 5000,
    types: ['restaurant']
  };
  var request2 = {
    location: pyrmont,
    radius: 5000,
    types: ['park']
  };
  infowindow = new google.maps.InfoWindow();

  var service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, callback);
  service.nearbySearch(request2, callback);


  var infowindow = new google.maps.InfoWindow({
    map: map,
    position: pos,
    content: 'You are here.'
  });

  map.setCenter(pos);
}, function() {
  handleNoGeolocation(true);
});
} else {
    // Browser doesn't support Geolocation
    handleNoGeolocation(false);
  }


}

function callback(results, status) {

  // You don't actually need this container to make it work
  var listContainer = document.createElement("div");
// add it to the page
document.getElementsByTagName("body")[0].appendChild(listContainer);
// Make the list itself which is a <ul>
var listElement = document.createElement("ol");
// add it to the page
listContainer.appendChild(listElement);
// Set up a loop that goes through the items in listItems one at a time

if (status == google.maps.places.PlacesServiceStatus.OK) {
  for (var i = 0; i < results.length; i++) {
    createMarker(results[i]);
    console.log("Name of Place " + i + ": "+ results[i].name);
         // create a <li> for each one.
         var listItem = document.createElement("li");
        // add the item text
        listItem.innerHTML = results[i].name;
        // add listItem to the listElement
        listElement.appendChild(listItem);

      }
    }
  }

  function createMarker(place) {
    var placeLoc = place.geometry.location;
    var marker = new google.maps.Marker({
      map: map,
      position: place.geometry.location
    });

    google.maps.event.addListener(marker, 'click', function() {
      infowindow.setContent(place.name);
      infowindow.open(map, this);
    });
  }

// wait for the page to load
function makelist(results){
// Establish the array which acts as a data source for the list
var listData = [ 'Blue' , 'Red' , 'White' , 'Green' , 'Black' , 'Orange'];
// Make a container element for the list - which is a <div>
// You don't actually need this container to make it work
var listContainer = document.createElement("div");
// add it to the page
document.getElementsByTagName("body")[0].appendChild(listContainer);
// Make the list itself which is a <ul>
var listElement = document.createElement("ul");
// add it to the page
listContainer.appendChild(listElement);
// Set up a loop that goes through the items in listItems one at a time
var numberOfListItems = listData.length;
for( var i =  0 ; i < numberOfListItems ; ++i){
// create a <li> for each one.
var listItem = document.createElement("li");
// add the item text
listItem.innerHTML = listData[i];
// add listItem to the listElement
listElement.appendChild(listItem);
}
}


google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
<body>
  <div id="map-canvas"></div>
  <div id="layout-middle">
    <div class="wrapper">
      <div id="content">
        <h2>Map</h2>
        <!--implement this after POI, work is in ncsu github
        <textarea id="search1" placeholder="Starting Location"></textarea>
        <textarea id="search2" placeholder="End Location"></textarea>
        <button type="submit" class="btn btn-primary large" onclick="initialize();">Submit</button>
        <h4 align="center">Directions</h4>

        <div id="directions" style="overflow: auto; height:200px;border-top:2px dashed;"></div>
        <p class="spacer"></p>
        <div id="POI" style="width:400px; height:800px;"></div>--!>
      </div>
    </div>
  </div>

</body>
</html>