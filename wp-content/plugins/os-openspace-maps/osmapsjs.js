OpenLayers.Layer.Markers.prototype.getDataExtent = function() {
  var maxExtent = null;
  if (this.markers && (this.markers.length > 0)) {
    var maxExtent=new OpenSpace.MapBounds();
    for (var i = 0, len = this.markers.length; i < len; i++) {
      var marker = this.markers[i];
      maxExtent.extend(marker.lonlat);
    }
  }
  return maxExtent;
}

function NGR2NE(ngr) {
  var e;
  var n;

  ngr = ngr.toUpperCase(ngr);

  // Remove any spaces
  var bits = ngr.split(' ');
  ngr = "";
  for (var i = 0; i < bits.length; i++)
    ngr += bits[i];

  var c = ngr.charAt(0);
  if (c =='S') { 
    e = 0;
    n = 0;
  }
  else if (c == 'T') {
    e = 500000;
    n = 0;
  }
  else if (c == 'N') { 
    n = 500000;
    e = 0;
  }
  else if (c == 'O') {
    n = 500000;
    e = 500000;
  }
  else if(c == 'H') {
    n = 1000000;
    e = 0;
  }
  else 
    return null;

  c = ngr.charAt(1);
  if (c == 'I')
    return null;

  c = ngr.charCodeAt(1) - 65;
  if (c > 8)
    c -= 1;
  e += (c % 5) * 100000;
  n += (4 - Math.floor(c/5)) * 100000;

  c = ngr.substr(2);
  if ((c.length % 2) == 1) 
    return null;
  if (c.length > 10) 
    return null;

  try {
    var s = c.substr(0, c.length / 2);
    while (s.length < 5)
      s += '0';
    e += parseInt(s, 10); 
    if (isNaN(e))
      return null; 
    s = c.substr(c.length / 2);
    while (s.length < 5)
      s += '0';
    n += parseInt(s, 10);
    if (isNaN(n))
      return null;

    return new OpenSpace.MapPoint(e, n);
  }
  catch (ex) {
    return null;
  }
}

function ValidateLongitude(value) {
  if (isNaN(value)) {
    alert (value + ' is not a valid coordinate');
    return false;
  }
  if ((value >= -180) && (value <= 180)) {
    return true;
  }
  alert(value + ' is not a valid coordinate');
  return false;
}

function ConvertCoordinates(pos) {
  var ne = NGR2NE(pos);
  if (ne) return ne;
  var coord = pos.split(',');
  if (coord.length != 2) {
    alert(pos + ' is not a valid lat long, it should be two decimal numbers separated by a comma');
    return null;
  }
  if (ValidateLongitude(coord[0]) && ValidateLongitude(coord[1])) 
  {
    gridProjection = new OpenSpace.GridProjection();
    var lonlat = new OpenLayers.LonLat(coord[1], coord[0]);
    return gridProjection.getMapPointFromLonLat(lonlat);    
  }
  return null;
}
