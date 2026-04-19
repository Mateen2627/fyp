@php
    $entity = $record ?? null;
@endphp

<?php foreach ($fields as $field): ?>

    <?php if (in_array($field, ['title', 'course_name', 'course_code', 'subject', 'label', 'detail_value', 'report_type'])): ?>
        <div class="form-group">
            <label>{{ ucwords(str_replace('_', ' ', $field)) }}</label>
            <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field, data_get($entity, $field)) }}" {{ in_array($field, ['title', 'subject', 'label', 'detail_value']) ? 'required' : '' }}>
        </div>
    <?php endif; ?>

    <?php if (in_array($field, ['description', 'remarks', 'content', 'message', 'notes'])): ?>
        <div class="form-group">
            <label>{{ ucwords(str_replace('_', ' ', $field)) }}</label>
            <textarea name="{{ $field }}" class="form-control" {{ in_array($field, ['content', 'message']) ? 'required' : '' }}>{{ old($field, data_get($entity, $field)) }}</textarea>
        </div>
    <?php endif; ?>

    <?php if (in_array($field, ['due_date', 'attendance_date'])): ?>
        <div class="form-group">
            <label>{{ ucwords(str_replace('_', ' ', $field)) }}</label>
            <input type="date" name="{{ $field }}" class="form-control" value="{{ old($field, optional(data_get($entity, $field))->format('Y-m-d')) }}" {{ $field === 'attendance_date' ? 'required' : '' }}>
        </div>
    <?php endif; ?>

    <?php if ($field === 'scheduled_at'): ?>
        <div class="form-group">
            <label>Scheduled At</label>
            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', optional(data_get($entity, 'scheduled_at'))->format('Y-m-d\\TH:i')) }}">
        </div>
    <?php endif; ?>

    <?php if ($field === 'total_marks'): ?>
        <div class="form-group">
            <label>Total Marks</label>
            <input type="number" min="0" name="total_marks" class="form-control" value="{{ old('total_marks', data_get($entity, 'total_marks')) }}">
        </div>
    <?php endif; ?>

    <?php if ($field === 'status'): ?>
        @php
            $statusOptions = ['pending', 'in_progress', 'submitted', 'graded'];
            if (in_array('attendance_date', $fields)) {
                $statusOptions = ['present', 'absent', 'late', 'excused'];
            }
            if (in_array('priority', $fields)) {
                $statusOptions = ['open', 'in_progress', 'resolved', 'closed'];
            }
        @endphp
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                @foreach($statusOptions as $option)
                    <option value="{{ $option }}" {{ old('status', data_get($entity, 'status')) === $option ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                @endforeach
            </select>
        </div>
    <?php endif; ?>

    <?php if ($field === 'priority'): ?>
        <div class="form-group">
            <label>Priority</label>
            <select name="priority" class="form-control" required>
                @foreach(['low', 'medium', 'high'] as $priority)
                    <option value="{{ $priority }}" {{ old('priority', data_get($entity, 'priority')) === $priority ? 'selected' : '' }}>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
        </div>
    <?php endif; ?>

<?php endforeach; ?>
