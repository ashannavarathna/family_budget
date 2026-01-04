@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('styles')
<style>
    .row-bordered {
        border-bottom: 1px solid #ccc;
    }

    .category-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .list-group-item.pl-4 {
        padding-left: 2rem !important;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1>Dashboard</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="d-flex align-items-center justify-content-between">
                            <!-- Previous month -->
                            <button id="btn-prev-month" class="btn btn-sm btn-light" aria-label="前月">
                                <i class="fas fa-chevron-left"></i>
                            </button>                            
                            <!-- Month label -->
                            <div>
                                <!-- h3 class="card-title">カテゴリ別支出</h3 -->
                                <h5 class="mb-1" id="h-month-label">****年*月</h5>
                                <small id="h-month-range">(*月*日〜*月*日)</small>
                            </div>
                            <!-- Next month -->
                            <button id="btn-next-month" class="btn btn-sm btn-light"aria-label="翌月">
                                <i class="fas fa-chevron-right"></i>
                            </button>                            
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <!-- Total Summary -->
                        <div class="row mb-3 py-2 row-bordered">
                            <div class="col-4">
                                <div class="text-muted">収入</div>
                                <strong id="h-total-income">¥0</strong>
                            </div>
                            <div class="col-4">
                                <div class="text-muted">支出</div>
                                <strong id="h-total-expense">¥0</strong>
                            </div>
                            <div class="col-4">
                                <div class="text-muted">収支</div>
                                <strong id="h-month-balance">¥0</strong>
                            </div>
                        </div>
                        <!-- Draw chart -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>

                        <!-- Category List -->

                        <div class="row">
                            <div class="col-12">
                                <div class="list-group list-group-flush" id="category-list">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>

<script>
    let categoryChart;
    let currentOffSetMonth = 0;

    // central color palettes
    const COLOR_PALETTES = {
        expense: [
            '#e74c3c', '#f1948a', '#bb8fce',
            '#7fb3d5', '#48c9b0', '#f7dc6f',
            '#5dade2', '#af7ac5', '#d2b4de'
        ],
        income: [
            '#7f8c8d', '#95a5a6', '#b2babb',
            '#ccd1d1', '#aeb6bf'
        ]
    };    

    // Cache DOM
    const dom = {
        monthLabel: document.getElementById('h-month-label'),
        monthRange: document.getElementById('h-month-range'),
        income: document.getElementById('h-total-income'),
        expense: document.getElementById('h-total-expense'),
        balance: document.getElementById('h-month-balance'),
    }


    /* Load summary by month */
    document.getElementById('btn-prev-month').addEventListener('click', () => {
        currentOffSetMonth = currentOffSetMonth - 1;
        loadChart(currentOffSetMonth);
        
    });
    document.getElementById('btn-next-month').addEventListener('click', () => {
        currentOffSetMonth = currentOffSetMonth + 1;
        loadChart(currentOffSetMonth);
    });

    // a shared color map
    function buildCategoryColorMap(categories) {
        const map = {};

        ['expense', 'income'].forEach(type => {
            const palette = COLOR_PALETTES[type];

            categories[type].forEach((item, index) => {
                map[`${type}:${item.category}`] =
                    palette[index % palette.length];
            });
        });

        return map;
    }    


    /* ======================
        init headers
    ====================== */
    function initHeaders(headers){
        dom.monthLabel.textContent = headers.month_label;
        dom.monthRange.textContent = `(${headers.range_label})`;

        dom.income.textContent  = `¥${headers.totals.income.toLocaleString()}`;
        dom.expense.textContent = `¥${headers.totals.expense.toLocaleString()}`;
        dom.balance.textContent = `¥${(headers.totals.income - headers.totals.expense).toLocaleString()}`;
        dom.balance.classList.remove('text-success', 'text-danger');
        if((headers.totals.income - headers.totals.expense) < 0){
            dom.balance.classList.add('text-danger');
        }

        if((headers.totals.income - headers.totals.expense) > 0){
            dom.balance.classList.add('text-success');
        }

    }

    /* ======================
        Generate category list
    ====================== */
    function renderCategoryList(categories, containerId) {

        // Colors for categories
        const categoryColors = [
            '#e74c3c','#f1948a','#bb8fce',
            '#7fb3d5','#48c9b0','#f7dc6f',
            '#5dade2','#af7ac5','#d2b4de'
        ];

        const typeLabels = {
            expense: '支出',
            income: '収入'
        };        

        const container = document.getElementById(containerId);
        container.innerHTML = '';

        categories.forEach((item, index) => {
            const color = categoryColors[index % categoryColors.length];

            container.insertAdjacentHTML(
                'beforeend', 
                `<div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                    <span class="category-dot mr-2" style="background:${color}"></span>
                    ${item.category}
                    </div>
                    <strong>¥${Number(item.total).toLocaleString()}</strong>
                </div>`
            );
        });
    }

    // Grouped Categories By Income, Expense
    function renderGroupedCategoryList(categories, colorMap) {
        const container = document.getElementById('category-list');
        container.innerHTML = '';

        const typeLabels = {
            expense: '支出',
            income: '収入'
        };

        Object.entries(categories).forEach(([typeKey, items]) => {
            if (!items.length) return;

            /* ---- Type Header ---- */
            container.insertAdjacentHTML('beforeend', `
            <div class="list-group-item bg-light text-muted font-weight-bold text-left">
                ${typeLabels[typeKey]}
            </div>
            `);

            /* ---- Category Items ---- */
            items.forEach((item, index) => {
            const color = colorMap[`${typeKey}:${item.category}`];

            container.insertAdjacentHTML('beforeend', `
                <div class="list-group-item d-flex justify-content-between align-items-center pl-4">
                <div class="d-flex align-items-center">
                    <span class="category-dot mr-2" style="background:${color}"></span>
                    ${item.category}
                </div>
                <strong>¥${Number(item.total).toLocaleString()}</strong>
                </div>
            `);
            });
        });
    }   

    /* ======================
       Center Text Plugin
    ====================== */
    const centerTextPlugin = {
        id: 'centerText'
        , afterDraw(chart) {

            if (window.innerWidth > 576) return; // mobile only

            const {
                ctx
                , chartArea
            } = chart;
            const data = chart.data.datasets[0].data;
            const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);

            ctx.save();
            ctx.textAlign = 'center';

            ctx.font = 'bold 14px sans-serif';
            ctx.fillStyle = '#777';
            ctx.textBaseline = 'bottom';
            ctx.fillText('支出', (chart.width / 2), (chart.height / 2) - 2);

            ctx.font = 'bold 18px sans-serif';
            ctx.fillStyle = '#333';
            ctx.fillText('¥' + total.toLocaleString(), (chart.width / 2), (chart.height / 2) + 20);

            ctx.restore();
        }
    };

    /* ======================
       Slice Text (Mobile)
    ====================== */
    const sliceTextPlugin = {
        id: 'sliceText'
        , afterDraw(chart) {
            if (window.innerWidth > 576) return; // mobile only

            const {
                ctx
            } = chart;
            const meta = chart.getDatasetMeta(0);
            const data = chart.data.datasets[0].data;
            const labels = chart.data.labels;
            const total = data.reduce((a, b) => a + b, 0);

            ctx.save();
            ctx.font = '10px sans-serif';
            ctx.fillStyle = '#000';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            meta.data.forEach((el, i) => {
                const angle = el.startAngle + (el.endAngle - el.startAngle) / 2;
                const radius = (el.innerRadius + el.outerRadius) / 2;

                const x = el.x + Math.cos(angle) * radius;
                const y = el.y + Math.sin(angle) * radius;

                //const percent = Math.round(data[i]/total*100);
                const percent = ((data[i] / total) * 100).toFixed(0);
                // Hide tiny slices 
                if (percent < 5) return;

                /* Label */
                ctx.font = '16px sans-serif';
                ctx.fillStyle = '#111';
                ctx.fillText(labels[i], x, y - 15);

                /* Percentage */
                ctx.font = 'bold 11px sans-serif';
                ctx.fillText(percent + '%', x, y);
            });

            ctx.restore();
        }
    };

    /* ======================
       Callout Lines (Desktop)
    ====================== */
    const calloutPlugin = {
        id: 'callout'
        , afterDraw(chart) {
            if (window.innerWidth < 576) return; // desktop only

            const {
                ctx
            } = chart;
            const meta = chart.getDatasetMeta(0);
            const data = chart.data.datasets[0].data;
            const labels = chart.data.labels;
            const total = data.reduce((a, b) => a + b, 0);

            meta.data.forEach((el, i) => {
                const angle = el.startAngle + (el.endAngle - el.startAngle) / 2;
                const r = el.outerRadius;

                const x1 = el.x + Math.cos(angle) * r;
                const y1 = el.y + Math.sin(angle) * r;
                const x2 = el.x + Math.cos(angle) * (r + 14);
                const y2 = el.y + Math.sin(angle) * (r + 14);

                const isRight = Math.cos(angle) > 0;
                const x3 = x2 + (isRight ? 36 : -36);
                const y3 = y2;

                ctx.strokeStyle = chart.data.datasets[0].backgroundColor[i];
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.lineTo(x3, y3);
                ctx.stroke();

                const percent = ((data[i] / total) * 100).toFixed(1);

                ctx.textAlign = isRight ? 'left' : 'right';
                ctx.textBaseline = 'middle';
                ctx.fillText(`${labels[i]} - ${percent}%`, x3 + (isRight ? 6 : -6), y3);
            });

            ctx.restore();

        }
    };

    /* ======================
       Chart Init
    ====================== */
    document.addEventListener('DOMContentLoaded', loadChart(currentOffSetMonth));

    function loadChart(offset_month) {
        fetch(`{{ route("admin.reports.current-month-summary") }}?offset-month=${offset_month}`)
            .then(res => res.json())
            .then(data => {
                //console.log(data);
                initHeaders(data.headers);
                //renderCategoryList(data.categories.expense, 'expense-list');
                //renderCategoryList(data.categories.income, 'income-list');

                const colorMap = buildCategoryColorMap(data.categories);
                renderGroupedCategoryList(data.categories, colorMap);

                // drawing chart for expense
                const labels = data.categories.expense.map(i => i.category);
                const values = data.categories.expense.map(i => Number(i.total));
                const colors = data.categories.expense.map(
                    i => colorMap[`expense:${i.category}`]
                );

                renderChart(labels, values, colors);

            });
    }

    function renderChart(labels, values, colors) {
        const ctx = document.getElementById('categoryChart');

        if (categoryChart) categoryChart.destroy();

        categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                layout: {
                    //padding: { top: 30, bottom: 30, left: 40,right: 40}
                    //custom padding desktop - mobile
                    padding: window.innerWidth > 576 ? 
                    { top: 30, bottom: 30, left: 40, right: 40} 
                    : 
                    {top: 20, bottom: 20, left: 20, right: 20}
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            },
            plugins: [
                centerTextPlugin,
                sliceTextPlugin,
                calloutPlugin
            ]
        });

        /* Update on resize */
        window.addEventListener('resize', () => categoryChart.update());
    }

</script>
@endsection
