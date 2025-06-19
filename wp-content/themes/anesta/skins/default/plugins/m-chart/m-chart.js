/* global jQuery:false */
/* global ANESTA_STORAGE:false */

( function() {
	"use strict";

	var $window   = jQuery( window ),
		$document = jQuery( document ),
		$body = jQuery('body');
	
	var chartOptions = {
	    chart: {
	        backgroundColor: 'transparent',
	        plotBackgroundColor: 'transparent',
	        plotBorderWidth: 0,

	        spacingBottom: 1,
			spacingLeft: 0,
			spacingRight: 0,
			spacingTop: 5,

			style: {
	            fontFamily: 'inherit'
	        }
	    },
	    title: {
	        style: {
	            display: 'none'
	        }
	    },
	    xAxis: {
	    	labels: {
		    	style: {
		            fontSize: '12px',
		            fontWeight: '400',
		            letterSpacing: '0.1em',
		            textTransform: 'uppercase'
		        },
				y: 25
	        },
	        tickWidth: 0
	    },
	    yAxis: {
	    	labels: {
		    	style: {
		            fontSize: '12px',
		            fontWeight: '400',
		            letterSpacing: '0.1em',
		            textTransform: 'uppercase'
		        },
		        padding: 3,
		        x: -12,
				y: 6
	        },
	        tickInterval: 10
	    },
	    legend: {
	    	itemDistance: anesta_m_chart_legend_spacer(),
	    	itemMarginBottom: 7,
			itemMarginTop: 0,
	    	itemStyle: {
	            fontSize: '14px',
	            fontWeight: '400'		        
	        },
	        align: 'left',
	        margin: 8,
        	symbolWidth: 4,
        	symbolHeight: 4,
        	symbolPadding: 13,
        	x: 25
	    },
	    tooltip: {
	    	shadow: false,
	    	borderRadius: 6,
	    	borderWidth: 0,
	    	padding: 12
	    },
	    plotOptions: {
			series: {
				events: {
					legendItemClick: function() {
						var clickedSeries = this,
						series = clickedSeries.chart.series,
						allSeriesVis = series.map(series => series.visible),
						isSeriesVis;

						allSeriesVis[clickedSeries.index] = !allSeriesVis[clickedSeries.index];
						isSeriesVis = allSeriesVis.some(vis => vis);
						if (!isSeriesVis) return false;
					}
				}
			}
		}
	};

	var column_options = {
	    chart: {
	        plotBorderWidth: 1,
	        spacingBottom: -6,
	    },
	    plotOptions: {
	        series: {
	            pointWidth: 7,
           	 	borderRadius: 5,
           	 	borderWidth: 0
	        }
	    }
	};

	var area_options = {
	    chart: {
	        plotBorderWidth: 1
	    },	    
	    plotOptions: {
	        series: {
	            fillOpacity: 1,
	            marker: {
	            	symbol: 'circle',
	            	lineColor: '#ffffff',
					lineWidth: 2,
					width: 7,
					height: 7
	            }
	        },
	        area: {
	            pointPlacement: 'on'
	        }
	    },
	    xAxis: {
	    	labels: {
		    	align: 'center'
	        },
        	tickmarkPlacement: 'on'
	    }
	};

	var spline_options = {
	    chart: {
	        borderWidth: 0,
	        plotBorderWidth: 0,
	    },  
	    plotOptions: {
	        series: {
	            marker: {
	            	symbol: 'circle',
	            	lineColor: '#ffffff',
					lineWidth: 2,
					width: 7,
					height: 7
	            }
	        }
	    },
	    xAxis: {
	        tickWidth: 1,
        	tickLength: 7,
        	labels: {
	            align: 'center'
	        },
		    align: 'center'
	    },
	    yAxis: {
	    	gridLineWidth: 0,
        	gridLineColor: 'transparent !important'
	    }
	};

	var pie_options = {
	    chart: {
			spacingTop: 37
	    },
	    plotOptions: {
	        series: {
	        	innerSize: '88%',
	        },
	        pie: {
      			borderWidth: 3,
      		}
	    },
	    legend: {	    	
	        align: 'left',
	        verticalAlign: 'top',
	        floating: true,
	        y: -43,
	        x: -4
	    }
	};

	var radar_options = {
	    chart: {
			spacingTop: 25,
	        spacingBottom: 0,
	    }, 
	    plotOptions: {	    	
	        series: {
	        	fillOpacity: 0.85,
	            marker: {
	            	symbol: 'circle',
	            	lineColor: '#ffffff',
					lineWidth: 2,
					width: 7,
					height: 7
	            }
	        }
	    },
	    yAxis: {
	        tickInterval: 5,
	        labels: {
	        	enabled: false,
		        padding: 0,
		        x: 0,
				y: 0
	        }
	    },
	    xAxis: {
	        labels: {
		        style: {
		            fontSize: '13px',
		            letterSpacing: '0',
		            textTransform: 'none'
		        },
		        x: 0,
				y: 0
	        }
	    },
	    legend: {	    	
	        align: 'left',
	        verticalAlign: 'top',
	        floating: true,
	        y: -31,
	        x: -4
	    }
	};

	var polar_options = {
	    chart: {
			spacingTop: 37,
	    },
	    plotOptions: {	
	    	series: {
	            fillOpacity: 0.85
	    	},
            column: {
                pointPadding: 0,
                groupPadding: 0,
      			borderWidth: 0,
            },
	    },
	    xAxis: {
	        labels: {
		        style: {
		            fontSize: '13px',
		            letterSpacing: '0',
		            textTransform: 'none'
		        },
		        x: 0,
				y: 0
	        }
	    },
	    yAxis: {
	        labels: {
	        	enabled: false,
		        padding: 0,
		        x: 0,
				y: 0
	        },
	        plotBands: [{
	            from: 0,
	            to: 10000000,
	            color: $body.hasClass('scheme_dark') ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)',
	        }],
	        alternateGridColor: $body.hasClass('scheme_dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
	    },
	    legend: {	    	
	        align: 'left',
	        verticalAlign: 'top',
	        floating: true,
	        y: -43,
	        x: -4
	    }
	};

	Highcharts.setOptions(chartOptions);



	/* AJAX and hidden elements
	-----------------------------------------------------------------*/
	$window.on( 'elementor/frontend/init', function() {
		if ( typeof window.elementorFrontend !== 'undefined' && typeof window.elementorFrontend.hooks !== 'undefined' ) {
			// If Elementor is in the Editor's Preview mode
			if ( elementorFrontend.isEditMode() ) {
				// Init elements after creation
				elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $cont ) {		
					jQuery( '.m-chart:not(.inited)' ).each( function() {
						var chart = jQuery(this);
						anesta_m_chart_render(chart);
					});
				});
			}
		}
	});



	/* Render
	-----------------------------------------------------------------*/	
	jQuery( '.m-chart:not(.inited)' ).on( 'render_done', function( event ) {
		var chart = jQuery(this);
		anesta_m_chart_render(chart);
	});



	function anesta_m_chart_render(chart) {
		var chart_obj = chart.highcharts();		

		// Column Chart
		if ( chart.find('.highcharts-column-series').length > 0 && chart.find('.highcharts-radial-axis').length == 0 ) {
			chart.addClass('inited');
			chart_obj.update(column_options);
		}

		// Area Chart
		if ( chart.find('.highcharts-area-series').length > 0 && chart.find('.highcharts-radial-axis').length == 0 ) {
			chart.addClass('inited').addClass('m-chart-area');
			chart_obj.update(area_options);
		}

		// Spline Chart
		if ( chart.find('.highcharts-spline-series').length > 0 ) {
			chart.addClass('inited').addClass('m-chart-spline');
			chart_obj.update(spline_options);
		}

		// Pie Chart
		if ( chart.find('.highcharts-pie-series').length > 0 ) {
			chart.addClass('inited').addClass('m-chart-pie');
			chart_obj.update(pie_options);
		}

		// Radar Chart
		if ( chart.find('.highcharts-area-series').length > 0 && chart.find('.highcharts-radial-axis').length > 0 ) {
			chart.addClass('inited').addClass('m-chart-radar');
			chart_obj.update(radar_options);
		}

		// Polar Chart
		if ( chart.find('.highcharts-column-series').length > 0 && chart.find('.highcharts-radial-axis').length > 0 ) {
			chart.addClass('inited').addClass('m-chart-polar');
			chart_obj.update(polar_options);
		}

		// legend
		chart.find('.highcharts-legend .highcharts-point').each(function() {
			var item = jQuery(this);
			var fill = item.attr('fill');
			var rgb = anesta_m_chart_hexToRgb(fill);

			// Column Chart
			if ( ( chart.find('.highcharts-column-series').length > 0 && chart.find('.highcharts-radial-axis').length == 0 ) ||
			// Area Chart
			( chart.find('.highcharts-area-series').length > 0 && chart.find('.highcharts-radial-axis').length == 0 ) ||
			// Spline Chart
			( chart.find('.highcharts-spline-series').length > 0 ) ) {
				var opacity = $body.hasClass('scheme_dark') ? '0.15' : '0.3';
				var rgba = rgb.replace('rgb', 'rgba').replace(')', ', ' + opacity + ')');
				item.attr('stroke', fill).attr('y', 10).css('filter', 'drop-shadow( 0 0 6px ' + rgba + ')');
			}


			// Pie Chart
			if ( ( chart.find('.highcharts-pie-series').length > 0 ) ||
			// Radar Chart
			( chart.find('.highcharts-area-series').length > 0 && chart.find('.highcharts-radial-axis').length > 0 ) ||
			// Polar Chart
			( chart.find('.highcharts-column-series').length > 0 && chart.find('.highcharts-radial-axis').length > 0 ) ) {
				var opacity = $body.hasClass('scheme_dark') ? '0.1' : '0.2';
				var rgba = rgb.replace('rgb', 'rgba').replace(')', ', ' + opacity + ')');
				item.attr('stroke', fill).attr('y', 10).css('filter', 'drop-shadow( 2px 4px 2px ' + rgba + ')');
			}
		});

		// Column Chart
		if ( chart.find('.highcharts-column-series').length > 0 && chart.find('.highcharts-radial-axis').length == 0 ) {
			chart.find('.highcharts-series-group .highcharts-point').each(function() {
				var item = jQuery(this);
				var fill = item.attr('fill');
				var rgb = anesta_m_chart_hexToRgb(fill);
				var opacity = $body.hasClass('scheme_dark') ? '0.1' : '0.2';
				var rgba = rgb.replace('rgb', 'rgba').replace(')', ', ' + opacity + ')');

				item.css('filter', 'drop-shadow( 0 5px 8px ' + rgba + ')');
			});
		}

		// Spline Chart
		if ( chart.find('.highcharts-spline-series').length > 0 ) {
			chart.find('.highcharts-series-group .highcharts-graph').each(function() {
				var item = jQuery(this);
				var fill = item.attr('stroke');
				var rgb = anesta_m_chart_hexToRgb(fill);
				var opacity = $body.hasClass('scheme_dark') ? '0.1' : '0.19';
				var rgba = rgb.replace('rgb', 'rgba').replace(')', ', ' + opacity + ')');

				item.css('filter', 'drop-shadow( -1px 12px 3px ' + rgba + ')');
			});
		}
	}

	function anesta_m_chart_hexToRgb(hex) {
	    var c;
	    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
	        c= hex.substring(1).split('');
	        if(c.length== 3){
	            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
	        }
	        c= '0x'+c.join('');
	        return 'rgb('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+')';
	    }
	    return hex;
	}

	function anesta_m_chart_legend_spacer() {
		var width = $window.width();
		if ( width < 1679 ) {
			return 20;
		} else {
			return 30;
		}
    }
})();
