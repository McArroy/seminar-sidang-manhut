// resources/views/admin/letters/invitation-thesis.blade.php

@php
    use App\Http\Controllers\DateIndoFormatterController;
@endphp

<x-app-layout>
    <x-slot name="pagetitle">Pratinjau Undangan Sidang</x-slot>
    <x-slot name="icon">fe:document</x-slot>

    <x-letter-viewer>
        <!DOCTYPE html>
        <html lang='id'>
        <head>
            <title>Undangan Ujian Akhir Sarjana</title>
            <style>
                @page { size: A4; margin: 0; }
                body { font-family: 'Calibri', sans-serif; font-size: 11pt; position: relative; width: 210mm; height: 297mm; margin: 0; padding: 45mm 20mm 25mm 25mm; }
                .letterhead { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
                .content { position: relative; z-index: 1; }
                .title { font-size: 14pt; font-weight: bold; text-align: center; text-decoration: underline; margin-bottom: 20px; }
                .header-info { margin-bottom: 20px; }
                .header-info p { margin: 2px 0; }
                table { width: 100%; border-collapse: collapse; }
                td { padding: 5px 0; vertical-align: top; }
                .signature-section { margin-top: 50px; text-align: right; }
                .date-location { margin-bottom: 100px; }
            </style>
        </head>
        <body>
            <img src="{{ url('/assets/img/letter/letterhead.jpg') }}" alt="letterhead" class="letterhead">
            <div class="content">
                <div class="header-info">
                    <p>No. : {{ $data['letter']->letternumber }}</p>
                    <p>Lampiran : Satu berkas draf skripsi</p>
                    <p>Perihal : Ujian Akhir Sarjana</p>
                    <p style="margin-top: 20px;">Kepada Yth. :</p>
                    <p style="margin-left: 20px;">Ketua Komisi Pembimbing</p>
                    <p style="margin-left: 20px;">Penguji Luar Komisi</p>
                    <p style="margin-left: 20px;">Ketua Sidang</p>
                </div>
                
                <p>Dengan ini kami mengharapkan kehadiran saudara untuk menjadi Penguji Ujian Akhir Sarjana mahasiswa Departemen Manajemen Hutan :</p>
                
                <table class="main-table">
                    <tr>
                        <td style="width: 25%;">Nama</td>
                        <td style="width: 5%;">:</td>
                        <td>{{ $data['academic']->username }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ strtoupper($data['academic']->useridnumber) }}</td>
                    </tr>
                    <tr>
                        <td>Judul Skripsi</td>
                        <td>:</td>
                        <td style="overflow-wrap: break-word; white-space: normal; text-align: justify;">{{ $data['academic']->title }}</td>
                    </tr>
                    <tr>
                        <td>Hari/Tanggal</td>
                        <td>:</td>
                        <td>{{ DateIndoFormatterController::Full($data['academic']->date) }}</td>
                    </tr>
                    <tr>
                        <td>Pukul/Tempat</td>
                        <td>:</td>
                        <td>{{ $data['academic']->time }} / {{ $data['academic']->room }}</td>
                    </tr>
                </table>

                <p style="margin-top: 20px;">Besar harapan kami, saudara dapat hadir tepat pada waktunya. Atas perhatiannya kami mengucapkan terima kasih.</p>

                <div class="signature-section">
                    <p class="date-location">Bogor, {{ DateIndoFormatterController::Simple($data['letter']->letterdate) }}</p>
                    <p>Ketua Sidang,</p>
                    <div style="margin-top: 50px;">
                        <p style="text-decoration: underline;">{{ explode(' - ', $data['letter']->chairman_session)[1] }}</p>
                        <p>NIP. {{ strtoupper(explode(' - ', $data['letter']->chairman_session)[0]) }}</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
    </x-letter-viewer>
</x-app-layout>