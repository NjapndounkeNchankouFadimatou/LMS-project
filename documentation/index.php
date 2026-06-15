<?php
/**
 * File: documentation/index.php
 * Purpose: Documentation page (accessible to logged-in users only).
 */

require_once '../includes/header.php';
?>

<link rel="stylesheet" href="/LMS-project/assets/css/documentation.css">

<main class="content no-sidebar">

    <div class="doc-container">

        <!-- Page title -->
        <div class="doc-header">
            <h1>LMS Documentation</h1>
            <p>Learn how to use the Learning Management System</p>
        </div>

        <!-- Table of contents as horizontal navbar -->
        <nav class="doc-toc">
            <a href="#overview" class="doc-toc-link">1. Overview</a>
            <a href="#student" class="doc-toc-link">2. Student Guide</a>
            <a href="#teacher" class="doc-toc-link">3. Teacher Guide</a>
            <a href="#admin" class="doc-toc-link">4. Admin Guide</a>
            <a href="#faq" class="doc-toc-link">5. FAQ</a>
        </nav>

        <!-- Section 1: Overview -->
        <div class="doc-section" id="overview">
            <h2>1. Overview</h2>
            <p>
                This LMS (Learning Management System) is a platform that allows teachers
                to publish courses (PDF or video), create quizzes, and track student progress.
                Students can follow courses, take quizzes, and earn certificates when they
                complete a module.
            </p>
            <p>There are three types of users :</p>
            <ul>
                <li><strong>Student</strong> : follows courses, takes quizzes, earns certificates</li>
                <li><strong>Teacher</strong> : publishes courses, creates quizzes, tracks students</li>
                <li><strong>Admin</strong> : manages users, modules, and the platform</li>
            </ul>
        </div>

        <!-- Section 2: Student Guide -->
        <div class="doc-section" id="student">
            <h2>2. Student Guide</h2>

            <h3>2.1 Browse Courses</h3>
            <p>From your dashboard, click <strong>Modules</strong> in the sidebar. You will see all available modules. Click on a module to access its content (PDF or video).</p>

            <h3>2.2 Take a Quiz</h3>
            <p>After viewing a course, click <strong>Take Quiz</strong>. Answer all questions and submit. Your score will be saved and your progression updated automatically.</p>

            <h3>2.3 Track your Progress</h3>
            <p>Go to <strong>My Modules</strong> to see your scores per course and your overall progression (%) per module.</p>

            <h3>2.4 Certificates</h3>
            <p>When you complete all courses in a module with a passing score, a certificate is automatically issued. Go to <strong>Certificates</strong> to view it.</p>
        </div>

        <!-- Section 3: Teacher Guide -->
        <div class="doc-section" id="teacher">
            <h2>3. Teacher Guide</h2>

            <h3>3.1 Publish a Module</h3>
            <p>From your dashboard, click <strong>Publish</strong>. Fill in the module description, course name, description, choose the type (PDF or video), upload your file, and add quiz questions.</p>

            <h3>3.2 Add Quiz Questions</h3>
            <p>For each question, fill in the question text, the four answer options, and select the radio button next to the correct one.</p>

            <h3>3.3 Track Students</h3>
            <p>Go to <strong>My Modules</strong> to see students enrolled in your modules, or <strong>Statistics</strong> for detailed progress and average scores.</p>
        </div>

        <!-- Section 4: Admin Guide -->
        <div class="doc-section" id="admin">
            <h2>4. Admin Guide</h2>

            <h3>4.1 Manage Platform</h3>
            <p>Go to <strong>Manage Platform</strong> to view all users (change their role or delete accounts) and all modules (delete if needed).</p>

            <h3>4.2 Certificates</h3>
            <p>Go to <strong>Certificates</strong> to see student averages per module and manually issue certificates when appropriate.</p>
        </div>

        <!-- Section 5: FAQ -->
        <div class="doc-section" id="faq">
            <h2>5. FAQ</h2>

            <h3>What file formats are accepted for courses ?</h3>
            <p>PDF files and video files (MP4 recommended).</p>

            <h3>How is my progression calculated ?</h3>
            <p>Your progression in a module = (number of courses completed / total courses in module) × 100.</p>

            <h3>How do I get a certificate ?</h3>
            <p>Complete all course quizzes in a module with a passing score (50% minimum).</p>
        </div>

    </div>

</main>

<?php
require_once '../includes/footer.php';
?>