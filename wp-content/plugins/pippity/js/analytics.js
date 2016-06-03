anl = {
	init: function() {
		anl.updateChart(anl.currentType);
		$j('.pty_pListShell', '.pty_anl_col').mouseover(anl.showPList).mouseout(anl.hidePList);
		$j('.pty_popupSelect').live('click', anl.selectPopup);
		$j('.pty_chartTypeSelect').live('click', anl.selectType_clickHandler);
		anl.initTypes();
	},
	initTypes: function() {
		$j('.pty_chartTypeList').empty();
		$j.each(anl.types, function(i, v){
			if (i != anl.currentType) {
				$j('.pty_chartTypeList').append('<dt><a href="#" id="pty_chartTypeSelect-'+i+'" class="pty_chartTypeSelect rc3">'+v.label+'</a></dt>');
			}
			else {
				$j('h1', '#pty_chartTypeShell').html(anl.types[i].label + ' <span class="right">▼</span>'); 
			}
		});
	},
	selectType_clickHandler: function() {
		anl.selectType(_pty.idData($j(this)));
	},
	selectType: function(type) {
		anl.currentType = type;
		anl.updateChart(anl.currentType);
		anl.initTypes();
	},
	updateChart: function(show) {
		anl.points = {};  
		anl.chart = new google.visualization.DataTable();
		anl.chart.addColumn('string', 'Date');
		var numCols = 0;
		for (var i = 1; i < 3; i++) {
			var thisCol = 'col'+i;
			if (anl[thisCol]) {
				anl.chart.addColumn('number', anl[thisCol].label);
				anl.prepData(thisCol);
				numCols++;
			}    
		}
		if (anl.numPoints() < 2) {
			$j('#pty_noData').show();
			$j('#pty_mainChart').hide();
		}
		else {
			var rows = [];
			$j('#pty_noData').hide();
			$j('#pty_mainChart').show();
			anl.pointSort();
			$j.each(anl.points, function(i, v){ 
				i = v[0];
				v = v[1];
				var row = [];
				var d = new Date(i*1000);
				row[0] = d.getMonth()+1 + '/' + d.getDate() + '/' + d.getFullYear();
				for (var i = 1; i < numCols+1; i++) {
					var thisCol = 'col'+i;
					if (v[thisCol] !== undefined) {
						var tV = v[thisCol];
						if (tV.imps != undefined && tV.imps > 0) {
							tV.crate = tV.convs / tV.imps * 100;
						}
						$j.each(anl.types, function(i, v){
							if (tV[i] == undefined) {
								tV[i] = v.def;
							}
						});
						row[i] = parseFloat(tV[show].toFixed(2));
					}
					else {
						row[i] = anl.types[show].def;
					}
				}
				rows.push(row);
			});
			anl.chart.addRows(rows);
			var chart = new google.visualization.AreaChart(document.getElementById('pty_mainChart'));
			chart.draw(anl.chart, {
				width:1112,
				height: 200, 
				chartArea: {top: 10, left: 60, height:130},
				title: '',
				pointSize: 2,
			    hAxis: {title: '', textStyle: {color: '#000'}, showTextEvery: 7, slantedText: true},
				vAxis: {title: '', textStyle: {color: '#000'}},
				legend: 'none',
				curveType: 'function'
			});           
		}
	},
	numPoints: function(){
		var size = 0, key;
		$j.each(anl.points, function(i, v){
			size++;
		});
		return size;	
	},
	updateColumns: function() {
		 for (var i = 1; i < 3; i++) {
			 var thisCol = 'col'+i;
			 $j('h1', '#pty_'+thisCol).html(anl[thisCol].label + ' <span class="right">▼</span>');
		 }                                               
		return true;
	},
	updatePopupData: function() {
		var popupids = [];
		popupids.push(anl.col1.popupid);
		popupids.push(anl.col2.popupid);
		popupids = popupids.join(',');
		_pty.a.send('getSetAnalytics', {popups : popupids, start: pty.rangeStart, end: pty.rangeEnd}, function(rsp) {
			$j.each(rsp.analytics, function(i, v){
				var col = '';
				if (i == anl.col1.popupid) {
					col = 'col1';
				}
				else {
					col = 'col2';
				}
				col = $j('#pty_'+col);
				$j('.pty_data-imps', col).html(v.impressions);
				$j('.pty_data-convs', col).html(v.conversions);
				$j('.pty_data-cRate', col).html((parseFloat(v.cRate).toFixed(2))+'%');
				$j('.pty_data-closeTime', col).html((parseFloat(v.closeTime).toFixed(2))+' seconds');
				$j('.pty_data-leaveTime', col).html((parseFloat(v.leaveTime).toFixed(2))+' seconds');
			});
		}); 

	},
	prepData: function(col) {
		$j.each(anl[col].a.daily, function(i, v){
			if (i > pty.rangeStart && i < pty.rangeEnd) {
				if (anl.points[i] == undefined) {
					anl.points[i] = {};
				}
				anl.points[i][col] = v;
			}
		}); 
	},
	showPList: function() {
		var $this = $j(this);
		var plist = $j('.pty_popupList', $this.parent());
		if (anl.plistTimo != undefined) {
			clearTimeout(anl.plistTimo);
		}
		if (!plist.is(':visible')) {
			plist.empty();
			$j.each(anl.plist, function(i, v){
				if (anl.col1.popupid != i && anl.col2.popupid != i) {
					plist.append('<dt><a href="#" id="pty_popupSelect-'+i+'" class="pty_popupSelect rc3">'+v+'</a></dt>');
				}
			});
			if (!anl.plist.length) {
				 plist.append('<dt><span class="pty_noOtherPopups">No other popups</span></dt>');
			}
			plist.show();
		}
	},
	hidePList: function() {
		anl.plistTimo = setTimeout(function(){
			$j('.pty_popupList').hide();
		}, 20);
	}, 
	selectPopup: function() {
		var $this = $j(this);
		var id = _pty.idData($this);
		_pty.a.send('getPopup', {popupid:id},  function(rsp) {
			var col = $this.closest('.pty_anl_col');
			if (col.attr('id').indexOf('col1') > -1) {
				col = 'col1';
			}
			else {
				col = 'col2';
			}
			anl[col] = rsp.popup;
			setTimeout(function(){
				anl.updateChart(anl.currentType);
			}, 10);
			anl.updateColumns();
			anl.hidePList();
		});
	},
	pointSort: function() {
		var o = anl.points;
		var a = [];
		var i, tmp;
		$j.each(o, function(i, v){
			a.push([i, o[i]]);
		});
		a.sort(function(a,b){ return a[0]>b[0]?1:-1; });
		anl.points = a;
	},
	col1: false,
	col2: false,
	points: {},
	currentType: 'convs',
	plist: [],
	types: {
		imps: {def: 0, label: "Impressions"}, 
		convs:{def: 0, label: "Conversions"}, 
		crate:{def: 0, label: "Conversion Rate (%)"},
		closeTime:{def: 0, label: "Time on Popup (s)"},
		leaveTime:{def: 0, label: "Time on Page (s)"}
	},
	_end: true
}

Object.size = function(obj) {

};
