<?php

namespace App\Console\Commands;

use App\Mail\MailAuto;
use App\Models\UserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMailAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = UserModel::pluck('email')->toArray();
        Mail::bcc($users)->send(new MailAuto);
    }
}
