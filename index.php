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
    line-height: 0.5;
    background-color: white;
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
    .chart-container {
        height: 1000px;
        width: 200%;
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
        <h1 style="title">Who's on the books? NBA contracts 2019-2025</h1>
        <span style="padding-left: 1px;" class="control" onclick="prev_year();">previous</span>
        <h1 style="text-align: center; display: inline-block;"><b id="year">2019-20</b></h1>
        <span class="control" onclick="next_year();">next</span>
        <div class="chart-container">
            <div class="tooltip">hello there</div>
            <div id="title_layer"></div>
            <div id="salary_treemap"></div>
        </div>
        <h3>about</h3>
        <p>Each rectangle represents a player and is scaled by that player's contract size for the
        selected year. Players are grouped by their teams, indicated by their color and abbreviation.
        Press <b>previous</b> or <b>next</b> to step through available seasons. Hover (or click on mobile) over a player to 
        see their name and contract in millions or thousands of dollars. Note: the scale can
        change dramatically between years since the chart is configured to make use of the entire space no 
        matter how many contracts exist for that year.</p>
        <!---
        <h2>Tutorial</h2>
        <p>This type of data visualization is called a treemap. The only place I'd seen treemaps before 
        embarking on this project was in software for breaking down disk usage.     They are similar to pie charts,
        but more effective for comparison between "parts" rather than "parts" to the whole. 
        It's certainly easier for me at least to compare rectangles than slices.</p>--->
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
