@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ env('FRONT_URL') }} . 'images/logos/mail.png'" class="logo" alt="{{ env('APP_NAME') }} Logo">
</a>
</td>
</tr>
