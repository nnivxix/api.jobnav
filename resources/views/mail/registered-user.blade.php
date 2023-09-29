{{-- Do not use indentation to avoid spaces in the outputted HTML --}}

<x-mail::layout>

<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

# Thank you for joining with us!

Please verify your email address by clicking the link below:

<x-mail::button :url="$url">
Verify Email
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>

</x-mail::layout>