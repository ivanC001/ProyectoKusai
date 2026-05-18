@props(['url'])
@php
    $mailLogoUrl = config('mail.logo_url');
    $resolvedLogoUrl = is_string($mailLogoUrl) && trim($mailLogoUrl) !== ''
        ? $mailLogoUrl
        : 'https://kusay.pe/favicon.ico';
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
    <span style="width: 46px; height: 46px; border-radius: 999px; background: #133f2f; border: 2px solid #2b7b55; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 6px 14px rgba(16, 57, 40, .22);">
        <img src="{{ $resolvedLogoUrl }}" class="logo" alt="Logo Kusay.pe" style="width: 26px; height: 26px; border-radius: 999px;">
    </span>
    <span style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 28px; font-weight: 900; line-height: 1; letter-spacing: .06em; color: #0f3325;">
        KUSAY<span style="color:#df8347;">.</span><span style="color:#1b6c49;">PE</span>
    </span>
</a>
</td>
</tr>

