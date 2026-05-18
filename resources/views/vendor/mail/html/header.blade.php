@props(['url'])
@php
    $mailLogoUrl = config('mail.logo_url');
    $resolvedLogoUrl = is_string($mailLogoUrl) && trim($mailLogoUrl) !== ''
        ? $mailLogoUrl
        : 'https://kusay.pe/assets/image/log4.png';
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
    <span style="width: 52px; height: 52px; border-radius: 999px; background: #133f2f; border: 2px solid #2b7b55; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 6px 14px rgba(16, 57, 40, .22); overflow: hidden;">
        <img src="{{ $resolvedLogoUrl }}" class="logo" alt="Logo Kusay.pe" style="width: 100%; height: 100%; object-fit: cover;">
    </span>
    <span style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 28px; font-weight: 900; line-height: 1; letter-spacing: .06em; color: #0f3325;">
        KUSAY<span style="color:#df8347;">.</span><span style="color:#1b6c49;">PE</span>
    </span>
</a>
</td>
</tr>

