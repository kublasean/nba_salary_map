function create_treemap(container_id, data) {

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

    var get_treemap = function(data) {
        const treemap = d3.treemap()
            .size([width, height])
            .padding(2);
        return treemap(
            d3.hierarchy(data)
                .sum(d => d["2019_20"])
                .sort((a, b) => b["2019_20"] - a["2019_20"])
        );
    };

    var color = d3.scaleOrdinal(d3.schemeCategory10);

    var root = get_treemap(data);
    var leaf = svg.selectAll("g")
        .data(root.leaves())
        .join("g")
            .attr("transform", d => `translate(${d.x0},${d.y0})`);

    leaf.append("rect")
        .attr("fill", d => { while (d.depth > 1) d = d.parent; return color(d.data.name); })
        .attr("fill-opacity", 0.6)
        .attr("width", d => d.x1 - d.x0)
        .attr("height", d => d.y1 - d.y0);

}
