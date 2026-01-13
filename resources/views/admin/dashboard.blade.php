@extends('layouts.app')

@section('title', 'Dashboard Admin | WokaPayment')

@section('content')

<div class="grid grid-cols-12 gap-4 md:gap-6">

    <!-- Bagian Kartu Dashboard -->
    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            
            <!-- Total Peserta -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                    {!! $icons['all-participants'] !!}
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Peserta</span>
                        <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                            {{ $totalPeserta }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Total Peserta PKL -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                    {!! $icons['pkl-participants'] !!}
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Peserta PKL</span>
                        <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                            {{ $totalPesertaPKL }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Total Peserta Kursus -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                    {!! $icons['kursus-participants'] !!}
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Peserta Kursus</span>
                        <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                            {{ $totalPesertaKursus }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                    {!! $icons['payments'] !!}
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Pembayaran</span>
                        <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                            {{ $totalPembayaran }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Pembayaran Bulanan PKL & Kursus -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6 overflow-x-auto">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pembayaran Bulanan PKL & Kursus</h2>
            <div class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Total Bulanan: <span id="totalPayments">Rp0,00</span>
            </div>
            <div id="paymentsChart" class="h-[400px] min-w-[300px] md:min-w-full"></div>
        </div>

        <!-- ApexCharts JS -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                let currentFilter = 'All';
                const chartColors = { 'PKL': '#4F46E5', 'Kursus': '#10B981' };

                const options = {
                    chart: { type: 'bar', height: 400, toolbar: { show: true } },
                    series: [],
                    xaxis: { categories: [] },
                    colors: [],
                    tooltip: {
                        y: {
                            formatter: val => 'Rp' + Number(val).toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            columnWidth: '50%',
                            distributed: false,
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: { enabled: false },
                    responsive: [
                        {
                            breakpoint: 640,
                            options: {
                                plotOptions: { bar: { columnWidth: '70%' } }
                            }
                        }
                    ]
                };

                const chart = new ApexCharts(document.querySelector("#paymentsChart"), options);
                chart.render();

                function formatRupiah(number) {
                    return 'Rp' + Number(number).toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                function updateTotal(series) {
                    const total = series.reduce((sum, s) =>
                        sum + s.data.reduce((a, b) => a + Number(b), 0)
                        , 0);
                    document.querySelector('#totalPayments').textContent = formatRupiah(total);
                }

                function fetchDataAndUpdateChart() {
                    fetch("{{ route('admin.admin.dashboard.payments-data') }}")
                        .then(res => res.json())
                        .then(data => {
                            const allSeries = [];
                            const colors = [];

                            ['PKL', 'Kursus'].forEach(type => {
                                if (currentFilter === 'All' || currentFilter === type) {
                                    allSeries.push({
                                        name: type,
                                        data: data[type].map(Number)
                                    });
                                    colors.push(chartColors[type]);
                                }
                            });

                            chart.updateOptions({
                                xaxis: { categories: data.months },
                                colors: colors
                            });
                            chart.updateSeries(allSeries);
                            updateTotal(allSeries);
                        });
                }

                fetchDataAndUpdateChart();
                setInterval(fetchDataAndUpdateChart, 10000);

                ['PKL', 'Kursus', 'All'].forEach(filter => {
                    const el = document.querySelector(`#filter${filter}`);
                    if (el) {
                        el.addEventListener('click', () => {
                            currentFilter = filter;
                            fetchDataAndUpdateChart();
                        });
                    }
                });

            });
        </script>
    </div>

    <!-- Sisa konten tetap sama seperti Blade-mu -->
    <!-- <div class="col-span-12"> -->
        <!-- Statistics Chart -->
        <!-- <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
            <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
                <div class="w-full">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistics</h3>
                    <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                        Target you’ve set for each month
                    </p>
                </div>
                <div class="flex items-start w-full gap-3 sm:justify-end">
                    <div x-data="{ selected: 'overview' }" class="inline-flex w-fit items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                        @php
                            $options = [
                                ['value' => 'overview', 'label' => 'Overview'],
                                ['value' => 'sales', 'label' => 'Sales'],
                                ['value' => 'revenue', 'label' => 'Revenue'],
                            ];
                        @endphp
                        @foreach ($options as $option)
                            <button @click="selected = '{{ $option['value'] }}'" 
                                :class="selected === '{{ $option['value'] }}' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
                                class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white">
                                {{ $option['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <div id="chartThree" class="-ml-4 min-w-[700px] pl-2 xl:min-w-full"></div>
            </div>
        </div> -->
    <!-- </div> -->

</div>

@endsection
