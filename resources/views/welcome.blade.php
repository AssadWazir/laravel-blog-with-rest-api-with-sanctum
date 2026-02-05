<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'BlogPost') }} - Welcome</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <style>
            <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f8f9fa;
                color: #333;
            }

            /* Navbar Styling */
            .navbar {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                padding: 1rem 0;
            }

            .navbar-brand {
                font-weight: bold;
                font-size: 1.5rem;
                color: white !important;
            }

            .navbar-nav .nav-link {
                color: white !important;
                margin-left: 1rem;
                transition: all 0.3s ease;
                border-radius: 4px;
                padding: 0.5rem 1rem !important;
            }

            .navbar-nav .nav-link:hover {
                background-color: rgba(255, 255, 255, 0.2);
                transform: translateY(-2px);
            }

            .nav-link.btn-login {
                background-color: white;
                color: #667eea !important;
                font-weight: 600;
                padding: 0.5rem 1.5rem !important;
                border-radius: 25px;
                margin-left: 1rem;
            }

            .nav-link.btn-login:hover {
                background-color: #f0f0f0;
                box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
            }

            /* Hero Section */
            .hero {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 80px 0;
                text-align: center;
                margin-bottom: 60px;
            }

            .hero h1 {
                font-size: 3.5rem;
                font-weight: bold;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            }

            .hero p {
                font-size: 1.3rem;
                margin-bottom: 2rem;
                opacity: 0.95;
            }

            .btn-cta {
                background-color: white;
                color: #667eea;
                border: none;
                padding: 12px 30px;
                font-weight: 600;
                border-radius: 25px;
                margin: 0 10px;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }

            .btn-cta:hover {
                background-color: #f0f0f0;
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                text-decoration: none;
            }

            .btn-cta-outline {
                background-color: transparent;
                color: white;
                border: 2px solid white;
                padding: 10px 28px;
                font-weight: 600;
                border-radius: 25px;
                margin: 0 10px;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }

            .btn-cta-outline:hover {
                background-color: white;
                color: #667eea;
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                text-decoration: none;
            }

            /* Blog Posts Section */
            .blog-section {
                max-width: 1000px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .section-title {
                text-align: center;
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 3rem;
                color: #333;
            }

            .post-card {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                transition: all 0.3s ease;
                margin-bottom: 2rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                border-left: 4px solid #667eea;
            }

            .post-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            }

            .post-card-body {
                padding: 2rem;
            }

            .post-card h3 {
                color: #333;
                font-weight: bold;
                margin-bottom: 1rem;
                font-size: 1.5rem;
            }

            .post-card h3:hover {
                color: #667eea;
            }

            .post-meta {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                color: #666;
                flex-wrap: wrap;
            }

            .post-meta-item {
                margin-right: 1.5rem;
                display: flex;
                align-items: center;
            }

            .post-meta-item i {
                margin-right: 0.5rem;
                color: #667eea;
            }

            .post-excerpt {
                color: #666;
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }

            .read-more {
                color: #667eea;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
            }

            .read-more:hover {
                color: #764ba2;
            }

            .read-more i {
                margin-left: 0.5rem;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
            }

            .empty-state i {
                font-size: 4rem;
                color: #667eea;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            .empty-state h3 {
                color: #333;
                font-weight: bold;
                margin-bottom: 1rem;
            }

            .empty-state p {
                color: #666;
                margin-bottom: 2rem;
            }

            /* Footer */
            footer {
                background-color: #2c3e50;
                color: white;
                text-align: center;
                padding: 2rem 0;
                margin-top: 4rem;
                border-top: 4px solid #667eea;
            }

            footer p {
                margin: 0;
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .post-card {
                animation: fadeInUp 0.5s ease;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 2rem;
                }

                .hero p {
                    font-size: 1rem;
                }

                .btn-cta, .btn-cta-outline {
                    display: block;
                    margin: 10px 0;
                }

                .section-title {
                    font-size: 1.8rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <i class="fas fa-pen-nib"></i> BlogPost
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="nav-link btn-login" style="border: none; cursor: pointer;">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn-login" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero">
            <div class="container">
                <h1>Welcome to Our Blog</h1>
                <p>Discover amazing stories, insights, and knowledge shared by our community</p>
                <div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-cta">
                            <i class="fas fa-arrow-right"></i> Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-cta">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-cta-outline">
                                <i class="fas fa-user-plus"></i> Create Account
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <!-- Blog Posts Section -->
        <div class="blog-section">
            <h2 class="section-title">Latest Posts</h2>

            @if (isset($posts) && $posts->count() > 0)
                @foreach ($posts as $post)
                    <div class="post-card">
                        <div class="post-card-body">
                            <h3>{{ $post->title }}</h3>
                            <div class="post-meta">
                                <div class="post-meta-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ $post->user?->name ?? 'Unknown' }}</span>
                                </div>
                                <div class="post-meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <p class="post-excerpt">
                                {{ Str::limit($post->body, 200) }}
                            </p>
                            <a href="{{ route('posts.show', $post) }}" class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="post-card empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Posts Yet</h3>
                    <p>There are no blog posts available at the moment. Check back soon for new content!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-pen"></i> Create Your First Post
                        </a>
                    @endauth
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer>
            <div class="container">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'BlogPost') }}. All rights reserved.</p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</html>
