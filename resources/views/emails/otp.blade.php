<x-mail::message>
  # Halo!

  Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi (reset password) untuk akun Anda di
  **{{ $appName }}**.

  Gunakan kode verifikasi di bawah ini untuk melanjutkan proses reset password:

  <x-mail::panel>
    <h1 style="text-align: center; letter-spacing: 5px; font-size: 32px; color: #4f46e5; margin: 0;">{{ $otp }}</h1>
  </x-mail::panel>

  Kode ini akan kadaluarsa dalam **5 menit**.

  Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Keamanan akun Anda tetap terjaga selama Anda tidak
  memberikan kode ini kepada siapapun.

  Terima kasih,<br>
  Tim IT **{{ $appName }}**
</x-mail::message>