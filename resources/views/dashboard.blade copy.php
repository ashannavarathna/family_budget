@extends('layouts.admin')

@section('title', 'ダッシュボード')

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
                <div class="card-header">
                    <h3 class="card-title">カテゴリ別支出</h3>
                </div>

                <div class="card-body">
                    <div style="height:320px">
                        <canvas id="categoryChart"></canvas>
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
                                    <th >月</th>
                                    <th >収入</th>
                                    <th >支出</th>
                                    <th >残高</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlySummary as $summary)
                                <tr>
                                    <td><span class="badge">{{ DateTime::createFromFormat('!m', $summary->month)->format('F (m)') }}</span></td>
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
  id: 'centerText',
  afterDraw(chart) {
    const { ctx } = chart;
    const total = chart.data.datasets[0].data.reduce((a,b)=>a+b,0);

    ctx.save();
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    ctx.font = '12px sans-serif';
    ctx.fillStyle = '#888';
    ctx.fillText('支出', chart.width/2, chart.height/2 - 10);

    ctx.font = 'bold 16px sans-serif';
    ctx.fillStyle = '#000';
    ctx.fillText('¥' + total.toLocaleString(), chart.width/2, chart.height/2 + 10);

    ctx.restore();
  }
};

/* ======================
   Slice Text (Mobile)
====================== */
const sliceTextPlugin = {
  id: 'sliceText',
  afterDraw(chart) {
    if (window.innerWidth > 576) return;

    const { ctx } = chart;
    const meta = chart.getDatasetMeta(0);
    const data = chart.data.datasets[0].data;
    const labels = chart.data.labels;
    const total = data.reduce((a,b)=>a+b,0);

    ctx.save();
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    meta.data.forEach((el, i) => {
      const angle = el.startAngle + (el.endAngle - el.startAngle)/2;
      const radius = (el.innerRadius + el.outerRadius)/2;

      const x = el.x + Math.cos(angle)*radius;
      const y = el.y + Math.sin(angle)*radius;

      const percent = Math.round(data[i]/total*100);
      if (percent < 8) return;

      ctx.fillStyle = '#DEE';
      ctx.font = '10px sans-serif';
      ctx.fillText(labels[i], x, y - 7);

      ctx.font = 'bold 11px sans-serif';
      ctx.fillText(percent + '%', x, y + 7);
    });

    ctx.restore();
  }
};

/* ======================
   Callout Lines (Desktop)
====================== */
const calloutPlugin = {
  id: 'callout',
  afterDraw(chart) {
    if (window.innerWidth < 576) return;

    const { ctx } = chart;
    const meta = chart.getDatasetMeta(0);

    meta.data.forEach((el, i) => {
      const angle = el.startAngle + (el.endAngle - el.startAngle)/2;
      const r = el.outerRadius;

      const x1 = el.x + Math.cos(angle)*r;
      const y1 = el.y + Math.sin(angle)*r;
      const x2 = el.x + Math.cos(angle)*(r + 20);
      const y2 = el.y + Math.sin(angle)*(r + 20);

      ctx.strokeStyle = chart.data.datasets[0].backgroundColor[i];
      ctx.lineWidth = 1;
      ctx.beginPath();
      ctx.moveTo(x1, y1);
      ctx.lineTo(x2, y2);
      ctx.stroke();
    });
  }
};

/* ======================
   Load Chart
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
    type: 'doughnut',
    data: {
      labels,
      datasets: [{
        data: values,
        backgroundColor: [
          '#e74c3c','#f1948a','#bb8fce',
          '#7fb3d5','#48c9b0','#f7dc6f'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        legend: { display: false },
        tooltip: { enabled: false }
      }
    },
    plugins: [
      centerTextPlugin,
      sliceTextPlugin,
      calloutPlugin
    ]
  });
}
</script>
@endsection
