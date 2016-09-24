<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<script type="text/javascript" src="../js/jsapi.js"></script>

<script type="text/javascript">
  google.load("visualization", "1", {packages: ["timeline"]});
	google.setOnLoadCallback(drawChart);
	
  function drawChart() {
    var container = document.getElementById('example4.2');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();

    dataTable.addColumn({ type: 'string', id: 'Group' });
    dataTable.addColumn({ type: 'string', id: 'Category' });
    dataTable.addColumn({ type: 'string', id: 'ID' });
    dataTable.addColumn({ type: 'date', id: 'Start' });
    dataTable.addColumn({ type: 'date', id: 'End' });
    dataTable.addRows([
        [ 'GROUP #1', 'CategoryA', 'C00001', new Date(2016, 0, 1), new Date(2016, 0, 31) ],
        [ 'GROUP #1', 'CategoryA', 'C00002', new Date(2016, 1, 1), new Date(2016, 1, 28) ],
        [ 'GROUP #1', 'CategoryA', 'C00003', new Date(2016, 3, 1),  new Date(2016, 3, 15) ],
        [ 'GROUP #1', 'CategoryB', 'C00003', new Date(2016, 0, 21),  new Date(2016, 2, 19) ],
        [ 'GROUP #1', 'CategoryA', 'C00004', new Date(2016, 0, 1),  new Date(2016, 0, 15) ],
        [ 'GROUP #2', 'CategoryC', 'C00005', new Date(2016, 2, 8),  new Date(2016, 2, 15) ],
        [ 'GROUP #3', 'CategoryC', 'C00006', new Date(2016, 5, 1),  new Date(2016, 5, 15) ],
        [ 'GROUP #4', 'CategoryA', 'C00007', new Date(2016, 1, 15),  new Date(2016, 1, 25) ],
		[ 'SPAS14FEB16', 'MOV', 'EK-11', new Date(2016, 2, 02), new Date(2016, 2, 19)], 
	  	[ 'SPAS14FEB16', 'RME', 'EK-11', new Date(2016, 2, 24), new Date(2016, 3, 29)], 
	  	[ 'SPAS14FEB16', 'RME', 'EK-31', new Date(2017, 1, 13), new Date(2017, 2, 16)], 
	  	[ 'SPAS14FEB16', 'MOV', 'BALAM-23', new Date(2017, 02, 17), new Date(2017, 02, 26)], 
	  	[ 'SPAS14FEB16', 'RMA', 'BALAM-23', new Date(2017, 02, 27), new Date(2017, 05, 27)], 
	  	[ 'SPAS14FEB16', 'RME', 'BALAM-13', new Date(2017, 05, 30), new Date(2017, 06, 28)], 
	  	[ 'SAS01ABR16', 'MOV', 'EK 11', new Date(2016, 02, 08), new Date(2016, 03, 06)], 
	  	[ 'SAS01ABR16', 'RME', 'EK 11', new Date(2016, 03, 11), new Date(2016, 04, 08)], 
	  	[ 'SAS01ABR16', 'RME', 'EK 31', new Date(2016, 04, 12), new Date(2016, 05, 16)] 

    ]);

    var colors = [];
    var colorMap = {
        // should contain a map of category -> color for every category
        CategoryA: '#e63b6f',
        CategoryB: '#19c362',
        CategoryC: '#592df7',
		PER: '#7fff00',
        TER: '#ffff00',
        RMA: '#238c00',
		RME: '#592df7',
		MOV: '#4da6ff',
		EST: '#e63b6f',
		INAC: '#e63b6f'
    }
    for (var i = 0; i < dataTable.getNumberOfRows(); i++) {
        colors.push(colorMap[dataTable.getValue(i, 1)]);
    }

    var rowHeight = 41;
    var chartHeight = (dataTable.getNumberOfRows() + 1) * rowHeight;

    var options = {
        timeline: { 
            groupByRowLabel: true,
        },                          
        avoidOverlappingGridLines: true,
        height: chartHeight,
        width: '100%',
        colors: colors
    };

    // use a DataView to hide the category column from the Timeline
    var view = new google.visualization.DataView(dataTable);
    view.setColumns([0, 2, 3, 4]);

    chart.draw(view, options);
}
google.load('visualization', '1', {packages:['timeline'], callback: drawChart});
</script>

<div id="example4.2" style="height: 300px;"></div>

<body>
</body>
</html>