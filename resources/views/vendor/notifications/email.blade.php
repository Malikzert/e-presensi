<x-mail::message>
{{-- LOGIKA DECISION JUDUL --}}
@if (isset($actionText) && (str_contains(strtolower($actionText), 'sandi') || str_contains(strtolower($actionText), 'password')))
# Pemulihan Kata Sandi
@elseif (isset($actionText) && (str_contains(strtolower($actionText), 'verifikasi') || str_contains(strtolower($actionText), 'verify')))
# Verifikasi Akun Baru
@else
# @lang('Halo!')
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}
@endforeach

{{-- LOGIKA DECISION TOMBOL --}}
@isset($actionText)
<?php
    // Warna 'error' (Merah) untuk Reset, 'success' (Hijau Emerald) untuk Verifikasi
    $isReset = str_contains(strtolower($actionText), 'sandi') || str_contains(strtolower($actionText), 'password');
    $color = $isReset ? 'error' : 'success'; 
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Salam hangat,<br>
**IT RSU Anna Medika**
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Jika Anda mengalami kendala saat menekan tombol "{{ $actionText }}", silakan salin dan tempel tautan di bawah ini ke browser Anda:
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>