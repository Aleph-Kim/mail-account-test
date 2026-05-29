@extends('layouts.app')

@section('content')

    {{-- ── Modal ── --}}
    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-xl bg-white p-7 shadow-2xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">메일 계정 설정</h2>
                <button type="button" onclick="closeModal()"
                        class="cursor-pointer rounded p-1 text-2xl leading-none text-slate-400 transition hover:text-slate-900">&times;
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-[1fr_2fr_1fr]">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Mailer</label>
                    <select id="m_mailer"
                            class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                        @foreach (['smtp', 'sendmail', 'mailgun', 'ses', 'postmark'] as $m)
                            <option
                                value="{{ $m }}" {{ old('mailer', $defaults['mailer']) === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Host</label>
                    <input type="text" id="m_host" value="{{ old('host', $defaults['host']) }}"
                           placeholder="smtp.example.com"
                           class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Port</label>
                    <input type="number" id="m_port" value="{{ old('port', $defaults['port']) }}" placeholder="465"
                           class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-[2fr_1fr_1fr]">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Username</label>
                    <input type="text" id="m_username" value="{{ old('username', $defaults['username']) }}"
                           placeholder="user@example.com"
                           class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Password</label>
                    <input type="password" id="m_password" value="{{ old('password', $defaults['password']) }}"
                           placeholder="••••••••"
                           autocomplete="new-password"
                           class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Encryption</label>
                    <select id="m_encryption"
                            class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                        <option
                            value="tls" {{ old('encryption', $defaults['encryption']) === 'tls' ? 'selected' : '' }}>
                            tls
                        </option>
                        <option
                            value="ssl" {{ old('encryption', $defaults['encryption']) === 'ssl' ? 'selected' : '' }}>
                            ssl
                        </option>
                        <option value="" {{ old('encryption', $defaults['encryption']) === '' ? 'selected' : '' }}>none
                        </option>
                    </select>
                </div>
            </div>

            <hr class="my-6 border-slate-100">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">From Address</label>
                    <input type="email" id="m_from_address" value="{{ old('from_address', $defaults['from_address']) }}"
                           placeholder="noreply@example.com"
                           class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">From Name</label>
                    <input type="text" id="m_from_name" value="{{ old('from_name', $defaults['from_name']) }}"
                           placeholder="발신자 이름"
                           class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                </div>
            </div>

            <button type="button" onclick="closeModal()"
                    class="mt-6 w-full cursor-pointer rounded-lg bg-indigo-500 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-600">
                저장
            </button>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="w-full max-w-6xl rounded-xl bg-white p-8 shadow-[0_4px_24px_rgba(0,0,0,0.08)]">
        <div class="mb-7 flex items-center justify-between gap-4">
            <h1 class="text-[1.375rem] font-bold text-slate-900">메일 발송 테스트</h1>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openModal()"
                        class="flex cursor-pointer shrink-0 items-center gap-1.5 whitespace-nowrap rounded-lg border-[1.5px] border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-500 hover:text-indigo-500">
                    메일 계정 설정
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex cursor-pointer shrink-0 items-center gap-1.5 whitespace-nowrap rounded-lg border-[1.5px] border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-600 transition hover:border-red-400 hover:text-red-500">
                        로그아웃
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border-[1.5px] border-green-300 bg-green-50 px-5 py-4 text-sm text-green-800">
                <span class="font-bold">✓</span> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            @php $err = session('error'); @endphp
            <div class="mb-6 rounded-lg border-[1.5px] border-red-300 bg-red-50 px-5 py-4 text-sm text-red-800">
                <div
                    class="mb-2 inline-block rounded bg-red-100 px-2 py-0.5 text-xs font-bold text-red-900">{{ $err['class'] }}</div>
                <div class="mb-3 font-semibold leading-relaxed break-words">{{ $err['message'] }}</div>
                <details class="mt-2">
                    <summary class="cursor-pointer select-none text-xs font-semibold text-red-700 hover:underline">스택
                        트레이스
                        보기
                    </summary>
                    <div
                        class="mt-2.5 max-h-80 overflow-y-auto whitespace-pre-wrap break-all rounded-md border border-red-200 bg-red-50/50 px-4 py-3 font-mono text-[0.72rem] text-red-900">{{ $err['trace'] }}</div>
                </details>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="mb-6 rounded-lg border-[1.5px] border-red-300 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800 leading-relaxed">
                @foreach ($errors->all() as $e)
                    {{ $e }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('send') }}" id="mail-form">
            @csrf

            {{-- Hidden inputs mirroring modal fields --}}
            <input type="hidden" name="mailer" id="h_mailer">
            <input type="hidden" name="host" id="h_host">
            <input type="hidden" name="port" id="h_port">
            <input type="hidden" name="username" id="h_username">
            <input type="hidden" name="password" id="h_password">
            <input type="hidden" name="encryption" id="h_encryption">
            <input type="hidden" name="from_address" id="h_from_address">
            <input type="hidden" name="from_name" id="h_from_name">

            <div id="to-field" class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">수신 이메일</label>
                <div id="to-rows" class="flex flex-col gap-2">
                    @foreach (old('to_address', ['']) as $addr)
                        <div class="to-row flex items-center gap-2">
                            <input type="email" name="to_address[]" value="{{ $addr }}"
                                   placeholder="recipient@example.com"
                                   class="min-w-0 flex-1 rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                            <button type="button" onclick="removeToRow(this)"
                                    class="btn-remove cursor-pointer flex h-9 w-9 shrink-0 items-center justify-center rounded-md border-[1.5px] border-red-300 text-lg leading-none text-red-500 transition hover:bg-red-50 {{ $loop->first && count(old('to_address', [''])) === 1 ? 'invisible' : '' }}">
                                &times;
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addToRow()"
                        class="mt-1 cursor-pointer self-start rounded-md border-[1.5px] border-indigo-500 px-3 py-1.5 text-xs font-semibold text-indigo-500 transition hover:bg-indigo-50">
                    + 수신 이메일 추가
                </button>
            </div>

            <hr class="my-6 border-slate-100">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">메일 제목</label>
                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="메일 제목을 입력하세요"
                       class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">HTML 본문</label>
                    <textarea id="html-input" name="html_content" placeholder="<h1>안녕하세요</h1>&#10;<p>메일 본문을 입력하세요.</p>"
                              class="h-80 w-full resize-y rounded-lg border-[1.5px] border-slate-200 px-3 py-2 font-mono text-sm leading-relaxed outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">{{ old('html_content') }}</textarea>
                </div>

                <div id="preview-section" class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between gap-2">
                        <label
                            class="shrink-0 text-xs font-semibold uppercase tracking-wide text-slate-600">미리보기</label>
                        <span
                            class="text-right text-xs text-amber-600 font-medium">※ 실제 메일 클라이언트 환경에 따라 다를 수 있습니다.</span>
                    </div>
                    <div id="preview-warnings" class="hidden flex-col gap-1.5">
                        <div class="rounded-lg border-[1.5px] border-red-200 bg-red-50 px-3 py-2.5">
                            <p class="mb-2 text-xs font-semibold text-red-700">호환성 경고 — 아래 문법은 일부 메일 클라이언트에서 무시되거나
                                차단됩니다.</p>
                            <ul id="warnings-list" class="flex flex-wrap gap-1.5"></ul>
                        </div>
                    </div>
                    <iframe id="html-preview"
                            class="flex-1 w-full rounded-lg border-[1.5px] border-slate-200 bg-white"
                            style="min-height:220px;"
                            sandbox="allow-same-origin"></iframe>
                    <p class="text-right text-xs text-amber-600">※ Gmail·Outlook 등 클라이언트마다 CSS 지원 범위가 달라 실제 수신 화면과 차이가
                        있을 수 있습니다.</p>
                </div>
            </div>

            <button type="submit"
                    class="mt-6 w-full cursor-pointer rounded-lg bg-indigo-500 py-2.5 text-base font-semibold text-white transition hover:bg-indigo-600 active:bg-indigo-700">
                메일 발송
            </button>
        </form>
    </div>

    <script>
        const modalIds = ['mailer', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'];
        const modal = document.getElementById('modal');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        async function closeModal() {
            const data = {};
            modalIds.forEach(id => {
                data[id] = document.getElementById('m_' + id).value;
            });

            try {
                await fetch('{{ route('settings.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(data),
                });
            } catch (_) {
            }

            modalIds.forEach(id => {
                document.getElementById('h_' + id).value = data[id];
            });
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Sync hidden fields with modal defaults on load
        modalIds.forEach(id => {
            document.getElementById('h_' + id).value = document.getElementById('m_' + id).value;
        });

        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal();
        });

        function addToRow() {
            const rows = document.getElementById('to-rows');
            const row = document.createElement('div');
            row.className = 'to-row flex items-center gap-2';
            row.innerHTML = '<input type="email" name="to_address[]" placeholder="recipient@example.com" class="min-w-0 flex-1 rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">' +
                '<button type="button" onclick="removeToRow(this)" class="btn-remove cursor-pointer flex h-9 w-9 shrink-0 items-center justify-center rounded-md border-[1.5px] border-red-300 text-lg leading-none text-red-500 transition hover:bg-red-50">&times;</button>';
            rows.appendChild(row);
            updateRemoveButtons();
        }

        function removeToRow(btn) {
            btn.closest('.to-row').remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('#to-rows .to-row');
            rows.forEach((row, i) => {
                row.querySelector('.btn-remove').classList.toggle('invisible', rows.length === 1 && i === 0);
            });
        }

        const htmlInput = document.getElementById('html-input');
        const previewSection = document.getElementById('preview-section');
        const htmlPreview = document.getElementById('html-preview');
        const previewWarnings = document.getElementById('preview-warnings');
        const warningsList = document.getElementById('warnings-list');

        const CHECKS = [
            // 일반 (다수 클라이언트)
            {re: /<script/i, label: '<script>', reason: '모든 클라이언트에서 차단'},
            {re: /<(?:video|audio|canvas)/i, label: '<video/audio/canvas>', reason: '대부분 미지원'},
            {re: /<iframe/i, label: '<iframe>', reason: '대부분 차단'},
            {re: /<form[\s>\/]/i, label: '<form>', reason: 'Gmail 등에서 차단'},
            {re: /<link[^>]+stylesheet/i, label: '외부 CSS (<link>)', reason: '대부분 차단'},
            {re: /display\s*:\s*flex/i, label: 'display:flex', reason: 'Outlook 미지원'},
            {re: /display\s*:\s*grid/i, label: 'display:grid', reason: '대부분 미지원'},
            {re: /position\s*:\s*(fixed|sticky)/i, label: 'position:fixed/sticky', reason: '미지원'},
            {re: /var\s*\(--/i, label: 'CSS 변수 (var)', reason: '미지원'},
            {re: /@font-face/i, label: '@font-face', reason: '일부만 지원'},
            {re: /fonts\.googleapis\.com/i, label: 'Google Fonts', reason: 'Gmail 등에서 차단'},
            {re: /linear-gradient|radial-gradient/i, label: 'CSS gradient', reason: 'Outlook 미지원'},
            {re: /background-image\s*:/i, label: 'background-image', reason: 'Outlook에서 무시될 수 있음'},
            {re: /border-radius\s*:/i, label: 'border-radius', reason: 'Outlook 미지원'},
            {re: /box-shadow\s*:/i, label: 'box-shadow', reason: 'Outlook 미지원'},
            {re: /transition\s*:|animation\s*:/i, label: 'transition/animation', reason: '대부분 미지원'},
            // 네이버 메일 전용
            {re: /<style[\s>]/i, label: '<style>', reason: '네이버에서 전체 제거, 인라인 스타일만 적용됨', client: '네이버'},
            {re: /class\s*=/i, label: 'class 속성', reason: '<style> 제거로 클래스 스타일 무효화', client: '네이버'},
            {re: /@media/i, label: '@media 쿼리', reason: '<style> 제거로 미디어 쿼리 무효화', client: '네이버'},
            {re: /<svg/i, label: '<svg>', reason: '네이버에서 렌더링 불가', client: '네이버'},
            {
                re: /position\s*:\s*absolute/i,
                label: 'position:absolute',
                reason: '네이버 레이아웃 컨테이너에 의해 깨질 수 있음',
                client: '네이버'
            },
            {re: /max-width\s*:/i, label: 'max-width', reason: '네이버 뷰어 래퍼에 의해 무시될 수 있음', client: '네이버'},
        ];

        const emptyPreview = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:0.8rem;font-family:sans-serif;user-select:none;">HTML을 입력하면 여기에 미리보기가 표시됩니다.</div>';

        function updatePreview() {
            const html = htmlInput.value.trim();
            htmlPreview.srcdoc = html || emptyPreview;

            const esc = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

            const hits = html ? CHECKS.filter(c => c.re.test(html)) : [];
            if (hits.length) {
                warningsList.innerHTML = hits.map(h =>
                    `<li class="inline-flex items-center gap-1 rounded-md bg-red-100 px-2 py-0.5 text-xs text-red-800">
                    <code class="font-mono font-semibold">${esc(h.label)}</code>
                    <span class="text-red-500">— ${esc(h.reason)}</span>
                </li>`
                ).join('');
                previewWarnings.classList.remove('hidden');
                previewWarnings.classList.add('flex');
            } else {
                previewWarnings.classList.add('hidden');
                previewWarnings.classList.remove('flex');
            }
        }

        htmlInput.addEventListener('input', updatePreview);
        updatePreview();
    </script>

@endsection
