<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# Oops!
@else
# Halo!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
<p style="color:#333; font-size:16px;">{{ $line }}</p>
@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = '#e6a43b';
?>
<x-mail::button :url="$actionUrl" style="background-color: #e6a43b; border-radius:8px; color:#fff; font-weight:bold; padding:12px 24px; font-size:16px;">
    {{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
<p style="color:#333; font-size:16px;">{{ $line }}</p>
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Salam hangat,<br>
<b>{{ config('app.name') }}</b>
@endif

{{-- Footer --}}
<hr style="border:0; border-top:1px solid #e6a43b; margin:32px 0 16px 0;">
<p style="font-size:13px; color:#888; text-align:center;">Email ini dikirim otomatis oleh sistem GASSOR. Jika Anda tidak meminta reset kata sandi, abaikan email ini.</p>

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
