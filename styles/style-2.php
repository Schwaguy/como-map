<?php
$mapStyle['dynamic'] = "[{'featureType':'administrative','elementType':'labels.text.fill','stylers':[{'color':'#444444'}]},{'featureType':'landscape','elementType':'all','stylers':[{'color':'#f2f2f2'}]},{'featureType':'poi','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'road','elementType':'all','stylers':[{'saturation':-100},{'lightness':45}]},{'featureType':'road.highway','elementType':'all','stylers':[{'visibility':'simplified'}]},{'featureType':'road.arterial','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'transit','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'all','stylers':[{'color':'#afb1b1'},{'visibility':'on'}]}]"; 
			
$mapStyle['static'] = 'feature:administrative%7Celement:geometry%7Ccolor:0xa7a7a7&style=feature:administrative%7Celement:labels.text.fill%7Ccolor:0x737373%7Cvisibility:on&style=feature:landscape%7Celement:geometry.fill%7Ccolor:0xefefef%7Cvisibility:on&style=feature:landscape.man_made%7Cvisibility:off&style=feature:landscape.natural.landcover%7Cvisibility:off&style=feature:landscape.natural.terrain%7Cvisibility:off&style=feature:poi%7Celement:geometry.fill%7Ccolor:0xdadada%7Cvisibility:off&style=feature:poi%7Celement:labels%7Cvisibility:off&style=feature:poi%7Celement:labels.icon%7Cvisibility:off&style=feature:poi.attraction%7Ccolor:0xe18aa5%7Cvisibility:off&style=feature:poi.attraction%7Celement:geometry%7Cvisibility:off&style=feature:poi.attraction%7Celement:labels%7Cvisibility:off&style=feature:poi.attraction%7Celement:labels.text%7Ccolor:0x897c7c&style=feature:poi.attraction%7Celement:labels.text.fill%7Cvisibility:off&style=feature:poi.attraction%7Celement:labels.text.stroke%7Cvisibility:on&style=feature:road%7Celement:labels.icon%7Cvisibility:off&style=feature:road%7Celement:labels.text.fill%7Ccolor:0x696969&style=feature:road.arterial%7Celement:geometry.fill%7Ccolor:0xffffff&style=feature:road.arterial%7Celement:geometry.stroke%7Ccolor:0xd6d6d6%7Cvisibility:off&style=feature:road.highway%7Celement:geometry.fill%7Ccolor:0xd9d9d9&style=feature:road.highway%7Celement:geometry.stroke%7Ccolor:0xb9b9b9%7Cvisibility:on&style=feature:road.local%7Celement:geometry.fill%7Ccolor:0xffffff%7Cvisibility:on%7Cweight:1.8&style=feature:road.local%7Celement:geometry.stroke%7Ccolor:0xd7d7d7%7Cvisibility:off&style=feature:transit%7Ccolor:0x808080%7Cvisibility:off&style=feature:water%7Csaturation:100%7Cvisibility:on&style=feature:water%7Celement:geometry%7Csaturation:-63%7Clightness:53%7Cvisibility:on&style=feature:water%7Celement:geometry.fill%7Ccolor:0xd4d4d3&style=feature:water%7Celement:geometry.stroke%7Cvisibility:off&style=feature:water%7Celement:labels%7Cvisibility:off&style=feature:water%7Celement:labels.icon%7Csaturation:-75%7Clightness:61%7Cvisibility:on&style=feature:water%7Celement:labels.text%7Ccolor:0x6a6a6a%7Cvisibility:on&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x7c7c7c%7Cvisibility:off'; 
$mapStyle['bing'] = "elements: {
area: { fillColor: '#dadada' },
water: { fillColor: '#d3d3d3',labelVisible: true, labelColor: '#6c96cf' },
majorRoad:{fillColor:'#d5d5d5', strokeColor: '#c7c7c7', labelColor: '#6c96cf'},
tollRoad: { fillColor: '#ffffff', strokeColor: '#b3b3b3', labelColor: '#6c96cf', labelVisible: false},
arterialRoad: { fillColor: '#ffffff', strokeColor: '#d7dae7', labelColor: '#6c96cf'}, 
controlledAccessHighway: { fillColor: '#ffffff', strokeColor: '#b3b3b3', labelColor: '#6c96cf',labelVisible: false}, 
highway: { fillColor: '#ffffff', strokeColor: '#b3b3b3', labelColor: '#025894', labelVisible: false }, 
road: { fillColor: '#696969', strokeColor: '#f696969', labelColor: '#6c96cf'}, 
street: { fillColor: '#ffffff', strokeColor: '#ffffff', labelColor: '#6c96cf'}, 
	transit: { fillColor: '#808080', strokeColor: '#c2c2c2', labelColor: '#6c96cf' } 
}, 
settings: { 
	landColor: '#efefef' 
}"; 
?>