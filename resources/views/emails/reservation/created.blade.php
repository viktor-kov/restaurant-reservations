<x-mail::message>
# {{ __('Your reservation was created') }}

## {{ __('Details:') }}<br>
{{ __('Date & Time: ') }} {{ $reservation->date->format('d.m.Y H:i') }}<br>
{{ __('Seats:') }} {{ $reservation->seats_count }}<br>
{{ __('Notes: ') }} {{ $reservation->notes }}<br>

{{ config('app.name') }}
</x-mail::message>
