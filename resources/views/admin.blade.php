@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hallo {{ auth()->user()->name }} 👑</h3>
            </div>
            <div class="card-body">
                <p>Berhasil masuk dashboard admin.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Total Active Users</h3>
            </div>
            <div class="card-body">
                <div id="active-users-chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                name: 'Total Active Users',
                data: [{{ $totalActiveUsers }}]
            }],
            chart: {
                type: 'bar',
                height: 200,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['Active Users'],
            },
            yaxis: {
                title: {
                    text: 'Count'
                }
            },
            fill: {
                opacity: 1
            },
            colors: ['#007bff']
        };

        var chart = new ApexCharts(document.querySelector("#active-users-chart"), options);
        chart.render();
    });
</script>
@endsection
