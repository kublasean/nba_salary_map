<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="resource/d3.min.js"></script>
<script src="resource/colors.js"></script>
<script src="data/2019_2020_contracts.js"></script>
<script src="treemap.js"></script>
<link rel="stylesheet" href="/resource/w3.css">
<link rel="stylesheet" href="/resource/seansite.css">

<style>
nav {
    z-index: 3;
}
html, body {
    overflow: auto;
    position: relative;
    margin: 0;
    padding: 0;
}
#salary_treemap, #title_layer {
    width: 100%;
    height: 100%;
    position: absolute;
}
#salary_treemap {
    z-index: 1;
}
#title_layer {
    z-index: 0;
}
rect:hover {
    fill-opacity: 1.0;
}
.tooltip {
    display: none;
    background-color: white;
    border: 1px solid black;
    padding: 10px;
    position: absolute;
    z-index: 2;
    margin: 20px;
}
.chart-container {
    height: 450px;
    width: 100%;
    position: relative;
    overflow: hidden;
}
.body-container {
    line-height: 0.5;
    position: relative;
}
.control:hover {
    cursor: pointer;
}
#year {
    color: grey;
}
h1 {
    padding-bottom: 0px;
    margin-bottom: 6px;
}

@media only screen and (max-width: 600px) {
    .body-container {
        width: 200%;
    }
    .chart-container {
        height: 1000px;
    }
    .control {
        margin-right: 10px;
        margin-left: 10px;
        height: 25px;
        color: blue;
        font-size: 25pt;
    }
}
</style>
</head>
<body class="base-color">
    <?php $_GET['section'] = "nba"; include('../../../cgi-bin/navbar.php'); ?>
    <div class="body-container foreground-color">
        <span style="padding-left: 1px;" class="control" onclick="prev_year();">prev</span>
        <h1 style="text-align: center; display: inline-block;"><b id="year">2019-20</b> NBA Contracts</h1>
        <span class="control" onclick="next_year();">next</span>
        <div class="chart-container">
            <div class="tooltip">hello there</div>
            <div id="title_layer"></div>
            <div id="salary_treemap"></div>
        </div>
    </div>
<script>
var ind = 0;
var years = ["2019_20","2020_21","2021_22","2022_23","2023_24","2024_25"];
var chart = create_treemap("#salary_treemap", "#title_layer", GLOBAL_DATA, years[0]);
function next_year() {
    ind = (ind + 1) % years.length;
    chart.data_update(years[ind]);
    document.getElementById("year").innerText = years[ind].replace("_", "-");
}
function prev_year() {
    ind -= 1;
    if (ind < 0) {
        ind = years.length-1;
    }
    chart.data_update(years[ind]);
    document.getElementById("year").innerText = years[ind].replace("_", "-");
}
</script>
</body>
</html>