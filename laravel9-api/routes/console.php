<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Artisan::command('admin:create', function () {
    $name = $this->ask('Nome do admin');
    $email = $this->ask('Email do admin');
    $cpf = $this->ask('cpf do admin');
    $password = $this->secret('Senha do admin');
    $role = 'admin';

    $user = User::create([
        'name' => $name,
        'email' => $email,
        'cpf' => $cpf,
        'password' => Hash::make($password),
        'role' => 'admin',
    ]);

    $this->info('Admin criado com sucesso!');
})->purpose('Criar um administrador inicial');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
