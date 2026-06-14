<?php
/**
 * File: documentation/index.php
 * Purpose: Documentation page (accessible to logged-in users only).
 * Includes header and footer. No sidebar.
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

        <!-- Table of contents -->
        <div class="doc-toc">
            <h2>Table of Contents</h2>
            <ul>
                <li><a href="#overview">1. Overview</a></li>
                <li><a href="#student">2. Student Guide</a></li>
                <li><a href="#teacher">3. Teacher Guide</a></li>
                <li><a href="#admin">4. Admin Guide</a></li>
                <li><a href="#faq">5. FAQ</a></li>
            </ul>
        </div>

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
            <p>From your dashboard, click <strong>Courses</strong> in the sidebar. You will see all available courses grouped by module. Click on a course to access its content (PDF or video).</p>

            <h3>2.2 Take a Quiz</h3>
            <p>After viewing a course, click <strong>Take Quiz</strong>. Answer all questions and submit. Your score will be saved and your progression updated automatically.</p>

            <h3>2.3 Track your Progress</h3>
            <p>Go to <strong>Progress</strong> in the sidebar to see your scores per course and your overall progression (%) per module.</p>

            <h3>2.4 Certificates</h3>
            <p>When you complete all courses in a module with a passing score, a certificate is automatically issued. Go to <strong>Certificates</strong> to view and print it.</p>
        </div>

        <!-- Section 3: Teacher Guide -->
        <div class="doc-section" id="teacher">
            <h2>3. Teacher Guide</h2>

            <h3>3.1 Create a Course</h3>
            <p>From your dashboard, click <strong>Create Course</strong>. Fill in the course name, description, select a module, choose the type (PDF or video) and upload your file. A first quiz will be created at the same time.</p>

            <h3>3.2 Add Quiz Questions</h3>
            <p>After creating a course, go to <strong>Manage Courses</strong>, select your course and click <strong>Add Question</strong> to add questions and answers to the quiz.</p>

            <h3>3.3 Track Students</h3>
            <p>Go to <strong>Student Progress</strong> in the sidebar to see the list of students who have taken your course quizzes and their scores.</p>
        </div>

        <!-- Section 4: Admin Guide -->
        <div class="doc-section" id="admin">
            <h2>4. Admin Guide</h2>

            <h3>4.1 Manage Modules</h3>
            <p>Go to <strong>Modules</strong> in the sidebar to create, edit or delete modules. A module groups several courses on the same topic.</p>

            <h3>4.2 Manage Users</h3>
            <p>Go to <strong>Users</strong> to see all registered users, change their role or delete their account.</p>

            <h3>4.3 Global Statistics</h3>
            <p>Go to <strong>Statistics</strong> to see the number of courses per teacher, enrollments per module, and average quiz scores.</p>

            <h3>4.4 Certificates</h3>
            <p>Go to <strong>Certificates</strong> to see all certificates issued to students.</p>
        </div>

        <!-- Section 5: FAQ -->
        <div class="doc-section" id="faq">
            <h2>5. FAQ</h2>

            <h3>What file formats are accepted for courses ?</h3>
            <p>PDF files and video files (MP4 recommended).</p>

            <h3>How is my progression calculated ?</h3>
            <p>Your progression in a module = (number of quizzes passed / total quizzes in module) × 100.</p>

            <h3>How do I get a certificate ?</h3>
            <p>Complete all course quizzes in a module with a passing score (50% minimum).</p>
        </div>

    </div>

</main>

<?php
require_once '../includes/footer.php';
?>