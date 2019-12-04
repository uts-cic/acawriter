<?php

namespace App\Console\Commands;

use App\User;
use App\Events\UserRegistered;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user record';

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
     * @return mixed
     */
    public function handle()
    {
        $data = $this->getData();
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        event(new UserRegistered($user, $data['role']));
        $this->display($user);
    }

    /**
     * Ask for admin data.
     *
     * @return array
     */
    private function getData() : array
    {
        $data['name'] = $this->ask('User name');
        $data['email'] = $this->ask('Email address');
        $data['password'] = $this->secret('Password');
        $data['confirm_password'] = $this->secret('Confirm password');
        while (! $this->isValidPassword($data['password'], $data['confirm_password'])) {
            if (! $this->isRequiredLength($data['password'])) {
                $this->error('Password must be more that six characters');
            }
            if (! $this->isMatch($data['password'], $data['confirm_password'])) {
                $this->error('Password and Confirm password do not match');
            }
            $data['password'] = $this->secret('Password');
            $data['confirm_password'] = $this->secret('Confirm password');
        }
        $roles = array(
            1 => 'demo',
            2 => 'user',
            3 => 'staff',
            4 => 'admin',
        );
        $data['role'] = $this->choice('Role', $roles);
        if (!in_array($data['role'], $roles)) {
            $this->error('Role was not selected');
        }
        return $data;
    }

    /**
     * Display created admin.
     *
     * @param array $admin
     * @return void
     */
    private function display(User $user) : void
    {
        $headers = ['Name', 'Email', 'Role'];
        $fields = [
            'Name' => $user->name,
            'Email' => $user->email,
            'Role' => $user->getRole()
        ];
        $this->info('User created');
        $this->table($headers, [$fields]);
    }

    /**
     * Check if password is vailid
     *
     * @param string $password
     * @param string $confirmPassword
     * @return boolean
     */
    private function isValidPassword(string $password, string $confirmPassword) : bool
    {
        return $this->isRequiredLength($password) &&
        $this->isMatch($password, $confirmPassword);
    }

    /**
     * Check if password and confirm password matches.
     *
     * @param string $password
     * @param string $confirmPassword
     * @return bool
     */
    private function isMatch(string $password, string $confirmPassword) : bool
    {
        return $password === $confirmPassword;
    }

    /**
     * Checks if password is longer than six characters.
     *
     * @param string $password
     * @return bool
     */
    private function isRequiredLength(string $password) : bool
    {
        return strlen($password) > 6;
    }
}
