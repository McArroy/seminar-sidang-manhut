@props(["id" => null, "data", "IsThesisDefense" => null])

@php
use App\Http\Controllers\DateIndoFormatterController;
use App\Http\Controllers\UserController;

if ($id)
	$id = "pdf-source-" . $id;
else
	$id = "pdf-source";
@endphp

<x-letter-viewer id="{{ $id }}" title="Pratinjau Formulir {{ $IsThesisDefense ? 'Sidang Akhir' : 'Seminar' }}" sandbox="allow-scripts allow-same-origin allow-modals">
	<!DOCTYPE html>
	<html lang='en'>
		<head>
			<meta charset='UTF-8'>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<title>Berkas Pendaftaran {{ $IsThesisDefense ? 'Sidang Akhir' : 'Seminar' . ' - ' . UserController::GetUsername($data['useridnumber']) }}></title>
			<style>
				@page
				{
					margin: 0;
					size: A4;
				}

				html
				{
					align-items: center;
					display: flex;
					height: auto;
					justify-content: center;
					width: 100%;
				}

				html, body
				{
					margin: 0;
					padding: 0;
				}

				body
				{
					font-family: 'Calibri';
					font-size: 11pt;
					height: 297mm;
					position: relative;
					width: 210mm;
				}

				.letterhead
				{
					height: 100%;
					left: 0;
					position: absolute;
					top: 0;
					width: 100%;
					z-index: -1;
				}

				.wrapper
				{
					box-sizing: border-box;
					height: 100%;
					padding: 45mm 20mm 25mm 25mm;
					position: relative;
					width: 100%;
				}
				
				.main-table td
				{
					padding: 4px 0;
					vertical-align: top;
				}

				.label
				{
					width: 150px;
				}

				.colon
				{
					width: 10px;
				}

				.value-long
				{
					border-bottom: 1px solid black;
					height: 18px;
				}

				h2, h3
				{
					font-weight: bold;
					margin: 0;
					padding: 0;
				}

				.center
				{
					text-align: center;
				}
			</style>
		</head>
		<body>
			<div class='wrapper'>
				<img src='{{ \App\Http\Controllers\HelperController::Asset('assets/img/letter/letterhead.jpg') }}' alt='letterhead' class='letterhead'>
				<main>
					<h3 class='center' style='font-size: {{ $IsThesisDefense ? '16pt' : '22pt' }}; text-decoration: underline;'>FORMULIR {{ $IsThesisDefense ? 'PENDAFTARAN UJIAN AKHIR SARJANA' : 'SEMINAR' }}</h3>
					{{ !$IsThesisDefense ? '<br><br>' : '' }}
					<table class='main-table'>
						@if ($IsThesisDefense)
						<tr>
							<td colspan='3'>Yang bertanda tangan di bawah ini:</td>
						</tr>
						<tr>
							<td class='label'>Nama Mahasiswa</td>
							<td class='colon'>:</td>
							<td class='value'>{{ UserController::GetUsername($data['useridnumber']) }}</td>
						</tr>
						<tr>
							<td class='label'>NIM</td>
							<td class='colon'>:</td>
							<td class='value'>{{ strtoupper($data['useridnumber']) }}</td>
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
							<td class='value'>{{ DateIndoFormatterController::Full($data['date']) }}</td>
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
							<td class='value'>{{ UserController::GetUsername($data['useridnumber']) . ' / ' . strtoupper($data['useridnumber']) }}</td>
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
							<td class='value'>{{ DateIndoFormatterController::Full($data['date']) . ' / ' . $data['time'] }}</td>
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
								{{ !$IsThesisDefense ?
									'Diketahui oleh:<br>Dosen Pembimbing,' :
									'<br><br>Ketua Komisi Pembimbing,' }}
								<div style='margin-top: 87px;'>
									1. <span style='border-bottom: 1px solid black; display: inline-block; width: 200px;'>{{ explode(' - ', $data['supervisor1'])[1] }}</span><br>
									&nbsp;NIP&nbsp;&nbsp;{{ strtoupper(explode(' - ', $data['supervisor1'])[0]) }}
								</div>
								@if (!$IsThesisDefense)
								<div style='margin-top: 87px;'>
									2. <span style='border-bottom: 1px solid black; display: inline-block; width: 200px;'>{{ explode(' - ', $data['supervisor2'])[1] }}</span><br>
									&nbsp;NIP&nbsp;&nbsp;{{ strtoupper(explode(' - ', $data['supervisor2'])[0]) }}
								</div>
								@endif
							</td>
							@if ($IsThesisDefense)
							<td style='width: auto; vertical-align: top;'>
								<br><br><br><br><br><br><br><br><br>
								Menyetujui:
							</td>
							@endif
							<td style='width: 50%; vertical-align: top; '>
								<div style='float: right; margin-left: 90px ;'>
									Bogor, {{ DateIndoFormatterController::Today() }}<br>
									Seminaris,
									<div style='margin-top: 90px;'>
										{{ UserController::GetUsername($data['useridnumber']) }}
									</div>
									<div style='margin-top: 35px;'>
										{{ $IsThesisDefense ? '<br>Anggota Komisi Pembimbing' : 'Komisi AJMP dan Kemahasiswaan' }}
										<div style='margin-top: 90px;'>
											<span style='border-bottom: 1px solid black; display: inline-block; width: 200px;'>{{ (isset($IsThesisDefense) ? explode(' - ', $data['supervisor2'])[1] : '') }}</span><br>
											&nbsp;NIP&nbsp;&nbsp;{{ strtoupper(explode(' - ', $data['supervisor2'])[0]) }}
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