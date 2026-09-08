<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Examination System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f3f4f6; font-family: 'Inter', sans-serif; }
        .auth-card { 
            max-width: 450px; 
            margin: 60px auto; 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            border: 1px solid rgba(255,255,255,0.4);
            background: #fff;
        }
        .form-control:focus { box-shadow: none; border-color: #4F46E5; }
        .btn-primary { background-color: #4F46E5; border-color: #4F46E5; }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark">Create an Account</h3>
                    <p class="text-muted">Join the examination platform</p>
                </div>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="/register" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">City</label>
                        <input type="text" name="city" class="form-control form-control-lg" placeholder="e.g. New York" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-3 fw-bold">Sign Up</button>
                    <div class="text-center mt-4">
                        <span class="text-muted small">Already have an account?</span> <a href="/login" class="text-decoration-none fw-bold" style="color: #4F46E5;">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
