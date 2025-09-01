@php
	use Carbon\Carbon;

	$querySearch = request()->query("search") ?? "";
	$queryType = request()->query("type") ?? "";
	$querySemester = request()->query("search") ?? "";

	if (empty($querySemester))
	{
		$now = Carbon::now();
		$year = $now->year;
		$month = $now->month;

		$querySemester = ($month >= 7)
			? "{$year}-" . ($year + 1)
			: ($year - 1) . "-{$year}";
		
		if (isset($semesterList[$querySemester]))
			$semesterSelectTriggered = true;
	}
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/schedule.css') }}">
	@endsection
	
	<x-slot name="title">Jadwal</x-slot>
	<x-slot name="icon">material-symbols-light:calendar-clock-outline-sharp</x-slot>
	<x-slot name="pagetitle">Jadwal Seminar & Sidang</x-slot>

	<x-input-wrapper class="filter" id="semester" type="select" label="Semester*" placeholder="Pilih Semester">
		<option value="all" {{ ($querySemester === "all" || $querySemester === "" ? " selected" : "") }}>Semua Semester</option>
		
		@foreach ($semesterList as $code => $label)
			<option value="{{ $code }}" {{ ($querySemester === $code ? " selected" : "") }}>{{ $label }}</option>
		@endforeach
	</x-input-wrapper>

	@if (isset($semesterSelectTriggered))
		<script>
			var $select = $("select#semester");
			var selectedValue = "{{ $querySemester }}";

			if ($select.length && $select.find("option[value='" + selectedValue + "']").length)
				$select.val(selectedValue).trigger("change");
		</script>
	@endif

	<div class="top">
		<x-input-wrapper class="filter" id="type" type="select" placeholder="Pilih Jenis">
			<option value="all" {{ ($queryType === "all" || $queryType === "" ? " selected" : "") }}>Semua Jenis</option>
			<option value="seminar" {{ ($queryType === "seminar" ? " selected" : "") }}>Seminar Proposal</option>
			<option value="thesisdefense" {{ ($queryType === "thesisdefense" ? " selected" : "") }}>Sidang Akhir</option>
		</x-input-wrapper>

		<x-input-wrapper class="search type-1" id="search" type="text" placeholder="Cari" value="{{ $querySearch }}" autofocus />
	</div>

	<div class="middle">
		<table>
			<thead>
				<tr>
					@if ($userRole === "admin")
					<th class="status-schedule">Status</th>
					@endif
					<th class="type">Jenis</th>
					<th class="title">Judul</th>
					<th class="name">Mahasiswa</th>
					<th class="name">Pembimbing</th>
					<th class="name">Penguji</th>
					<th class="schedule">Jadwal Sidang</th>
				</tr>
			</thead>
			<tbody>
			@forelse ($dataSubmissions as $index => $item)
				<tr>
					@if ($userRole === "admin")
					<th class="status-schedule">
						@if ($item->status_schedule === 1)
						<x-button class="status-passed" disabled>Selesai</x-button>
						@else
						<form id="form-delete" action="{{ route('admin.schedule') }}" method="POST">
							@csrf
							@method("DELETE")
							
							<x-button class="remove">Hapus</x-button>
						</form>
						@endif
					</th>
					@endif
					<td class="type">{{ ucfirst($item->submission_type) }}</td>
					<td class="title">{{ $item->title }}</td>
					<td class="name">{!! $item->username ?? "<i>Data Mahasiswa Tidak Ditemukan</i>" !!}</td>
					<td class="name">
						<ul>
							<li>{!! $item->supervisor1 ?? "<i>Data Dosen Tidak Ditemukan</i>" !!}</li>
							<li>{!! $item->supervisor2 ?? "<i>Data Dosen Tidak Ditemukan</i>" !!}</li>
						</ul>
					</td>
					<td class="name">
						<ul>
							<li>Prof. Dr. Ir. Sudarsono Soedomo, MS</li>
							<li>Dr. Ir. Harnios Arief, M.Sc.F.Trop</li>
						</ul>
					</td>
					<td class="schedule">
						<ul>
							<li>{{ $item->place }}</li>
							<li>{{ $item->date_parsed }}</li>
							<li>{{ $item->time }}</li>
						</ul>
					</td>
				</tr>
			@empty
			<tr>
				<td colspan="6" class="not-found">Tidak Ada Data Yang Ditemukan</td>
			</tr>
			@endforelse
			</tbody>
		</table>
	</div>

	<x-navigator-buttons :data="$dataSubmissions" />
</x-app-layout>