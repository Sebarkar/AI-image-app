<x-mail::message>
{{ __('mail.auth.Someone request') }}
# {{ __('mail.auth.One Time Password') }}
<x-mail::panel>
{{ $password->code }}
</x-mail::panel>

{{__('mail.auth.Thanks')}},<br>
{{ config('app.name') }}
</x-mail::message>
