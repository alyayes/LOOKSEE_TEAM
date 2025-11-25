$(function() {
    "use strict";

    // Fungsi Pembantu untuk menggelapkan warna
    function darkenColor(hexOrRgb, percent) {
        // Handle RGBA/RGB colors
        const parseRgb = (str) => {
            const match = str.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/);
            if (match) {
                return {
                    r: parseInt(match[1]),
                    g: parseInt(match[2]),
                    b: parseInt(match[3]),
                    a: match[4] ? parseFloat(match[4]) : 1
                };
            }
            return null;
        };

        let colorObj = parseRgb(hexOrRgb);

        if (colorObj) {
            let p = percent / 100;
            let R = Math.max(0, colorObj.r - (colorObj.r * p));
            let G = Math.max(0, colorObj.g - (colorObj.g * p));
            let B = Math.max(0, colorObj.b - (colorObj.b * p));
            return `rgba(${Math.round(R)},${Math.round(G)},${Math.round(B)},${colorObj.a})`;
        }

        // Handle HEX colors
        let f = parseInt(hexOrRgb.slice(1), 16);
        let t = percent < 0 ? 0 : 255; // Target value (0 for darken, 255 for lighten)
        let p = percent < 0 ? percent * -1 : percent; // Absolute percentage
        let R = f >> 16;
        let G = (f >> 8) & 0x00FF;
        let B = f & 0x0000FF;

        return "#" + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
    }

    // --- Chart 1: June Total Sales (Bar Chart) ---
    var ctx1 = document.getElementById("chart1").getContext('2d');

    var gradientStroke_chart1_total_sales = ctx1.createLinearGradient(0, 0, 0, 300);
    gradientStroke_chart1_total_sales.addColorStop(0, '#6078ea');
    gradientStroke_chart1_total_sales.addColorStop(1, '#17c5ea');

    var myChart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Jun'],
            datasets: [{
                label: 'Total Sales',
                // Pastikan sales_june didefinisikan di PHP dan di-echo ke JS
                data: [typeof sales_june !== 'undefined' ? sales_june : 1250000],
                borderColor: gradientStroke_chart1_total_sales,
                backgroundColor: gradientStroke_chart1_total_sales,
                hoverBackgroundColor: gradientStroke_chart1_total_sales,
                borderRadius: 20,
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0, // Set min to 0 to avoid starting at 100 for sales
                    max: 50000000,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // --- Chart 2: Order Status Distribution (Doughnut Chart) ---
    var ctx2 = document.getElementById("chart2").getContext('2d');

    const chart2Labels = typeof chartDoughnutLabels !== 'undefined' ? chartDoughnutLabels : ["Pending", "Prepared", "Shipped", "Completed"];
    const chart2DataValues = typeof chartDoughnutDataValues !== 'undefined' ? chartDoughnutDataValues : [14, 3, 3, 3];
    const chart2Colors = typeof chartDoughnutColorsFromBadges !== 'undefined' ? chartDoughnutColorsFromBadges : [
        'rgba(255, 193, 7, .8)', // Pending (Warning)
        'rgb(253, 152, 0)',     // Prepared (Orange)
        'rgb(148, 190, 253)',   // Shipped (Info/Light Blue)
        'rgb(60, 199, 134)'     // Completed (Success)
    ];

    var myChart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: chart2Labels,
            datasets: [{
                data: chart2DataValues,
                backgroundColor: chart2Colors,
                hoverBackgroundColor: chart2Colors.map(color => darkenColor(color, 10)),
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '82%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let label = tooltipItem.label || '';
                            if (label) { label += ': '; }
                            label += tooltipItem.raw + ' orders';
                            return label;
                        }
                    }
                }
            }
        }
    });

    // --- Chart 3 (Weekly Access) ---
    var ctx3 = document.getElementById('chart3').getContext('2d');

    var gradientStroke1_chart3 = ctx3.createLinearGradient(0, 0, 0, 300);
    gradientStroke1_chart3.addColorStop(0, '#00b09b');
    gradientStroke1_chart3.addColorStop(1, '#96c93d');

    var myChart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            // Menggunakan variabel weeklyAccessLabels yang di-echo dari PHP
            labels: typeof weeklyAccessLabels !== 'undefined' ? weeklyAccessLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Akses User',
                // Menggunakan variabel weeklyAccessData yang di-echo dari PHP
                data: typeof weeklyAccessData !== 'undefined' ? weeklyAccessData : [5, 30, 16, 23, 8, 14, 2],
                backgroundColor: [
                    gradientStroke1_chart3
                ],
                fill: {
                    target: 'origin',
                    above: 'rgb(21 202 32 / 15%)',
                }, 
                tension: 0.4,
                borderColor: [
                    gradientStroke1_chart3
                ],
                borderWidth: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + ' user';
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah User'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hari'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // --- Chart 4 (Trending Posts by Likes) - PIE CHART & WARNA SOLID (FILTERED & TOP 3) ---
    if (document.getElementById("chart4")) {
        var ctx4 = document.getElementById("chart4").getContext('2d');

        const chart4PieColors = [
            '#FF6384', // Merah muda
            '#36A2EB', // Biru
            '#FFCE56', // Kuning
            '#4BC0C0', // Teal
            '#9966FF', // Ungu
            '#FF9900', // Oranye
            '#00FF00'  // Hijau terang
        ];

        // Mendapatkan data asli dari PHP atau fallback
        // Pastikan trendingPostsLabels dan trendingPostsData di-echo dari PHP sebagai array JSON
        const originalLabelsChart4 = typeof trendingPostsLabels !== 'undefined' ? trendingPostsLabels : ['Post 1', 'Post 2', 'Post 3', 'Post 4', 'Post 5'];
        const originalDataChart4 = typeof trendingPostsData !== 'undefined' ? trendingPostsData : [10, 3, 2, 0, 0];

        // Menggabungkan labels dan data, lalu filter dan urutkan
        let combinedData = [];
        for (let i = 0; i < originalDataChart4.length; i++) {
            if (originalDataChart4[i] > 0) { // Hanya sertakan data dengan likes > 0
                combinedData.push({
                    label: originalLabelsChart4[i],
                    likes: originalDataChart4[i]
                });
            }
        }

        // Urutkan berdasarkan jumlah likes secara menurun
        combinedData.sort((a, b) => b.likes - a.likes);

        // Ambil hanya 3 data teratas
        const top3Data = combinedData.slice(0, 3);

        const filteredLabelsChart4 = top3Data.map(item => item.label);
        const filteredDataChart4 = top3Data.map(item => item.likes);

        // Jika setelah filter dan ambil top 3 tidak ada data (semua 0 atau tidak ada data), tampilkan 'Tidak ada data'
        if (filteredDataChart4.length === 0) {
            filteredLabelsChart4.push('Tidak ada data');
            filteredDataChart4.push(1); // Beri nilai 1 agar pie chart tetap terlihat sebagai satu potongan
            // Opsional: ganti warna jika hanya ada "Tidak ada data"
            chart4PieColors[0] = '#CCCCCC'; // Abu-abu
        }

        var myChart4 = new Chart(ctx4, {
            type: 'pie',
            data: {
                labels: filteredLabelsChart4,
                datasets: [{
                    data: filteredDataChart4,
                    // Pastikan jumlah warna cukup untuk semua slice yang difilter
                    backgroundColor: chart4PieColors.slice(0, filteredLabelsChart4.length),
                    hoverBackgroundColor: chart4PieColors.slice(0, filteredLabelsChart4.length).map(color => darkenColor(color, 10)),
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '0%', // Untuk pie chart penuh (bukan doughnut)
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            color: '#333'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                // Hitung persentase
                                let sum = 0;
                                let dataArr = context.dataset.data;
                                for (let i = 0; i < dataArr.length; i++) {
                                    sum += Number(dataArr[i]);
                                }
                                // Hindari pembagian nol jika sum adalah 0 (misalnya, jika hanya ada "Tidak ada data")
                                let percentage = sum === 0 ? '0.00%' : (context.raw / sum * 100).toFixed(2) + '%';
                                return label + context.raw + ' Likes (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    }


    // --- Chart 5 (Top Trending Categories) ---
    if (document.getElementById("chart5")) {
        var ctx5 = document.getElementById("chart5").getContext('2d');
        
        const chart5BarColorsMan = '#42e695'; // Hijau
        const chart5BarColorsWoman = '#f54ea2'; // Pink

        // Pastikan allTrendingLabels, trendingProductsMan, trendingProductsWoman di-echo dari PHP
        // Sediakan fallback array kosong jika tidak terdefinisi
        const labelsForChart5 = (typeof allTrendingLabels !== 'undefined' && Array.isArray(allTrendingLabels) && allTrendingLabels.length > 0) ? 
                                    allTrendingLabels : ['Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5'];

        const trendingProductsManData = (typeof trendingProductsMan !== 'undefined' && Array.isArray(trendingProductsMan)) ? trendingProductsMan : [];
        const trendingProductsWomanData = (typeof trendingProductsWoman !== 'undefined' && Array.isArray(trendingProductsWoman)) ? trendingProductsWoman : [];

        // Siapkan data untuk dataset 'Trending Pria'
        const dataForMan = labelsForChart5.map(label => {
            const product = trendingProductsManData.find(item => item.nama_produk === label);
            return product ? product.total_ordered_quantity : 0;
        });

        // Siapkan data untuk dataset 'Trending Wanita'
        const dataForWoman = labelsForChart5.map(label => {
            const product = trendingProductsWomanData.find(item => item.nama_produk === label);
            return product ? product.total_ordered_quantity : 0;
        });

        var myChart5 = new Chart(ctx5, {
            type: 'bar',
            data: {
                labels: labelsForChart5,
                datasets: [{
                    label: 'Trending Man',
                    data: dataForMan,
                    backgroundColor: chart5BarColorsMan,
                    borderColor: chart5BarColorsMan,
                    hoverBackgroundColor: darkenColor(chart5BarColorsMan, 10),
                    pointRadius: 0,
                    fill: false,
                    borderWidth: 1
                }, {
                    label: 'Trending Woman',
                    data: dataForWoman,
                    backgroundColor: chart5BarColorsWoman,
                    borderColor: chart5BarColorsWoman,
                    hoverBackgroundColor: darkenColor(chart5BarColorsWoman, 10),
                    pointRadius: 0,
                    fill: false,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                barPercentage: 0.5,
                categoryPercentage: 0.8,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw + ' terjual';
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Terjual'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Produk'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});