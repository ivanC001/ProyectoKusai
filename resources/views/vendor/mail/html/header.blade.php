@props(['url'])
@php
    $mailLogoUrl = config('mail.logo_url');
    $resolvedLogoUrl = is_string($mailLogoUrl) && trim($mailLogoUrl) !== ''
        ? $mailLogoUrl
        : 'https://kusay.pe/assets/image/log4.png';
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
    <img src="{{ $resolvedLogoUrl }}" class="logo" alt="Logo Kusay.pe" style="max-height: 76px; width: auto; display: block;">
</a>
</td>
</tr>

