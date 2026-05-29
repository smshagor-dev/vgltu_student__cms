@extends('layouts.admin_app')

@section('content')
@php
    $selectedMultipleUsers = collect(old('user_ids', []))->map(fn ($id) => (string) $id)->all();
    $selectedSingleUser = old('user_id', $selectedUser?->id);
@endphp
<style>
    .email-campaign-card {
        max-width: 1100px;
        margin: 0 auto;
        background: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 18px 45px rgba(30, 60, 114, 0.12);
    }

    .email-campaign-card__heading h2 {
        margin-bottom: 8px;
        font-size: 1.9rem;
        font-weight: 800;
        color: #10213b;
    }

    .email-campaign-card__heading p {
        margin-bottom: 24px;
        color: #66758a;
    }

    .email-campaign-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .email-campaign-field {
        margin-bottom: 18px;
    }

    .email-campaign-field--full {
        grid-column: 1 / -1;
    }

    .email-campaign-field label {
        display: block;
        margin-bottom: 8px;
        color: #1f2f4d;
        font-weight: 700;
    }

    .email-campaign-field .form-control,
    .email-campaign-field .form-select {
        min-height: 48px;
        border-radius: 14px;
    }

    .email-campaign-help {
        margin-top: 6px;
        font-size: 0.88rem;
        color: #6c757d;
    }

    .email-campaign-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .email-campaign-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .email-campaign-badge {
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

    .email-campaign-recipient-panel {
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-radius: 18px;
        background: linear-gradient(180deg, #fcfdff 0%, #f8fbff 100%);
        overflow: hidden;
    }

    .email-campaign-recipient-toolbar {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        padding: 14px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background: rgba(255, 255, 255, 0.9);
    }

    .email-campaign-recipient-search,
    .email-campaign-recipient-selected-search {
        min-height: 46px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.3);
        padding: 0 14px;
        width: 100%;
    }

    .email-campaign-recipient-count {
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

    .email-campaign-recipient-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
        gap: 0;
    }

    .email-campaign-user-browser,
    .email-campaign-selected-preview {
        padding: 14px;
    }

    .email-campaign-user-browser {
        border-right: 1px solid rgba(148, 163, 184, 0.18);
    }

    .email-campaign-user-list,
    .email-campaign-selected-list {
        display: grid;
        gap: 10px;
        max-height: 330px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .email-campaign-selected-list {
        max-height: 292px;
    }

    .email-campaign-user-item,
    .email-campaign-selected-item {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 14px;
        background: #fff;
        padding: 12px 14px;
        text-align: left;
        transition: 0.2s ease;
    }

    .email-campaign-user-item:hover,
    .email-campaign-selected-item:hover {
        border-color: rgba(29, 78, 216, 0.4);
        box-shadow: 0 10px 24px rgba(29, 78, 216, 0.08);
        transform: translateY(-1px);
    }

    .email-campaign-user-item.is-selected {
        border-color: #1d4ed8;
        background: #eff6ff;
        box-shadow: 0 12px 28px rgba(29, 78, 216, 0.12);
    }

    .email-campaign-user-item strong,
    .email-campaign-selected-item strong {
        display: block;
        color: #10213b;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .email-campaign-user-item span,
    .email-campaign-selected-item span {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 0.84rem;
        word-break: break-word;
    }

    .email-campaign-selected-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .email-campaign-selected-heading strong {
        color: #10213b;
        font-size: 0.95rem;
    }

    .email-campaign-selected-empty,
    .email-campaign-user-empty {
        padding: 18px 16px;
        border: 1px dashed rgba(148, 163, 184, 0.32);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.8);
        color: #64748b;
        font-size: 0.9rem;
        text-align: center;
    }

    .email-campaign-native-select {
        display: none !important;
    }

    .email-campaign-single-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(260px, 0.8fr);
        gap: 0;
    }

    .email-campaign-single-browser,
    .email-campaign-single-preview {
        padding: 14px;
    }

    .email-campaign-single-browser {
        border-right: 1px solid rgba(148, 163, 184, 0.18);
    }

    .email-campaign-single-search {
        min-height: 46px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.3);
        padding: 0 14px;
        width: 100%;
        margin-bottom: 12px;
    }

    .email-campaign-single-list {
        display: grid;
        gap: 10px;
        max-height: 320px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .email-campaign-single-item {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 14px;
        background: #fff;
        padding: 12px 14px;
        text-align: left;
        transition: 0.2s ease;
    }

    .email-campaign-single-item:hover {
        border-color: rgba(29, 78, 216, 0.4);
        box-shadow: 0 10px 24px rgba(29, 78, 216, 0.08);
        transform: translateY(-1px);
    }

    .email-campaign-single-item.is-selected {
        border-color: #1d4ed8;
        background: #eff6ff;
        box-shadow: 0 12px 28px rgba(29, 78, 216, 0.12);
    }

    .email-campaign-single-item strong,
    .email-campaign-single-selected strong {
        display: block;
        color: #10213b;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .email-campaign-single-item span,
    .email-campaign-single-selected span {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 0.84rem;
        word-break: break-word;
    }

    .email-campaign-single-selected,
    .email-campaign-single-empty {
        padding: 16px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, 0.22);
    }

    .email-campaign-single-empty {
        border-style: dashed;
        text-align: center;
        color: #64748b;
    }

    .email-campaign-editor-shell {
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(180deg, #fcfdff 0%, #f8fbff 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }

    .email-campaign-editor-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background: rgba(241, 245, 249, 0.7);
        flex-wrap: wrap;
    }

    .email-campaign-editor-top strong {
        color: #10213b;
        font-size: 0.98rem;
    }

    .email-campaign-editor-tools {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .email-campaign-editor-tools span {
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.9);
        color: #475569;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .email-campaign-editor-shell textarea {
        border: 0;
        border-radius: 0;
    }

    .email-campaign-editor-shell .cke {
        border: 0 !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    .email-campaign-editor-shell .cke_top {
        border: 0 !important;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18) !important;
        background: #fff !important;
        padding: 10px 12px !important;
    }

    .email-campaign-editor-shell .cke_bottom {
        display: none !important;
    }

    .email-campaign-editor-shell .cke_contents {
        min-height: 360px;
        background: #fff;
    }

    .email-campaign-editor-shell .cke_contents iframe {
        min-height: 360px !important;
        background: #fff;
    }

    .email-campaign-editor-status {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 16px 14px;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        background: #fff;
        flex-wrap: wrap;
    }

    .email-campaign-editor-status small {
        color: #64748b;
        font-size: 0.82rem;
    }

    .email-campaign-editor-status .editor-state {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #0f766e;
    }

    .email-campaign-editor-status .editor-state::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.15);
    }

    .email-campaign-editor-status .editor-state.is-error {
        color: #dc2626;
    }

    .email-campaign-editor-status .editor-state.is-error::before {
        background: #dc2626;
        box-shadow: 0 0 0 5px rgba(220, 38, 38, 0.12);
    }

    .email-campaign-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .email-campaign-submit {
        border: 0;
        border-radius: 999px;
        padding: 13px 24px;
        background: linear-gradient(135deg, #10213b, #1d4ed8);
        color: #fff;
        font-weight: 800;
    }

    .email-campaign-preview {
        padding: 18px;
        border-radius: 18px;
        background: #f8fbff;
        border: 1px solid rgba(148, 163, 184, 0.2);
        color: #475569;
    }

    @media (max-width: 767px) {
        .email-campaign-card {
            padding: 18px;
        }

        .email-campaign-grid {
            grid-template-columns: 1fr;
        }

        .email-campaign-editor-top,
        .email-campaign-editor-status {
            padding-left: 12px;
            padding-right: 12px;
        }

        .email-campaign-recipient-toolbar,
        .email-campaign-recipient-shell,
        .email-campaign-single-shell {
            grid-template-columns: 1fr;
        }

        .email-campaign-user-browser,
        .email-campaign-single-browser {
            border-right: 0;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }

        .email-campaign-editor-shell .cke_contents,
        .email-campaign-editor-shell .cke_contents iframe {
            min-height: 300px !important;
        }
    }
</style>

<div class="email-campaign-card">
    <div class="email-campaign-card__heading">
        <h2>Send Email Notification</h2>
        <p>Create a dedicated email campaign for all users, one user, or multiple selected users. The queue will send 2 emails every 1 minute until all selected users are finished.</p>
    </div>

    <div class="email-campaign-header-bar">
        <div class="email-campaign-badges">
            <span class="email-campaign-badge"><i class="fas fa-envelope-open-text"></i> Rich Email Composer</span>
            <span class="email-campaign-badge"><i class="fas fa-list-check"></i> Important Tools Enabled</span>
            <span class="email-campaign-badge"><i class="fas fa-clock"></i> 2 Emails / Minute Queue</span>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.email-notifications.index') }}" class="btn btn-outline-primary">View Email Campaign History</a>
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

    <form method="POST" action="{{ route('admin.email-notifications.store') }}" id="emailCampaignForm">
        @csrf

        <div class="email-campaign-grid">
            <div class="email-campaign-field">
                <label for="recipient_type">Recipient Type</label>
                <select name="recipient_type" id="recipient_type" class="form-select">
                    <option value="all" {{ old('recipient_type', $selectedUser ? 'single' : 'all') === 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="single" {{ old('recipient_type', $selectedUser ? 'single' : 'all') === 'single' ? 'selected' : '' }}>Single User</option>
                    <option value="multiple" {{ old('recipient_type') === 'multiple' ? 'selected' : '' }}>Multiple Users</option>
                </select>
            </div>

            <div class="email-campaign-field">
                <label for="url">Portal Link</label>
                <input type="text" name="url" id="url" class="form-control" value="{{ old('url', route('home')) }}" placeholder="{{ route('home') }}">
                <div class="email-campaign-help">This button link appears inside the email.</div>
            </div>

            <div class="email-campaign-field email-campaign-field--full" id="multipleUsersField">
                <label for="user_ids">Select Multiple Users</label>
                <select name="user_ids[]" id="user_ids" class="form-select email-campaign-native-select" multiple>
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
                <div class="email-campaign-recipient-panel">
                    <div class="email-campaign-recipient-toolbar">
                        <input
                            type="text"
                            id="multipleUserSearch"
                            class="email-campaign-recipient-search"
                            placeholder="Search users by name or email"
                        >
                        <span class="email-campaign-recipient-count" id="selectedUserCount">0 selected</span>
                    </div>
                    <div class="email-campaign-recipient-shell">
                        <div class="email-campaign-user-browser">
                            <div class="email-campaign-user-list" id="multipleUserList"></div>
                        </div>
                        <div class="email-campaign-selected-preview">
                            <div class="email-campaign-selected-heading">
                                <strong>Recipients</strong>
                            </div>
                            <input
                                type="text"
                                id="selectedUserSearch"
                                class="email-campaign-recipient-selected-search"
                                placeholder="Search selected recipients"
                            >
                            <div class="email-campaign-help">Click a user to add or remove. Selected users appear here, and the list shows 5 at a time with scroll for the rest.</div>
                            <div class="email-campaign-selected-list" id="selectedUserList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="email-campaign-field email-campaign-field--full" id="singleUserField">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" class="form-select email-campaign-native-select">
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
                <div class="email-campaign-recipient-panel">
                    <div class="email-campaign-single-shell">
                        <div class="email-campaign-single-browser">
                            <input
                                type="text"
                                id="singleUserSearch"
                                class="email-campaign-single-search"
                                placeholder="Search user by name or email"
                            >
                            <div class="email-campaign-single-list" id="singleUserList"></div>
                        </div>
                        <div class="email-campaign-single-preview">
                            <div class="email-campaign-selected-heading">
                                <strong>Selected User</strong>
                            </div>
                            <div class="email-campaign-help">The selected user will appear in this side section.</div>
                            <div id="singleSelectedUser"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="email-campaign-field">
                <label for="title">Email Subject</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Important hostel update" required>
            </div>

            <div class="email-campaign-field">
                <label for="description">Short Summary</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}" placeholder="Optional short summary shown in campaign history">
            </div>

            <div class="email-campaign-field email-campaign-field--full">
                <label for="body_html">Email Body</label>
                <div class="email-campaign-editor-shell">
                    <div class="email-campaign-editor-top">
                        <strong>Compose Your Email</strong>
                        <div class="email-campaign-editor-tools">
                            <span>Headings</span>
                            <span>Colors</span>
                            <span>Lists</span>
                            <span>Tables</span>
                            <span>HTML Embed</span>
                        </div>
                    </div>
                    <textarea name="body_html" id="body_html" class="form-control" rows="12" required>{!! old('body_html') !!}</textarea>
                    <div class="email-campaign-editor-status">
                        <small>Use short sections, links, buttons, lists, and tables for the best email readability.</small>
                        <span class="editor-state" id="editorState">Editor loading...</span>
                    </div>
                </div>
                <div class="email-campaign-help">Important tools are enabled for rich email writing, and the content will be saved as formatted HTML.</div>
            </div>

            <div class="email-campaign-field email-campaign-field--full">
                <div class="email-campaign-preview">
                    <strong>Sending rule:</strong> The system sends 2 emails every 1 minute. Example: if you select 100 users, all emails should finish in about 50 minutes.
                </div>
            </div>
        </div>

        <div class="email-campaign-actions">
            <a href="{{ route('admin.email-notifications.index') }}" class="btn btn-outline-primary">History</a>
            <button type="submit" class="email-campaign-submit">Queue Email Campaign</button>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recipientType = document.getElementById('recipient_type');
        const singleUserField = document.getElementById('singleUserField');
        const multipleUsersField = document.getElementById('multipleUsersField');
        const editorState = document.getElementById('editorState');
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

        syncRecipientField();

        if (recipientType) {
            recipientType.addEventListener('change', syncRecipientField);
        }

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
                singleSelectedUser.innerHTML = '<div class="email-campaign-single-empty">No user selected yet.</div>';
                return;
            }

            singleSelectedUser.innerHTML = ''
                + '<button type="button" class="email-campaign-single-selected" data-clear-single-user="true">'
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
                singleUserList.innerHTML = '<div class="email-campaign-single-empty">No users matched your search.</div>';
                return;
            }

            singleUserList.innerHTML = visibleUsers.map(function (user) {
                return ''
                    + '<button type="button" class="email-campaign-single-item' + (user.selected ? ' is-selected' : '') + '" data-single-user-id="' + user.id + '">'
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

        if (singleUserSearch) {
            singleUserSearch.addEventListener('input', renderSingleUserList);
        }

        renderSingleUserList();
        renderSingleSelectedUser();

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
                selectedUserList.innerHTML = '<div class="email-campaign-selected-empty">No recipients selected yet.</div>';
                return;
            }

            selectedUserList.innerHTML = selectedUsers.map(function (user) {
                return ''
                    + '<button type="button" class="email-campaign-selected-item" data-user-id="' + user.id + '">'
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
                multipleUserList.innerHTML = '<div class="email-campaign-user-empty">No users matched your search.</div>';
                return;
            }

            multipleUserList.innerHTML = visibleUsers.map(function (user) {
                return ''
                    + '<button type="button" class="email-campaign-user-item' + (user.selected ? ' is-selected' : '') + '" data-user-id="' + user.id + '">'
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

        if (multipleUserSearch) {
            multipleUserSearch.addEventListener('input', renderUserBrowser);
        }

        if (selectedUserSearch) {
            selectedUserSearch.addEventListener('input', renderSelectedUsers);
        }

        renderUserBrowser();
        renderSelectedUsers();

        const setEditorState = function (message, isError) {
            if (!editorState) {
                return;
            }

            editorState.textContent = message;
            editorState.classList.toggle('is-error', !!isError);
        };

        if (typeof CKEDITOR === 'undefined') {
            setEditorState('Editor failed to load, plain textarea mode active', true);
            return;
        }

        try {
            CKEDITOR.replace('body_html', {
                height: 380,
                removeButtons: '',
                extraAllowedContent: '*(*);*{*}',
                allowedContent: true,
                contentsCss: [
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
                ],
                toolbar: [
                    { name: 'document', items: ['Source'] },
                    { name: 'clipboard', items: ['Undo', 'Redo'] },
                    { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar'] }
                ]
            });

            CKEDITOR.instances.body_html.on('instanceReady', function () {
                setEditorState('Editor ready', false);
                CKEDITOR.instances.body_html.setReadOnly(false);
            });
        } catch (error) {
            console.error(error);
            setEditorState('Editor failed to load, plain textarea mode active', true);
        }
    });
</script>
@endsection
