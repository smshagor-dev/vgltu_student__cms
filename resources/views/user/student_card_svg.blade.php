@php
    $name = $user->full_name ?: 'Student Name';
    $department = $user->department ?: 'Department not available';
    $email = $user->email ?: 'N/A';
    $mobile = $user->mobile_number ?: 'N/A';
    $room = $user->room_number ?: 'N/A';
    $dob = $user->date_of_birth ?: 'N/A';
    $initial = strtoupper(mb_substr($name, 0, 1));
@endphp
<svg width="900" height="560" viewBox="0 0 900 560" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="cardGradient" x1="84" y1="40" x2="842" y2="520" gradientUnits="userSpaceOnUse">
            <stop stop-color="#1A1022"/>
            <stop offset="0.55" stop-color="#3B1E45"/>
            <stop offset="1" stop-color="#A33567"/>
        </linearGradient>
        <linearGradient id="photoGlow" x1="0" y1="0" x2="1" y2="1">
            <stop stop-color="rgba(255,255,255,0.28)"/>
            <stop offset="1" stop-color="rgba(255,255,255,0.08)"/>
        </linearGradient>
        <filter id="cardShadow" x="20" y="10" width="860" height="540" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feDropShadow dx="0" dy="18" stdDeviation="24" flood-color="#201126" flood-opacity="0.26"/>
        </filter>
    </defs>

    <rect width="900" height="560" rx="28" fill="#F4EDF3"/>

    <g filter="url(#cardShadow)">
        <rect x="42" y="32" width="816" height="496" rx="30" fill="url(#cardGradient)"/>
        <rect x="42.75" y="32.75" width="814.5" height="494.5" rx="29.25" stroke="rgba(255,255,255,0.16)" stroke-width="1.5"/>
    </g>

    <rect x="72" y="64" width="214" height="34" rx="17" fill="rgba(255,255,255,0.12)"/>
    <text x="92" y="86" fill="#FFFFFF" font-size="12" font-family="Arial, sans-serif" font-weight="700" letter-spacing="1.8">OFFICIAL STUDENT CARD</text>

    <text x="72" y="130" fill="#FFFFFF" font-size="30" font-family="Arial, sans-serif" font-weight="700">VGLTU Asian Student Forum</text>

    <rect x="72" y="166" width="234" height="234" rx="28" fill="url(#photoGlow)" stroke="rgba(255,255,255,0.14)" stroke-width="1.5"/>

    @if($photoDataUri)
        <clipPath id="photoClip">
            <rect x="84" y="178" width="210" height="210" rx="22"/>
        </clipPath>
        <image x="84" y="178" width="210" height="210" href="{{ $photoDataUri }}" clip-path="url(#photoClip)" preserveAspectRatio="xMidYMid slice"/>
    @else
        <rect x="84" y="178" width="210" height="210" rx="22" fill="rgba(255,255,255,0.10)"/>
        <text x="189" y="302" text-anchor="middle" fill="#FFFFFF" font-size="84" font-family="Arial, sans-serif" font-weight="700">{{ $initial }}</text>
    @endif

    <foreignObject x="72" y="420" width="250" height="72">
        <div xmlns="http://www.w3.org/1999/xhtml" style="font-family: Arial, sans-serif; color: #FFFFFF;">
            <div style="font-size: 31px; font-weight: 700; line-height: 1.12; max-height: 38px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $name }}</div>
            <div style="margin-top: 8px; font-size: 17px; line-height: 1.35; color: rgba(255,255,255,0.78); max-height: 44px; overflow: hidden;">{{ $department }}</div>
        </div>
    </foreignObject>

    <foreignObject x="344" y="112" width="476" height="336">
        <div xmlns="http://www.w3.org/1999/xhtml" style="font-family: Arial, sans-serif; color: #FFFFFF; width: 100%; height: 100%;">
            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; width: 100%; height: 100%;">
                <div style="min-height: 136px; padding: 18px 18px 16px; border-radius: 22px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.12); box-sizing: border-box;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.68);">Email</div>
                    <div style="margin-top: 14px; font-size: 20px; font-weight: 600; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere;">{{ $email }}</div>
                </div>
                <div style="min-height: 136px; padding: 18px 18px 16px; border-radius: 22px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.12); box-sizing: border-box;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.68);">Mobile Number</div>
                    <div style="margin-top: 14px; font-size: 20px; font-weight: 600; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere;">{{ $mobile }}</div>
                </div>
                <div style="min-height: 136px; padding: 18px 18px 16px; border-radius: 22px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.12); box-sizing: border-box;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.68);">Room Number</div>
                    <div style="margin-top: 14px; font-size: 20px; font-weight: 600; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere;">{{ $room }}</div>
                </div>
                <div style="min-height: 136px; padding: 18px 18px 16px; border-radius: 22px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.12); box-sizing: border-box;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.68);">Date of Birth</div>
                    <div style="margin-top: 14px; font-size: 20px; font-weight: 600; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere;">{{ $dob }}</div>
                </div>
            </div>
        </div>
    </foreignObject>

    <rect x="72" y="492" width="756" height="1.5" fill="rgba(255,255,255,0.16)"/>
    <foreignObject x="72" y="503" width="756" height="28">
        <div xmlns="http://www.w3.org/1999/xhtml" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 1.4; color: rgba(255,255,255,0.76);">
            Generated from the official VGLTU student portal for student identity reference.
        </div>
    </foreignObject>
</svg>
