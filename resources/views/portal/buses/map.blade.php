<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0,
            width=device-width" />
            <script src="https://js.api.here.com/v3/3.1/mapsjs-core.js"
            type="text/javascript" charset="utf-8"></script>
          <script src="https://js.api.here.com/v3/3.1/mapsjs-core-legacy.js"
            type="text/javascript" charset="utf-8"></script>
          <script src="https://js.api.here.com/v3/3.1/mapsjs-service.js"
            type="text/javascript" charset="utf-8"></script>
          <script src="https://js.api.here.com/v3/3.1/mapsjs-service-legacy.js"
            type="text/javascript" charset="utf-8"></script>        
          <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
          <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script> 
          <script src="https://kit.fontawesome.com/539aa3b9f4.js" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" 
            crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" 
            crossorigin="anonymous"></script>
          <script src="{{ asset('js/flexible-ployline.js') }}"></script>
            
          <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container" style="margin:0px;padding:0px;max-width:100%">
      <div class="row" style="margin:0px;padding:0px;height:100%;position:relative;">
          <div class="col" style="margin:0px;padding:0px;" id="mapContainer">            
          </div>
          <div class="btn-group" style="position:absolute;top:10;left:10">
            <button type="button" class="btn btn-primary" onclick="setHome();">
              Set Home
              <i class="fas fa-home"></i>
            </button>
          </div>
      </div>
    </div>
        
    <script>
      var userToken = "{{$user['code']}}";
      var platform = new H.service.Platform({
        'apikey': 'nlmy3lRojlPkJaEVMuZkkkiGIHCfbRXUgoPL1d_blzI'
      });
      var maptypes = platform.createDefaultLayers();
      var homeLocation = { lng: {{$lng}}, lat: {{$lat}} };
      var map = new H.Map(
        document.getElementById('mapContainer'),
        maptypes.raster.normal.map,
        {
          zoom: 20,
          center: { lng: {{$lng}}, lat: {{$lat}} },
        });
      window.addEventListener('resize', () => map.getViewPort().resize());
      var mapEvents = new H.mapevents.MapEvents(map);
      var behavior = new H.mapevents.Behavior(mapEvents);
      var ui = H.ui.UI.createDefault(map, maptypes);
      var homeIcon = new H.map.Icon('{{ asset('here/img/House.png') }}');
      var centerIcon = new H.map.Icon('{{ asset('here/img/Plus_01.png') }}');
      var busIcon = new H.map.Icon('{{ asset('here/img/Bus.png') }}');
      var outIcon = new H.map.Icon('{{ asset('here/img/out.png') }}');
      var inIcon = new H.map.Icon('{{ asset('here/img/in.png') }}');
      var centerMark = new H.map.Marker(homeLocation, { icon: centerIcon  });	 
      var inMarker =  new H.map.Marker(homeLocation, { icon: inIcon, visibility: false });
      var outMarker =  new H.map.Marker(homeLocation, { icon: outIcon, visibility: false });	
      var homeMarker = new H.map.Marker({ lat: {{$home[0]['lat']}}, lng: {{$home[0]['lng']}}}, { icon: homeIcon  });
      map.addObject(centerMark);
      map.addObject(homeMarker);
      map.addObject(inMarker);
      map.addObject(outMarker);
      map.getViewModel().addEventListener('sync', function() {
        var center = map.getCenter();
        centerMark.setGeometry(center);
      });

      function DrawRoute(routeString) {
        if(routeString) {
          var linestring = new H.geo.LineString();
		  var cntr = 0;
		  var max = routeString.split(',').length;
          routeString.split(',').forEach( item => {
			cntr++;
            if (item == "" || cntr + 3 >= max)
            {
              return;
            }
            var route,
            routeShape;
            var result = decode(item);
            result.polyline.forEach( point => {
              linestring.pushLatLngAlt(point[0], point[1]);
            });            
          })  
          var routeOutline = new H.map.Polyline(linestring, {
            style: {
              lineWidth: 5,
              strokeColor: 'rgba(0, 128, 255, 0.7)',
              lineTailCap: 'arrow-tail',
              lineHeadCap: 'arrow-head'
            }
          });
          
          var routeArrows = new H.map.Polyline(linestring, {
            style: {
              lineWidth: 5,
              fillColor: 'white',
              strokeColor: 'rgba(255, 255, 255, 1)',
              lineDash: [0, 5],
              lineTailCap: 'arrow-tail',
              lineHeadCap: 'arrow-head' }
            }
          );
          
          var routeLine = new H.map.Group();
          routeLine.addObjects([routeOutline, routeArrows]);
          map.addObjects([routeLine]);
          map.getViewModel().setLookAtData({bounds: routeLine.getBoundingBox()});        
        }
      };

      var router = platform.getRoutingService();
      var buses = new Map();
      setInterval(getBusLocation, 10000);
	  getBusLocation();

      function getBusLocation()
      {
        $.get('{{ asset('api/app/map/bus?api_token=') }}' + userToken, 
        (result) => {
          result.forEach(element => {
            if (Array.isArray(element.buses) && element.buses.length)
            {
              var bus = element.buses[0];
              if (Array.isArray(element.s2j) && element.s2j.length  && element.s2j[0].journey_id == bus.journeys[0].id)
              {
                if (element.s2j[0].in_lng)
                {
                  inMarker.setGeometry(
                    { 
                      lng: element.s2j[0].in_lng, 
                      lat: element.s2j[0].in_lat,
                    });

                  inMarker.setVisibility(true);
                }

                if (element.s2j[0].out_lng)
                {
                  outMarker.setGeometry(
                    { 
                      lng: element.s2j[0].out_lng, 
                      lat: element.s2j[0].out_lat,
                    });
                  outMarker.setVisibility(true);
                  return;
                }
              }  
              
              if (buses.has(bus.id))
              {
                var current = buses.get(bus.id);
                current.setGeometry(
                  { 
                    lng: bus.journeys[0].current_lng, 
                    lat: bus.journeys[0].current_lat
                  });
              }
              else
              {
                var marker = new H.map.Marker(
                  { 
                    lng: bus.journeys[0].current_lng, 
                    lat: bus.journeys[0].current_lat
                  }, 
                  {icon: busIcon});
                buses.set(bus.id, marker);
                map.addObject(marker);
                var routeString = bus.journeys[0].route;
                DrawRoute(routeString);
              }
            }            
          });
        });
      }
      
      function setHome()
      {
        $.post("{{ asset('api/app/map/home?api_token=') }}" + userToken + "&lat=" + map.getCenter().lat + "&lng=" + map.getCenter().lng, (data) => {
            homeMarker.setGeometry({ lat: data.lat, lng: data.lng});
          });
      }      
    </script>
  </body>
</html>

