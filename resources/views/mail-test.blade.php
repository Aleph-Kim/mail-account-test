<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>메일 발송 테스트</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
            width: 100%;
            max-width: 680px;
            padding: 2rem;
        }

        h1 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        h1 span {
            font-size: 1.5rem;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }

        label {
            font-size: .78rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        input, select {
            width: 100%;
            padding: .55rem .75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            font-size: .95rem;
            color: #0f172a;
            background: #fff;
            transition: border-color .15s;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .row-3 {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
        }

        .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 1.5rem 0;
        }

        .btn {
            width: 100%;
            padding: .7rem;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
            margin-top: 1.25rem;
        }

        .btn:hover {
            background: #4f46e5;
        }

        .btn:active {
            background: #4338ca;
        }

        .alert {
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: .93rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #166534;
        }

        .alert-success::before {
            content: '✓  ';
            font-weight: 700;
        }

        .alert-error {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            color: #991b1b;
        }

        .error-type {
            font-size: .78rem;
            font-weight: 700;
            background: #fee2e2;
            color: #7f1d1d;
            border-radius: 4px;
            padding: .1rem .45rem;
            margin-bottom: .5rem;
            display: inline-block;
        }

        .error-message {
            font-weight: 600;
            margin-bottom: .75rem;
            line-height: 1.5;
            word-break: break-word;
        }

        details {
            margin-top: .5rem;
        }

        summary {
            cursor: pointer;
            font-size: .8rem;
            color: #b91c1c;
            font-weight: 600;
            user-select: none;
        }

        summary:hover {
            text-decoration: underline;
        }

        .trace {
            margin-top: .6rem;
            padding: .75rem 1rem;
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 6px;
            font-family: 'SFMono-Regular', Consolas, monospace;
            font-size: .72rem;
            color: #7f1d1d;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 320px;
            overflow-y: auto;
        }

        @media (max-width: 520px) {
            .row-2, .row-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="card">
    <h1>메일 발송 테스트</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        @php $err = session('error'); @endphp
        <div class="alert alert-error">
            <div class="error-type">{{ $err['class'] }}</div>
            <div class="error-message">{{ $err['message'] }}</div>
            <details>
                <summary>스택 트레이스 보기</summary>
                <div class="trace">{{ $err['trace'] }}</div>
            </details>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <div class="error-message">
                @foreach ($errors->all() as $e)
                    {{ $e }}<br>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('send') }}">
        @csrf

        <div class="row-3">
            <div class="field">
                <label>Mailer</label>
                <select name="mailer">
                    @foreach (['smtp', 'sendmail', 'mailgun', 'ses', 'postmark'] as $m)
                        <option value="{{ $m }}" {{ old('mailer', 'smtp') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Host</label>
                <input type="text" name="host" value="{{ old('host', 'smtp.naver.com') }}"
                       placeholder="smtp.example.com">
            </div>
            <div class="field">
                <label>Port</label>
                <input type="number" name="port" value="{{ old('port', '465') }}" placeholder="587">
            </div>
        </div>

        <div class="row-3" style="margin-top:1rem">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="user@example.com">
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" value="{{ old('password') }}" placeholder="••••••••"
                       autocomplete="new-password">
            </div>
            <div class="field">
                <label>Encryption</label>
                <select name="encryption">
                    <option value="tls" {{ old('encryption') === 'tls' ? 'selected' : '' }}>tls</option>
                    <option value="ssl" {{ old('encryption', 'ssl') === 'ssl' ? 'selected' : '' }}>ssl</option>
                    <option value="" {{ old('encryption') === '' ? 'selected' : '' }}>none</option>
                </select>
            </div>
        </div>

        <hr class="divider">

        <div class="row-2">
            <div class="field">
                <label>From Address</label>
                <input type="email" name="from_address" value="{{ old('from_address') }}"
                       placeholder="noreply@example.com">
            </div>
            <div class="field">
                <label>From Name</label>
                <input type="text" name="from_name" value="{{ old('from_name', '테스트 메일 발송') }}" placeholder="발신자 이름">
            </div>
        </div>

        <hr class="divider">

        <div class="field">
            <label>To Address</label>
            <input type="email" name="to_address" value="{{ old('to_address') }}" placeholder="recipient@example.com">
        </div>

        <button type="submit" class="btn">테스트 메일 발송</button>
    </form>
</div>
</body>
</html>
