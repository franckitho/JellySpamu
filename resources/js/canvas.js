var sprite_src = "http://www.clker.com/cliparts/w/O/e/P/x/i/map-marker-hi.png";
var canvas = document.getElementById('canvas_preview');
var context = canvas.getContext("2d");
var gwidth = canvas.getAttribute('width')
var gheight = canvas.getAttribute('height')
var mapSprite = new Image();
mapSprite.src = canvas.getAttribute('src');

var Marker = function () {
    this.Sprite = new Image();
    this.Sprite.src = sprite_src;
    this.Width = 12;
    this.Height = 20;
    this.XPos = 0;
    this.YPos = 0;
}

var Markers = new Array();
var mouseClicked = function (mouse) {
    var rect = canvas.getBoundingClientRect();
    var mouseXPos = (mouse.x - rect.left);
    var mouseYPos = (mouse.y - rect.top);
    var marker = new Marker();
    marker.XPos = mouseXPos - (marker.Width / 2);
    marker.YPos = mouseYPos - marker.Height;
    Markers.pop();
    Markers.push(marker);
}

canvas.addEventListener("mousedown", mouseClicked, false);
context.font = "15px Arial";
context.textAlign = "center";

var main = function () {
    draw();
};

var draw = function () {
        context.fillStyle = "#000";
        context.fillRect(0, 0, canvas.width, canvas.height);
        context.drawImage(mapSprite, 0, 0, gwidth, gheight);
        var tempMarker = Markers[0];
        context.drawImage(tempMarker.Sprite, tempMarker.XPos, tempMarker.YPos, tempMarker.Width, tempMarker.Height);
}

setInterval(main, (1000 / 60));
