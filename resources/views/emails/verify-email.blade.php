<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi email Bonsaiku</title>
</head>
<body style="margin:0;background:#f5f5f0;color:#2d3e2f;font-family:Arial,Helvetica,sans-serif;line-height:1.6;">
  <div style="padding:32px 16px;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid rgba(45,62,47,.14);">
      <div style="padding:26px 32px;background:#2d3e2f;color:#f5f5f0;">
        <div style="font-size:24px;font-weight:700;letter-spacing:.02em;">bonsaiku</div>
        <div style="margin-top:4px;font-size:12px;color:#d8ddd6;">Marketplace bonsai pilihan</div>
      </div>

      <div style="padding:36px 32px;">
        <p style="margin:0 0 20px;font-size:16px;">Halo {{ $user->name }},</p>
        <h1 style="margin:0 0 14px;font-size:25px;line-height:1.3;color:#2d3e2f;">Verifikasi email Anda</h1>
        <p style="margin:0 0 24px;color:#536057;">
          Terima kasih sudah mendaftar di Bonsaiku. Klik tombol di bawah untuk mengaktifkan akun Anda.
        </p>

        <div style="margin:0 0 28px;">
          <a href="{{ $verificationUrl }}" style="display:inline-block;padding:13px 22px;background:#2d3e2f;color:#f5f5f0;text-decoration:none;font-size:14px;font-weight:700;">
            Verifikasi email
          </a>
        </div>

        <p style="margin:0 0 10px;font-size:13px;color:#69746b;">Tautan ini berlaku selama 60 menit.</p>
        <p style="margin:0;font-size:13px;color:#69746b;">Jika Anda tidak merasa membuat akun, abaikan email ini.</p>

        <div style="margin-top:28px;padding-top:20px;border-top:1px solid #e5e8e3;">
          <p style="margin:0 0 8px;font-size:12px;color:#69746b;">Jika tombol tidak berfungsi, buka tautan berikut:</p>
          <a href="{{ $verificationUrl }}" style="font-size:12px;color:#8a7d55;word-break:break-all;">{{ $verificationUrl }}</a>
        </div>
      </div>

      <div style="padding:18px 32px;background:#f5f5f0;color:#69746b;font-size:12px;">
        Email ini dikirim otomatis oleh Bonsaiku.
      </div>
    </div>
  </div>
</body>
</html>
