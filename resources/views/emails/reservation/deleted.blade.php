<x-mail::message>
# {{ __('Your reservation has been deleted') }}

## {{ __('Details:') }}<br>
{{ __('Date & Time: ') }} {{ $reservation->date->format('d.m.Y H:i') }}<br>

{{ config('app.name') }}
</x-mail::message>
