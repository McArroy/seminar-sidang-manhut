@php
	use App\Http\Controllers\DateIndoFormatterController;
	use App\Http\Controllers\UserController;
	use Carbon\Carbon;

	$SelectedSemester = $_GET["semester"] ?? "";

	if (empty($SelectedSemester))
	{
		$now = Carbon::now();
		$year = $now->year;
		$month = $now->month;

		$SelectedSemester = ($month >= 7)
			? "{$year}-" . ($year + 1)
			: ($year - 1) . "-{$year}";
		
		$semesterSelectTriggered = true;
	}

	$SelectedType = $_GET["type"] ?? "";
	$InputSearch = $_GET["search"] ?? "";
	$PageSearch = $_GET["page"] ?? "1";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/schedule.css?v=1.0">
	@endsection
	
	<x-slot name="title">Jadwal</x-slot>
	<x-slot name="icon">material-symbols-light:calendar-clock-outline-sharp</x-slot>
	<x-slot name="pagetitle">Jadwal Seminar & Sidang</x-slot>

	<x-input-wrapper class="filter" id="semester" type="select" label="Semester*" placeholder="Pilih Semester" onchange="UpdateQueryParam('semester', this.value, ['page']);">
		<option value="all" {{ ($SelectedSemester === "all" || $SelectedSemester === "" ? " selected" : "") }}>Semua Semester</option>
		
		@foreach ($semesterList as $code => $label)
			<option value="{{ $code }}" {{ ($SelectedSemester === $code ? " selected" : "") }}>{{ $label }}</option>
		@endforeach
	</x-input-wrapper>

	@if (isset($semesterSelectTriggered))
		<script>
			$("select#semester").trigger("change");
		</script>
	@endif

	<div class="top">
		<x-input-wrapper class="filter" id="type" type="select" placeholder="Pilih Jenis" onchange="UpdateQueryParam('type', this.value);">
			<option value="all" {{ ($SelectedType === "all" || $SelectedType === "" ? " selected" : "") }}>Semua Jenis</option>
			<option value="seminar" {{ ($SelectedType === "seminar" ? " selected" : "") }}>Seminar Proposal</option>
			<option value="thesisdefense" {{ ($SelectedType === "thesisdefense" ? " selected" : "") }}>Sidang Akhir</option>
		</x-input-wrapper>

		<x-input-wrapper class="search type-1" id="search" type="text" placeholder="Cari" value="{{ $InputSearch }}" oninput="UpdateQueryParam('search', this.value, ['page']);" autofocus />
	</div>

	<div class="middle">
		<table>
			<thead>
				<tr>
					<th>Jenis</th>
					<th>Judul</th>
					<th>Mahasiswa</th>
					<th>Pembimbing</th>
					<th>Penguji</th>
					<th>Jadwal Sidang</th>
				</tr>
			</thead>
			<tbody>
			@forelse ($dataSubmissions as $index => $item)
				<tr>
					<td>{{ ucfirst($item->submission_type) }}</td>
					<td>{{ $item->title }}</td>
					<td>{{ UserController::GetUsername($item->useridnumber) }}</td>
					<td>
						<ul>
							<li>{{ explode("-", $item->supervisor1)[0] }}</li>
							<li>{{ explode("-", $item->supervisor2)[0] }}</li>
						</ul>
					</td>
					<td>
						<ul>
							<li>Prof. Dr. Ir. Sudarsono Soedomo, MS</li>
							<li>Dr. Ir. Harnios Arief, M.Sc.F.Trop</li>
						</ul>
					</td>
					<td>
						<ul>
							<li>{{ $item->place }}</li>
							<li>{{ DateIndoFormatterController::Full($item->date, 1) }}</li>
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

	<div class="bottom-actions">
		<p>Menampilkan {{ $dataSubmissions->firstItem() }} sampai {{ $dataSubmissions->lastItem() }} dari {{ $dataSubmissions->total() ?? 0 }} data</p>

		<div class="buttons">
			@php
				$CurrentPage = $dataSubmissions->currentPage();
				$LastPage = $dataSubmissions->lastPage();
			@endphp

			@if ($CurrentPage <= 1)
				<x-button class="previous" value="previous" disabled>Sebelumnya</x-button>
			@else
				<x-button class="previous" value="previous" onclick="UpdateQueryParam('page', '{{ $CurrentPage > 1 ? $CurrentPage - 1 : 1 }}');">Sebelumnya</x-button>
			@endif

			@for ($i = 1; $i <= $LastPage; $i++)
				<x-button class="page{{ $CurrentPage === $i ? ' active' : '' }}" value="{{ $i }}" onclick="UpdateQueryParam('page', this.value);">{{ $i }}</x-button>
			@endfor
			
			@if ($CurrentPage >= $LastPage)
				<x-button class="next" value="next" disabled>Berikutnya</x-button>
			@else
				<x-button class="next" value="next" onclick="UpdateQueryParam('page', '{{ (isset($PageSearch) ? ((int)$PageSearch + 1) : 2) }}');">Berikutnya</x-button>
			@endif
		</div>
	</div>
</x-app-layout>