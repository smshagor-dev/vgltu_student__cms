@extends('layouts.admin_app')

@section('content')
@php
    $selectedMultipleUsers = collect(old('user_ids', []))->map(fn ($id) => (string) $id)->all();
    $selectedSingleUser = old('user_id', $selectedUser?->id);
@endphp
<style>
    .notification-card {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 18px 45px rgba(30, 60, 114, 0.12);
    }

    .notification-card__heading h2 {
        margin-bottom: 8px;
        font-size: 1.9rem;
        font-weight: 800;
        color: #10213b;
    }

    .notification-card__heading p {
        margin-bottom: 24px;
        color: #66758a;
    }

    .notification-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .notification-field {
        margin-bottom: 18px;
    }

    .notification-field--full {
        grid-column: 1 / -1;
    }

    .notification-field label {
        display: block;
        margin-bottom: 8px;
        color: #1f2f4d;
        font-weight: 700;
    }

    .notification-field .form-control,
    .notification-field .form-select {
        min-height: 48px;
        border-radius: 14px;
    }

    .notification-help {
        margin-top: 6px;
        font-size: 0.88rem;
        color: #6c757d;
    }

    .notification-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .notification-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .notification-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 999px;
        background: #eef4ff;
        color: #1d4ed8;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .notification-recipient-panel {
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-radius: 18px;
        background: linear-gradient(180deg, #fcfdff 0%, #f8fbff 100%);
        overflow: hidden;
    }

    .notification-recipient-toolbar {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        padding: 14px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background: rgba(255, 255, 255, 0.9);
    }

    .notification-recipient-search,
    .notification-recipient-selected-search,
    .notification-single-search {
        min-height: 46px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.3);
        padding: 0 14px;
        width: 100%;
    }

    .notification-single-search {
        margin-bottom: 12px;
    }

    .notification-recipient-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        padding: 0 16px;
        border-radius: 999px;
        background: #e8f0ff;
        color: #1d4ed8;
        font-size: 0.84rem;
        font-weight: 800;
    }

    .notification-recipient-shell,
    .notification-single-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(260px, 0.8fr);
        gap: 0;
    }

    .notification-user-browser,
    .notification-selected-preview,
    .notification-single-browser,
    .notification-single-preview {
        padding: 14px;
    }

    .notification-user-browser,
    .notification-single-browser {
        border-right: 1px solid rgba(148, 163, 184, 0.18);
    }

    .notification-user-list,
    .notification-selected-list,
    .notification-single-list {
        display: grid;
        gap: 10px;
        max-height: 330px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .notification-selected-list {
        max-height: 292px;
    }

    .notification-user-item,
    .notification-selected-item,
    .notification-single-item,
    .notification-single-selected {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 14px;
        background: #fff;
        padding: 12px 14px;
        text-align: left;
        transition: 0.2s ease;
    }

    .notification-user-item:hover,
    .notification-selected-item:hover,
    .notification-single-item:hover,
    .notification-single-selected:hover {
        border-color: rgba(29, 78, 216, 0.4);
        box-shadow: 0 10px 24px rgba(29, 78, 216, 0.08);
        transform: translateY(-1px);
    }

    .notification-user-item.is-selected,
    .notification-single-item.is-selected {
        border-color: #1d4ed8;
        background: #eff6ff;
        box-shadow: 0 12px 28px rgba(29, 78, 216, 0.12);
    }

    .notification-user-item strong,
    .notification-selected-item strong,
    .notification-single-item strong,
    .notification-single-selected strong {
        display: block;
        color: #10213b;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .notification-user-item span,
    .notification-selected-item span,
    .notification-single-item span,
    .notification-single-selected span {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 0.84rem;
        word-break: break-word;
    }

    .notification-selected-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .notification-selected-heading strong {
        color: #10213b;
        font-size: 0.95rem;
    }

    .notification-selected-empty,
    .notification-user-empty,
    .notification-single-empty {
        padding: 18px 16px;
        border: 1px dashed rgba(148, 163, 184, 0.32);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.8);
        color: #64748b;
        font-size: 0.9rem;
        text-align: center;
    }

    .notification-native-select {
        display: none !important;
    }

    .notification-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .notification-switch {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 18px;
        border: 1px solid #dbe4f0;
        border-radius: 14px;
        background: #f8fbff;
        margin-top: 6px;
    }

    .notification-switch input {
        margin-top: 4px;
    }

    .notification-submit {
        border: 0;
        border-radius: 999px;
        padding: 13px 24px;
        background: linear-gradient(135deg, #10213b, #1d4ed8);
        color: #fff;
        font-weight: 800;
    }

    @media (max-width: 767px) {
        .notification-card {
            padding: 18px;
        }

        .notification-grid,
        .notification-recipient-toolbar,
        .notification-recipient-shell,
        .notification-single-shell {
            grid-template-columns: 1fr;
        }

        .notification-user-browser,
        .notification-single-browser {
            border-right: 0;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }
    }
</style>

<div class="notification-card">
    <div class="notification-card__heading">
        <h2>Send Notification</h2>
        <p>Send a portal notification from the admin panel to all users, one user, or multiple selected users.</p>
    </div>

    <div class="notification-header-bar">
        <div class="notification-badges">
            <span class="notification-badge"><i class="fas fa-bell"></i> Smart Recipient Picker</span>
            <span class="notification-badge"><i class="fas fa-users"></i> Search & Preview Users</span>
            <span class="notification-badge"><i class="fas fa-paper-plane"></i> Instant Delivery</span>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary">View Notification List</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.notifications.store') }}">
        @csrf

        <div class="notification-grid">
            <div class="notification-field">
                <label for="recipient_type">Recipient Type</label>
                <select name="recipient_type" id="recipient_type" class="form-select">
                    <option value="all" {{ old('recipient_type', $selectedUser ? 'single' : 'all') === 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="single" {{ old('recipient_type', $selectedUser ? 'single' : 'all') === 'single' ? 'selected' : '' }}>Single User</option>
                    <option value="multiple" {{ old('recipient_type') === 'multiple' ? 'selected' : '' }}>Multiple Users</option>
                </select>
            </div>

            <div class="notification-field">
                <label for="url">Open URL</label>
                <input type="text" name="url" id="url" class="form-control" value="{{ old('url', route('home')) }}" placeholder="{{ route('home') }}">
                <div class="notification-help">Users will be taken to this link when they open the notification.</div>
            </div>

            <div class="notification-field notification-field--full" id="multipleUsersField">
                <label for="user_ids">Select Multiple Users</label>
                <select name="user_ids[]" id="user_ids" class="form-select notification-native-select" multiple>
                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            data-name="{{ $user->full_name }}"
                            data-email="{{ $user->email }}"
                            {{ in_array((string) $user->id, $selectedMultipleUsers, true) ? 'selected' : '' }}
                        >
                            {{ $user->full_name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                <div class="notification-recipient-panel">
                    <div class="notification-recipient-toolbar">
                        <input type="text" id="multipleUserSearch" class="notification-recipient-search" placeholder="Search users by name or email">
                        <span class="notification-recipient-count" id="selectedUserCount">0 selected</span>
                    </div>
                    <div class="notification-recipient-shell">
                        <div class="notification-user-browser">
                            <div class="notification-user-list" id="multipleUserList"></div>
                        </div>
                        <div class="notification-selected-preview">
                            <div class="notification-selected-heading">
                                <strong>Recipients</strong>
                            </div>
                            <input type="text" id="selectedUserSearch" class="notification-recipient-selected-search" placeholder="Search selected recipients">
                            <div class="notification-help">Click a user to add or remove. Selected users appear here, and the list shows 5 at a time with scroll for the rest.</div>
                            <div class="notification-selected-list" id="selectedUserList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="notification-field notification-field--full" id="singleUserField">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" class="form-select notification-native-select">
                    <option value="">Choose a user</option>
                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            data-name="{{ $user->full_name }}"
                            data-email="{{ $user->email }}"
                            {{ (string) $selectedSingleUser === (string) $user->id ? 'selected' : '' }}
                        >
                            {{ $user->full_name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                <div class="notification-recipient-panel">
                    <div class="notification-single-shell">
                        <div class="notification-single-browser">
                            <input type="text" id="singleUserSearch" class="notification-single-search" placeholder="Search user by name or email">
                            <div class="notification-single-list" id="singleUserList"></div>
                        </div>
                        <div class="notification-single-preview">
                            <div class="notification-selected-heading">
                                <strong>Selected User</strong>
                            </div>
                            <div class="notification-help">The selected user will appear in this side section.</div>
                            <div id="singleSelectedUser"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="notification-field">
                <label for="title">Notification Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Important update for students" required>
            </div>

            <div class="notification-field">
                <label for="icon">Icon Class</label>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', 'fas fa-bell') }}" placeholder="fas fa-bell">
                <div class="notification-help">You can use any valid Font Awesome icon class here.</div>
            </div>

            <div class="notification-field notification-field--full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Write the full message here...">{{ old('description') }}</textarea>
            </div>
        </div>

        <label class="notification-switch">
            <input type="checkbox" name="send_email_also" value="1" {{ old('send_email_also') ? 'checked' : '' }}>
            <span>
                <strong>Send email also</strong><br>
                The notification recipients will also receive an email using the existing email campaign function.
            </span>
        </label>

        <div class="notification-actions">
            <button type="submit" class="notification-submit">Send Notification</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recipientType = document.getElementById('recipient_type');
        const singleUserField = document.getElementById('singleUserField');
        const multipleUsersField = document.getElementById('multipleUsersField');
        const singleUserSelect = document.getElementById('user_id');
        const singleUserList = document.getElementById('singleUserList');
        const singleUserSearch = document.getElementById('singleUserSearch');
        const singleSelectedUser = document.getElementById('singleSelectedUser');
        const multipleUserSelect = document.getElementById('user_ids');
        const multipleUserList = document.getElementById('multipleUserList');
        const selectedUserList = document.getElementById('selectedUserList');
        const multipleUserSearch = document.getElementById('multipleUserSearch');
        const selectedUserSearch = document.getElementById('selectedUserSearch');
        const selectedUserCount = document.getElementById('selectedUserCount');

        const syncRecipientField = function () {
            if (!recipientType || !singleUserField || !multipleUsersField) {
                return;
            }

            const isSingle = recipientType.value === 'single';
            const isMultiple = recipientType.value === 'multiple';
            singleUserField.style.display = isSingle ? 'block' : 'none';
            multipleUsersField.style.display = isMultiple ? 'block' : 'none';
        };

        const getSingleUserOptions = function () {
            if (!singleUserSelect) {
                return [];
            }

            return Array.from(singleUserSelect.options)
                .filter(function (option) {
                    return option.value !== '';
                })
                .map(function (option) {
                    return {
                        id: option.value,
                        name: option.dataset.name || '',
                        email: option.dataset.email || '',
                        selected: option.selected,
                    };
                });
        };

        const renderSingleSelectedUser = function () {
            if (!singleSelectedUser || !singleUserSelect) {
                return;
            }

            const selected = getSingleUserOptions().find(function (user) {
                return user.selected;
            });

            if (!selected) {
                singleSelectedUser.innerHTML = '<div class="notification-single-empty">No user selected yet.</div>';
                return;
            }

            singleSelectedUser.innerHTML = ''
                + '<button type="button" class="notification-single-selected" data-clear-single-user="true">'
                + '<strong>' + selected.name + '</strong>'
                + '<span>' + selected.email + '</span>'
                + '</button>';

            const clearButton = singleSelectedUser.querySelector('[data-clear-single-user]');
            if (clearButton) {
                clearButton.addEventListener('click', function () {
                    singleUserSelect.value = '';
                    renderSingleUserList();
                    renderSingleSelectedUser();
                });
            }
        };

        var renderSingleUserList = function () {
            if (!singleUserList || !singleUserSelect) {
                return;
            }

            const searchTerm = (singleUserSearch ? singleUserSearch.value : '').trim().toLowerCase();
            const visibleUsers = getSingleUserOptions().filter(function (user) {
                const haystack = (user.name + ' ' + user.email).toLowerCase();
                return searchTerm === '' || haystack.includes(searchTerm);
            });

            if (!visibleUsers.length) {
                singleUserList.innerHTML = '<div class="notification-single-empty">No users matched your search.</div>';
                return;
            }

            singleUserList.innerHTML = visibleUsers.map(function (user) {
                return ''
                    + '<button type="button" class="notification-single-item' + (user.selected ? ' is-selected' : '') + '" data-single-user-id="' + user.id + '">'
                    + '<strong>' + user.name + '</strong>'
                    + '<span>' + user.email + '</span>'
                    + '</button>';
            }).join('');

            Array.from(singleUserList.querySelectorAll('[data-single-user-id]')).forEach(function (button) {
                button.addEventListener('click', function () {
                    singleUserSelect.value = button.dataset.singleUserId;
                    renderSingleUserList();
                    renderSingleSelectedUser();
                });
            });
        };

        const getUserOptions = function () {
            if (!multipleUserSelect) {
                return [];
            }

            return Array.from(multipleUserSelect.options).map(function (option) {
                return {
                    id: option.value,
                    name: option.dataset.name || '',
                    email: option.dataset.email || '',
                    selected: option.selected,
                };
            });
        };

        const updateSelectedCount = function (count) {
            if (!selectedUserCount) {
                return;
            }

            selectedUserCount.textContent = count + ' selected';
        };

        const renderSelectedUsers = function () {
            if (!selectedUserList || !multipleUserSelect) {
                return;
            }

            const searchTerm = (selectedUserSearch ? selectedUserSearch.value : '').trim().toLowerCase();
            const selectedUsers = getUserOptions().filter(function (user) {
                if (!user.selected) {
                    return false;
                }

                const haystack = (user.name + ' ' + user.email).toLowerCase();
                return searchTerm === '' || haystack.includes(searchTerm);
            });

            updateSelectedCount(getUserOptions().filter(function (user) {
                return user.selected;
            }).length);

            if (!selectedUsers.length) {
                selectedUserList.innerHTML = '<div class="notification-selected-empty">No recipients selected yet.</div>';
                return;
            }

            selectedUserList.innerHTML = selectedUsers.map(function (user) {
                return ''
                    + '<button type="button" class="notification-selected-item" data-user-id="' + user.id + '">'
                    + '<strong>' + user.name + '</strong>'
                    + '<span>' + user.email + '</span>'
                    + '</button>';
            }).join('');

            Array.from(selectedUserList.querySelectorAll('[data-user-id]')).forEach(function (button) {
                button.addEventListener('click', function () {
                    const option = Array.from(multipleUserSelect.options).find(function (item) {
                        return item.value === button.dataset.userId;
                    });

                    if (!option) {
                        return;
                    }

                    option.selected = false;
                    renderUserBrowser();
                    renderSelectedUsers();
                });
            });
        };

        var renderUserBrowser = function () {
            if (!multipleUserList || !multipleUserSelect) {
                return;
            }

            const searchTerm = (multipleUserSearch ? multipleUserSearch.value : '').trim().toLowerCase();
            const visibleUsers = getUserOptions().filter(function (user) {
                const haystack = (user.name + ' ' + user.email).toLowerCase();
                return searchTerm === '' || haystack.includes(searchTerm);
            });

            if (!visibleUsers.length) {
                multipleUserList.innerHTML = '<div class="notification-user-empty">No users matched your search.</div>';
                return;
            }

            multipleUserList.innerHTML = visibleUsers.map(function (user) {
                return ''
                    + '<button type="button" class="notification-user-item' + (user.selected ? ' is-selected' : '') + '" data-user-id="' + user.id + '">'
                    + '<strong>' + user.name + '</strong>'
                    + '<span>' + user.email + '</span>'
                    + '</button>';
            }).join('');

            Array.from(multipleUserList.querySelectorAll('[data-user-id]')).forEach(function (button) {
                button.addEventListener('click', function () {
                    const option = Array.from(multipleUserSelect.options).find(function (item) {
                        return item.value === button.dataset.userId;
                    });

                    if (!option) {
                        return;
                    }

                    option.selected = !option.selected;
                    renderUserBrowser();
                    renderSelectedUsers();
                });
            });
        };

        syncRecipientField();

        if (recipientType) {
            recipientType.addEventListener('change', syncRecipientField);
        }

        if (singleUserSearch) {
            singleUserSearch.addEventListener('input', renderSingleUserList);
        }

        if (multipleUserSearch) {
            multipleUserSearch.addEventListener('input', renderUserBrowser);
        }

        if (selectedUserSearch) {
            selectedUserSearch.addEventListener('input', renderSelectedUsers);
        }

        renderSingleUserList();
        renderSingleSelectedUser();
        renderUserBrowser();
        renderSelectedUsers();
    });
</script>
@endsection
