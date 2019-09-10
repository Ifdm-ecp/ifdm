var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 5.4545, lng: -74.356},
    zoom: 8
  });
}
