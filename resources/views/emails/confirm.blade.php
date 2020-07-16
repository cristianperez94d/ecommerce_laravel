@component('mail::message')
# Hola {{$user->name}}

Por favor verifica la el nuevo correo que registraste usando el siguiente boton:


@component('mail::button', ['url' =>  route("verify", $user->verification_token) ])
Confirmar mi cuenta
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent