
class BubbleChart
  constructor: (data) ->
    @data = data
    @width = 800
    @height = 400
    @paddingleft = 320

    @tooltip = CustomTooltip("gates_tooltip", 240)

    # locations the nodes will move towards
    # depending on which view is currently being
    # used
    @center = {x: @width / 2, y: @height / 2}
    @year_centers = {
      "Africa, regional": {x: 155, y: (@height / 2) - 20},
      "Asia, regional": {x: 254, y: (@height / 2) - 20},
      "Bilateral, unspecified": {x: 354, y: (@height / 2) - 20},
      "Europe, regional": {x: 457, y: (@height / 2) - 20},
      "Middle East, regional": {x: 558, y: (@height / 2) - 20},
      "South America, regional": {x: 658, y: (@height / 2) - 20}
    }

    # used when setting up force and
    # moving around nodes
    @layout_gravity = -0.01
    @damper = 0.1

    # these will be set in create_nodes and create_vis
    @vis = null
    @nodes = []
    @force = null
    @circles = null

    # nice looking colors - no reason to buck the trend
    @fill_color = d3.scale.ordinal()
      .domain(["Africa, regional", "Asia, regional", "Bilateral, unspecified", "Europe, regional", "Middle East, regional", "South America, regional"])
      .range(["#006FA1", "#149CCB", "#1A5565", "#0AC7F3", "#76DBF6", "#3B82A1"])

    # use the max total_amount in the data as the max in the scale's domain
    max_amount = d3.max(@data, (d) -> parseInt(d.total_budget))
    @radius_scale = d3.scale.pow().exponent(0.5).domain([0, max_amount]).range([2, 20])
    
    this.create_nodes()
    this.create_vis()

  # create node objects from original data
  # that will serve as the data behind each
  # bubble in the vis, then add each node
  # to @nodes to be used later
  create_nodes: () =>
    @data.forEach (d) =>
      node = {
        id: d.id + d.code
        project_id: d.id
        radius: @radius_scale(parseInt(d.total_budget))
        value: d.total_budget
        name: d.title
        org: d.name
        group: d.name
        year: d.name
        x: Math.random() * 900
        y: Math.random() * 800
      }
      @nodes.push node

    @nodes.sort (a,b) -> b.value - a.value


  # create svg at #vis and then 
  # create circle representation for each node
  create_vis: () =>
    @vis = d3.select("#vis").append("svg")
      .attr("width", @width)
      .attr("height", @height)
      .attr("padding-left", @paddingleft)
      .attr("id", "svg_vis")

    @circles = @vis.selectAll("circle")
      .data(@nodes, (d) -> d.id)

    # used because we need 'this' in the 
    # mouse callbacks
    that = this

    # radius will be set to 0 initially.
    # see transition below
    @circles.enter().append("circle")
      .attr("r", 0)
      .attr("fill", (d) => @fill_color(d.group))
      .attr("stroke-width", 0)
      .attr("stroke", (d) => d3.rgb(@fill_color(d.group)).darker())
      .attr("id", (d) -> "bubble_#{d.id}")
      .on("mouseover", (d,i) -> that.show_details(d,i,this))
      .on("mouseout", (d,i) -> that.hide_details(d,i,this))
      .on("click", (d,i) -> that.go_to_project(d,i,this))

    # Fancy transition to make bubbles appear, ending with the
    # correct radius
    @circles.transition().duration(2000).attr("r", (d) -> d.radius)


  # Charge function that is called for each node.
  # Charge is proportional to the diameter of the
  # circle (which is stored in the radius attribute
  # of the circle's associated data.
  # This is done to allow for accurate collision 
  # detection with nodes of different sizes.
  # Charge is negative because we want nodes to 
  # repel.
  # Dividing by 8 scales down the charge to be
  # appropriate for the visualization dimensions.
  charge: (d) ->
    -Math.pow(d.radius, 2) / 8

  # Starts up the force layout with
  # the default values
  start: () =>
    @force = d3.layout.force()
      .nodes(@nodes)
      .size([@width, @height])

  # Sets up force layout to display
  # all nodes in one circle.
  display_group_all: () =>
    @force.gravity(@layout_gravity)
      .charge(this.charge)
      .friction(0.9)
      .on "tick", (e) =>
        @circles.each(this.move_towards_center(e.alpha))
          .attr("cx", (d) -> d.x)
          .attr("cy", (d) -> d.y)
    @force.start()

    this.hide_years()

  # Moves all circles towards the @center
  # of the visualization
  move_towards_center: (alpha) =>
    (d) =>
      d.x = d.x + (@center.x - d.x) * (@damper + 0.02) * alpha
      d.y = d.y + (@center.y - d.y) * (@damper + 0.02) * alpha

  # sets the display of bubbles to be separated
  # into each year. Does this by calling move_towards_year
  display_by_year: () =>
    @force.gravity(@layout_gravity)
      .charge(this.charge)
      .friction(0.9)
      .on "tick", (e) =>
        @circles.each(this.move_towards_year(e.alpha))
          .attr("cx", (d) -> d.x)
          .attr("cy", (d) -> d.y)
    @force.start()

    this.display_years()

  # move all circles to their associated @year_centers 
  move_towards_year: (alpha) =>
    (d) =>
      target = @year_centers[d.year]
      d.x = d.x + (target.x - d.x) * (@damper + -0.03) * alpha * 1.1
      d.y = d.y + (target.y - d.y) * (@damper + -0.03) * alpha * 1.5

  # Method to display year titles
  display_years: () =>
    years_x = {"Africa": 65, "Asia": 225, "Bilateral": 375, "Europe": 488, "Middle East": 602, "South America": 740}
    years_data = d3.keys(years_x)

    rect = @vis.append("svg:rect")
      .attr("x", 0)
      .attr("y", @height - 120)
      .attr("height", 1)
      .attr("width", @width)
      .attr("fill", "#0070A2")

    header = @vis.append("text")
      .attr("class", "scatter-plot-header")
      .attr("x", 0)
      .attr("y", 60)
      .attr("text-anchor", "left")
      .text("Budget per project for region")

    years = @vis.selectAll(".years")
      .data(years_data)

    years.enter().append("text")
      .attr("class", "years")
      .attr("x", (d) => years_x[d] )
      .attr("y", @height - 94)
      .attr("text-anchor", "middle")
      .text((d) -> d)

  # Method to hide year titles
  hide_years: () =>
    years = @vis.selectAll(".years").remove()

  show_details: (data, i, element) =>
    d3.select(element).attr("fill", "red")
    content = "<div class='scatter-popup-wrapper'>"
    content +="<div class='scatter-popup-region'>#{data.group}</div>"
    content +="<div class='scatter-popup-title'> #{data.name}</div>"
    content +="<div class='scatter-popup-budget-header'>Budget<br>US$</div><div class='scatter-popup-budget-value'> $#{addCommas(data.value)}</div>"
    content +="<div class='scatter-popup-visit'>Click to visit the project page</div>"
    content +="</div>"
    @tooltip.showTooltip(content,d3.event)


  hide_details: (data, i, element) =>
    d3.select(element).attr("fill", (d) => d3.rgb(@fill_color(d.group)))
    @tooltip.hideTooltip()

  go_to_project:(data, i, element) =>

    window.location.href = home_url + "/project/"+data.project_id+"/";


root = exports ? this

$ ->
  chart = null

  render_vis = (csv) ->
    chart = new BubbleChart csv
    chart.start()
    root.display_all()
  root.display_all = () =>
    chart.display_group_all()
  root.display_year = () =>
    chart.display_by_year()
  root.toggle_view = (view_type) =>
    if view_type == 'year'
      root.display_year()
    else
      root.display_all()
  d3.json search_url + "activity-list-vis/?format=json&reporting_organisation__in=41120", render_vis
