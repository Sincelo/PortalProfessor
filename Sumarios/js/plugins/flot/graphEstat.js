(function($)
{
    "use strict";

    var options = {
        bars: {
            vertical: {
            }
        }
    };
	
	function processOptions(plot, options)
    {
        var barnumbers = options.series.bars.vertical;

        barnumbers.show = barnumbers.show || false;
        barnumbers.threshold = barnumbers.threshold || false;
        barnumbers.xOffset = barnumbers.xOffset || 0;
        barnumbers.yOffset = barnumbers.yOffset || 0;
    }
	
	function draw(plot, ctx)
    {
        // loop through each series
        $.each(plot.getData(), function(index, series)
        {		var xAlign, yAlign;
				var barnumbers = series.bars.vertical;
				// make sure this series should show the bar numbers
            if (!barnumbers.show) {
                return false;
            }
				//caso tenha sido especificado pelo utilizador	
				if ($.isFunction(barnumbers.xAlign)) {
                xAlign = barnumbers.xAlign;
				}
				else{
					xAlign = function(x) { return x + (series.bars.barWidth / 2); };
				}
				
				if ($.isFunction(barnumbers.yAlign)) {
                yAlign = barnumbers.yAlign;
            }
				else{
						yAlign = function(y) { return y / 2; };	
				}	

				var points = series.datapoints.points;
				var ctx = plot.getCanvas().getContext('2d');
				var offset = plot.getPlotOffset();				
				var xaxis = plot.getXAxes()[0];
				var yaxis = plot.getYAxes()[0];
				
				for (var i = 0; i < points.length; i += series.datapoints.pointsize)
					{				
						var text;
						var xOffset = barnumbers.xOffset;
						var yOffset = barnumbers.yOffset;
						var barNumber = i+1;					
				
						text = points[barNumber];						
					
						var xPos = xaxis.p2c(xAlign(points[i]))+offset.left;
						var yPos = yaxis.p2c(yAlign(points[i+1]))+offset.top;				
					
					ctx.save();
					ctx.translate(xPos, yPos);
					ctx.rotate(-Math.PI/2);
					
					ctx.fillText(text.toString(10),5,3);					
					ctx.restore();						
				}			
		});
		}
				
function init(plot)
    {
        plot.hooks.processOptions.push(processOptions);
        plot.hooks.draw.push(draw);
    }
	
    // push as an available plugin to Flot
    $.plot.plugins.push({
        init: init,
        options: options,
        name: 'graphEstat',
        version: '1.0'
    });

})(jQuery);				
				