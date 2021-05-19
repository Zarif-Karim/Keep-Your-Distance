// Set SVG width, height, and padding
const w = 500;
const h = 500;
const padding = 60;
d3.csv('live-camera.csv', function (d) {
return [
	+d['Wavelength'],
	+d['Sample_1_Absorbance'],
	+d['Sample_2_Absorbance']
]
}).then(plot_data);