<?php
// File: public/charts/standalone-pie-chart.php

session_start();

// Check if the user is logged in. If not, redirect or show an error.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // For a standalone chart, you might just show a message or redirect.
    // For this example, we'll just allow it to load but the API call will fail if not logged in.
    // In a real application, you'd handle this more robustly, e.g., redirect to login.php
    // header("location: login.php");
    // exit;
}

// Include the Database connection class
// Adjust the path: from 'public/charts/' to 'includes/' is two levels up.
require_once __DIR__ . '/../../includes/Database.php';

// You don't need to fetch data directly here for the chart,
// as the JavaScript will fetch it via the API endpoint.
// This PHP block is mainly for session management and including Database.php.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standalone Pie Chart (Database Connected)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- D3.js CDN -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Full viewport height */
            margin: 0;
            padding: 20px;
            box-sizing: border-box; /* Include padding in element's total width and height */
        }
        .chart-section {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 1.25rem; /* rounded-2xl */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 900px; /* Limit max width for better presentation */
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .chart-content {
            display: flex;
            flex-direction: column; /* Default to column for small screens */
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        @media (min-width: 768px) { /* Medium screens and up */
            .chart-content {
                flex-direction: row; /* Row for larger screens */
                justify-content: space-around;
            }
        }
        .chart-container-d3 {
            position: relative;
            width: 100%;
            height: 400px; /* Fixed height for the chart area */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem; /* Space below chart on small screens */
        }
        @media (min-width: 768px) {
            .chart-container-d3 {
                width: 60%; /* Take up more space for the chart on larger screens */
                margin-right: 1.5rem; /* Space between chart and legend */
                margin-bottom: 0; /* No bottom margin on larger screens */
            }
        }
        .loading-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1.125rem;
        }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #a259ff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 0.5rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .tooltip {
            position: absolute;
            text-align: center;
            padding: 0.5rem 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 0.5rem;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            font-size: 0.875rem;
            z-index: 100;
        }
        .arc path {
            stroke: #fff;
            stroke-width: 1.5px;
            transition: opacity 0.2s, transform 0.2s;
        }
        .arc:hover path {
            opacity: 0.8;
            transform: scale(1.03);
        }
        .arc text {
            font-size: 0.85rem;
            fill: #333;
            font-weight: 500;
            pointer-events: none;
        }
        .pie-chart-legend {
            display: grid;
            grid-template-columns: 1fr; /* Default to single column for small screens */
            gap: 0.75rem; /* Gap between legend items */
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: #f9fafb;
            box-shadow: inset 0 1px 3px 0 rgba(0,0,0,0.1);
        }
        @media (min-width: 640px) { /* Small screens and up */
            .pie-chart-legend {
                grid-template-columns: 1fr 1fr; /* Two columns for legend */
            }
        }
        @media (min-width: 768px) { /* Medium screens and up */
            .pie-chart-legend {
                width: 40%; /* Legend takes remaining space */
                padding: 0; /* Reset padding for flex distribution */
                box-shadow: none; /* Remove inner shadow on larger screens */
                background-color: transparent;
            }
        }
        .pie-chart-legend-item {
            display: flex;
            align-items: center;
            color: #4b5563; /* Gray-700 */
            font-size: 0.875rem; /* text-sm */
            font-weight: 500;
        }
        .pie-chart-legend-color {
            width: 1rem; /* w-4 */
            height: 1rem; /* h-4 */
            border-radius: 9999px; /* rounded-full */
            margin-right: 0.5rem; /* mr-2 */
            flex-shrink: 0; /* Prevent color box from shrinking */
        }
    </style>
</head>
<body>

    <section class="chart-section">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses Chart (Database Connected)</h2>
        <div class="chart-content">
            <div id="pie-chart-container" class="chart-container-d3">
                <div class="loading-indicator">
                    <div class="loading-spinner"></div>
                    Loading chart data...
                </div>
            </div>
            <div id="pie-chart-legend" class="pie-chart-legend">
                </div>
        </div>
        <div class="tooltip" id="chart-tooltip"></div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartContainer = d3.select("#pie-chart-container");
            const tooltip = d3.select("#chart-tooltip");
            const loadingIndicator = chartContainer.select(".loading-indicator");
            const legendContainer = d3.select("#pie-chart-legend");

            const margin = { top: 20, right: 20, bottom: 20, left: 20 };

            function drawChart(data) {
                // Remove loading indicator
                loadingIndicator.remove();
                legendContainer.html(''); // Clear previous legend

                if (!data || data.length === 0) {
                    chartContainer.append("p")
                        .attr("class", "text-gray-500 text-lg")
                        .text("No spending data available to display.");
                    return;
                }

                const containerWidth = chartContainer.node().getBoundingClientRect().width;
                const containerHeight = chartContainer.node().getBoundingClientRect().height;

                const width = containerWidth - margin.left - margin.right;
                const height = containerHeight - margin.top - margin.bottom;
                const radius = Math.min(width, height) / 2;

                const svg = chartContainer.append("svg")
                    .attr("width", containerWidth)
                    .attr("height", containerHeight)
                    .attr("viewBox", `0 0 ${containerWidth} ${containerHeight}`)
                    .append("g")
                    .attr("transform", `translate(${containerWidth / 2}, ${containerHeight / 2})`);

                const pie = d3.pie()
                    .value(d => d.value)
                    .sort(null);

                const arc = d3.arc()
                    .innerRadius(radius * 0.6)
                    .outerRadius(radius * 0.9);

                const outerArc = d3.arc()
                    .innerRadius(radius * 0.95)
                    .outerRadius(radius * 0.95);

                const color = d3.scaleOrdinal(d3.schemeCategory10); // D3's built-in color scheme

                const arcs = svg.selectAll(".arc")
                    .data(pie(data))
                    .enter().append("g")
                    .attr("class", "arc");

                arcs.append("path")
                    .attr("d", arc)
                    .attr("fill", d => color(d.data.category))
                    .on("mouseover", function(event, d) {
                        d3.select(this).transition().duration(100).attr("transform", "scale(1.03)");
                        tooltip.style("opacity", 1);
                        const percentage = ((d.endAngle - d.startAngle) / (2 * Math.PI) * 100).toFixed(1);
                        tooltip.html(`<strong>${d.data.category}</strong><br>RM${d.data.value.toFixed(2)} (${percentage}%)`)
                            .style("left", (event.pageX + 10) + "px")
                            .style("top", (event.pageY - 28) + "px");
                    })
                    .on("mouseout", function() {
                        d3.select(this).transition().duration(100).attr("transform", "scale(1)");
                        tooltip.style("opacity", 0);
                    });

                // Add labels
                arcs.append("text")
                    .attr("transform", d => `translate(${outerArc.centroid(d)})`)
                    .attr("dy", "0.35em")
                    .text(d => d.data.category)
                    .style("text-anchor", d => {
                        const midAngle = (d.startAngle + d.endAngle) / 2;
                        return (midAngle < Math.PI ? "start" : "end");
                    })
                    .style("fill", "#333");

                // Add a central text label for total
                const totalValue = d3.sum(data, d => d.value);
                svg.append("text")
                    .attr("text-anchor", "middle")
                    .attr("dy", "0.35em")
                    .attr("class", "total-label")
                    .style("font-size", "1.5rem")
                    .style("font-weight", "bold")
                    .style("fill", "#333")
                    .text(`Total: RM${totalValue.toFixed(2)}`);

                // Generate Legend
                data.forEach(d => {
                    const legendItem = legendContainer.append("div")
                        .attr("class", "pie-chart-legend-item");
                    legendItem.append("span")
                        .attr("class", "pie-chart-legend-color")
                        .style("background-color", color(d.category));
                    legendItem.append("span")
                        .text(d.category);
                });
            }

            // Function to fetch and draw chart data
            function fetchAndDrawChart() {
                chartContainer.html(`
                    <div class="loading-indicator">
                        <div class="loading-spinner"></div>
                        Loading chart data...
                    </div>
                `); // Show loading indicator
                legendContainer.html(''); // Clear old legend

                // Fetch data from your PHP API endpoint
                // Path adjusted: from 'public/charts/' to 'public/api/' is one level up.
                fetch('../api/get_spending_data.php') // This is the API endpoint that returns JSON
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            console.error("API Error:", data.error);
                            chartContainer.html(`<p class="text-red-500">Error: ${data.error}</p>`);
                        } else {
                            drawChart(data);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        chartContainer.html('<p class="text-red-500">Failed to load chart data.</p>');
                    });
            }

            // Initial chart load when the page loads
            fetchAndDrawChart();
        });
    </script>
</body>
</html>
