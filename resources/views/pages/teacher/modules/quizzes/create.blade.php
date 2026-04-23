@extends('layouts.master')
@section('page_title', 'Add Quiz')
@section('content')

<style>
    /* Modern Professional Styling */
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }
    
    /* Main Card */
    .dashboard-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* Card Header */
    .card-header-modern {
        background: white;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-modern i {
        font-size: 20px;
        color: var(--primary);
    }
    
    .card-header-modern h5 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    /* Card Body */
    .card-body-modern {
        padding: 28px;
    }
    
    /* Form Groups */
    .form-group-modern {
        margin-bottom: 24px;
    }
    
    .form-label-modern {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .form-label-modern i {
        margin-right: 6px;
        color: var(--primary);
        font-size: 12px;
    }
    
    .form-label-modern .required {
        color: var(--danger);
        margin-left: 4px;
    }
    
    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control-modern:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    textarea.form-control-modern {
        resize: vertical;
        min-height: 100px;
    }
    
    select.form-control-modern {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }
    
    /* Button */
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.3);
    }
    
    .btn-secondary-modern {
        background: #f1f5f9;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        color: var(--gray);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        margin-left: 12px;
    }
    
    .btn-secondary-modern:hover {
        background: #e2e8f0;
    }
    
    /* Form Row */
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    /* Helper Text */
    .helper-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }
    
    .helper-text i {
        font-size: 10px;
        margin-right: 4px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .card-body-modern {
            padding: 20px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .btn-primary-modern, .btn-secondary-modern {
            width: 100%;
            justify-content: center;
            margin: 0 0 10px 0;
        }
        
        .btn-secondary-modern {
            margin-left: 0;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-plus-circle"></i>
        <h5 class="mb-0">Create New Quiz</h5>
    </div>
    
    <div class="card-body-modern">
        <form enctype="multipart/form-data" action="{{ route('teacher.quizzes.store') }}" method="post">
            @csrf
            
            <!-- Quiz Title -->
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-heading"></i> Quiz Title <span class="required">*</span>
                </label>
                <input type="text" name="title" class="form-control-modern" placeholder="Enter quiz title" required>
                <div class="helper-text">
                    <i class="fas fa-info-circle"></i> Give your quiz a clear, descriptive title
                </div>
            </div>
            
            <!-- Assign to Class + Course -->
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-chalkboard"></i> Assign to Class & Course <span class="required">*</span>
                </label>
                <select class="form-control-modern" id="pair_select" required>
                    <option value="">— Select Class and Course —</option>
                    @foreach($pairs as $pair)
                        <option value="{{ $pair->course_id }}|{{ $pair->my_class_id }}">
                            📚 {{ optional($pair->my_class)->name ?: 'Class '.$pair->my_class_id }} — {{ optional($pair->course)->title ?: 'Course '.$pair->course_id }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="course_id" id="course_id">
                <input type="hidden" name="my_class_id" id="my_class_id">
                <div class="helper-text">
                    <i class="fas fa-info-circle"></i> Select the class and course for this quiz
                </div>
            </div>
            
            <!-- Description -->
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-align-left"></i> Description
                </label>
                <textarea name="description" class="form-control-modern" placeholder="Enter quiz description, instructions, or guidelines..."></textarea>
                <div class="helper-text">
                    <i class="fas fa-info-circle"></i> Provide detailed instructions for students
                </div>
            </div>
            
            <!-- Quiz PDF -->
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-file-pdf"></i> Quiz PDF
                </label>
                <input type="file" name="attachment" class="form-control-modern" accept="application/pdf">
                <div class="helper-text">
                    <i class="fas fa-info-circle"></i> Upload quiz as PDF (max size: 2MB)
                </div>
            </div>
            
            <div class="form-row">
                <!-- Total Marks -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-star"></i> Total Marks
                    </label>
                    <input type="number" min="0" name="total_marks" class="form-control-modern" placeholder="e.g., 100">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Maximum marks for this quiz
                    </div>
                </div>
                
                <!-- Scheduled At -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Scheduled Date & Time
                    </label>
                    <input type="datetime-local" name="scheduled_at" class="form-control-modern">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> When should the quiz be available?
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-group-modern" style="margin-top: 32px;">
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i> Create Quiz
                </button>
                <button type="reset" class="btn-secondary-modern">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('pair_select').addEventListener('change', function(){
        const [courseId, classId] = (this.value || '|').split('|');
        document.getElementById('course_id').value = courseId || '';
        document.getElementById('my_class_id').value = classId || '';
    });
</script>

@endsection