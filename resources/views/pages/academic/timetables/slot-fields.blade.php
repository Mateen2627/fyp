<div class="form-row">
@foreach(['from' => 'Start', 'to' => 'End'] as $key => $label)
<div class="col-md-6 form-group"><label>{{ $label }} time</label><div class="d-flex">
<input aria-label="{{ $label }} hour" class="form-control mr-1" type="number" name="hour_{{ $key }}" min="1" max="12" required value="{{ $slot ? $slot->{'hour_'.$key} : ($key === 'from' ? 9 : 10) }}">
<select aria-label="{{ $label }} minute" class="form-control mr-1" name="min_{{ $key }}">@for($m=0; $m<60; $m++)<option value="{{ sprintf('%02d', $m) }}" {{ $slot && (int)$slot->{'min_'.$key} === $m ? 'selected' : '' }}>{{ sprintf('%02d', $m) }}</option>@endfor</select>
<select aria-label="{{ $label }} AM or PM" class="form-control" name="meridian_{{ $key }}"><option {{ $slot && $slot->{'meridian_'.$key} === 'AM' ? 'selected' : '' }}>AM</option><option {{ $slot && $slot->{'meridian_'.$key} === 'PM' ? 'selected' : '' }}>PM</option></select>
</div></div>@endforeach</div>
