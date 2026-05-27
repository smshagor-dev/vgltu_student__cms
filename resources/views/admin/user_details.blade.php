@extends('layouts.admin_app')

@section('content')
<style>
    .student-directory {
        display: grid;
        gap: 24px;
    }

    .student-search {
        max-width: 420px;
    }

    .student-card-grid {
        display: grid;
        gap: 18px;
    }

    .student-card {
        display: grid;
        grid-template-columns: 132px minmax(0, 1fr);
        gap: 22px;
        padding: 22px;
        border-radius: 26px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .student-card__photo {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .student-card__photo img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 28px;
        border: 4px solid rgba(37, 99, 235, 0.08);
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.12);
    }

    .student-card__meta {
        display: grid;
        gap: 12px;
    }

    .student-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .student-card__top h4 {
        margin: 0 0 6px;
    }

    .student-card__top p {
        margin: 0;
        color: #64748b;
    }

    .student-card__facts {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    }

    .student-card__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .student-card__actions form {
        margin: 0;
    }

    @media (max-width: 767.98px) {
        .student-card {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .student-card__actions {
            justify-content: center;
        }
    }
</style>

<div class="admin-page student-directory">
    <section class="admin-hero-card">
        <h2>Student Directory</h2>
        <p>Browse filtered student records with a cleaner operational view for profile review, password reset, and account actions.</p>
    </section>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Students Details</h3>
                <p>Search directly from the database by student name, room, phone, email, country, or department.</p>
            </div>
            <div class="admin-chip">
                <i class="fas fa-users"></i>
                <span id="studentCount">{{ method_exists($users, 'total') ? $users->total() : $users->count() }} Students</span>
            </div>
        </div>

        <div class="student-search mb-4">
            <input type="text" id="searchInput" class="form-control" value="{{ $search ?? '' }}" placeholder="Search from database by name, room, phone, email, country, department">
        </div>

        @if ($users->isEmpty())
            <div class="admin-empty">No students found for this filter.</div>
        @else
            <div class="student-card-grid" id="studentCardGrid">
                @include('admin.partials.user_details_cards')
            </div>
        @endif
    </section>

    <div class="admin-pagination" id="studentPagination">
        {{ $users->links() }}
    </div>
</div>

<script>
const userSearchInput = document.getElementById('searchInput');
const studentCardGrid = document.getElementById('studentCardGrid');
const studentPagination = document.getElementById('studentPagination');
const studentCount = document.getElementById('studentCount');
let userSearchTimeout = null;

function loadDirectory(page = 1) {
    if (!studentCardGrid || !studentPagination || !studentCount) {
        return;
    }

    const search = userSearchInput.value.trim();
    const url = new URL('{{ route('admin.users.list', ['category' => $category ?? 'total', 'value' => $value ?? null]) }}', window.location.origin);
    url.searchParams.set('page', page);

    if (search) {
        url.searchParams.set('search', search);
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            studentCardGrid.innerHTML = data.cards;
            studentPagination.innerHTML = data.pagination;
            studentCount.textContent = `${data.count} Students`;

            const browserUrl = new URL('{{ route('admin.users.list', ['category' => $category ?? 'total', 'value' => $value ?? null]) }}', window.location.origin);

            if (search) {
                browserUrl.searchParams.set('search', search);
            }

            if (page > 1) {
                browserUrl.searchParams.set('page', page);
            }

            window.history.replaceState({}, '', browserUrl);
        })
        .catch(() => {
            studentCardGrid.innerHTML = '<div class="admin-empty">Unable to load students right now.</div>';
        });
}

if (userSearchInput) {
    userSearchInput.addEventListener('input', function () {
        clearTimeout(userSearchTimeout);
        userSearchTimeout = setTimeout(() => loadDirectory(1), 350);
    });
}

if (studentPagination) {
    studentPagination.addEventListener('click', function (event) {
        const link = event.target.closest('a');

        if (!link) {
            return;
        }

        event.preventDefault();
        const url = new URL(link.href);
        const page = url.searchParams.get('page') || 1;
        loadDirectory(page);
    });
}
</script>
@endsection
