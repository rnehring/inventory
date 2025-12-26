import ApexCharts from 'apexcharts';
import {
    formatterUSD
} from './app';

// axios.get('/all-time-counts')
//     .then(function (response) {
//         //  COUNTS BY USER ALL TIME
//         console.log(response);
//
//         let chartData = [];
//
//         response.data.forEach((record) => {
//             chartData.push({
//                 x: `${record.first_name} ${record.last_name}`,
//                 y: parseInt(record.counts) || 0  // Ensure it's a number
//             });
//         });
//
//         const options = {
//             colors: ['#0015FF','#FF00A1','#90FE00','#8400FF','#00FFF7'],
//             series: [
//                 {
//                     name: "Total Counts",  // Better name
//                     data: chartData,  // ✅ Use the array directly, not wrapped in []
//                 },
//             ],
//             chart: {
//                 type: "bar",
//                 height: "400px",
//                 fontFamily: "Inter, sans-serif",
//                 toolbar: {
//                     show: false,
//                 },
//                 background: '#FFFFFF00',
//             },
//             plotOptions: {
//                 bar: {
//                     distributed: true,
//                     horizontal: false,
//                     columnWidth: "70%",
//                     borderRadiusApplication: "end",
//                     borderRadius: 8,
//                 },
//             },
//             tooltip: {
//                 shared: true,
//                 intersect: false,
//                 style: {
//                     fontFamily: "Inter, sans-serif",
//                 },
//             },
//             states: {
//                 hover: {
//                     filter: {
//                         type: "darken",
//                         value: 1,
//                     },
//                 },
//             },
//             stroke: {
//                 show: true,
//                 width: 0,
//                 colors: ["transparent"],
//             },
//             grid: {
//                 show: false,
//                 strokeDashArray: 4,
//                 padding: {
//                     left: 2,
//                     right: 2,
//                     top: -14
//                 },
//             },
//             dataLabels: {
//                 enabled: false,
//             },
//             legend: {
//                 show: false,
//             },
//             xaxis: {
//                 floating: false,
//                 labels: {
//                     show: true,
//                     style: {
//                         fontFamily: "Inter, sans-serif",
//                         cssClass: 'text-xs font-normal fill-body text-white'
//                     }
//                 },
//                 axisBorder: {
//                     show: false,
//                 },
//                 axisTicks: {
//                     show: false,
//                 },
//             },
//             yaxis: {
//                 show: true,
//             },
//             fill: {
//                 opacity: 1,
//             },
//             theme:{
//                 mode: 'dark',
//             }
//         }
//
//         if(document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
//             const chart = new ApexCharts(document.getElementById("column-chart"), options);
//             chart.render();
//         }
//
//     });




// axios.get('/brand-progress')
//     .then(function (response) {
//
//         let percentageData = [];
//         let companies = [];
//
//         response.data.forEach((record) => {
//             percentageData.push(parseFloat(record.percentage) || 0);  // Ensure numbers
//             companies.push(record.company);
//         });
//
//         const getChartOptions = () => {
//             return {
//                 series: percentageData,
//                 colors: ['#0015FF','#FF00A1','#90FE00','#8400FF','#00FFF7','#FF7300'],
//                 chart: {
//                     height: "350px",
//                     width: "100%",
//                     type: "radialBar",
//                     sparkline: {
//                         enabled: true,
//                     },
//                     background: "#FFFFFF00",
//                 },
//                 plotOptions: {
//                     radialBar: {
//                         track: {
//                             background: "#203765",
//                         },
//                         dataLabels: {
//                             show: false,
//                         },
//                         hollow: {
//                             margin: 0,
//                             size: "32%",
//                         }
//                     },
//                 },
//                 grid: {
//                     show: false,
//                     strokeDashArray: 4,
//                     padding: {
//                         left: 2,
//                         right: 2,
//                         top: -23,
//                         bottom: -20,
//                     },
//                 },
//                 labels: companies,
//                 legend: {
//                     show: true,
//                     position: "bottom",
//                     fontFamily: "Inter, sans-serif",
//                 },
//                 tooltip: {
//                     enabled: true,
//                     x: {
//                         show: false,
//                     },
//                 },
//                 yaxis: {
//                     show: false,
//                     labels: {
//                         formatter: function (value) {
//                             return value + '%';
//                         }
//                     }
//                 }
//             }
//         }
//
//         if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
//             const chart = new ApexCharts(document.querySelector("#radial-chart"), getChartOptions());
//             chart.render();
//         }
//
//     });


// axios.get('/warehouse-value')
//     .then(function (response) {
//
//         let percentageData = [];
//         let companies = [];
//         let totalValue = 0;
//
//         response.data.forEach((record) => {
//             percentageData.push(parseFloat(record.pct) || 0);  // Ensure numbers
//             totalValue += record.expected;
//         });
//
//         const getChartOptions = () => {
//             return {
//                 series: percentageData,
//                 colors: ['#0015FF','#FF00A1','#90FE00'],
//                 chart: {
//                     height: 320,
//                     width: "100%",
//                     type: "donut",
//                 },
//                 stroke: {
//                     colors: ["transparent"],
//                     lineCap: "",
//                 },
//                 plotOptions: {
//                     pie: {
//                         donut: {
//                             labels: {
//                                 show: true,
//                                 name: {
//                                     show: true,
//                                     fontFamily: "Inter, sans-serif",
//                                     offsetY: 20,
//                                     color: "#FFFFFF",
//                                 },
//                                 total: {
//                                     showAlways: true,
//                                     show: true,
//                                     label: "Inventory Value",
//                                     fontFamily: "Inter, sans-serif",
//                                     color: "#FFFFFF",
//                                     formatter: function (w) {
//                                         return formatterUSD.format(totalValue)
//                                     },
//                                 },
//                                 value: {
//                                     show: true,
//                                     fontFamily: "Inter, sans-serif",
//                                     offsetY: -20,
//                                     formatter: function (value) {
//                                         return value + "k"
//                                     },
//                                 },
//                             },
//                             size: "80%",
//                         },
//                     },
//                 },
//                 grid: {
//                     padding: {
//                         top: -2,
//                     },
//                 },
//                 labels: ["Plant 1", "Plant 2", "Plant 3"],
//                 dataLabels: {
//                     enabled: false,
//                 },
//                 legend: {
//                     position: "bottom",
//                     fontFamily: "Inter, sans-serif",
//                 },
//                 yaxis: {
//                     labels: {
//                         formatter: function (value) {
//                             return value + "k"
//                         },
//                     },
//                 },
//                 xaxis: {
//                     labels: {
//                         formatter: function (value) {
//                             return value  + "k"
//                         },
//                     },
//                     axisTicks: {
//                         show: false,
//                     },
//                     axisBorder: {
//                         show: false,
//                     },
//                 },
//             }
//         }
//
//         if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
//             const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
//             chart.render();
//         }
//
//
//     });
