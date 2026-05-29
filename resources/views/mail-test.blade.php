<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>메일 발송 테스트</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-start justify-center p-4 sm:p-8 font-sans text-slate-900">

{{-- ── Modal ── --}}
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-xl bg-white p-7 shadow-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">메일 계정 설정</h2>
            <button type="button" onclick="closeModal()"
                    class="rounded p-1 text-2xl leading-none text-slate-400 transition hover:text-slate-900">&times;
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
                <input type="password" id="m_password" value="{{ old('password', $defaults['password']) }}" placeholder="••••••••"
                       autocomplete="new-password"
                       class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Encryption</label>
                <select id="m_encryption"
                        class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                    <option value="tls" {{ old('encryption', $defaults['encryption']) === 'tls' ? 'selected' : '' }}>
                        tls
                    </option>
                    <option value="ssl" {{ old('encryption', $defaults['encryption']) === 'ssl' ? 'selected' : '' }}>
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
                class="mt-6 w-full rounded-lg bg-indigo-500 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-600">
            저장
        </button>
    </div>
</div>

{{-- ── Main Card ── --}}
<div class="w-full max-w-2xl rounded-xl bg-white p-8 shadow-[0_4px_24px_rgba(0,0,0,0.08)]">
    <div class="mb-7 flex items-center justify-between gap-4">
        <h1 class="text-[1.375rem] font-bold text-slate-900">메일 발송 테스트</h1>
        <button type="button" onclick="openModal()"
                class="flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-lg border-[1.5px] border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-500 hover:text-indigo-500">
            메일 계정 설정
        </button>
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
                <summary class="cursor-pointer select-none text-xs font-semibold text-red-700 hover:underline">스택 트레이스
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
                        <input type="email" name="to_address[]" value="{{ $addr }}" placeholder="recipient@example.com"
                               class="min-w-0 flex-1 rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
                        <button type="button" onclick="removeToRow(this)"
                                class="btn-remove flex h-9 w-9 shrink-0 items-center justify-center rounded-md border-[1.5px] border-red-300 text-lg leading-none text-red-500 transition hover:bg-red-50 {{ $loop->first && count(old('to_address', [''])) === 1 ? 'invisible' : '' }}">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addToRow()"
                    class="mt-1 self-start rounded-md border-[1.5px] border-indigo-500 px-3 py-1.5 text-xs font-semibold text-indigo-500 transition hover:bg-indigo-50">
                + 수신 이메일 추가
            </button>
        </div>

        <hr class="my-6 border-slate-100">

        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">메일 제목</label>
            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="메일 제목을 입력하세요"
                   class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
        </div>

        <div class="mt-4 flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">HTML 본문</label>
            <textarea name="html_content" placeholder="<h1>안녕하세요</h1>&#10;<p>메일 본문을 입력하세요.</p>"
                      class="min-h-[200px] w-full resize-y rounded-lg border-[1.5px] border-slate-200 px-3 py-2 font-mono text-sm leading-relaxed outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">{{ old('html_content') }}</textarea>
        </div>

        <button type="submit"
                class="mt-6 w-full rounded-lg bg-indigo-500 py-2.5 text-base font-semibold text-white transition hover:bg-indigo-600 active:bg-indigo-700">
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
        } catch (_) {}

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
            '<button type="button" onclick="removeToRow(this)" class="btn-remove flex h-9 w-9 shrink-0 items-center justify-center rounded-md border-[1.5px] border-red-300 text-lg leading-none text-red-500 transition hover:bg-red-50">&times;</button>';
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
</script>
</body>
</html>
