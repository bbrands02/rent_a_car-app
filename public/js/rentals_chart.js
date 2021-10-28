window.onload = function () {

    var chart = new CanvasJS.Chart("rentalsChart", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Rentals - november 2020"
        },
        axisX: {
            valueFormatString: "DD MMM",
            crosshair: {
                enabled: true,
                snapToDataPoint: true
            }
        },
        axisY: {
            includeZero: true,
            crosshair: {
                enabled: true
            }
        },
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            verticalAlign: "bottom",
            horizontalAlign: "left",
            dockInsidePlotArea: true,
            itemclick: toogleDataSeries
        },
        data: [{
            type: "line",
            showInLegend: true,
            name: "Rentals",
            markerType: "square",
            xValueFormatString: "DD MMM, YYYY",
            color: "blue",
            dataPoints: [
                {x: new Date(2020, 10, 3), y: 8},
                {x: new Date(2020, 10, 4), y: 2},
                {x: new Date(2020, 10, 5), y: 5},
                {x: new Date(2020, 10, 6), y: 0},
                {x: new Date(2020, 10, 7), y: 7},
                {x: new Date(2020, 10, 8), y: 4},
                {x: new Date(2020, 10, 9), y: 0},
                {x: new Date(2020, 10, 10), y: 7},
                {x: new Date(2020, 10, 11), y: 2},
                {x: new Date(2020, 10, 12), y: 3},
                {x: new Date(2020, 10, 13), y: 6},
                {x: new Date(2020, 10, 14), y: 3},
                {x: new Date(2020, 10, 15), y: 7},
                {x: new Date(2020, 10, 16), y: 3}
            ]
        }]
    });
    chart.render();

    function toogleDataSeries(e) {
        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        chart.render();
    }

}