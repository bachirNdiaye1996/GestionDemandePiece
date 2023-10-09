function getChartColorsArray(id) {
  var element = document.getElementById(id);
  if (element !== null) {
    var colorsAttr = element.getAttribute("data-colors");
    var colors = JSON.parse(colorsAttr);
    return colors.map(function(color) {
      var cleanedColor = color.replace(" ", "");
      if (cleanedColor.indexOf("--") === -1) {
        return cleanedColor;
      } else {
        var computedColor = getComputedStyle(document.documentElement).getPropertyValue(cleanedColor);
        return computedColor || undefined;
      }
    });
  }
}

var pieChartElement = document.getElementById("pieChart");
var pieChartColors = getChartColorsArray("pieChart");
var pieChart = new Chart(pieChartElement, {
  type: "pie",
  data: {
    labels: ["Hommes", "Femmes"],
    datasets: [{
      data: [300, 180],
      backgroundColor: pieChartColors,
      hoverBackgroundColor: pieChartColors,
      hoverBorderColor: "#fff"
    }]
  }
});

var radarChartElement = document.getElementById("radar");
var radarChartColors = getChartColorsArray("radar");
var radarChart = new Chart(radarChartElement, {
  type: "radar",
  data: {
    // Provide the data for your radar chart here
  },
  options: {
    // Configure the options for your radar chart here
  }
});

var doughnutChartElement = document.getElementById("doughnut");
var doughnutChartColors = getChartColorsArray("doughnut");
var doughnutChart = new Chart(doughnutChartElement, {
  type: "doughnut",
  data: {
    labels: ["Agent(s)", "Service(s)"],
    datasets: [{
      data: [300, 210],
      backgroundColor: doughnutChartColors,
      hoverBackgroundColor: doughnutChartColors,
      hoverBorderColor: "#fff"
    }]
  }
});
