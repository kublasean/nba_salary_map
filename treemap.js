function format_salary(val) {
    var label = " mil";
    var tmp = val / Math.pow(10, 6);
    if (tmp < 1.0) {
        label = "";
        val = val.toLocaleString();
    }
    else {
        val = tmp.toFixed(2);
    }
    return "$" + val + label;
}

function create_treemap(container_id, title_id, data) {

    // set the dimensions and margins of the graph
    var margin = {top: 0, right: 0, bottom: 0, left: 0};
    var width = d3.select(container_id).node().clientWidth - margin.left - margin.right;
    var height = d3.select(container_id).node().clientHeight - margin.top - margin.bottom;

    // append the svg object to the container
    var svg = d3.select(container_id)
        .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
        .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    var title_layer = d3.select(title_id)
        .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
        .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    var get_treemap = function(data) {
        const treemap = d3.treemap()
            .size([width, height])
            .padding(1);
        return treemap(
            d3.hierarchy(data)
                .sum(d => d["2019_20"])
                .sort((a, b) => b["2019_20"] - a["2019_20"])
        );
    };

    var get_color = function(name) {
        return colors[name].colors[colors[name].mainColor].hex;
    }

    var get_text_color = function(name) {
        return colors[name].colors[colors[name].secondaryColor].hex;
    }

    var Tooltip = d3.select(".tooltip");

    // from https://www.d3-graph-gallery.com/graph/interactivity_tooltip.html
    // Three function that change the tooltip when user hover / move / leave a cell
    var mouseover = function(d) {
        Tooltip.style("display", "initial");
    };
    var mousemove = function(d) {
        console.log(d.data);
        Tooltip.html("<p>Rank: " + d.data.rk + "</p><p>"+d.data.player+"</p><p>"+format_salary(d.data["2019_20"])+"</p>");
            
        if (d.x0 < width/2) {
            Tooltip.style("left", (d.x0 + d3.mouse(this)[0]+15) + "px")
                .style("right", "initial");
        }
        else {
            Tooltip.style("left", "initial")
                .style("right", width - (d.x0 + d3.mouse(this)[0]-15) + "px");
        }
        if (d.y0 < height/2) {
            Tooltip.style("top", (d.y0 + d3.mouse(this)[1]) + "px")
                .style("bottom", "initial");
        }
        else {
            Tooltip.style("bottom", height - (d.y0 + d3.mouse(this)[1]) + "px")
                .style("top", "initial");
        }

    };
    var mouseleave = function(d) {
        Tooltip.style("display", "none");
    };

    var root = get_treemap(data);
    var leaf = svg.selectAll("g")
        .data(root.leaves())
        .join("g")
            .attr("transform", d => `translate(${d.x0},${d.y0})`);

    
    /*leaf.append("text")
        .attr("x", 5)
        .attr("y", 20)
        .text(d => d.data.player)
        .attr("fill", "white");//d => get_text_color(d.data.tm));*/

    console.log(root.descendants().filter(function(d){return d.depth==1}));

    title_layer.selectAll("titles")
        .data(root.descendants().filter(function(d){return d.depth==1}))
        .enter()
        .append("text")
            .attr("class", "team-title")
            .attr("x", d => d.x0)// + (d.x1 - d.x0)/2.0)
            .attr("y", d => d.y1-3)// - (d.y1 - d.y0)/2.0)
            .text(function(d){ return d.data.name })
            .attr("font-size", d => d3.min([d.y1-d.y0, (d.x1-d.x0)/3]) + "px")
            .attr("fill", d => get_color(d.data.name))
            .attr("fill-opacity", 0.8);

    leaf.append("rect")
        .attr("fill", d => get_color(d.data.tm))
        .attr("fill-opacity", 0.6)
        .attr("width", d => d.x1 - d.x0)
        .attr("height", d => d.y1 - d.y0)
        .on("mouseover", mouseover)
        .on("mousemove", mousemove)
        .on("mouseleave", mouseleave);
}
