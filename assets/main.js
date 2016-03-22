function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('map'), {
   center: {lat: window.lat?window.lat:-33.8688, lng: window.lng?window.lng:151.2195},
   zoom: 16,
   mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  var markers = [];
  if(window.lat)
    markers.push(new google.maps.Marker({
      map: map,
      icon: 'http://www.teclib-edition.com/wp-content/uploads/2015/06/map-marker.png',
      title: "Partner",
      position: {lat: window.lat, lng: window.lng}
    }));

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  google.maps.event.addDomListener(input, 'keydown', function(e) {
    if (e.keyCode == 13) {
      e.preventDefault();
    }
  });
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
        marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      marker = new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      });

      marker.addListener('click', function() {
        getPlaceInfo(place);
      });

      markers.push(marker);

      if(places.length == 1)
        getPlaceInfo(place);
      //place.place_id
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

function getCountry(addrComponents) {
    for (var i = 0; i < addrComponents.length; i++) {
        if (addrComponents[i].types[0] == "country") {
            return addrComponents[i].long_name;
        }
        if (addrComponents[i].types.length == 2) {
            if (addrComponents[i].types[0] == "political") {
                return addrComponents[i].long_name;
            }
        }
    }
    return false;
}

function getPlaceInfo(place){
  jQuery.get( "https://maps.googleapis.com/maps/api/place/details/json?placeid="+
    place.place_id + "&key=" + window.gmak, function( data ) {
    document.getElementById("meta-address").value =
        data.result.formatted_address;
    document.getElementById("meta-country").value =
        getCountry(data.result.address_components);
    document.getElementById("meta-web").value =
        data.result.website ? data.result.website : '';
    var latitude = place.geometry.location.lat();
    var longitude = place.geometry.location.lng();

    document.getElementById("meta-latitude").value = latitude;
    document.getElementById("meta-longitude").value = longitude;
  });
}
