@php
    $footerSystemLinks = [
        ['label' => 'Home', 'url' => route('welcome')],
        ['label' => 'University', 'url' => route('about-university')],
        ['label' => 'Courses', 'url' => route('courses.page')],
        ['label' => 'Contact', 'url' => route('contact.page')],
    ];
    $footerServiceLinks = [
        ['label' => 'View All Student', 'url' => route('students.all')],
        ['label' => 'View Passed Student', 'url' => route('passed-students.all')],
        ['label' => 'View All Image', 'url' => route('photo-galleries.all')],
        ['label' => 'View All Videos', 'url' => route('video-galleries.all')],
    ];
    $footerSocialLinks = $settings['footer_social_links'] ?? [
        ['label' => 'Facebook', 'icon_class' => 'fab fa-facebook-f', 'url' => route('welcome'), 'icon_url' => null],
        ['label' => 'WhatsApp', 'icon_class' => 'fab fa-whatsapp', 'url' => 'https://wa.me/79954949836', 'icon_url' => null],
        ['label' => 'Instagram', 'icon_class' => 'fab fa-instagram', 'url' => route('welcome'), 'icon_url' => null],
    ];
@endphp

<style>
    .edu-footer {
        margin-top: 4rem;
        padding: 0 0 1.2rem;
    }

    .edu-footer__panel {
        position: relative;
        padding: 2.2rem 2rem 1.1rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(255, 252, 250, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.85);
        border-radius: 24px;
        box-shadow:
            0 26px 60px rgba(60, 43, 58, 0.12),
            0 8px 22px rgba(60, 43, 58, 0.06),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(16px);
        overflow: hidden;
    }


    .edu-footer__panel::before,
    .edu-footer__panel::after {
        content: "";
        position: absolute;
        pointer-events: none;
    }

    .edu-footer__panel::before {
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, rgba(215, 89, 139, 0.78), rgba(242, 171, 114, 0.58), rgba(95, 138, 255, 0.6));
    }

    .edu-footer__panel::after {
        right: 24px;
        bottom: 18px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(215, 89, 139, 0.08), transparent 68%);
    }

    .edu-footer h5 {
        margin: 0;
        color: #1f1824;
    }

    .edu-footer__inner {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) repeat(2, minmax(0, 0.92fr));
        align-items: flex-start;
        gap: 2rem;
        padding: 0 0 1.75rem;
        border-bottom: 1px solid rgba(35, 23, 38, 0.08);
    }

    .edu-footer__cta {
        max-width: 100%;
        min-height: 100%;
        padding: 1.4rem 1.45rem;
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(255, 247, 241, 0.92), rgba(255, 255, 255, 0.88));
        border: 1px solid rgba(35, 23, 38, 0.06);
        box-shadow:
            0 16px 32px rgba(76, 42, 65, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .edu-footer__eyebrow {
        display: inline-block;
        margin-bottom: 0.7rem;
        color: #bb3e71;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .edu-footer__cta h5 {
        margin-bottom: 1rem;
        font-size: clamp(2.05rem, 3vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.045em;
        line-height: 1;
        max-width: 8.5ch;
    }

    .edu-footer__description {
        margin: 0 0 1.1rem;
        color: #6f6572;
        font-size: 0.96rem;
        line-height: 1.75;
        max-width: 36ch;
    }

    .edu-footer__location {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 1.4rem;
        color: #5b5360;
        font-size: 0.9rem;
        line-height: 1.5;
        padding: 0.7rem 0.9rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.76);
        border: 1px solid rgba(35, 23, 38, 0.06);
    }

    .edu-footer__location i {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(215, 89, 139, 0.12);
        color: #bb3e71;
        flex-shrink: 0;
    }

    .edu-footer__signup-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 0.88rem 1.6rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        letter-spacing: 0.01em;
        box-shadow: 0 14px 30px rgba(187, 62, 113, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
    }

    .edu-footer__signup-btn:hover {
        transform: translateY(-2px);
        color: #ffffff;
        background: linear-gradient(135deg, #bb3e71, #241726);
        box-shadow: 0 18px 34px rgba(187, 62, 113, 0.3);
    }

    .edu-footer__cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .edu-footer__outline-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 0.88rem 1.6rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(35, 23, 38, 0.1);
        color: #241726;
        text-decoration: none;
        font-weight: 700;
        letter-spacing: 0.01em;
        box-shadow: 0 14px 28px rgba(76, 42, 65, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
    }

    .edu-footer__outline-btn:hover {
        transform: translateY(-2px);
        color: #bb3e71;
        box-shadow: 0 18px 34px rgba(76, 42, 65, 0.12);
    }

    .alumni-modal .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 26px 60px rgba(15, 23, 42, 0.18);
    }

    .alumni-modal .modal-header {
        padding: 22px 24px 18px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 58%, #0f766e 100%);
        color: #fff;
    }

    .alumni-modal .modal-title {
        color: #fff;
        font-weight: 800;
    }

    .alumni-modal .btn-close {
        filter: invert(1);
    }

    .alumni-modal .modal-body {
        padding: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .alumni-form-grid {
        display: grid;
        gap: 16px;
    }

    .alumni-repeat-list {
        display: grid;
        gap: 12px;
    }

    .alumni-repeat-row {
        display: grid;
        gap: 10px;
        grid-template-columns: minmax(0, 1fr) auto;
    }

    .edu-footer__nav {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
        min-height: 100%;
        padding: 1.25rem 1.25rem 1.15rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.68);
        border: 1px solid rgba(35, 23, 38, 0.06);
        box-shadow: 0 12px 28px rgba(76, 42, 65, 0.05);
    }

    .edu-footer__label {
        color: #1f1824;
        font-size: 0.84rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .edu-footer__menu-list {
        display: grid;
        gap: 0.9rem;
        width: 100%;
    }

    .edu-footer__menu-list a {
        position: relative;
        padding-left: 1rem;
        color: #5f5663;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.55;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .edu-footer__menu-list a::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0.72em;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: rgba(187, 62, 113, 0.45);
        transform: translateY(-50%);
    }

    .edu-footer__menu-list a:hover {
        color: #241726;
        transform: translateX(2px);
    }

    .edu-footer__bottom {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding-top: 1rem;
        color: #756b79;
        font-size: 0.88rem;
    }

    .edu-footer__meta,
    .edu-footer__credit {
        line-height: 1.7;
    }

    .edu-footer__credit a {
        color: #241726;
        font-weight: 700;
        text-decoration: none;
    }

    .edu-footer__credit a:hover {
        color: #bb3e71;
    }

    .edu-footer__social {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.7rem;
    }

    .edu-footer__social a {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(35, 23, 38, 0.08);
        color: #241726;
        text-decoration: none;
        box-shadow: 0 10px 22px rgba(76, 42, 65, 0.08);
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .edu-footer__social a:hover {
        transform: translateY(-1px);
        color: #bb3e71;
        background: #fff4f7;
        border-color: rgba(187, 62, 113, 0.16);
        box-shadow: 0 14px 26px rgba(76, 42, 65, 0.12);
    }

    .edu-footer__social img {
        width: 20px;
        height: 20px;
        object-fit: contain;
        display: block;
    }

    @media (max-width: 991px) {
        .edu-footer__inner {
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }
    }

    @media (max-width: 767px) {
        .edu-footer__panel {
            padding: 1.6rem 1rem 1rem;
        }

        .edu-footer__cta-actions {
            flex-direction: column;
        }

        .edu-footer__cta,
        .edu-footer__nav {
            padding: 1rem;
        }

        .edu-footer__bottom {
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .edu-footer__social {
            width: 100%;
            justify-content: flex-start;
        }

        .alumni-repeat-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<footer class="edu-footer" id="contact">
    <div class="container">
        <div class="edu-footer__panel">
            <div class="edu-footer__inner">
                <div class="edu-footer__cta">
                    <span class="edu-footer__eyebrow">Student Portal</span>
                    <h4>Join With Us</h4>
                    <div class="edu-footer__location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $settings['topbar_location'] ?? 'Voronezh, Russian Federation' }}</span>
                    </div>
                    <div class="edu-footer__cta-actions">
                        <a class="edu-footer__signup-btn" href="{{ route('register') }}">Join Now</a>
                        <button type="button" class="edu-footer__outline-btn" data-bs-toggle="modal" data-bs-target="#alumniNetworkModal">Add Profile to Alumni Network</button>
                    </div>
                </div>
                <div class="edu-footer__nav">
                    <div class="edu-footer__label">Quick Links</div>
                    <div class="edu-footer__menu-list">
                        @foreach ($footerSystemLinks as $item)
                            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="edu-footer__nav">
                    <div class="edu-footer__label">Our Gallery</div>
                    <div class="edu-footer__menu-list">
                        @foreach ($footerServiceLinks as $item)
                            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="edu-footer__bottom">
                <div class="edu-footer__meta">
                    <span>&copy; {{ date('Y') }} VGLTU ASIAN STUDENT</span>
                </div>
                <div class="edu-footer__credit">
                    Design & developed by
                    <a href="https://smshagor.com" target="_blank" rel="noopener noreferrer">Shahanur Islam Shagor</a>
                </div>
                <div class="edu-footer__social" aria-label="Social media links">
                    @foreach ($footerSocialLinks as $item)
                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $item['label'] }}">
                            @if (!empty($item['icon_url']))
                                <img src="{{ $item['icon_url'] }}" alt="{{ $item['label'] }}">
                            @else
                                <i class="{{ $item['icon_class'] ?? 'fas fa-link' }}"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade alumni-modal" id="alumniNetworkModal" tabindex="-1" aria-labelledby="alumniNetworkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="small text-uppercase fw-bold" style="letter-spacing:.12em; opacity:.82;">Alumni Network</div>
                    <h5 class="modal-title" id="alumniNetworkModalLabel">Add Profile to Alumni Network</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('alumni-network.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alumni-form-grid">
                        <div>
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Degree</label>
                            <div id="alumniDegreeList" class="alumni-repeat-list">
                                <div class="alumni-repeat-row">
                                    <input type="text" name="degree[]" class="form-control" required>
                                    <button type="button" class="btn btn-outline-primary" onclick="addAlumniField('alumniDegreeList', 'degree[]')">Add</button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Department</label>
                            <div id="alumniDepartmentList" class="alumni-repeat-list">
                                <div class="alumni-repeat-row">
                                    <input type="text" name="department[]" class="form-control" required>
                                    <button type="button" class="btn btn-outline-primary" onclick="addAlumniField('alumniDepartmentList', 'department[]')">Add</button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Pass Year</label>
                            <div id="alumniPassYearList" class="alumni-repeat-list">
                                <div class="alumni-repeat-row">
                                    <input type="text" name="pass_year[]" class="form-control" required>
                                    <button type="button" class="btn btn-outline-primary" onclick="addAlumniField('alumniPassYearList', 'pass_year[]')">Add</button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Upload Photo</label>
                            <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="edu-footer__signup-btn border-0">Submit Alumni Request</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function addAlumniField(containerId, fieldName) {
        const container = document.getElementById(containerId);
        const row = document.createElement('div');
        row.className = 'alumni-repeat-row';
        row.innerHTML = `
            <input type="text" name="${fieldName}" class="form-control" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">Remove</button>
        `;
        container.appendChild(row);
    }
</script>
