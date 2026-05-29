@extends('layouts.app')

@section('title', '로그인')

@section('body-class', 'items-center')

@section('content')
<div class="w-full max-w-sm rounded-xl bg-white p-8 shadow-[0_4px_24px_rgba(0,0,0,0.08)]">
    <h1 class="mb-6 text-[1.375rem] font-bold text-slate-900">메일 발송 테스트</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border-[1.5px] border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">아이디</label>
            <input type="text" name="username" value="{{ old('username') }}" autocomplete="username"
                   class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">비밀번호</label>
            <input type="password" name="password" autocomplete="current-password"
                   class="w-full rounded-lg border-[1.5px] border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-500 focus:ring-[3px] focus:ring-indigo-500/15">
        </div>
        <button type="submit"
                class="mt-2 w-full cursor-pointer rounded-lg bg-indigo-500 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-600 active:bg-indigo-700">
            로그인
        </button>
    </form>
</div>
@endsection
