@php
	$dataSeminar = $academics->filter(function($item){ return ($item->academictype === "seminar" && $item->is_accepted !== 0); })->count();
	$dataThesisdefense = $academics->filter(function($item){ return ($item->academictype === "thesisdefense" && $item->is_accepted !== 0); })->count();
	$dataWaiting = $academics->filter(function($item){ return ($item->is_accepted === null || $item->is_accepted === "") && ($item->comment === null || $item->comment === ""); })->count();
	$dataVerified = $academics->filter(function($item){ return $item->is_accepted === 1; })->count();
	$dataRejected = $academics->filter(function($item){ return $item->is_accepted === 0; })->count();
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/dashboard.css') }}">
	@endsection

	<x-slot name="title">Dashboard</x-slot>
	<x-slot name="icon">material-symbols:dashboard-outline</x-slot>
	<x-slot name="pagetitle">Dashboard Admin</x-slot>
	
	<div class="admin">
		<div class="top">
			<div class="card">
				<div class="text">
					<h2>Total Seminar</h2>
					<div class="counter">
						<h2>{{ $dataSeminar }}</h2>
						<iconify-icon icon="icon-park-outline:up-two" width="21"></iconify-icon>
					</div>
				</div>
				<img src="{{ \App\Http\Controllers\HelperController::Asset('assets/img/card-1.png') }}" alt="card-1">
			</div>
			<div class="card">
				<div class="text">
					<h2>Total Sidang Akhir</h2>
					<div class="counter">
						<h2>{{ $dataThesisdefense }}</h2>
						<iconify-icon icon="icon-park-outline:up-two" width="21"></iconify-icon>
					</div>
				</div>
				<img src="{{ \App\Http\Controllers\HelperController::Asset('assets/img/card-2.png') }}" alt="card-2">
			</div>
			<div class="card">
				<div class="text">
					<h2>Menunggu Diverifikasi</h2>
					<div class="counter">
						<h2>{{ $dataWaiting }}</h2>
						<iconify-icon icon="icon-park-outline:up-two" width="21"></iconify-icon>
					</div>
				</div>
				<img src="{{ \App\Http\Controllers\HelperController::Asset('assets/img/card-3.png') }}" alt="card-3">
			</div>
		</div>

		<div class="bottom">
			<div class="chart">
				<h6>Status Pendaftaran</h6>
				<canvas class="pie-chart"></canvas>
			</div>
			<div class="chart">
				<h6>Grafik Tren Pendaftaran</h6>
				<canvas class="line-chart"></canvas>
			</div>
		</div>
	</div>

	<script>
		const PieCtx = $("canvas.pie-chart");
		const LineCtx = $("canvas.line-chart");

		new Chart(PieCtx,
		{
			type: "pie",
			data:
			{
				labels: ["Diverifikasi", "Ditolak", "Menunggu"],
				datasets:
				[
					{
						backgroundColor: ["#1CA885", "#F04438", "#F7B731"],
						borderWidth: 1,
						data: [{{ $dataVerified  }}, {{ $dataRejected }}, {{ $dataWaiting }}]
					}
				],
			},
			options:
			{
				plugins:
				{
					legend:
					{
						position: "bottom"
					}
				},
				responsive: true
			}
		});

		new Chart(LineCtx,
		{
			type: "line",
			data:
			{
				labels: {!! json_encode($dataMonthLabels) !!},
				datasets:
				[
					{
						borderColor: "#0B318F",
						backgroundColor: "rgba(11, 49, 143, .08)",
						data: {!! json_encode($dataMonthly) !!},
						fill: true,
						label: "Jumlah Pendaftaran",
						tension: .4
					}
				],
			},
			options:
			{
				plugins:
				{
					legend:
					{
						display: true
					}
				},
				responsive: true,
				scales:
				{
					y:
					{
						beginAtZero: true
					}
				}
			}
		});
	</script>
</x-app-layout>