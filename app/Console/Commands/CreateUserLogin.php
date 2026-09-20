<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class CreateUserLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-login {model : "student" or "teacher"} {id : The record id} {--password=password : The login password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a login account for an existing student or teacher record';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $model = strtolower($this->argument('model'));

        $role = match ($model) {
            'student' => Role::Student,
            'teacher' => Role::Teacher,
            default => null,
        };

        if ($role === null) {
            $this->error('Model must be "student" or "teacher".');

            return self::FAILURE;
        }

        $record = match ($model) {
            'student' => Student::find($this->argument('id')),
            'teacher' => Teacher::find($this->argument('id')),
        };

        if ($record === null) {
            $this->error("No {$model} record with id {$this->argument('id')} found.");

            return self::FAILURE;
        }

        if ($record->hasLogin()) {
            $this->error("This {$model} already has a login account.");

            return self::FAILURE;
        }

        $this->createUser($record, $role);

        $this->info("Login created for {$record->full_name} ({$record->email}).");

        return self::SUCCESS;
    }

    private function createUser(Model $record, Role $role): void
    {
        $user = User::create([
            'name' => $record->full_name,
            'email' => $record->email,
            'password' => $this->option('password'),
            'role' => $role,
        ]);

        $record->user()->associate($user)->save();
    }
}
