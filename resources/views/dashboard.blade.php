@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('styles')
<style>
    .row-bordered {
        border-bottom: 1px solid #ccc;
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <!-- h3 class="card-title">カテゴリ別支出</h3 -->
                        <h5 class="mb-1">2021年2月</h5>
                        <small>(2月1日〜2月28日)</small>
                    </div>
                    <div class="card-body text-center">
                        <!-- Total Summary -->
                        <div class="row mb-3 py-2 row-bordered">
                            <div class="col-4">
                                <div class="text-muted">収入</div>
                                <strong>¥24,928</strong>
                            </div>
                            <div class="col-4">
                                <div class="text-muted">支出</div>
                                <strong>¥10,066</strong>
                            </div>
                            <div class="col-4">
                                <div class="text-muted">収支</div>
                                <strong class="text-success">¥14,862</strong>
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
                                <div class="list-group list-group-flush">

                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="category-dot" style="background:#e74c3c"></span>
                                            外食
                                        </div>
                                        <strong>¥5,131</strong>
                                    </div>

                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="category-dot" style="background:#f5b7b1"></span>
                                            食料品
                                        </div>
                                        <strong>¥600</strong>
                                    </div>

                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="category-dot" style="background:#af7ac5"></span>
                                            映画・音楽・ゲーム
                                        </div>
                                        <strong>¥1,430</strong>
                                    </div>

                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="category-dot" style="background:#d2b4de"></span>
                                            本
                                        </div>
                                        <strong>¥675</strong>
                                    </div>

                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="category-dot" style="background:#5dade2"></span>
                                            教養・教育
                                        </div>
                                        <strong>¥1,500</strong>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">月次概要 ({{ date('Y') }})</h3>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>月</th>
                                        <th>収入</th>
                                        <th>支出</th>
                                        <th>残高</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlySummary as $summary)
                                    <tr>
                                        <td><span class="badge">{{ DateTime::createFromFormat('!m', $summary->month)->format('F (m)')
                        }}</span></td>
                                        <td class="text-success">¥{{ number_format($summary->total_income) }}</td>
                                        <td class="text-danger">¥{{ number_format($summary->total_expense) }}</td>
                                        <td class="font-weight-bold {{ $summary->net_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                            ¥{{ number_format($summary->net_balance) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    document.addEventListener('DOMContentLoaded', loadChart);

    function loadChart() {
        fetch('{{ route("admin.reports.category-summary-crnt-month") }}?transaction_type_id=2')
            .then(res => res.json())
            .then(data => {
                const labels = data.map(i => i.category_name);
                const values = data.map(i => Number(i.total));
                renderChart(labels, values);
            });
    }

    function renderChart(labels, values) {
        const ctx = document.getElementById('categoryChart');

        if (categoryChart) categoryChart.destroy();

        categoryChart = new Chart(ctx, {
            type: 'doughnut'
            , data: {
                labels
                , datasets: [{
                    data: values
                    , backgroundColor: [
                        '#e74c3c', '#f1948a', '#bb8fce'
                        , '#7fb3d5', '#48c9b0', '#f7dc6f'
                    ]
                    , borderColor: '#fff'
                    , borderWidth: 2
                }]
            }
            , options: {
                responsive: true
                , maintainAspectRatio: false
                , cutout: '70%'
                , layout: {
                    //padding: { top: 30, bottom: 30, left: 40,right: 40}
                    //custom padding desktop - mobile
                    padding: window.innerWidth > 576 ? {
                        top: 30
                        , bottom: 30
                        , left: 40
                        , right: 40
                    } : {
                        top: 10
                        , bottom: 10
                        , left: 10
                        , right: 10
                    }
                }
                , plugins: {
                    legend: {
                        display: false
                    }
                    , tooltip: {
                        enabled: true
                    }
                }
            }
            , plugins: [
                centerTextPlugin
                , sliceTextPlugin
                , calloutPlugin
            ]
        });

        /* Update on resize */
        window.addEventListener('resize', () => categoryChart.update());
    }

</script>
@endsection
