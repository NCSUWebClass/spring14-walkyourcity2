<!DOCTYPE html>
<html>
	<head>
		<title>Place searches</title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<style type="text/css">
			* {
				-webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
			}

			html, body, #map-canvas {
    			margin: 0px;
    			padding: 0px;
  		}

      body {

      }

      #map-canvas {
        width:70%;
        height:400px;
        border:1px solid #610585;
        border-radius: 3px;
        box-shadow:3px 3px 2px #888;
        margin:auto;
        margin-top:10px;
      }

      #content {
        text-align:center;
      }

      #submit {
        background-color:#610585;
        padding:7px;
        font-weight:bold;
        font-variant:small-caps;
        color:white;
        display:inline-block;
        border-radius:3px;
        font-size:16px;
        border:1px solid #610585;
        cursor:pointer;

      }

      #submit:hover {
        color:#CCC;
      }


  	</style>
  		<link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
  		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places,geometry"></script>
      <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
  		<script type="text/javascript">
        $(document).ready(function () {


        
  			var map;
  			var infowindow = new google.maps.InfoWindow({content: ""});
  			var directionsService = new google.maps.DirectionsService();
			var directionsDisplay = new google.maps.DirectionsRenderer();
  			var pos;
  			var current_lat;
  			var current_lng;

  			function getDirections(place) {
	
  				var curLoc = pos;
  				var endLoc = new google.maps.LatLng(place.geometry.location.k, place.geometry.location.A);
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
        			console.log("dest lat and lng: " + place.geometry.location.k + " " + place.geometry.location.A);
  				directionsService.route(request, function(response, status) {
  					if (status == google.maps.DirectionsStatus.OK) {
  						directionsDisplay.setDirections(response);
  					}
  				});
  			}

  			function initialize() {
				
  				// Try HTML5 geolocation
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
    					pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    					current_lat = position.coords.latitude;
    					current_lng = position.coords.longitude;

   						var pyrmont = new google.maps.LatLng(current_lat, current_lng);
   						console.log("lat and lng: " + current_lat + " " +current_lng);

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
  						//	location: pyrmont,
  						//	radius: 5000,
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
			  }

      $("#submit").on('click', function () {
        initialize();
      });

			function callback(results, status) {
				if (document.getElementById("poi-list")) {
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
    			//var placeLoc = place.geometry.location;
    			var marker = new google.maps.Marker({
      				map: map,
      				position: place.geometry.location
    			});

    			google.maps.event.addListener(marker, 'click', function() {
      				infowindow.setContent(place.name + "</br>" + place.rating + "/5");
      				infowindow.open(map, this);
      				getDirections(place);
    			});
  			}

			//google.maps.event.addDomListener(window, 'load', initialize);
	
			Element.prototype.remove = function() {
    			this.parentElement.removeChild(this);
			}
			
			NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    			for(var i = 0, len = this.length; i < len; i++) {
        			if (this[i] && this[i].parentElement) {
         	   			this[i].parentElement.removeChild(this[i]);
        			}
    			}
			}
      initialize();
      });
		</script>
	</head>
	<body onload="initialize()">
		<div id="map-canvas"></div>
  		<div id="layout-middle">
    		<div class="wrapper">
      			<div id="content">
        			<h2>Map</h2>
        				<textarea id="search1" placeholder="Enter Start Location"></textarea>
        				<textarea id="search2" placeholder="Enter End Location"></textarea>
        				<select id="cat1">
        					<option value=""></option>
        					<option value="museum">Museum</option>
        					<option value="park">Park</option>
           					<option value="restaurant">Restaurant</option>
           					<option value="shopping_mall">Shopping mall</option>
        				</select>
        				<div id="submit">Submit</div>
        			<h4 align="center">Directions</h4>

        			<div id="directions" style="overflow: auto; height:200px;border-top:2px dashed;"></div>
        			<p class="spacer"></p>
        			<div id="POI" style="width:400px; height:800px;"></div>
      			</div>
    		</div>
  		</div>

	</body>
</html>
