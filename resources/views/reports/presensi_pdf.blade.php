<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* Kop Surat Section */
        .kop-surat { border-bottom: 3px double #065f46; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .logo { position: absolute; top: 0; left: 0; width: 70px; }
        .header-text { text-align: center; margin-left: 70px; margin-right: 70px; }
        .header-text h2 { margin: 0; color: #065f46; text-transform: uppercase; font-size: 18px; }
        .header-text p { margin: 2px 0; font-size: 9px; color: #555; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background-color: #10b981; color: white; padding: 10px; text-align: center; border: 1px solid #065f46; text-transform: uppercase; }
        table.data-table td { padding: 8px; border: 1px solid #ddd; text-align: center; }
        table.data-table tr:nth-child(even) { background-color: #f0fdf4; }
        
        /* Footer & TTD */
        .footer-container { margin-top: 40px; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-space { height: 60px; }
        .print-info { margin-top: 50px; text-align: left; font-size: 9px; color: #777; clear: both; }
        
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <img src="{{ public_path('images/logors.png') }}" class="logo">
        <div class="header-text">
            <h2>RSU ANNA MEDIKA</h2>
            <p>Jl. R.E. Marthadinata, Wr 07, Mlajah, Kec. Bangkalan, Kabupaten Bangkalan, Jawa Timur 69116</p>
            <p>Telp: (031) 99303942 | Email: info@rsannamedika.com</p>
            <p style="font-weight: bold; font-size: 12px; margin-top: 5px; color: #333;">{{ strtoupper($title) }}</p>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Pegawai</td>
            <td width="2%">:</td>
            <td><strong>{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{ date('d F Y') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('DD/MM/Y') }}</td>
                <td>{{ $row->shift->nama_shift ?? '-' }}</td>
                <td>{{ $row->jam_masuk ?? '--:--' }}</td>
                <td>{{ $row->jam_pulang ?? '--:--' }}</td>
                <td style="font-weight: bold; color: {{ $row->status == 'Terlambat' ? '#b91c1c' : '#065f46' }}">
                    {{ strtoupper($row->status) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-container">
        <div class="ttd-box">
            <p>Bangkalan, {{ date('d F Y') }}</p>
            <p>Pegawai,</p>
            <div class="ttd-space"></div>
            <p><strong>( {{ $user->name }} )</strong></p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="print-info">
        <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Manajemen RS Anna Medika pada {{ date('d/m/Y H:i') }}.</p>
    </div>

</body>
</html>