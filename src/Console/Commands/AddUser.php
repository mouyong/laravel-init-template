<?php

namespace ZhenMu\LaravelInitTemplate\Console\Commands;

use Illuminate\Console\Command;
use ZhenMu\LaravelInitTemplate\Repositories\UserRepository;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data['username'] = $this->ask('please input username');
        $data['password'] = $this->ask('please input password');

        $user = app(UserRepository::class)->create($data)->refresh();

        $this->table(array_keys($user->toArray()), [$user->toArray()]);

        return 0;
    }
}
