// resources/views/admin/letters/announcement-seminar.blade.php

@php
    use App\Http\Controllers\DateIndoFormatterController;
@endphp

<x-app-layout>
    <x-slot name="pagetitle">Pratinjau Pengumuman Seminar</x-slot>
    <x-slot name="icon">fe:document</x-slot>

    <x-letter-viewer>
        <!DOCTYPE html>
        <html lang='id'>
        <head>
            <title>Pengumuman Seminar</title>
            <style>
                @page { size: A4; margin: 0; }
                body { font-family: 'Calibri', sans-serif; font-size: 11pt; position: relative; width: 210mm; height: 297mm; margin: 0; padding: 45mm 20mm 25mm 25mm; }
                .letterhead { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
                .content { position: relative; z-index: 1; }
                .header, .footer { text-align: center; }
                .title { font-size: 16pt; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
                .body-text { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                td { padding: 5px 0; vertical-align: top; }
                .signature-section { margin-top: 50px; text-align: right; }
                .date-location { margin-bottom: 100px; }
            </style>
        </head>
        <body>
            <img src="{{ url('/assets/img/letter/letterhead.jpg') }}" alt="letterhead" class="letterhead">
            <div class="content">
                <div class="header">
                    <p class="title">PENGUMUMAN</p>
                </div>

                <div class="body-text">
                    <p>Bersama ini diumumkan bahwa Seminar Penelitian Mahasiswa Program S1 Departemen Manajemen Hutan Fakultas Kehutanan dan Lingkungan IPB yang akan dilaksanakan pada:</p>
                </div>
                
                <table class="main-table">
                    <tr>
                        <td style="width: 25%;">Nama / NIM</td>
                        <td style="width: 5%;">:</td>
                        <td>{{ $data['academic']->username }} / {{ strtoupper($data['academic']->useridnumber) }}</td>
                    </tr>
                    <tr>
                        <td>Hari / Tanggal</td>
                        <td>:</td>
                        <td>{{ DateIndoFormatterController::Full($data['academic']->date) }}</td>
                    </tr>
                    <tr>
                        <td>Waktu / Tempat</td>
                        <td>:</td>
                        <td>{{ $data['academic']->time }} / {{ $data['academic']->room }}</td>
                    </tr>
                    <tr>
                        <td>Judul Tugas Akhir</td>
                        <td>:</td>
                        <td style="overflow-wrap: break-word; white-space: normal; text-align: justify;">{{ $data['academic']->title }}</td>
                    </tr>
                    <tr>
                        <td>Dosen Pembimbing</td>
                        <td>:</td>
                        <td>
                            <ol>
                                @foreach($data['academic']->lecturers as $lecturer)
                                <li>{{ explode(' - ', $lecturer)[1] }}</li>
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>Moderator</td>
                        <td>:</td>
                        <td>{{ explode(' - ', $data['letter']->moderator)[1] }}</td>
                    </tr>
                </table>

                <div class="signature-section">
                    <p class="date-location">Bogor, {{ DateIndoFormatterController::Simple($data['letter']->letterdate) }}</p>
                    <p>Ketua,</p>
                    <div style="margin-top: 50px;">
                        <p style="text-decoration: underline;">Dr. Soni Trison, S.Hut, M.Si</p>
                        <p>NIP. 197711232007011002</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
    </x-letter-viewer>
</x-app-layout>