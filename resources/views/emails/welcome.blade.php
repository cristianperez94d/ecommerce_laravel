@component('mail::message')
# Hola {{$user->name}}

Por favor verifica la cuenta que creaste usando el siguiente boton:


@component('mail::button', ['url' => route("verify", $user->verification_token) ])
Confirmar mi cuenta
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent