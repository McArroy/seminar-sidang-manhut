@php
use App\Http\Controllers\DateIndoFormatter;
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/registrationform.css?v=1.0">
	@endsection

	@section("activate-navbar", "active")

	@php
		if (!in_array($_GET["type"] ?? null, ["seminar", "thesisdefense"]))
		{
			header("Location: " . url()->current() . "?type=seminar");
			exit;
		}
	@endphp

	<x-slot name="title">Form Pendaftaran</x-slot>
	<x-slot name="icon">basil:document-outline</x-slot>

	@if ($_GET["type"] === "seminar")

	<x-slot name="pagetitle">Form Pendafataran Seminar Proposal</x-slot>

	@elseif ($_GET["type"] === "thesisdefense")

	@php $IsThesisDefense = true; @endphp

	<x-slot name="pagetitle">Form Pendafataran Sidang Akhir</x-slot>

	@endif

	<div class="letter">
		<p>Berkas form pendaftaran {{ (isset($IsThesisDefense) ? "sidang akhir" : "seminar") }} berhasil dibuat, harap <span>download</span> file berikut dan <span>lengkapi tanda tangan yang tertera</span>.</p>
		<p>Setelah berkas form pendaftaran {{ (isset($IsThesisDefense) ? "sidang akhir" : "seminar") }} ditandatangani, <span>upload kembali</span> di Menu <span>Persyaratan {{ (isset($IsThesisDefense) ? "Sidang" : "Seminar") }}</span> bersama berkas pendukung lainnya.</p>
		<x-letter-viewer id="pdf-source" title="Preview Berkas {{ (isset($IsThesisDefense) ? 'Sidang Akhir' : 'Seminar') }}" sandbox="allow-scripts allow-same-origin allow-modals">
			<!DOCTYPE html>
			<html lang='en'>
				<head>
					<meta charset='UTF-8'>
					<meta name='viewport' content='width=device-width, initial-scale=1.0'>
					<title>Berkas Pendaftaran {{ (isset($IsThesisDefense) ? 'Sidang Akhir' : 'Seminar') . ' - ' . Auth::user()->username }}></title>
					<style>
						@page {
							size: A4;
							margin: 0;
						}
						html, body {
							width: 210mm;
							height: 297mm;
							margin: 0;
							padding: 0;
						}
						body {
							position: relative;
							font-family: 'Calibri';
							font-size: 11pt;
						}
						.letterhead {
							height: 100%;
							left: 0;
							position: absolute;
							top: 0;
							width: 100%;
							z-index: -1;
						}
						.wrapper {
							width: 100%;
							height: 100%;
							position: relative;
							padding: 45mm 20mm 25mm 25mm;
							box-sizing: border-box;
						}
						.footer {
							position: absolute;
							bottom: 25mm;
							left: 0;
							width: 100%;
							text-align: center;
							font-size: 10pt;
							font-style: italic;
						}
						.main-table td {
							padding: 4px 0;
							vertical-align: top;
						}
						.label { width: 150px; }
						.colon { width: 10px; }
						.value-long { border-bottom: 1px solid black; height: 18px; }
						h2, h3 { font-weight: bold; margin: 0; padding: 0; }
						.center { text-align: center; }
					</style>
				</head>
				<body>
					<div class='wrapper'>
						<img src='/assets/img/letter/letterhead.jpg' alt='letterhead' class='letterhead'>
						<main>
							<h3 class='center' style='font-size: {{ (isset($IsThesisDefense) ? '16pt' : '22pt') }}; text-decoration: underline;'>FORMULIR {{ (isset($IsThesisDefense) ? 'PENDAFTARAN UJIAN AKHIR SARJANA' : 'SEMINAR') }}</h3>
							{{ (!isset($IsThesisDefense) ? '<br><br>' : '') }}
							<table class='main-table'>
								@if (isset($IsThesisDefense))
								<tr>
									<td colspan='3'>Yang bertanda tangan di bawah ini:</td>
								</tr>
								<tr>
									<td class='label'>Nama Mahasiswa</td>
									<td class='colon'>:</td>
									<td class='value'>{{ Auth::user()->username }}</td>
								</tr>
								<tr>
									<td class='label'>NIM</td>
									<td class='colon'>:</td>
									<td class='value'>{{ Auth::user()->useridnumber }}</td>
								</tr>
								<tr>
									<td class='label'>Semester</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['semester'] }}</td>
								</tr>
								<tr>
									<td class='label'>Alamat di Bogor</td>
									<td class='colon'>:</td>
									<td class='value' style= 'overflow-wrap: break-word ; white-space : normal ; text-align: justify;'>{{ $data['address'] }}</td>
								</tr>
								<tr>
									<td colspan='3'>Bermaksud akan melaksanakan ujian akhir pada:</td>
								</tr>
								<tr>
									<td class='label'>Hari / Tanggal</td>
									<td class='colon'>:</td>
									<td class='value'>{{ DateIndoFormatter::Full($data['date']) }}</td>
								</tr>
								<tr>
									<td class='label'>Waktu</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['time'] }}</td>
								</tr>
								<tr>
									<td class='label'>Tempat / Ruangan</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['place'] }}</td>
								</tr>
								<tr>
									<td class='label'>Judul Skripsi</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['title'] }}</td>
								</tr>
								@else
								<tr>
									<td class='label'>Nama / NRP</td>
									<td class='colon'>:</td>
									<td class='value'>{{ Auth::user()->username . ' / ' . Auth::user()->useridnumber }}</td>
								</tr>
								<tr>
									<td class='label'>Program Studi</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['studyprogram'] }}</td>
								</tr>
								<tr>
									<td class='label'>Departemen</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['department'] }}</td>
								</tr>
								<tr>
									<td colspan='3' style='padding-top: 10px;'>Akan melaksanakan seminar pada:</td>
								</tr>
								<tr>
									<td class='label'>Hari / Tanggal / Jam</td>
									<td class='colon'>:</td>
									<td class='value'>{{ DateIndoFormatter::Full($data['date']) . ' / ' . $data['time'] }}</td>
								</tr>
								<tr>
									<td class='label'>Tempat / Ruangan</td>
									<td class='colon'>:</td>
									<td class='value'>{{ $data['place'] }}</td>
								</tr>
								<tr>
									<td class='label'>Judul Skripsi</td>
									<td class='colon'>:</td>
									<td class='value' style= 'overflow-wrap: break-word ; white-space : normal ; text-align: justify;'>{{ $data['title'] }}</td>
								</tr>
								@endif
							</table>
							<table class='signature-table'>
								<tr>
									<td style='width: 50%; vertical-align: top;'>
										<br><br><br><br><br><br><br><br><br>
										{{ (!isset($IsThesisDefense) ?
											'Diketahui oleh:<br>Dosen Pembimbing,' :
											'<br><br>Ketua Komisi Pembimbing,') }}
										<div style='margin-top: 87px;'>
											1. <span style='border-bottom: 1px solid black; display: inline-block; width: 200px;'>{{ explode('-', $data['supervisor1'])[0] }}</span><br>
											&nbsp;NIP&nbsp;&nbsp;{{ explode('-', $data['supervisor1'])[1] }}
										</div>
										@if (!isset($IsThesisDefense))
										<div style='margin-top: 87px;'>
											2. <span style='border-bottom: 1px solid black; display: inline-block; width: 200px;'>{{ explode('-', $data['supervisor2'])[0] }}</span><br>
											&nbsp;NIP&nbsp;&nbsp;{{ explode('-', $data['supervisor2'])[1] }}
										</div>
										@endif
									</td>
									@if (isset($IsThesisDefense))
									<td style='width: auto; vertical-align: top;'>
										<br><br><br><br><br><br><br><br><br>
										Menyetujui:
									</td>
									@endif
									<td style='width: 50%; vertical-align: top; '>
										<div style='float: right; margin-left: 90px ;'>
											Bogor, {{ DateIndoFormatter::Today() }}<br>
											Seminaris,
											<div style='margin-top: 90px;'>
												{{ Auth::user()->username }}
											</div>
											<div style='margin-top: 35px;'>
												{{ (isset($IsThesisDefense) ? '<br>Anggota Komisi Pembimbing' : 'Komisi AJMP dan Kemahasiswaan') }}
												<div style='margin-top: 90px;'>
													<span style='border-bottom: 1px solid black; display: inline-block; width: 200px;'>{{ (isset($IsThesisDefense) ? explode('-', $data['supervisor2'])[0] : '') }}</span><br>
													&nbsp;NIP&nbsp;&nbsp;{{ (isset($IsThesisDefense) ? explode('-', $data['supervisor2'])[1] : '') }}
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</main>
					</div>
				</body>
			</html>
		</x-letter-viewer>
	</div>
</x-app-layout>