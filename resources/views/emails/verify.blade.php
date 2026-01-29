<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; background-color: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background-color: #0056b3; color: white; padding: 30px; text-align: center; }
        .header h2 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .body { padding: 40px 30px; text-align: left; }
        .body h3 { color: #0056b3; margin-top: 0; }
        .button-wrapper { text-align: center; margin: 30px 0; }
        .button { display: inline-block; padding: 14px 30px; background-color: #28a745; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; transition: background 0.3s; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; }
        .nik-box { background-color: #f1f8ff; padding: 10px; border-radius: 5px; border-left: 4px solid #0056b3; margin: 15px 0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>RSU ANNA MEDIKA</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.8;">Electronic Presence System</p>
        </div>
        <div class="body">
            <h3>Halo, {{ $name }}!</h3>
            <p>Selamat bergabung di keluarga besar RSU Anna Medika. Untuk dapat mulai melakukan absensi kehadiran, silakan verifikasi akun Anda.</p>
            
            <div class="nik-box">
                NIK Terdaftar: {{ $nik }}
            </div>

            <p>Klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>
            
            <div class="button-wrapper">
                <a href="{{ $url }}" class="button">Konfirmasi Akun Saya</a>
            </div>

            <p style="font-size: 13px; color: #666;">Jika Anda tidak merasa melakukan pendaftaran ini, silakan abaikan email ini atau hubungi bagian IT Support.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} <strong>IT Department RSU Anna Medika Madura</strong></p>
            <p>Jl. RE. Martadinata No.10, Bangkalan, Jawa Timur</p>
        </div>
    </div>
</body>
</html>