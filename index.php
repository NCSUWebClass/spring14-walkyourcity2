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
  			var infowindow = new google.maps.InfoWindow({content: ""});
  			var pos;
  			var current_lat;
  			var current_lng;


  
  			function getCat() {
  
  			}

  			function initialize() {
				
  				// Try HTML5 geolocation
				if (navigator.geolocation) {
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
   					request.types[0] =  document.getElementById("cat1").value;
  					//var request2 = {
  					//  location: pyrmont,
  					//  radius: 5000,
  					//	types: ['park']
  					//};
  					infowindow = new google.maps.InfoWindow();

  					var service = new google.maps.places.PlacesService(map);
  					service.nearbySearch(request, callback);
  					//service.nearbySearch(request2, callback);


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
	
				var directionsService = new google.maps.DirectionsService();
				var directionsDisplay = new google.maps.DirectionsRenderer();
	
				//set lat lang to Raleigh by default
				var latlng = new google.maps.LatLng(35.779943, -78.641617);
				curLoc =  pos;
				endLoc = document.getElementById("search2").value;
				//if route not asked, don't show it
				if(curLoc != null && endLoc != null){
					directionsDisplay.setMap(map);
					directionsDisplay.setPanel(document.getElementById("directions"));
					var start = curLoc; //Harris Field
		var end =  endLoc;	//Worksite address
		var request = {
			origin:start,
    		destination:end,
    		travelMode: google.maps.DirectionsTravelMode.WALKING,
    		unitSystem: google.maps.UnitSystem.IMPERIAL
      	};
		directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
			}
		});
	}
}

function callback(results, status) {
	if (document.getElementById("poi-list") != null) {
		document.getElementById("poi-list").remove();
	}
  // You don't actually need this container to make it work
  var listContainer = document.createElement("div");
  listContainer.setAttribute("id", "poi-list");
// add it to the page
document.getElementById("POI").appendChild(listContainer);
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
      infowindow.setContent(place.name + "</br>" + "Rating: " + place.rating + "/5");
      infowindow.open(map, this);
    });
  }
/**
	// wait for the page to load
	function makelist(results){
		// Establish the array which acts as a data source for the list
		var listData = [ 'Blue' , 'Red' , 'White' , 'Green' , 'Black' , 'Orange'];
		// Make a container element for the list - which is a <div>
		// You don't actually need this container to make it work
		var listContainer = document.createElement("div");
		listContainer.id = 
		document.getElementByID("POI").getElementsByTagName("div")[0].remove();
		// add it to the page
		document.getElementByID("POI")[0].appendChild(listContainer);
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
*/

	google.maps.event.addDomListener(window, 'load', initialize);
	
	Element.prototype.remove = function() {
    	this.parentElement.removeChild(this);
	}
	NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    	for(var i = 0, len = this.length; i < len; i++) {
        	if(this[i] && this[i].parentElement) {
         	   this[i].parentElement.removeChild(this[i]);
        	}
    	}
	}
</script>
</head>
<body>
  <div id="map-canvas"></div>
  <div id="layout-middle">
    <div class="wrapper">
      <div id="content">
        <h2>Map</h2>
        <textarea id="search1" placeholder="Starting Location"></textarea>
        <textarea id="search2" placeholder="End Location"></textarea>
        <select id="cat1">
        	<option value=""></option>
           <option value="museum">museum</option>
           <option value="park">park</option>
           <option value="restaurant">restaurant</option>
           <option value="shopping_mall">shopping mall</option>
        </select>
        <button type="submit" class="btn btn-primary large" onclick="initialize();">Submit</button>
        </form>
        <h4 align="center">Directions</h4>

        <div id="directions" style="overflow: auto; height:200px;border-top:2px dashed;"></div>
        <p class="spacer"></p>
        <div id="POI" style="width:400px; height:800px;"></div>
      </div>
    </div>
  </div>

</body>
</html>
