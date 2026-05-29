<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SendMailRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailTestController extends Controller
{
    public function index(Request $request)
    {
        $defaults = [
            'mailer'       => $request->cookie('mail_mailer',       config('mail.default', 'smtp')),
            'host'         => $request->cookie('mail_host',         config('mail.mailers.smtp.host', '')),
            'port'         => $request->cookie('mail_port',         config('mail.mailers.smtp.port', 465)),
            'username'     => $request->cookie('mail_username',     config('mail.mailers.smtp.username', '')),
            'password'     => $request->cookie('mail_password',     config('mail.mailers.smtp.password', '')),
            'encryption'   => $request->cookie('mail_encryption',   config('mail.mailers.smtp.encryption', 'ssl')),
            'from_address' => $request->cookie('mail_from_address', config('mail.from.address', '')),
            'from_name'    => $request->cookie('mail_from_name',    config('mail.from.name', '')),
        ];

        return view('mail-test', compact('defaults'));
    }

    public function saveSettings(Request $request): JsonResponse
    {
        $minutes = 60 * 24 * 365;

        return response()->json(['ok' => true])
            ->withCookie(cookie('mail_mailer',       $request->input('mailer', ''),       $minutes))
            ->withCookie(cookie('mail_host',         $request->input('host', ''),         $minutes))
            ->withCookie(cookie('mail_port',         $request->input('port', ''),         $minutes))
            ->withCookie(cookie('mail_username',     $request->input('username', ''),     $minutes))
            ->withCookie(cookie('mail_password',     $request->input('password', ''),     $minutes))
            ->withCookie(cookie('mail_encryption',   $request->input('encryption', ''),   $minutes))
            ->withCookie(cookie('mail_from_address', $request->input('from_address', ''), $minutes))
            ->withCookie(cookie('mail_from_name',    $request->input('from_name', ''),    $minutes));
    }

    public function send(SendMailRequest $request)
    {
        Config::set('mail.mailers.test_mailer', [
            'transport' => $request->mailer,
            'host' => $request->host,
            'port' => (int)$request->port,
            'encryption' => $request->encryption ?: null,
            'username' => $request->username,
            'password' => $request->password,
            'timeout' => 10,
        ]);

        Config::set('mail.from.address', $request->from_address);
        Config::set('mail.from.name', $request->from_name);

        try {
            Mail::mailer('test_mailer')->html(
                $request->html_content,
                function ($message) use ($request) {
                    $message->to($request->to_address)
                        ->subject($request->subject);
                }
            );

            return back()->with('success', "메일이 성공적으로 발송되었습니다.");
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', [
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
        }
    }
}
