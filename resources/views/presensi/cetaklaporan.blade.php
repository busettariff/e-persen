<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabledataPegawai {
            margin-top: 40px;
        }

        .tabledataPegawai tr td {
            padding: 5px;
        }

        .tablepresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tablepresensi tr th {
            border: 2px solid black;
            padding: 8px;
            text-align: center;
            background-color: white;
        }

        .tablepresensi tr td {
            border: 2px solid black;
            padding: 5px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width:30px">
                    <img src="{{ asset('assets/img/login/casper.png') }}" width="100" height="100" alt="">
                </td>
                <td>

                    <span id="title">
                        LAPORAN PRESENSI GURU <br>
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} <br>
                        SMK PERTIWI KUNINGAN
                    </span> <br>
                    <span>Jl. Siliwangi No. 26A Kasturi Kuningan 45512</span>
                </td>
            </tr>
        </table>
        <table class="tabledataPegawai">
            <tr>
                <td rowspan="6">
                    @php
                    $path = Storage::url('uploads/pegawai/'.$user->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="200px" height="200px">
                </td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <td>Nama Guru</td>
                <td>:</td>
                <td>{{ $user->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $user->jabatan }}</td>
            </tr>
            <tr>
                <td>Mata Pelajaran</td>
                <td>:</td>
                <td>{{ $user->nama_mapel }}</td>
            </tr>
            <tr>
                <td>No.HP</td>
                <td>:</td>
                <td>{{ $user->no_hp }}</td>
            </tr>
        </table>
        <table class="tablepresensi">
            <tr>
                <th>NO.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Keterangan</th>
            </tr>
            @foreach ($presensi as $d )
            @php
            $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
            $path_out = Storage::url('uploads/absensi/'.$d->foto_out);
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</td>
                <td>{{ $d->jam_in }}</td>
                <td><img src="{{ url($path_in) }}" alt="" width="50px" height="50px"></td>
                <td>{{ $d->jam_out !== null ? $d->jam_out : 'Belum Absen'}}</td>
                <td>
                    @if($d->jam_out !== null)
                    <img src="{{ url($path_out) }}" alt="" width="50px" height="50px">
                </td>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hourglass-high" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6.5 7h11" />
                    <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" />
                    <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" />
                </svg>
                @endif


                <td>
                    @if($d->jam_in > '07:00')
                    Terlambat
                    @else
                    Tepat Waktu
                    @endif
                </td>

            </tr>
            @endforeach
            <!-- Add your data rows here -->
        </table>
        <table width="100%" style="margin-top:100px">
            <tr>
                <td colspan="2" style="text-align: right">Kuningan, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align:bottom; height: 100px;">
                    <u>Name</u><br>
                    <i><b>Sekretaris</b></i>
                </td>
                <td style="text-align: center; vertical-align:bottom;">
                    <u>Name</u><br>
                    <i><b>Kepala Sekolah</b></i>
                </td>
            </tr>

        </table>
    </section>

</body>

</html>
