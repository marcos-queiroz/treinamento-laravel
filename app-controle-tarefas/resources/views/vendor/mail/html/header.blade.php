<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Tasks Control')
<img src="https://beecoffee.com.br/upload/beecoffee/files/base/ico.png" class="logo" alt="BeeCoffee Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
