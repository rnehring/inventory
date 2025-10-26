import Chart from 'chart.js/auto';
axios.get('/dashboard-data')
    .then(function (response) {
        //  COUNTS BY USER ALL TIME
        new Chart(
            document.getElementById('counts-by-user-all-time'),
            {
                type: 'bar',
                responsive: true,
                data: {
                    labels: response.data.allTimeCounts.map(row => row.first_name + ' ' + row.last_name),
                    datasets: [
                        {
                            label: 'Counts by User (All Time)',
                            data: response.data.allTimeCounts.map(row => row.counts),
                            backgroundColor: ['#C33C54','#254E70','#37718E','#8EE3EF','#AEF3E7']
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }
        )

        //  COUNTS BY USER YESTERDAY
        new Chart(
            document.getElementById('counts-by-user-yesterday'),
            {
                type: 'bar',
                responsive: true,
                data: {
                    labels: response.data.yesterdayCounts.map(row => row.first_name + ' ' + row.last_name),
                    datasets: [
                        {
                            label: 'Counts by User (All Time)',
                            data: response.data.yesterdayCounts.map(row => row.counts),
                            backgroundColor: ['#C33C54','#254E70','#37718E','#8EE3EF','#AEF3E7']
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }
        )

        // COUNT PERCENTAGE BY COMPANY
        new Chart(
            document.getElementById('percent-by-company'),
            {
                type: 'bar',
                responsive: true,
                data: {
                    labels: response.data.percentageByCompany.map(row => row.company),
                    datasets: [
                        {
                            label: 'Counts by User (All Time)',
                            data: response.data.percentageByCompany.map(row => row.percentage),
                            backgroundColor: ['#C33C54','#254E70','#37718E','#8EE3EF','#AEF3E7']
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }
        )

        // PRE COUNT BINS VERIFIED
        // new Chart(
        //     document.getElementById('bins-verified-all-time'),
        //     {
        //         type: 'bar',
        //         responsive: true,
        //         data: {
        //             labels: response.data.preCountAllTime.map(row => row.user),
        //             datasets: [
        //                 {
        //                     label: 'Counts by User (All Time)',
        //                     data: response.data.preCountAllTime.map(row => row.counts),
        //
        //                 }
        //             ]
        //         },
        //         options: {
        //             plugins: {
        //                 legend: {
        //                     display: false
        //                 }
        //             }
        //         }
        //     }
        // )

        // PRE COUNT BINS VERIFIED
        // new Chart(
        //     document.getElementById('bins-verified-yesterday'),
        //     {
        //         type: 'bar',
        //         responsive: true,
        //         data: {
        //             labels: response.data.yesterdayPreCounts.map(row => row.user),
        //             datasets: [
        //                 {
        //                     label: 'Counts by User (All Time)',
        //                     data: response.data.yesterdayPreCounts.map(row => row.counts),
        //
        //                 }
        //             ]
        //         },
        //         options: {
        //             plugins: {
        //                 legend: {
        //                     display: false
        //                 }
        //             }
        //         }
        //     }
        // )


        // PRE COUNT BINS VERIFIED BY COMPANY
        // new Chart(
        //     document.getElementById('bins-verified-by-company'),
        //     {
        //         type: 'bar',
        //         responsive: true,
        //         data: {
        //             labels: response.data.companyPreCounts.map(row => row.company),
        //             datasets: [
        //                 {
        //                     label: 'Counts by User (All Time)',
        //                     data: response.data.companyPreCounts.map(row => row.counts),
        //
        //                 }
        //             ]
        //         },
        //         options: {
        //             plugins: {
        //                 legend: {
        //                     display: false
        //                 }
        //             }
        //         }
        //     }
        // )

    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .finally(function () {
        // always executed
    });
