window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(231,233,237)',
	green1: 'rgb(0, 128, 0)',
	green2: 'rgb(146, 208, 80)',
	brown: 'rgb(153, 102, 51)',
	blue1: 'rgb(0, 112, 192)',
	green_3: 'rgb(102, 153, 0)',
	blue2: 'rgb(0, 153, 255)',
	green_2: 'rgb(146, 208, 80)',
	aqua: 'rgb(51, 204, 204)'
	
};

window.randomScalingFactor = function() {
	return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
}