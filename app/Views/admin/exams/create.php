<div class="card">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-dark">Exam Details</h6>
    </div>
    <div class="card-body">
        <form action="/admin/exams/store" method="POST">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label text-muted small fw-bold">Exam Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label text-muted small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold">Start Time</label>
                    <input type="datetime-local" name="start_time" class="form-control" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold">End Time</label>
                    <input type="datetime-local" name="end_time" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Duration (Minutes)</label>
                    <input type="number" name="duration" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Total Marks</label>
                    <input type="number" step="0.01" name="total_marks" class="form-control" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Passing Marks</label>
                    <input type="number" step="0.01" name="passing_marks" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Negative Marking Ratio</label>
                    <input type="number" step="0.01" name="negative_marking" class="form-control" placeholder="0.00" value="0.00">
                </div>

                <div class="col-md-12 mt-4">
                    <label class="form-label text-muted small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">Draft (Invisible to students)</option>
                        <option value="published">Published (Active)</option>
                    </select>
                </div>
            </div>
            
            <hr class="my-4">
            <button type="submit" class="btn btn-primary fw-bold px-4">Create Exam</button>
            <a href="/admin/exams" class="btn btn-light ms-2">Cancel</a>
        </form>
    </div>
</div>
