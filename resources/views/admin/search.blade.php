@extends('layouts.admin')

@section('title', 'Admin – Search')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #0f1117;
            --ink2: #3d4358;
            --muted: #8b90a0;
            --line: #e4e7f0;
            --surface: #f5f6fa;
            --card: #ffffff;
            --accent: #3d5cff;
            --accent2: #ff5c8a;
            --radius: 12px;
        }

        /* ── Page header ── */
        .search-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid var(--line);
        }

        .search-header h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--ink);
            margin: 0;
            letter-spacing: -0.02em;
        }

        .search-header h1 span {
            color: var(--accent);
        }

        .search-header p {
            font-family: 'DM Sans', sans-serif;
            color: var(--muted);
            font-size: .85rem;
            margin: 4px 0 0;
        }

        /* ── Filter panel ── */
        .filter-panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .filter-label {
            font-family: 'DM Sans', sans-serif;
            font-size: .67rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: var(--muted);
            margin-bottom: 5px;
        }

        /* ── Search input ── */
        .search-wrap {
            position: relative;
        }

        .search-wrap .search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: .9rem;
            pointer-events: none;
        }

        .search-wrap input {
            padding-left: 36px !important;
            border-radius: 8px !important;
            border: 1.5px solid var(--line) !important;
            font-family: 'DM Sans', sans-serif;
            font-size: .88rem;
            background: var(--surface) !important;
            transition: border-color .15s, box-shadow .15s;
        }

        .search-wrap input:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px rgba(61, 92, 255, .1) !important;
            outline: none;
        }

        /* ── Category pills ── */
        .cat-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cat-pill {
            font-family: 'DM Sans', sans-serif;
            font-size: .78rem;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 999px;
            border: 1.5px solid var(--line);
            background: var(--surface);
            color: var(--ink2);
            cursor: pointer;
            transition: all .15s;
            user-select: none;
        }

        .cat-pill:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #eef1ff;
        }

        .cat-pill.active {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
        }

        .cat-pill .dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
        }

        .cat-pill[data-value="admins"] .dot {
            background: #f59e0b;
        }

        .cat-pill[data-value="users"] .dot {
            background: #10b981;
        }

        .cat-pill[data-value="posts"] .dot {
            background: #3d5cff;
        }

        .cat-pill[data-value=""].active {
            background: var(--ink);
            border-color: var(--ink);
        }

        /* ── Filter selects/dates ── */
        .form-select-sm,
        .form-control-sm {
            border-radius: 8px !important;
            border: 1.5px solid var(--line) !important;
            background: var(--surface) !important;
            font-family: 'DM Sans', sans-serif;
            font-size: .82rem;
        }

        .form-select-sm:focus,
        .form-control-sm:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px rgba(61, 92, 255, .1) !important;
        }

        .filter-divider {
            width: 1px;
            height: 32px;
            background: var(--line);
            margin: 0 4px;
            align-self: flex-end;
        }

        #resetFilters {
            font-family: 'DM Sans', sans-serif;
            font-size: .78rem;
            border-radius: 8px;
            border: 1.5px solid var(--line);
            color: var(--muted);
            background: transparent;
            padding: 5px 12px;
            transition: all .15s;
        }

        #resetFilters:hover {
            border-color: var(--accent2);
            color: var(--accent2);
            background: #fff0f4;
        }

        /* ── Stats bar ── */
        .stats-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 8px;
        }

        .result-label {
            font-family: 'Syne', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            color: var(--ink);
        }

        .result-label em {
            font-style: normal;
            color: var(--accent);
            font-size: 1.1rem;
        }

        .chips-wrap {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #eef1ff;
            color: var(--accent);
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 600;
            padding: 3px 10px;
            cursor: pointer;
            transition: background .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .chip:hover {
            background: #dde3ff;
        }

        /* ── Cards grid ── */
        #searchResult {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        /* ── Result card ── */
        .r-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            transition: box-shadow .18s, transform .18s, border-color .18s;
            animation: fadeUp .22s ease both;
            font-family: 'DM Sans', sans-serif;
        }

        .r-card:hover {
            box-shadow: 0 8px 28px rgba(61, 92, 255, .12);
            border-color: #c7d0ff;
            transform: translateY(-3px);
        }

        .r-card .card-img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            display: block;
        }

        .r-card .card-body {
            padding: 14px;
        }

        .r-card .card-title {
            font-size: .82rem;
            font-weight: 500;
            color: var(--ink);
            margin: 0 0 6px;
            line-height: 1.4;
        }

        .r-card .meta {
            font-size: .73rem;
            color: var(--muted);
        }

        .r-card .meta strong {
            color: var(--ink2);
            font-weight: 500;
        }

        .post-tag {
            display: inline-block;
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            padding: 2px 8px;
            border-radius: 4px;
            background: #eef1ff;
            color: var(--accent);
            margin-bottom: 6px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            /* makes it circular */
            object-fit: cover;
            /* prevents distortion */
            display: block;
        }

        .admin-card .avatar {
            background: #fef3c7;
            color: #92400e;
        }

        .user-card .avatar {
            background: #d1fae5;
            color: #065f46;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: .88rem;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: .73rem;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .role-badge {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .admin-card .role-badge {
            background: #fef3c7;
            color: #92400e;
        }

        .user-card .role-badge {
            background: #d1fae5;
            color: #065f46;
        }

        /* ── Skeleton ── */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 6px;
        }

        @keyframes shimmer {
            to {
                background-position: -200% 0;
            }
        }

        /* ── Empty ── */
        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 4rem 1rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--muted);
        }

        .empty-state .icon {
            font-size: 2.5rem;
            margin-bottom: .75rem;
            opacity: .35;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    {{-- Header --}}
    <div class="search-header">
        <div>
            <h1>Smart <span>Search</span></h1>
            <p>Browse admins, users and posts — filters apply instantly</p>
        </div>
    </div>

    {{-- Filter Panel --}}
    <form id="searchForm" class="filter-panel" onsubmit="return false;">
        <input type="hidden" id="categoryInput" value="">

        <div class="row g-2 align-items-end mb-3">
            <div class="col-12 col-md">
                <div class="filter-label">Keyword</div>
                <div class="search-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" class="form-control" id="searchInput" placeholder="Name, email, post content…"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-auto">
                <div class="filter-label">Date From</div>
                <input type="date" class="form-control form-control-sm" id="dateFrom">
            </div>
            <div class="col-auto">
                <div class="filter-label">Date To</div>
                <input type="date" class="form-control form-control-sm" id="dateTo">
            </div>
            <div class="col-auto">
                <div class="filter-label">Sort</div>
                <select class="form-select form-select-sm" id="sortInput" style="min-width:130px;">
                    <option value="newest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                    <option value="az">A → Z</option>
                    <option value="za">Z → A</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-end">
                <div class="filter-divider"></div>
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="button" id="resetFilters">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
            </div>
        </div>

        <div>
            <div class="filter-label mb-2">Category</div>
            <div class="cat-pills">
                <div class="cat-pill active" data-value="">All</div>
                <div class="cat-pill" data-value="admins"><span class="dot"></span>Admins</div>
                <div class="cat-pill" data-value="users"><span class="dot"></span>Users</div>
                <div class="cat-pill" data-value="posts"><span class="dot"></span>Posts</div>
            </div>
        </div>
    </form>

    {{-- Stats bar --}}
    <div class="stats-bar">
        <div class="result-label" id="resultLabel">&nbsp;</div>
        <div class="chips-wrap" id="activeFilters"></div>
    </div>

    {{-- Results grid --}}
    <div id="searchResult">
        @for ($i = 0; $i < 8; $i++)
            <div class="r-card" style="animation-delay:{{ $i * 40 }}ms">
                <div class="skeleton" style="height:130px;"></div>
                <div class="card-body">
                    <div class="skeleton mb-2" style="height:12px;width:55%;"></div>
                    <div class="skeleton mb-1" style="height:10px;width:90%;"></div>
                    <div class="skeleton" style="height:10px;width:65%;"></div>
                </div>
            </div>
        @endfor
    </div>

    <script>
        let allData = [];
        let debounceT = null;

        // ── Category pills ───────────────────────────────────────────────
        document.querySelectorAll('.cat-pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('categoryInput').value = this.dataset.value;
                fetchData();
            });
        });

        // ── Live keyword filter ──────────────────────────────────────────
        document.getElementById('searchInput').addEventListener('input', () => {
            clearTimeout(debounceT);
            debounceT = setTimeout(applyFilters, 260);
        });

        // ── Date / sort → re-fetch ───────────────────────────────────────
        ['dateFrom', 'dateTo', 'sortInput'].forEach(id => {
            document.getElementById(id).addEventListener('change', fetchData);
        });

        // ── Reset ────────────────────────────────────────────────────────
        document.getElementById('resetFilters').addEventListener('click', () => {
            document.getElementById('categoryInput').value = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            document.getElementById('sortInput').value = 'newest';
            document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
            document.querySelector('.cat-pill[data-value=""]').classList.add('active');
            fetchData();
        });

        // ── Fetch ────────────────────────────────────────────────────────
        function fetchData() {
            const category = document.getElementById('categoryInput').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const sort = document.getElementById('sortInput').value;

            showSkeletons();

            if (!category) {
                Promise.all([
                    fetchOne('admins', dateFrom, dateTo, sort),
                    fetchOne('users', dateFrom, dateTo, sort),
                    fetchOne('posts', dateFrom, dateTo, sort),
                ]).then(([a, u, p]) => {
                    allData = [...a, ...u, ...p];
                    applyFilters();
                });
            } else {
                fetchOne(category, dateFrom, dateTo, sort).then(data => {
                    allData = data;
                    applyFilters();
                });
            }
        }

        function fetchOne(category, dateFrom, dateTo, sort) {
            return $.ajax({
                url: "{{ route('admin.searchData') }}",
                method: 'GET',
                data: {
                    category,
                    search: '',
                    date_from: dateFrom,
                    date_to: dateTo,
                    sort
                },
            }).then(r => r.success ? r.data : []).catch(() => []);
        }

        // ── Client-side keyword search ───────────────────────────────────
        function applyFilters() {
            const kw = document.getElementById('searchInput').value.trim().toLowerCase();

            const filtered = allData.filter(item => {
                if (!kw) return true;
                return [item.name || '', item.email || '', item.content || '', item.user?.name || '']
                    .join(' ').toLowerCase().includes(kw);
            });

            renderChips(kw);
            renderCards(filtered);
        }

        // ── Chips ────────────────────────────────────────────────────────
        function renderChips(kw) {
            const wrap = document.getElementById('activeFilters');
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            wrap.innerHTML = '';

            if (kw) addChip(wrap, `"${kw}"`, () => {
                document.getElementById('searchInput').value = '';
                applyFilters();
            });
            if (dateFrom) addChip(wrap, `From ${dateFrom}`, () => {
                document.getElementById('dateFrom').value = '';
                fetchData();
            });
            if (dateTo) addChip(wrap, `To ${dateTo}`, () => {
                document.getElementById('dateTo').value = '';
                fetchData();
            });
        }

        function addChip(wrap, label, fn) {
            const c = document.createElement('div');
            c.className = 'chip';
            c.innerHTML = `${label} <span>×</span>`;
            c.querySelector('span').addEventListener('click', fn);
            wrap.appendChild(c);
        }

        // ── Skeletons ────────────────────────────────────────────────────
        function showSkeletons() {
            const el = document.getElementById('searchResult');
            el.innerHTML = Array.from({
                length: 8
            }, (_, i) => `
<div class="r-card" style="animation-delay:${i*40}ms">
    <div class="skeleton" style="height:130px;"></div>
    <div class="card-body">
        <div class="skeleton mb-2" style="height:12px;width:55%;"></div>
        <div class="skeleton mb-1" style="height:10px;width:90%;"></div>
        <div class="skeleton"      style="height:10px;width:65%;"></div>
    </div>
</div>`).join('');
            document.getElementById('resultLabel').innerHTML = '&nbsp;';
        }

        // ── Render ───────────────────────────────────────────────────────
        function renderCards(data) {
            const el = document.getElementById('searchResult');
            document.getElementById('resultLabel').innerHTML =
                `<em>${data.length}</em> result${data.length !== 1 ? 's' : ''} found`;

            if (!data.length) {
                el.innerHTML =
                    `<div class="empty-state"><div class="icon">🔍</div><p>No results match your filters.</p></div>`;
                return;
            }

            el.innerHTML = data.map((item, i) => buildCard(item, i)).join('');
        }

        function buildCard(item, i) {
            const delay = `animation-delay:${Math.min(i,14)*35}ms`;

            // Post
            if (item.content !== undefined) {
                const img = item.photo ? '/' + item.photo : 'https://placehold.co/320x130/eef1ff/3d5cff?text=Post';
                const excerpt = item.content.length > 90 ? item.content.substring(0, 90) + '…' : item.content;
                const date = item.created_at ? new Date(item.created_at).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }) : '';
                return ` 
<div class="r-card" style="${delay}">
    <img class="card-img" src="${img}" alt="" onerror="this.src='https://placehold.co/320x130/eef1ff/3d5cff?text=Post'">
    <div class="card-body">
        <div class="post-tag">Post</div>
        <p class="card-title">${excerpt}</p>
        ${item.user ? `<div class="meta">By <strong>${item.user.name}</strong></div>` : ''}
        ${date      ? `<div class="meta mt-1">${date}</div>` : ''}
    </div>
</div>`;
            }

            // User / Admin
            if (item.name && item.email) {
                const isAdmin = item.role === 'admin';
                const initials = item.name.trim().split(/\s+/).map(w => w[0]).join('').substring(0, 2).toUpperCase();
                const type = isAdmin ? 'admin-card' : 'user-card';
                const date = item.created_at ? new Date(item.created_at).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }) : '';

                return `
<div class="r-card ${type}" style="${delay}">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
        
            <img src="/${item.photo}" alt="Avatar" class="avatar-img rounded-2xl" onerror="this.onerror=null;this.src='https://placehold.co/42x42/d1fae5/065f46?text=${initials}';">
            <div class="user-info">
                <div class="user-name">${item.name}</div>
                <div class="user-email">${item.email}</div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <span class="role-badge">${item.role || 'user'}</span>
            ${date ? `<span class="meta">${date}</span>` : ''}
        </div>
    </div>
</div>`;
            }

            return '';
        }

        // ── Boot ─────────────────────────────────────────────────────────
        fetchData();
    </script>

@endsection
