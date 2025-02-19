<?php
    use function Livewire\Volt\state;
    use function Livewire\Volt\rules;
    use function Livewire\Volt\mount;

    state([
        'usser' => '',
        'pass' => '',
        'remember' => false
    ]);

    rules([
        'usser' => ['required', 'string'],
        'pass' => ['required', 'string'],
    ]);

    $login = function() {
        $this->validate();

        $credentials = [
            'usser' => $this->usser,
            'password' => $this->pass
        ];

        if (auth()->attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->intended('dashboard');
        }

        $this->addError('usser', 'Las credenciales ingresadas no son correctas.');
    };
?>

<div>
    <h2 class="text-2xl mb-4">Iniciar Sesión CC León</h2>
    
    <form wire:submit="login" class="space-y-4">
        <div>
            <x-input-label for="usser" value="Usuario" />
            <x-text-input wire:model="usser" 
                         id="usser" 
                         class="block mt-1 w-full" 
                         type="text" 
                         required 
                         autofocus />
            @error('usser') 
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <x-input-label for="pass" value="Contraseña" />
            <x-text-input wire:model="pass" 
                         id="pass" 
                         class="block mt-1 w-full"
                         type="password"
                         required />
            @error('pass') 
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center">
            <input wire:model="remember" id="remember" type="checkbox" class="mr-2">
            <label for="remember">Recordarme</label>
        </div>

        <div>
            <x-primary-button class="w-full">
                Ingresar
            </x-primary-button>
        </div>
    </form>
</div>