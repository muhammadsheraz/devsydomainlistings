<x-mail::message>
# Hey {{ $user_name }},

Congratulations! You won the "{{ $domain }}" domain! You can see your domain in your dashboard by visiting the following link:

View my dashboard → this is a button with link to the dashboard

Regards,<br>
{{ config('app.name') }}
</x-mail::message>
