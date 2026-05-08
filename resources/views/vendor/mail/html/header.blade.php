@props(['url'])
@php
    $mailLogoUrl = config('mail.logo_url');
    $resolvedLogoUrl = is_string($mailLogoUrl) && trim($mailLogoUrl) !== ''
        ? $mailLogoUrl
        : url('/assets/image/png 2.png');
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $resolvedLogoUrl }}" class="logo" alt="Logo Kusay.pe" style="max-height: 74px; width: auto;">
</a>
</td>
</tr>
