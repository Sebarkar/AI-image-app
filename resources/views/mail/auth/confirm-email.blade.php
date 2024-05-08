<x-mail::message>
# {{ __('mail.auth.Confirm email') }}
{{ __('mail.auth.Gain access to our service') }}
<x-button :href="$url">
{{ __('mail.auth.Confirm email button') }}
</x-button>
<x-mail::panel>
{{ $url }}
</x-mail::panel>

{{__('mail.auth.Thanks')}},<br>
{{ config('app.name') }}
</x-mail::message>
