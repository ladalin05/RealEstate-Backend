<?php

namespace App\Services;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected TransactionalEmailsApi $api;

    public function __construct()
    {
        $key = config('services.brevo.key');

        if (!$key) {
            Log::error('BREVO_API_KEY is missing in config/services.php');
            throw new \Exception('Brevo API key is missing.');
        }

        // Warn if wrong key type
        if (str_starts_with($key, 'xsmtp')) {
            Log::warning(
                'Wrong Brevo key type detected. You are using SMTP key (xsmtp). 
                Please use API v3 key (xkeysib-...) from Brevo dashboard.'
            );
        }

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', $key);

        $this->api = new TransactionalEmailsApi(
            new GuzzleClient(),
            $config
        );
    }

    /**
     * Send transactional email
     */
    public function sendSimpleEmail(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlContent,
        ?string $replyTo = null
    ) {
        $email = new SendSmtpEmail([
            'sender' => [
                'email' => config('services.brevo.sender_email'),
                'name'  => config('services.brevo.sender_name'),
            ],
            'to' => [[
                'email' => $toEmail,
                'name'  => $toName,
            ]],
            'subject'     => $subject,
            'htmlContent' => $htmlContent,
        ]);

        if ($replyTo) {
            $email['replyTo'] = [
                'email' => $replyTo
            ];
        }

        try {
            return $this->api->sendTransacEmail($email);
        } catch (\Throwable $e) {
            Log::error('Brevo email failed', [
                'message' => $e->getMessage(),
                'to' => $toEmail,
                'subject' => $subject,
            ]);

            throw $e;
        }
    }
}