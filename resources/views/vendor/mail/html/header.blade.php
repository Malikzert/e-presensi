@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhB7ewsH2yOQmNU6LMNJe3dUQxWtVEIsNJKVh_306R7N65PM_JpmSkjMoHauGpJW4P7d94a5Bk1IZznjW0TosUDYWosywtnHS0uTi6Do8a0fGfkwyVZ25FiEh5oBO_-pQO6DHJxlvqYgCXiJCe3IiiJepoI6usMmMgVLn0692kBz3F8Kij6dgBIL_n2ADM/s1334/logo%20RSU%20Anna%20Medika%20Madura.jpg" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
