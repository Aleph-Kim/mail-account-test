<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailTestController extends Controller
{
    public function index()
    {
        return view('mail-test');
    }

    public function send(Request $request)
    {
        $request->validate([
            'mailer'       => 'required|string',
            'host'         => 'required|string',
            'port'         => 'required|integer',
            'username'     => 'nullable|string',
            'password'     => 'nullable|string',
            'encryption'   => 'nullable|string',
            'from_address' => 'required|email',
            'from_name'    => 'required|string',
            'to_address'   => 'required|email',
        ]);

        Config::set('mail.mailers.test_mailer', [
            'transport'  => $request->mailer,
            'host'       => $request->host,
            'port'       => (int) $request->port,
            'encryption' => $request->encryption ?: null,
            'username'   => $request->username,
            'password'   => $request->password,
            'timeout'    => 10,
        ]);

        Config::set('mail.from.address', $request->from_address);
        Config::set('mail.from.name', $request->from_name);

        try {
            Mail::mailer('test_mailer')->raw(
                '메일 발송 테스트',
                function ($message) use ($request) {
                    $message->to($request->to_address)
                        ->subject('메일 발송 테스트');
                }
            );

            return back()->with('success', "{$request->to_address} 으로 메일이 성공적으로 발송되었습니다.");
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', [
                    'class'   => get_class($e),
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]);
        }
    }
}
